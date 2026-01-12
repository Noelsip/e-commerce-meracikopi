<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menus;
use App\Models\Orders;
use App\Models\Tables;
use App\Models\User;
use App\Enums\OrderStatus;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // Fetch data from database
        $totalOrders = Orders::count();
        $totalMenus = Menus::count();
        $totalTables = Tables::count();
        $totalUsers = User::count();

        // Total pendapatan (dari order yang sudah selesai)
        $totalRevenue = Orders::where('status', OrderStatus::COMPLETED)->sum('total_price');

        // Orders terbaru (5 terakhir)
        $recentOrders = Orders::with('user')
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
}
