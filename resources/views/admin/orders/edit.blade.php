<x-layouts.admin :title="'Edit Pesanan'">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center gap-2 text-sm hover:opacity-80 transition-opacity whitespace-nowrap"
                style="color: #D4A574;">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali ke Daftar Pesanan</span>
            </a>
        </div>

        <div class="rounded-xl p-6" style="background-color: #2b211e; border: 1px solid #3e302b;">
            <h2 class="text-xl font-bold mb-6" style="color: #f0f2bd;">Edit Pesanan #{{ $order->id }}</h2>

            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Status -->
                <div class="mb-5">
                    <label for="status" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Status <span class="text-red-400">*</span>
                    </label>
                    <select name="status" id="status"
                        class="w-full px-4 py-3 rounded-lg border-none focus:ring-2 focus:ring-amber-500"
                        style="background-color: #3e302b; color: #f0f2bd;" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ old('status', $order->status->value) == $status->value ? 'selected' : '' }}>
                                {{ ucfirst($status->value) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Type -->
                <div class="mb-5">
                    <label for="order_type" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Tipe Pesanan <span class="text-red-400">*</span>
                    </label>
                    <select name="order_type" id="order_type"
                        class="w-full px-4 py-3 rounded-lg border-none focus:ring-2 focus:ring-amber-500"
                        style="background-color: #3e302b; color: #f0f2bd;" required>
                        @foreach($orderTypes as $type)
                            <option value="{{ $type->value }}" {{ old('order_type', $order->order_type->value) == $type->value ? 'selected' : '' }}>
                                {{ $type->value == 'dine_in' ? 'Dine In' : ($type->value == 'take_away' ? 'Take Away' : 'Delivery') }}
                            </option>
                        @endforeach
                    </select>
                    @error('order_type')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Table -->
                <div class="mb-5">
                    <label for="table_id" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Meja
                    </label>
                    <select name="table_id" id="table_id"
                        class="w-full px-4 py-3 rounded-lg border-none focus:ring-2 focus:ring-amber-500"
                        style="background-color: #3e302b; color: #f0f2bd;">
                        <option value="">Tidak ada meja</option>
                        @foreach($tables as $table)
                            <option value="{{ $table->id }}" {{ old('table_id', $order->table_id) == $table->id ? 'selected' : '' }}>
                                Meja {{ $table->table_number }} ({{ $table->capacity }} kursi)
                            </option>
                        @endforeach
                    </select>
                    @error('table_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info (readonly) -->
                <div class="mb-6 p-4 rounded-lg" style="background-color: #3e302b;">
                    <p class="text-xs mb-2" style="color: #f0f2bd; opacity: 0.7;">Informasi Pesanan</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs" style="color: #f0f2bd; opacity: 0.7;">User</p>
                            <p class="text-sm font-medium" style="color: #f0f2bd;">{{ $order->user?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs" style="color: #f0f2bd; opacity: 0.7;">Total</p>
                            <p class="text-sm font-bold" style="color: #22c55e;">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 px-6 py-3 rounded-lg font-semibold transition-colors hover:opacity-90"
                        style="background-color: #D4A574; color: #1e1715;">
                        Update Pesanan
                    </button>
                    <a href="{{ route('admin.orders.index') }}"
                        class="px-6 py-3 rounded-lg font-semibold transition-colors hover:opacity-80 text-center"
                        style="background-color: #3e302b; color: #f0f2bd;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>