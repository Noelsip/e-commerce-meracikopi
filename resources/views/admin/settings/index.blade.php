<x-layouts.admin :title="'Settings'">
    <div class="max-w-2xl">
        <h1 class="text-2xl font-bold mb-6" style="color: #f0f2bd;">Pengaturan Toko</h1>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium"
                style="background-color: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.4); color: #4ade80;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Order Type Section -->
            <div class="rounded-xl border p-6" style="background-color: #2b211e; border-color: #3e302b;">
                <h2 class="text-lg font-semibold mb-1" style="color: #f0f2bd;">Jenis Pesanan (Order Type)</h2>
                <p class="text-sm mb-5" style="color: rgba(240,242,189,0.5);">
                    Aktifkan atau nonaktifkan jenis pesanan yang tersedia di halaman checkout.
                </p>

                <div class="space-y-4">
                    <!-- Takeaway -->
                    <div class="flex items-center justify-between p-4 rounded-lg" style="background-color: #3e302b;">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" style="color: #CA7842;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <div>
                                <p class="font-semibold" style="color: #f0f2bd;">Takeaway</p>
                                <p class="text-xs" style="color: rgba(240,242,189,0.5);">Pelanggan mengambil pesanan sendiri</p>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="order_type_takeaway" value="1"
                                {{ $settings['order_type_takeaway'] === '1' ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <!-- Dine In -->
                    <div class="flex items-center justify-between p-4 rounded-lg" style="background-color: #3e302b;">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" style="color: #CA7842;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="font-semibold" style="color: #f0f2bd;">Dine In</p>
                                <p class="text-xs" style="color: rgba(240,242,189,0.5);">Pelanggan makan di tempat</p>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="order_type_dine_in" value="1"
                                {{ $settings['order_type_dine_in'] === '1' ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <!-- Delivery -->
                    <div class="flex items-center justify-between p-4 rounded-lg" style="background-color: #3e302b;">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" style="color: #CA7842;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                            <div>
                                <p class="font-semibold" style="color: #f0f2bd;">Delivery</p>
                                <p class="text-xs" style="color: rgba(240,242,189,0.5);">Pesanan diantarkan ke alamat pelanggan</p>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="order_type_delivery" value="1"
                                {{ $settings['order_type_delivery'] === '1' ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="px-6 py-2 rounded-lg font-semibold text-sm transition-colors"
                        style="background-color: #CA7842; color: white;">
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            inset: 0;
            background-color: rgba(255,255,255,0.15);
            border-radius: 28px;
            transition: 0.3s;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            border-radius: 50%;
            transition: 0.3s;
        }

        .toggle-switch input:checked + .toggle-slider {
            background-color: #22c55e;
        }

        .toggle-switch input:checked + .toggle-slider::before {
            transform: translateX(24px);
        }
    </style>
</x-layouts.admin>
