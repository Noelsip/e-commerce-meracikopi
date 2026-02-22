<x-layouts.admin :title="'Dashboard'">
    <!-- Store Status Toggle -->
    <div id="storeToggleCard" class="rounded-xl border p-5 mb-6 transition-all duration-500"
         style="border-color: #3e302b; background: {{ $storeOpen ? 'linear-gradient(135deg, #1a2e1a 0%, #2b211e 50%)' : 'linear-gradient(135deg, #2e1a1a 0%, #2b211e 50%)' }};">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <!-- Status Indicator -->
                <div id="storeStatusDot" class="relative flex-shrink-0">
                    <div class="w-4 h-4 rounded-full {{ $storeOpen ? 'bg-green-500' : 'bg-red-500' }} transition-colors duration-500"
                         id="statusDot"></div>
                    <div class="absolute inset-0 w-4 h-4 rounded-full {{ $storeOpen ? 'bg-green-500' : 'bg-red-500' }} animate-ping opacity-30"
                         id="statusDotPing"></div>
                </div>
                <div>
                    <h2 class="text-lg font-bold" style="color: #f0f2bd;">Status Webstore</h2>
                    <p class="text-sm mt-0.5" id="storeStatusText">
                        <span style="color: {{ $storeOpen ? '#4ade80' : '#f87171' }};">
                            {{ $storeOpen ? '● Toko sedang BUKA — pelanggan dapat memesan' : '● Toko sedang TUTUP — pelanggan tidak bisa memesan' }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium" style="color: rgba(240,242,189,0.7);" id="toggleLabel">
                    {{ $storeOpen ? 'Buka' : 'Tutup' }}
                </span>
                <button id="storeToggleBtn" type="button" onclick="toggleStore()" 
                        class="store-toggle-switch {{ $storeOpen ? 'active' : '' }}"
                        title="{{ $storeOpen ? 'Klik untuk tutup toko' : 'Klik untuk buka toko' }}">
                    <span class="store-toggle-knob"></span>
                </button>
            </div>
        </div>
    </div>

    <style>
        .store-toggle-switch {
            position: relative;
            width: 64px;
            height: 34px;
            border-radius: 34px;
            background: #4b3a32;
            border: 2px solid #5a463c;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            flex-shrink: 0;
        }
        .store-toggle-switch:hover {
            border-color: #CA7842;
        }
        .store-toggle-switch.active {
            background: #22c55e;
            border-color: #16a34a;
        }
        .store-toggle-knob {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #f0f2bd;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        .store-toggle-switch.active .store-toggle-knob {
            left: 33px;
        }
        .store-toggle-switch:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>

    <script>
        async function toggleStore() {
            const btn = document.getElementById('storeToggleBtn');
            const card = document.getElementById('storeToggleCard');
            const statusText = document.getElementById('storeStatusText');
            const toggleLabel = document.getElementById('toggleLabel');
            const statusDot = document.getElementById('statusDot');
            const statusDotPing = document.getElementById('statusDotPing');

            btn.disabled = true;

            try {
                const response = await fetch('{{ route("admin.store.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    credentials: 'same-origin',
                });

                const data = await response.json();

                if (data.store_open) {
                    btn.classList.add('active');
                    card.style.background = 'linear-gradient(135deg, #1a2e1a 0%, #2b211e 50%)';
                    statusText.innerHTML = '<span style="color: #4ade80;">● Toko sedang BUKA — pelanggan dapat memesan</span>';
                    toggleLabel.textContent = 'Buka';
                    btn.title = 'Klik untuk tutup toko';
                    statusDot.className = 'w-4 h-4 rounded-full bg-green-500 transition-colors duration-500';
                    statusDotPing.className = 'absolute inset-0 w-4 h-4 rounded-full bg-green-500 animate-ping opacity-30';
                } else {
                    btn.classList.remove('active');
                    card.style.background = 'linear-gradient(135deg, #2e1a1a 0%, #2b211e 50%)';
                    statusText.innerHTML = '<span style="color: #f87171;">● Toko sedang TUTUP — pelanggan tidak bisa memesan</span>';
                    toggleLabel.textContent = 'Tutup';
                    btn.title = 'Klik untuk buka toko';
                    statusDot.className = 'w-4 h-4 rounded-full bg-red-500 transition-colors duration-500';
                    statusDotPing.className = 'absolute inset-0 w-4 h-4 rounded-full bg-red-500 animate-ping opacity-30';
                }

                // Brief success flash
                card.style.boxShadow = data.store_open 
                    ? '0 0 20px rgba(34, 197, 94, 0.3)' 
                    : '0 0 20px rgba(248, 113, 113, 0.3)';
                setTimeout(() => card.style.boxShadow = 'none', 2000);

            } catch (e) {
                console.error('Toggle store error:', e);
                alert('Gagal mengubah status toko. Coba lagi.');
            } finally {
                btn.disabled = false;
            }
        }
    </script>

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
                    <p class="text-3xl font-bold mt-2" style="color: #f0f2bd;">Rp
                        {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
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
                <table class="w-full min-w-[800px]">
                    <thead>
                        <tr style="border-bottom: 1px solid #3e302b;">
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">ID</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Meja</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Tipe</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Customer</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Total</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Pembayaran</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Pesanan</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Tanggal</th>
                            <th class="text-left py-3 px-4 text-sm font-medium" style="color: #f0f2bd;">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr style="border-bottom: 1px solid #3e302b;">
                                <td class="py-3 px-4 text-sm" style="color: #f0f2bd;">#{{ $order->id }}</td>
                                <td class="py-3 px-4 text-sm" style="color: #f0f2bd;">{{ $order->tables?->table_number ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-medium whitespace-nowrap"
                                        style="background-color: #3e302b; color: #D4A574;">
                                        {{ $order->order_type->label() }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm" style="color: #f0f2bd;">{{ $order->customer_name ?? 'Guest' }}</td>
                                <td class="py-3 px-4 text-sm" style="color: #22c55e;">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>
                                {{-- Status Pembayaran --}}
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-4 py-1 rounded-full text-xs font-medium whitespace-nowrap inline-block text-center" 
                                          style="background-color: {{ $order->payment_status?->color() ?? '#eab308' }}; color: #1a1a1a; min-width: 140px;">
                                        {{ $order->payment_status?->label() ?? 'Menunggu Pembayaran' }}
                                    </span>
                                </td>
                                {{-- Status Pesanan --}}
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-4 py-1 rounded-full text-xs font-medium whitespace-nowrap inline-block text-center" 
                                          style="background-color: {{ $order->order_status?->color() ?? '#eab308' }}; color: #1a1a1a; min-width: 140px;">
                                        {{ $order->order_status?->label() ?? 'Menunggu Diproses' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm" style="color: #f0f2bd;">
                                    {{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-4 text-sm" style="color: #f0f2bd;">{{ $order->notes ?? '-' }}</td>
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