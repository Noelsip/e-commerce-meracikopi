<x-layouts.admin :title="'Table Management'">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold" style="color: #f0f2bd;">Daftar Meja</h2>
        <a href="{{ route('admin.tables.create') }}"
            class="px-4 py-2 rounded-lg font-semibold transition-colors hover:opacity-90"
            style="background-color: #D4A574; color: #1e1715;">
            + Tambah Meja
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg" style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #D4A574;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Buttons -->
    <div class="mb-6 flex gap-3 flex-wrap">
        <button onclick="filterTables('all')"
            class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium transition-all" data-filter="all">
            Semua
        </button>
        <button onclick="filterTables('available')"
            class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all" data-filter="available">
            Tersedia
        </button>
        <button onclick="filterTables('occupied')"
            class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all" data-filter="occupied">
            Terisi
        </button>
        <button onclick="filterTables('reserved')"
            class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-all" data-filter="reserved">
            Reserved
        </button>
    </div>

    <!-- Summary Stats -->
    <div class="mb-6 flex gap-4">
        <div class="flex-1 rounded-xl p-4 text-center" style="background-color: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e;">
            <p class="text-3xl font-bold" style="color: #22c55e;">
                {{ $tables->where('status', 'available')->count() }}
            </p>
            <p class="text-sm" style="color: #f0f2bd;">Meja Tersedia</p>
        </div>
        <div class="flex-1 rounded-xl p-4 text-center" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444;">
            <p class="text-3xl font-bold" style="color: #ef4444;">
                {{ $tables->where('status', 'occupied')->count() }}
            </p>
            <p class="text-sm" style="color: #f0f2bd;">Meja Terisi</p>
        </div>
        <div class="flex-1 rounded-xl p-4 text-center" style="background-color: rgba(234, 179, 8, 0.1); border: 1px solid #eab308;">
            <p class="text-3xl font-bold" style="color: #eab308;">
                {{ $tables->where('status', 'reserved')->count() }}
            </p>
            <p class="text-sm" style="color: #f0f2bd;">Meja Reserved</p>
        </div>
    </div>

    <!-- Table Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="tables-grid">
        @forelse($tables as $table)
            <div class="table-card rounded-xl border p-6"
                data-status="{{ $table->status }}"
                style="background-color: #2b211e; border-color: #3e302b;">
                
                <p class="text-lg font-semibold" style="color: #f0f2bd;">Meja {{ $table->table_number }}</p>
                
                <p class="text-xl font-bold mt-2" style="color: {{ $table->status === 'available' ? '#22c55e' : ($table->status === 'occupied' ? '#ef4444' : '#eab308') }};">
                    {{ $table->capacity }}
                </p>
                
                <p class="text-xs mt-4" style="color: {{ $table->status === 'available' ? '#22c55e' : ($table->status === 'occupied' ? '#ef4444' : '#eab308') }};">
                    {{ $table->status === 'available' ? 'Tersedia' : ($table->status === 'occupied' ? 'Terisi' : 'Reserved') }} · {{ $table->capacity }} Kursi
                </p>

                    <div class="flex flex-col gap-2 flex-1">
                        <select onchange="updateStatus({{ $table->id }}, this.value)"
                            class="px-3 py-2 text-xs cursor-pointer rounded-lg"
                            style="background-color: #3e302b; color: #f0f2bd; border: none;">
                            <option value="available" {{ $table->status === 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="occupied" {{ $table->status === 'occupied' ? 'selected' : '' }}>Terisi</option>
                            <option value="reserved" {{ $table->status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                        </select>
                        
                        <div class="flex gap-2">
                            <button onclick="showQRCode({{ $table->id }}, '{{ $table->table_number }}', '{{ $table->qr_code_path ? asset('storage/' . $table->qr_code_path) : '' }}')"
                                    class="flex-1 px-3 py-2 text-xs rounded-lg text-center"
                                    style="background-color: #3e302b; color: #D4A574; border: 1px solid #D4A574;">
                                QR Code
                            </button>
                            
                            <a href="{{ route('admin.tables.edit', $table->id) }}"
                                class="flex-1 px-3 py-2 text-xs rounded-lg text-center"
                                style="background-color: #3e302b; color: #f0f2bd;">
                                Edit
                            </a>
                            
                            <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST"
                                onsubmit="return confirm('Hapus meja ini?');" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-3 py-2 text-xs rounded-lg"
                                    style="background-color: #3e302b; color: #ef4444;">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center" style="color: #f0f2bd;">
                <div class="flex flex-col items-center justify-center">
                    <p class="text-lg mb-4">Belum ada meja.</p>
                    <a href="{{ route('admin.tables.create') }}" class="px-4 py-2 rounded-lg font-semibold"
                        style="background-color: #D4A574; color: #1e1715;">
                        Tambah Meja Pertama
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $tables->links() }}

    <style>
        .filter-btn {
            background-color: #3e302b;
            color: #f0f2bd;
            border: 1px solid transparent;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background-color: #D4A574;
            color: #1e1715;
        }
    </style>

    <script>
        function filterTables(status) {
            const cards = document.querySelectorAll('.table-card');
            const buttons = document.querySelectorAll('.filter-btn');

            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.filter === status) {
                    btn.classList.add('active');
                }
            });

            cards.forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function updateStatus(tableId, newStatus) {
            fetch(`/admin/tables/${tableId}/status`, {
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
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengubah status meja.');
                });
        }
    </script>
