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
use App\Models\Payments;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\StatusPayments;

class OrderController extends Controller
{
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

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $orders->map(fn($order) => [
                'id' => $order->id,
                'order_type' => $order->order_type,
                'status' => $order->status,
                'total_price' => (int) $order->total_price,
                'delivery_fee' => (int) $order->delivery_fee,
                'discount_amount' => (int) $order->discount_amount,
                'final_price' => (int) $order->final_price,
                'created_at' => $order->created_at->toIso8601String(),
                'payments' => $order->payments->map(fn($payment) => [
                    'payment_method' => $payment->payment_method,
                    'payment_gateway' => $payment->payment_gateway,
                    'status' => $payment->status,
                ]),
                'items' => $order->order_items->map(fn($item) => [
                    'menu_id' => $item->menu_id,
                    'menu_name' => $item->menu->name ?? null,
                    'quantity' => $item->quantity,
                    'price' => (int) $item->price,
                ]),
            ])
        ], 200);
    }

    /**
     * Checkout dari cart (rename dari checkout ke store)
     */
    public function store(Request $request)
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
            'address.notes' => 'nullable|string',
            'notes' => 'nullable|string',
            'selected_item_ids' => 'required|array|min:1', // Add validation for selected items
            'selected_item_ids.*' => 'integer|exists:cart_items,id', // Each item must be valid cart_item ID
            'payment_method' => 'required|string|in:dana,gopay,shopeepay,qris,transfer_bank,cod,credit_card,ovo,linkaja',
        ]);

        return DB::transaction(function () use ($request) {
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
            if ($request->order_type === 'delivery') {
                // TODO: Integrate dengan third party delivery service
                // Untuk sementara set default atau dari request
                $deliveryFee = $request->input('delivery_fee', 0);
            }

            // Hitung final price
            $finalPrice = $totalPrice + $deliveryFee;

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
                    'postal_code' => $request->address['postal_code'],
                    'notes' => $request->address['notes'] ?? null,
                ]);
            }

            // Membuat order log pertama
            OrderLogs::create([
                'order_id' => $order->id,
                'status' => OrderStatus::PENDING_PAYMENT,
                'note' => 'Order created',
            ]);

            // Membuat payment record dengan payment method yang dipilih user
            $transactionId = 'MERACIKOPI-' . $order->id . '-' . time();
            Payments::create([
                'order_id' => $order->id,
                'payment_gateway' => 'midtrans',
                'payment_method' => $request->payment_method,
                'transaction_id' => $transactionId,
                'amount' => $finalPrice,
                'status' => StatusPayments::PENDING,
                'payload' => [],
            ]);

            // Empty the cart
            $cart->items()->delete();

            return response()->json([
                'message' => 'Order created successfully',
                'data' => [
                    'id' => $order->id,
                    'order_type' => $order->order_type,
                    'status' => $order->status,
                    'total_price' => (int) $order->total_price,
                    'delivery_fee' => (int) $order->delivery_fee,
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
                    'payment_method' => $payment->payment_method,
                    'payment_gateway' => $payment->payment_gateway,
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