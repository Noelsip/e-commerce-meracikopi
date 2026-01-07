<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\OrderAddresses;
use App\Models\OrderLogs;
use App\Models\Menus;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Orders::where('user_id', $request->user()->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $orders->map(fn($order) => [
                'id' => $order->id,
                'order_type' => $order->order_type->value,
                'status' => $order->status->value,
                'total_price' => (int) $order->total_price,
                'created_at' => $order->created_at->toIso8601String(),
            ])
        ]);
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
                $subtotal = $menu->price * $item['quantity'];
                $totalPrice += $subtotal;

                $orderItems[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'subtotal' => $subtotal,
                ];
            }

            $order = Orders::create([
                'user_id' => $request->user()->id,
                'order_type' => $request->order_type,
                'table_id' => $request->table_id,
                'status' => OrderStatus::PENDING,
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
                'status' => OrderStatus::PENDING,
                'note' => $request->note,
            ]);

            return response()->json([
                'message' => 'Order Created',
                'data' => [
                    'id' => $order->id,
                    'order_type' => $order->order_type->value,
                    'status' => 'pending_payment',
                    'total_price' => (int) $order->total_price,
                    'items' => $orderItems,
                ]
            ], 201);
        });
    }

    public function show(Request $request, $id)
    {
        $order = Orders::with(['order_items.menu', 'order_addresses', 'deliveries', 'payments', 'order_logs', 'user', 'tables'])
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $order->id,
                'order_type' => $order->order_type->value,
                'status' => $order->status->value,
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
                ] : null,
                'delivery' => $order->deliveries->first() ? [
                    'courier' => $order->deliveries->first()->courier_name,
                    'tracking_number' => $order->deliveries->first()->courier_order_id,
                    'delivery_status' => $order->deliveries->first()->status->value,
                ] : null,
                'payments' => $order->payments->map(fn($payment) => [
                    'id' => $payment->id,
                    'payment_gateway' => $payment->payment_gateway,
                    'payment_method' => $payment->payment_method,
                    'amount' => (int) $payment->amount,
                    'status' => $payment->status->value,
                    'paid_at' => $payment->paid_at?->toIso8601String(),
                ]),
                'logs' => $order->order_logs->map(fn($log) => [
                    'status' => $log->status->value,
                    'note' => $log->note,
                    'created_at' => $log->created_at->toIso8601String(),
                ]),
            ]
        ]);
    }
}