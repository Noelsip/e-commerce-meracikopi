<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menus;
use App\Models\Orders;
use App\Models\Tables;
use App\Models\User;
use App\Enums\OrderStatus;
use App\Enums\StatusPayments;

class DashboardAdminController extends Controller
{


    public function index()
    {
        // Fetch data from database
        $totalOrders = Orders::count();
        $totalMenus = Menus::count();
        $totalTables = Tables::count();
        $totalUsers = User::count();

        // Total pendapatan (dari order yang pembayarannya sudah berhasil)
        // Gunakan final_price, fallback ke total_price untuk order lama yang belum ada final_price
        $totalRevenue = Orders::where('payment_status', StatusPayments::PAID)
            ->sum(\DB::raw('COALESCE(NULLIF(final_price, 0), total_price)'));

        // Orders terbaru (5 terakhir)
        $recentOrders = Orders::with(['user', 'tables'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalMenus',
            'totalTables',
            'totalUsers',
            'totalRevenue',
            'recentOrders'
        ));
    }

    /**
     * API: Check for new paid orders since last check (used by polling notifications)
     */
    public function checkNewPaidOrders(Request $request)
    {
        $lastChecked = $request->query('last_checked');

        $query = Orders::with('tables')
            ->where('payment_status', StatusPayments::PAID);

        if ($lastChecked) {
            // Get orders that were updated to PAID after last check
            $query->where('updated_at', '>', $lastChecked);
        } else {
            // First check: don't show old orders, only start tracking from now
            return response()->json([
                'new_orders' => [],
                'count' => 0,
                'server_time' => now()->toIso8601String(),
            ]);
        }

        $newOrders = $query->orderBy('updated_at', 'desc')->get();

        return response()->json([
            'new_orders' => $newOrders->map(fn($order) => [
                'id' => $order->id,
                'customer_name' => $order->customer_name ?? 'Guest',
                'order_type' => $order->order_type?->label() ?? '-',
                'table_number' => $order->tables?->table_number ?? null,
                'total' => 'Rp ' . number_format($order->final_price ?: $order->total_price, 0, ',', '.'),
                'paid_at' => $order->updated_at->format('H:i'),
            ]),
            'count' => $newOrders->count(),
            'server_time' => now()->toIso8601String(),
        ]);
    }
}
