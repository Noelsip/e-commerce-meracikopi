<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\OrderAddresses;
use App\Models\OrderLogs;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Services\Shipping\ShippingQuoteService;

class OrderController extends Controller
{
    /**
     * Get checkout settings (service fee, etc.)
     * This endpoint is used by frontend to display fees from backend/third-party services
     */
    public function getCheckoutSettings()
    {
        return response()->json([
            'data' => [
                'service_fee' => (int) config('order.service_fee', 0),
            ]
        ]);
    }

    public function index(Request $request)
    {
        $guestToken = $request->attributes->get('guest_token');

        $query = Orders::with(['payments', 'order_items.menu']);

        // Filter by guest token or user_id
        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('guest_token', $guestToken);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'data' => $orders->map(fn($order) => [
                'id' => $order->id,
                'order_type' => $order->order_type,
                'status' => $order->status,
                'payment_status' => $order->payment_status?->value,
                'payment_status_label' => $order->payment_status?->label(),
                'order_status' => $order->order_status?->value,
                'order_status_label' => $order->order_status?->label(),
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'total_price' => (int) $order->total_price,
                'delivery_fee' => (int) $order->delivery_fee,
                'discount_amount' => (int) $order->discount_amount,
                'final_price' => (int) $order->final_price,
                'items_count' => $order->order_items->count(),
                'items_summary' => $order->order_items->take(3)->map(fn($item) => $item->menu?->name)->filter()->implode(', '),
                'items' => $order->order_items->map(fn($item) => [
                    'id' => $item->id,
                    'menu_id' => $item->menu_id,
                    'menu_name' => $item->menu?->name ?? 'Unknown Item',
                    'quantity' => $item->quantity,
                    'price' => (int) $item->price,
                ]),
                'payment' => $order->payments->first() ? [
                    'status' => $order->payments->first()->status,
                    'method' => $order->payments->first()->payment_method,
                    'paid_at' => $order->payments->first()->paid_at?->toIso8601String(),
                ] : null,
                'created_at' => $order->created_at->toIso8601String(),
            ]),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ], 200);
    }

    /**
     * Checkout dari cart (rename dari checkout ke store)
     */
    public function store(Request $request, ShippingQuoteService $shipping)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'order_type' => 'required|in:dine_in,take_away,delivery',
            'table_id' => 'required_if:order_type,dine_in|nullable|exists:tables,id',
            'address' => 'required_if:order_type,delivery|nullable|array',
            'address.receiver_name' => 'required_if:order_type,delivery|string',
            'address.phone' => 'required_if:order_type,delivery|string',
            'address.full_address' => 'required_if:order_type,delivery|string',
            'address.city' => 'required_if:order_type,delivery|string',
            'address.postal_code' => 'required_if:order_type,delivery|string',
            'address.province' => 'nullable|string',
            'address.latitude' => 'nullable|numeric',
            'address.longitude' => 'nullable|numeric',
            'address.rajaongkir_destination_id' => 'nullable|integer',
            'address.notes' => 'nullable|string',
            'shipping_quote_id' => 'required_if:order_type,delivery|string',
            'shipping_option_id' => 'required_if:order_type,delivery|string',
            'notes' => 'nullable|string',
            'selected_item_ids' => 'required|array|min:1', // Add validation for selected items
            'selected_item_ids.*' => 'integer', // Ownership validation is done in transaction
        ]);

        return DB::transaction(function () use ($request, $shipping) {
            $guestToken = $request->attributes->get('guest_token');



            $cartQuery = Cart::with('items.menu')
                ->where('guest_token', $guestToken);



            $cart = $cartQuery->lockForUpdate()->firstOrFail();

            // Validasi cart tidak kosong
            if ($cart->items->isEmpty()) {
                abort(422, 'Cart is empty');
            }

            // Filter only selected items
            $selectedItemIds = $request->input('selected_item_ids', []);
            $selectedItems = $cart->items->whereIn('id', $selectedItemIds);

            // Validate that we have selected items
            if ($selectedItems->isEmpty()) {
                abort(422, 'No items selected for checkout');
            }

            // Menghitung total harga dari server dengan diskon (hanya untuk item yang dipilih)
            $totalPrice = 0;
            $totalDiscountAmount = 0;

            foreach ($selectedItems as $item) {
                if (!$item->menu->is_available) {
                    abort(422, "Menu '{$item->menu->name}' is not available");
                }

                // Hitung dengan harga setelah diskon
                $finalPrice = $item->menu->final_price;
                $totalPrice += $finalPrice * $item->quantity;

                // Hitung total diskon
                $totalDiscountAmount += $item->menu->discount_amount * $item->quantity;
            }

            // Hitung delivery fee (jika order type = delivery)
            $deliveryFee = 0;
            $deliveryProvider = null;
            $deliveryService = null;
            $deliveryMeta = null;
            if ($request->order_type === 'delivery') {
                $quoteId = (string) $request->input('shipping_quote_id');
                $optionId = (string) $request->input('shipping_option_id');

                $quote = $shipping->getQuote($quoteId);
                if (!$quote) {
                    abort(422, 'Invalid shipping_quote_id');
                }

                $option = $shipping->getOptionFromQuote($quoteId, $optionId);
                if (!$option || !is_numeric($option['price'] ?? null)) {
                    abort(422, 'Invalid shipping_option_id');
                }

                $deliveryFee = (int) $option['price'];
                $deliveryProvider = $option['provider'] ?? null;
                $deliveryService = $option['service'] ?? null;
                $deliveryMeta = [
                    'quote_id' => $quoteId,
                    'option_id' => $optionId,
                    'channel' => $quote['channel'] ?? null,
                    'origin' => $quote['origin'] ?? null,
                    'destination' => $quote['destination'] ?? null,
                    'selected_option' => $option,
                    'meta' => $quote['meta'] ?? null,
                ];
            }

            // Get service fee from config (can be from third-party payment gateway)
            $serviceFee = (int) config('order.service_fee', 0);

            // Hitung final price (including service fee)
            $finalPrice = $totalPrice + $deliveryFee + $serviceFee - $totalDiscountAmount;

            // Membuat order
            $order = Orders::create([
                'guest_token' => $cart->guest_token,
                'user_id' => auth()->check() ? auth()->id() : null,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'order_type' => $request->order_type,
                'table_id' => $request->table_id,
                'total_price' => $totalPrice,
                'delivery_fee' => $deliveryFee,
                'service_fee' => $serviceFee,
                'delivery_provider' => $deliveryProvider,
                'delivery_service' => $deliveryService,
                'delivery_meta' => $deliveryMeta,
                'discount_amount' => $totalDiscountAmount,
                'final_price' => $finalPrice,
                'status' => OrderStatus::PENDING_PAYMENT,
                'notes' => $request->notes,
            ]);

            // Copy ONLY SELECTED cart_items ke order_items dengan harga final (setelah diskon)
            foreach ($selectedItems as $item) {
                OrderItems::create([
                    'order_id' => $order->id,
                    'menu_id' => $item->menu_id,
                    'quantity' => $item->quantity,
                    'price' => $item->menu->final_price, // Gunakan harga setelah diskon
                ]);
            }

            // Menyimpan address jika delivery
            if ($order->order_type === OrderType::DELIVERY->value) {
                OrderAddresses::create([
                    'order_id' => $order->id,
                    'receiver_name' => $request->address['receiver_name'],
                    'phone' => $request->address['phone'],
                    'full_address' => $request->address['full_address'],
                    'city' => $request->address['city'],
                    'province' => $request->address['province'] ?? null,
                    'postal_code' => $request->address['postal_code'],
                    'latitude' => $request->address['latitude'] ?? null,
                    'longitude' => $request->address['longitude'] ?? null,
                    'rajaongkir_destination_id' => $request->address['rajaongkir_destination_id'] ?? null,
                    'notes' => $request->address['notes'] ?? '',
                ]);
            }

            // Membuat order log pertama
            OrderLogs::create([
                'order_id' => $order->id,
                'status' => OrderStatus::PENDING_PAYMENT,
                'note' => 'Order created',
            ]);

            // PENTING: Cart tidak langsung dihapus!
            // Cart items akan dihapus setelah pembayaran berhasil (di webhook handler)
            // Ini memastikan jika payment gagal, user tidak kehilangan cart-nya

            return response()->json([
                'message' => 'Order created successfully',
                'data' => [
                    'id' => $order->id,
                    'order_type' => $order->order_type,
                    'status' => $order->status,
                    'total_price' => (int) $order->total_price,
                    'delivery_fee' => (int) $order->delivery_fee,
                    'service_fee' => (int) $order->service_fee,
                    'discount_amount' => (int) $order->discount_amount,
                    'final_price' => (int) $order->final_price,
                ]
            ], 201);
        });
    }

    public function show(Request $request, $id)
    {
        $guestToken = $request->attributes->get('guest_token');

        $query = Orders::with([
            'order_items.menu',
            'order_addresses',
            'deliveries',
            'payments',
            'order_logs',
            'user',
            'tables'
        ])->where('id', $id);

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('guest_token', $guestToken);
        }

        $order = $query->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $order->id,
                'order_type' => $order->order_type,
                'status' => $order->status,
                'total_price' => (int) $order->total_price,
                'delivery_fee' => (int) $order->delivery_fee,
                'discount_amount' => (int) $order->discount_amount,
                'final_price' => (int) $order->final_price,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'notes' => $order->notes,
                'user' => $order->user ? [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                ] : null,
                'table' => $order->tables ? [
                    'id' => $order->tables->id,
                    'table_number' => $order->tables->table_number,
                ] : null,
                'items' => $order->order_items->map(fn($item) => [
                    'menu_id' => $item->menu_id,
                    'menu_name' => $item->menu->name ?? null,
                    'quantity' => $item->quantity,
                    'price' => (int) $item->price,
                ]),
                'address' => $order->order_addresses->first() ? [
                    'receiver_name' => $order->order_addresses->first()->receiver_name,
                    'phone' => $order->order_addresses->first()->phone,
                    'full_address' => $order->order_addresses->first()->full_address,
                    'city' => $order->order_addresses->first()->city,
                    'postal_code' => $order->order_addresses->first()->postal_code,
                    'notes' => $order->order_addresses->first()->notes,
                ] : null,
                'delivery' => $order->deliveries->first() ? [
                    'status' => $order->deliveries->first()->status,
                ] : null,
                'payments' => $order->payments->map(fn($payment) => [
                    'amount' => (int) $payment->amount,
                    'status' => $payment->status,
                    'paid_at' => $payment->paid_at?->toIso8601String(),
                ]),
                'logs' => $order->order_logs->map(fn($log) => [
                    'status' => $log->status,
                    'note' => $log->note,
                    'created_at' => $log->created_at->toIso8601String(),
                ]),
            ]
        ], 200);
    }

    public function checkout(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $cart = Cart::with('items.menu')
                ->where('guest_token', $request->attributes->get('guest_token'))
                ->where('status', 'active')
                ->lockForUpdate()
                ->firstOrFail();

            // Validasi cart tidak kosong
            if ($cart->items->isEmpty()) {
                abort(422, 'Cart is empty');
            }

            // Menghitung total harga dari server
            $totalPrice = 0;

            foreach ($cart->items as $item) {
                if (!$item->menu->is_available) {
                    abort(422, "Menu '{$item->menu->name}' is not available");
                }

                $totalPrice += $item->menu->price * $item->quantity;
            }

            // Membuat order
            $order = Orders::create([
                'guest_token' => $cart->guest_token,
                'order_type' => OrderType::TAKE_AWAY->value,
                'status' => OrderStatus::PENDING_PAYMENT->value,
                'total_price' => $totalPrice,
                'note' => $request->note,
            ]);

            // Copy cart_items ke order_items
            foreach ($cart->items as $item) {
                OrderItems::create([
                    'order_id' => $order->id,
                    'menu_id' => $item->menu_id,
                    'quantity' => $item->quantity,
                    'price' => $item->menu->price,
                ]);
            }

            // Menyimpan address jika delivery
            if ($order->order_type === OrderType::DELIVERY) {
                OrderAddresses::create([
                    'order_id' => $order->id,
                    'receiver_name' => $request->address['receiver_name'],
                    'phone' => $request->address['phone'],
                    'full_address' => $request->address['full_address'],
                    'city' => $request->address['city'],
                    'postal_code' => $request->address['postal_code'],
                    'notes' => $request->address['notes'] ?? null,
                ]);
            }

            // Membuat oder log pertama
            OrderLogs::create([
                'order_id' => $order->id,
                'status' => OrderStatus::PENDING_PAYMENT->value,
                'note' => $request->note,
            ]);

            // Update cart status
            $cart->update(['status' => 'checked_out']);

            return response()->json([
                'message' => 'Order created succesfully',
                'data' => $order
            ], 201);
        });
    }
}
