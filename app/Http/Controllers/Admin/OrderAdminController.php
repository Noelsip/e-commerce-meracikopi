<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Tables;
use App\Models\User;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Orders::with(['user', 'tables'])->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by order type
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        $orders = $query->paginate(15);
        $statuses = OrderStatus::cases();
        $orderTypes = OrderType::cases();

        return view('admin.orders.index', compact('orders', 'statuses', 'orderTypes'));
    }

    public function show(Orders $order)
    {
        $order->load(['user', 'tables', 'order_items']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Orders $order)
    {
        $statuses = OrderStatus::cases();
        $orderTypes = OrderType::cases();
        $tables = Tables::where('is_active', true)->get();

        return view('admin.orders.edit', compact('order', 'statuses', 'orderTypes', 'tables'));
    }

    public function update(Request $request, Orders $order)
    {
        $request->validate([
            'status' => 'required|in:pending,process,done,cancelled',
            'order_type' => 'required|in:dine_in,take_away,delivery',
            'table_id' => 'nullable|exists:tables,id',
        ]);

        $order->update($request->only(['status', 'order_type', 'table_id']));

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy(Orders $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }

    // Quick status update via AJAX
    public function updateStatus(Request $request, Orders $order)
    {
        $request->validate([
            'status' => 'required|in:pending,process,done,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status pesanan berhasil diubah.']);
    }
}
