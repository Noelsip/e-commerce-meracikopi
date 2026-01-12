<x-layouts.admin :title="'Dashboard'">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Card Total Pesanan -->
        <div class="rounded-xl border p-6" style="background-color: #2b211e; border-color: #3e302b;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #f0f2bd;">Total Pesanan</p>
                    <p class="text-3xl font-bold mt-2" style="color: #f0f2bd;">{{ $totalOrders ?? 0 }}</p>
                </div>
                <div class="p-3 rounded-lg" style="background-color: #3e302b;">
                    <svg class="w-6 h-6" style="color: #f0f2bd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card Total Menu -->
        <div class="rounded-xl border p-6" style="background-color: #2b211e; border-color: #3e302b;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #f0f2bd;">Total Menu</p>
                    <p class="text-3xl font-bold mt-2" style="color: #f0f2bd;">{{ $totalMenus ?? 0 }}</p>
                </div>
                <div class="p-3 rounded-lg" style="background-color: #3e302b;">
                    <svg class="w-6 h-6" style="color: #f0f2bd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card Total Meja -->
        <div class="rounded-xl border p-6" style="background-color: #2b211e; border-color: #3e302b;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #f0f2bd;">Total Meja</p>
                    <p class="text-3xl font-bold mt-2" style="color: #f0f2bd;">{{ $totalTables ?? 0 }}</p>
                </div>
                <div class="p-3 rounded-lg" style="background-color: #3e302b;">
                    <svg class="w-6 h-6" style="color: #f0f2bd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card Total Pendapatan -->
        <div class="rounded-xl border p-6" style="background-color: #2b211e; border-color: #3e302b;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #f0f2bd;">Total Pendapatan</p>
                    <p class="text-3xl font-bold mt-2" style="color: #f0f2bd;">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 rounded-lg" style="background-color: #3e302b;">
                    <svg class="w-6 h-6" style="color: #f0f2bd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="rounded-xl border p-6" style="background-color: #2b211e; border-color: #3e302b;">
        <h3 class="text-lg font-semibold mb-4" style="color: #f0f2bd;">Pesanan Terbaru</h3>
        
        @if(isset($recentOrders) && $recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom: 1px solid #3e302b;">
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">ID</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Customer</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Total</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Status</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr style="border-bottom: 1px solid #3e302b;">
                                <td class="py-3 px-4 text-sm" style="color: #ccc;">#{{ $order->id }}</td>
                                <td class="py-3 px-4 text-sm" style="color: #ccc;">{{ $order->user->name ?? 'Guest' }}</td>
                                <td class="py-3 px-4 text-sm" style="color: #ccc;">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium" 
                                          style="background-color: {{ $order->status == 'completed' ? '#22c55e' : ($order->status == 'pending' ? '#eab308' : '#3b82f6') }}; color: white;">
                                        {{ ucfirst($order->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm" style="color: #ccc;">{{ $order->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-12 h-12 mx-auto mb-4" style="color: #f0f2bd;" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p style="color: #f0f2bd;">Belum ada pesanan</p>
            </div>
        @endif
    </div>
</x-layouts.admin>