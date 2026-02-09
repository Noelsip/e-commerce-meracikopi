<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Tables;
use App\Models\User;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\OrderProcessStatus;
use App\Enums\StatusPayments;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Orders::with(['user', 'tables'])->latest();

        // Filter by order status
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by order type
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        $orders = $query->paginate(15);
        $orderStatuses = OrderProcessStatus::cases();
        $paymentStatuses = StatusPayments::cases();
        $orderTypes = OrderType::cases();

        return view('admin.orders.index', compact('orders', 'orderStatuses', 'paymentStatuses', 'orderTypes'));
    }

    public function show(Orders $order)
    {
        $order->load(['user', 'tables', 'order_items']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Orders $order)
    {
        $orderStatuses = OrderProcessStatus::cases();
        $paymentStatuses = StatusPayments::cases();
        $orderTypes = OrderType::cases();
        $tables = Tables::where('is_active', true)->get();

        return view('admin.orders.edit', compact('order', 'orderStatuses', 'paymentStatuses', 'orderTypes', 'tables'));
    }

    public function update(Request $request, Orders $order)
    {
        $request->validate([
            'order_status' => 'required|in:' . implode(',', array_column(OrderProcessStatus::cases(), 'value')),
            'order_type' => 'required|in:dine_in,take_away,delivery',
            'table_id' => 'nullable|exists:tables,id',
        ]);

        $order->update($request->only(['order_status', 'order_type', 'table_id']));

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy(Orders $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }

    // Quick order status update via AJAX (manual by admin)
    public function updateStatus(Request $request, Orders $order)
    {
        $request->validate([
            'order_status' => 'required|in:' . implode(',', array_column(OrderProcessStatus::cases(), 'value')),
        ]);

        $order->update(['order_status' => $request->order_status]);

        return response()->json(['success' => true, 'message' => 'Status pesanan berhasil diubah.']);
    }
}
