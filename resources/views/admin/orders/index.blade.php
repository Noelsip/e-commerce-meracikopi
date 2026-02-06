<x-layouts.admin :title="'Pesanan'">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold" style="color: #f0f2bd;">Daftar Pesanan</h1>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex flex-wrap gap-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-3">
            <select name="status" class="px-4 py-2 rounded-lg text-sm"
                style="background-color: #2b211e; color: #f0f2bd; border: 1px solid #3e302b;">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                        {{ ucfirst($status->value) }}
                    </option>
                @endforeach
            </select>

            <select name="order_type" class="px-4 py-2 rounded-lg text-sm"
                style="background-color: #2b211e; color: #f0f2bd; border: 1px solid #3e302b;">
                <option value="">Semua Tipe</option>
                @foreach($orderTypes as $type)
                    <option value="{{ $type->value }}" {{ request('order_type') == $type->value ? 'selected' : '' }}>
                        {{ $type->value == 'dine_in' ? 'Dine In' : ($type->value == 'take_away' ? 'Take Away' : 'Delivery') }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium"
                style="background-color: #D4A574; color: #1e1715;">
                Filter
            </button>

            @if(request('status') || request('order_type'))
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-lg text-sm"
                    style="background-color: #3e302b; color: #f0f2bd;">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="rounded-xl border p-4" style="background-color: #2b211e; border-color: #3e302b;">
            <p class="text-sm font-medium" style="color: #f0f2bd;">Pending</p>
            <p class="text-2xl font-bold mt-1" style="color: #eab308;">
                {{ $orders->whereIn('status', [App\Enums\OrderStatus::CREATED, App\Enums\OrderStatus::PENDING_PAYMENT])->count() }}
            </p>
        </div>
        <div class="rounded-xl border p-4" style="background-color: #2b211e; border-color: #3e302b;">
            <p class="text-sm font-medium" style="color: #f0f2bd;">Proses</p>
            <p class="text-2xl font-bold mt-1" style="color: #3b82f6;">
                {{ $orders->whereIn('status', [App\Enums\OrderStatus::PAID, App\Enums\OrderStatus::PROCESSING, App\Enums\OrderStatus::READY, App\Enums\OrderStatus::ON_DELIVERY])->count() }}
            </p>
        </div>
        <div class="rounded-xl border p-4" style="background-color: #2b211e; border-color: #3e302b;">
            <p class="text-sm font-medium" style="color: #f0f2bd;">Selesai</p>
            <p class="text-2xl font-bold mt-1" style="color: #22c55e;">
                {{ $orders->where('status', App\Enums\OrderStatus::COMPLETED)->count() }}
            </p>
        </div>
        <div class="rounded-xl border p-4" style="background-color: #2b211e; border-color: #3e302b;">
            <p class="text-sm font-medium" style="color: #f0f2bd;">Dibatalkan</p>
            <p class="text-2xl font-bold mt-1" style="color: #ef4444;">
                {{ $orders->where('status', App\Enums\OrderStatus::CANCELLED)->count() }}
            </p>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="rounded-xl border overflow-hidden overflow-x-auto"
        style="background-color: #2b211e; border-color: #3e302b;">
        <table class="w-full min-w-[900px]">
            <thead>
                <tr style="background-color: #3e302b;">
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Meja</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Tipe</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Catatan</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase" style="color: #f0f2bd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-t" style="border-color: #3e302b;">
                        <td class="px-4 py-3 text-sm" style="color: #f0f2bd;">#{{ $order->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300" style="color: #f0f2bd;">
                            {{ $order->customer_name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm" style="color: #f0f2bd;">
                            {{ $order->tables?->table_number ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-medium whitespace-nowrap"
                                style="background-color: #3e302b; color: #D4A574;">
                                {{ $order->order_type->value == 'dine_in' ? 'Dine In' : ($order->order_type->value == 'take_away' ? 'Take Away' : 'Delivery') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @php
                                $statusColors = [
                                    'created' => '#eab308',
                                    'pending_payment' => '#eab308',
                                    'paid' => '#3b82f6',
                                    'processing' => '#3b82f6',
                                    'ready' => '#22c55e',
                                    'on_delivery' => '#f97316',
                                    'completed' => '#22c55e',
                                    'cancelled' => '#ef4444',
                                ];
                                $currentColor = $statusColors[$order->status->value] ?? '#f0f2bd';
                            @endphp
                            <select onchange="updateOrderStatus({{ $order->id }}, this.value)"
                                class="px-2 py-1 rounded text-xs cursor-pointer"
                                style="background-color: #3e302b; border: none; color: {{ $currentColor }};">
                                <option value="created" {{ $order->status->value == 'created' ? 'selected' : '' }}>Dibuat
                                </option>
                                <option value="pending_payment" {{ $order->status->value == 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="paid" {{ $order->status->value == 'paid' ? 'selected' : '' }}>Dibayar</option>
                                <option value="processing" {{ $order->status->value == 'processing' ? 'selected' : '' }}>
                                    Diproses</option>
                                <option value="ready" {{ $order->status->value == 'ready' ? 'selected' : '' }}>Siap</option>
                                <option value="on_delivery" {{ $order->status->value == 'on_delivery' ? 'selected' : '' }}>
                                    Diantar</option>
                                <option value="completed" {{ $order->status->value == 'completed' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="cancelled" {{ $order->status->value == 'cancelled' ? 'selected' : '' }}>Batal
                                </option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-sm font-medium" style="color: #22c55e;">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm" style="color: #f0f2bd;">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm" style="color: #f0f2bd; max-width: 150px;">
                            <span title="{{ $order->notes ?? '-' }}"
                                style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $order->notes ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-2 py-1 rounded text-xs"
                                    style="background-color: #3e302b; color: #f0f2bd;">
                                    Detail
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="px-2 py-1 rounded text-xs"
                                    style="background-color: #3b82f6; color: white;">
                                    Edit
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus pesanan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 rounded text-xs"
                                        style="background-color: #ef4444; color: white;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center" style="color: #f0f2bd;">
                            Belum ada pesanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $orders->withQueryString()->links() }}
    </div>

    <script>
        function updateOrderStatus(orderId, newStatus) {
            fetch(`/admin/orders/${orderId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: newStatus })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</x-layouts.admin>