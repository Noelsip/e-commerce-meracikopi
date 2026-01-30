<x-layouts.admin :title="'Detail Pesanan'">
    <div class="max-w-4xl mx-auto">
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

        <!-- Order Header -->
        <div class="rounded-xl border p-6 mb-6" style="background-color: #2b211e; border-color: #3e302b;">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold" style="color: #f0f2bd;">Pesanan #{{ $order->id }}</h2>
                <span class="px-3 py-1 rounded-full text-sm font-medium"
                    style="background-color: {{ $order->status->value == 'pending' ? 'rgba(234, 179, 8, 0.2)' : ($order->status->value == 'process' ? 'rgba(59, 130, 246, 0.2)' : ($order->status->value == 'done' ? 'rgba(34, 197, 94, 0.2)' : 'rgba(239, 68, 68, 0.2)')) }};
                           color: {{ $order->status->value == 'pending' ? '#eab308' : ($order->status->value == 'process' ? '#3b82f6' : ($order->status->value == 'done' ? '#22c55e' : '#ef4444')) }};">
                    {{ ucfirst($order->status->value) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs" style="color: #f0f2bd; opacity: 0.7;">User</p>
                    <p class="text-sm font-medium" style="color: #f0f2bd;">{{ $order->user?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs" style="color: #f0f2bd; opacity: 0.7;">Meja</p>
                    <p class="text-sm font-medium" style="color: #f0f2bd;">{{ $order->tables?->table_number ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs" style="color: #f0f2bd; opacity: 0.7;">Tipe Pesanan</p>
                    <p class="text-sm font-medium" style="color: #D4A574;">
                        {{ $order->order_type->value == 'dine_in' ? 'Dine In' : ($order->order_type->value == 'take_away' ? 'Take Away' : 'Delivery') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs" style="color: #f0f2bd; opacity: 0.7;">Tanggal Pesanan</p>
                    <p class="text-sm font-medium" style="color: #f0f2bd;">
                        {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs" style="color: #f0f2bd; opacity: 0.7;">Total</p>
                    <p class="text-lg font-bold" style="color: #22c55e;">Rp
                        {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="rounded-xl border overflow-hidden" style="background-color: #2b211e; border-color: #3e302b;">
            <div class="px-6 py-4" style="background-color: #3e302b;">
                <h3 class="font-semibold" style="color: #f0f2bd;">Item Pesanan</h3>
            </div>

            @if($order->order_items && $order->order_items->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom: 1px solid #3e302b;">
                            <th class="px-6 py-3 text-left text-xs font-medium" style="color: #f0f2bd;">Menu</th>
                            <th class="px-6 py-3 text-center text-xs font-medium" style="color: #f0f2bd;">Qty</th>
                            <th class="px-6 py-3 text-right text-xs font-medium" style="color: #f0f2bd;">Harga</th>
                            <th class="px-6 py-3 text-right text-xs font-medium" style="color: #f0f2bd;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->order_items as $item)
                            <tr style="border-bottom: 1px solid #3e302b;">
                                <td class="px-6 py-3 text-sm" style="color: #f0f2bd;">
                                    {{ $item->menu?->name ?? 'Menu tidak ditemukan' }}</td>
                                <td class="px-6 py-3 text-sm text-center" style="color: #f0f2bd;">{{ $item->quantity }}</td>
                                <td class="px-6 py-3 text-sm text-right" style="color: #f0f2bd;">Rp
                                    {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-3 text-sm text-right font-medium" style="color: #22c55e;">Rp
                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #3e302b;">
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium" style="color: #f0f2bd;">Total
                            </td>
                            <td class="px-6 py-3 text-right text-lg font-bold" style="color: #22c55e;">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="px-6 py-12 text-center" style="color: #f0f2bd;">
                    Tidak ada item dalam pesanan ini.
                </div>
            @endif
        </div>

        <!-- Order Notes -->
        @if($order->notes)
        <div class="rounded-xl border overflow-hidden mt-6" style="background-color: #2b211e; border-color: #3e302b;">
            <div class="px-6 py-4" style="background-color: #3e302b;">
                <h3 class="font-semibold" style="color: #f0f2bd;">Catatan Pesanan</h3>
            </div>
            <div class="px-6 py-4">
                <p class="text-sm" style="color: #f0f2bd; white-space: pre-line;">{{ $order->notes }}</p>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex gap-3 mt-6">
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="px-4 py-2 rounded-lg text-sm font-medium"
                style="background-color: #3b82f6; color: white;">
                Edit Pesanan
            </a>
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                onsubmit="return confirm('Hapus pesanan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium"
                    style="background-color: #ef4444; color: white;">
                    Hapus Pesanan
                </button>
            </form>
        </div>
    </div>
</x-layouts.admin>