</x-layouts.admin>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="background-color: rgba(0,0,0,0.8);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-sm rounded-2xl p-8 text-center" style="background-color: #2b211e; border: 1px solid #D4A574;">
            <button onclick="closeQRModal()" class="absolute top-4 right-4 text-2xl" style="color: #a89890;">×</button>
            
            <h3 class="text-xl font-bold mb-6" style="color: #f0f2bd;">QR Code Meja <span id="modalTableNumber"></span></h3>
            
            <div id="qrPlaceholder" class="aspect-square w-full rounded-xl mb-6 flex items-center justify-center" style="background-color: #3e302b;">
                <p style="color: #a89890;">Memuat QR Code...</p>
            </div>
            
            <img id="qrImage" src="" alt="QR Code" class="w-full aspect-square rounded-xl mb-6 hidden border-4 border-white">
            
            <div class="flex flex-col gap-3">
                <a id="downloadBtn" href="" download="" class="px-4 py-3 rounded-xl font-bold hidden" 
                   style="background-color: #D4A574; color: #1e1715;">
                    Download QR Code
                </a>
                
                <button onclick="regenerateQR()" class="px-4 py-3 rounded-xl font-semibold" 
                        style="background-color: transparent; color: #D4A574; border: 1px solid #D4A574;">
                    Regenerate Token & QR
                </button>
                
                <button onclick="closeQRModal()" class="px-4 py-3 rounded-xl font-semibold" 
                        style="background-color: #3e302b; color: #f0f2bd;">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentTableId = null;

    function showQRCode(id, number, path) {
        currentTableId = id;
        document.getElementById('modalTableNumber').textContent = number;
        document.getElementById('qrModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        if (path) {
            updateQRDisplay(path);
        } else {
            generateQR(id);
        }
    }

    function closeQRModal() {
        document.getElementById('qrModal').classList.add('hidden');
        document.body.style.overflow = '';
        // Reset display
        document.getElementById('qrImage').classList.add('hidden');
        document.getElementById('downloadBtn').classList.add('hidden');
        document.getElementById('qrPlaceholder').classList.remove('hidden');
    }

    function updateQRDisplay(path) {
        const img = document.getElementById('qrImage');
        const download = document.getElementById('downloadBtn');
        const placeholder = document.getElementById('qrPlaceholder');
        
        img.src = path;
        img.classList.remove('hidden');
        
        download.href = path;
        download.download = `QR_Meja_${document.getElementById('modalTableNumber').textContent}.png`;
        download.classList.remove('hidden');
        
        placeholder.classList.add('hidden');
    }

    function generateQR(id) {
        fetch(`/admin/tables/${id}/generate-qr`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.data && data.data.qr_code_url) {
                updateQRDisplay(data.data.qr_code_url);
            }
        })
        .catch(error => alert('Gagal generate QR Code'));
    }

    function regenerateQR() {
        if (!confirm('Token lama akan tidak berlaku. Lanjutkan?')) return;
        
        const placeholder = document.getElementById('qrPlaceholder');
        const img = document.getElementById('qrImage');
        const download = document.getElementById('downloadBtn');
        
        img.classList.add('hidden');
        download.classList.add('hidden');
        placeholder.classList.remove('hidden');
        placeholder.innerHTML = '<p style="color: #a89890;">Regenerating...</p>';

        fetch(`/admin/tables/${currentTableId}/regenerate-qr`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.data && data.data.qr_code_url) {
                updateQRDisplay(data.data.qr_code_url);
                placeholder.innerHTML = '<p style="color: #a89890;">Memuat QR Code...</p>';
            }
        })
        .catch(error => alert('Gagal regenerate QR Code'));
    }
</script>