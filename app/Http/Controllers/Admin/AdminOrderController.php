<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Orders;
use App\Enums\OrderStatus;
use App\Models\OrderLogs;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Orders::query()
            ->with('payments')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan type
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(10);

        return response()->json([
            'data' => $orders->map(fn($order) => [
                'id' => $order->id,
                'order_type' => $order->order_type,
                'status' => $order->status,
                'total_price' => (int) $order->total_price,
                'customer_name' => $order->customer_name,
                'payment_status' => $order->payments->first()?->status ?? null,
                'created_at' => $order->created_at->toIso8601String(),
            ]),
        ]);
    }

    public function show($id)
    {
        $order = Orders::with([
            'items.menu',
            'order_addresses',
            'payments',
            'logs',
            'delivery',
        ])->findOrFail($id);

        return response()->json([
            'data' => $order
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'note' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($id, $request) {
            $order = Orders::lockForUpdate()->findOrFail($id);

            $newStatus = OrderStatus::from($request->status);

            $order->update([
                'status' => $newStatus,
            ]);

            OrderLogs::create([
                'order_id' => $order->id,
                'status' => $newStatus,
                'note' => $request->note ?? 'Updated by admin',
            ]);

            return response()->json([
                'message' => 'Order status updated',
                'data' => [
                    'order_id' => $order->id,
                    'new_status' => $newStatus,
                ]
            ]);
        });
    }
}
