<x-layouts.admin :title="'Pesanan'">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold" style="color: #f0f2bd;">Daftar Pesanan</h1>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex flex-wrap gap-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-3">
            <select name="payment_status" class="px-4 py-2 rounded-lg text-sm"
                style="background-color: #2b211e; color: #f0f2bd; border: 1px solid #3e302b;">
                <option value="">Semua Status Pembayaran</option>
                @foreach($paymentStatuses as $status)
                    <option value="{{ $status->value }}" {{ request('payment_status') == $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <select name="order_status" class="px-4 py-2 rounded-lg text-sm"
                style="background-color: #2b211e; color: #f0f2bd; border: 1px solid #3e302b;">
                <option value="">Semua Status Pesanan</option>
                @foreach($orderStatuses as $status)
                    <option value="{{ $status->value }}" {{ request('order_status') == $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
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

            @if(request('payment_status') || request('order_status') || request('order_type'))
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
            <p class="text-sm font-medium" style="color: #f0f2bd;">Belum Bayar</p>
            <p class="text-2xl font-bold mt-1" style="color: #eab308;">
                {{ $orders->where('payment_status', App\Enums\StatusPayments::PENDING)->count() }}
            </p>
        </div>
        <div class="rounded-xl border p-4" style="background-color: #2b211e; border-color: #3e302b;">
            <p class="text-sm font-medium" style="color: #f0f2bd;">Sudah Bayar</p>
            <p class="text-2xl font-bold mt-1" style="color: #22c55e;">
                {{ $orders->where('payment_status', App\Enums\StatusPayments::PAID)->count() }}
            </p>
        </div>
        <div class="rounded-xl border p-4" style="background-color: #2b211e; border-color: #3e302b;">
            <p class="text-sm font-medium" style="color: #f0f2bd;">Dalam Proses</p>
            <p class="text-2xl font-bold mt-1" style="color: #3b82f6;">
                {{ $orders->whereIn('order_status', [App\Enums\OrderProcessStatus::PROCESSING, App\Enums\OrderProcessStatus::READY, App\Enums\OrderProcessStatus::ON_DELIVERY])->count() }}
            </p>
        </div>
        <div class="rounded-xl border p-4" style="background-color: #2b211e; border-color: #3e302b;">
            <p class="text-sm font-medium" style="color: #f0f2bd;">Selesai</p>
            <p class="text-2xl font-bold mt-1" style="color: #22c55e;">
                {{ $orders->where('order_status', App\Enums\OrderProcessStatus::COMPLETED)->count() }}
            </p>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="rounded-xl border overflow-hidden" style="background-color: #2b211e; border-color: #3e302b;">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background-color: #3e302b;">
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">ID</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">User</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Meja</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Tipe</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Status Pembayaran</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Status Pesanan</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Total</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Tanggal</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase" style="color: #f0f2bd;">Catatan</th>
                        <th class="px-3 py-3 text-right text-xs font-medium uppercase" style="color: #f0f2bd;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-t" style="border-color: #3e302b;">
                            <td class="px-3 py-3 text-sm" style="color: #f0f2bd;">#{{ $order->id }}</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm" style="color: #f0f2bd;">
                                {{ $order->customer_name ?? '-' }}</td>
                            <td class="px-3 py-3 text-sm" style="color: #f0f2bd;">
                                {{ $order->tables?->table_number ?? '-' }}
                            </td>
                            <td class="px-3 py-3 text-sm whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-xs font-medium whitespace-nowrap"
                                    style="background-color: #3e302b; color: #D4A574;">
                                    {{ $order->order_type->value == 'dine_in' ? 'Dine In' : ($order->order_type->value == 'take_away' ? 'Take Away' : 'Delivery') }}
                                </span>
                            </td>
                            {{-- Status Pembayaran (Badge, otomatis dari payment) --}}
                            <td class="px-3 py-3 text-sm">
                                <span class="px-4 py-1 rounded-full text-xs font-medium whitespace-nowrap inline-block text-center" 
                                      style="background-color: {{ $order->payment_status?->color() ?? '#eab308' }}; color: white; min-width: 140px;">
                                    {{ $order->payment_status?->label() ?? 'Menunggu Pembayaran' }}
                                </span>
                            </td>
                            {{-- Status Pesanan (Dropdown untuk admin input manual) --}}
                            <td class="px-3 py-3 text-sm">
                                <select onchange="updateOrderStatus({{ $order->id }}, this.value)"
                                    class="px-4 py-1 rounded-full text-xs cursor-pointer font-medium text-center"
                                    style="background-color: {{ $order->order_status?->color() ?? '#eab308' }}; border: none; color: white; min-width: 140px;">
                                    @foreach(App\Enums\OrderProcessStatus::cases() as $status)
                                        <option value="{{ $status->value }}" 
                                            {{ $order->order_status?->value == $status->value ? 'selected' : '' }}
                                            style="background-color: #2b211e; color: #f0f2bd;">
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-3 text-sm font-medium" style="color: #22c55e;">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-sm whitespace-nowrap" style="color: #f0f2bd;">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-3 py-3 text-sm" style="color: #f0f2bd; max-width: 120px;">
                                <span title="{{ $order->notes ?? '-' }}"
                                    style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $order->notes ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-right">
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
                        <td colspan="10" class="px-4 py-12 text-center" style="color: #f0f2bd;">
                            Belum ada pesanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
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
                body: JSON.stringify({ order_status: newStatus })
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