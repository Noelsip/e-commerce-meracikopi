<?php

namespace App\Http\Controllers\Customers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\OrderAddresses;
use App\Models\OrderLogs;
use App\Enums\OrderStatus;
use App\Enums\OrderType;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $guestToken = $request->attributes->get('guest_token');
        
        $query = Orders::query();

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
                'created_at' => $order->created_at->toIso8601String(),
            ])
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_type' => 'required|in:dine_in,take_away,delivery',
            'table_id' => 'required_if:order_type,dine_in|nullable|integer',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'address' => 'required_if:order_type,delivery|nullable|array',
            'address.receiver_name' => 'required_if:order_type,delivery|string',
            'address.phone' => 'required_if:order_type,delivery|string',
            'address.full_address' => 'required_if:order_type,delivery|string',
            'address.city' => 'required_if:order_type,delivery|string',
            'address.postal_code' => 'required_if:order_type,delivery|string',
            'address.notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $totalPrice = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $menu = Menus::find($item['menu_id']);
                
                if (!$menu || !$menu->is_available) {
                    return response()->json([
                        'message' => "Menu '{$menu->name}' is not available"
                    ], 400);
                }

                $subtotal = $menu->price * $item['quantity'];
                $totalPrice += $subtotal;

                $orderItems[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'subtotal' => $subtotal,
                ];
            }

            $guestToken = $request->attributes->get('guest_token');

            $order = Orders::create([
                'user_id' => auth()->id(),
                'guest_token' => auth()->check() ? null : $guestToken,
                'order_type' => $request->order_type,
                'table_id' => $request->table_id,
                'status' => OrderStatus::PENDING->value,
                'total_price' => $totalPrice,
            ]);

            foreach ($orderItems as $item) {
                OrderItems::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            if ($request->order_type === 'delivery' && $request->address) {
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

            OrderLogs::create([
                'order_id' => $order->id,
                'status' => OrderStatus::PENDING->value,
                'note' => $request->note,
            ]);

            return response()->json([
                'message' => 'Order Created',
                'data' => [
                    'id' => $order->id,
                    'order_type' => $order->order_type,
                    'status' => $order->status,
                    'total_price' => (int) $order->total_price,
                    'items' => $orderItems,
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