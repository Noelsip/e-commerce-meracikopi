<x-layouts.admin :title="'Catalog / Menu Management'">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold" style="color: #f0f2bd;">Daftar Menu</h2>
        <a href="{{ route('admin.menus.create') }}"
            class="px-4 py-2 rounded-lg font-semibold transition-colors hover:opacity-90"
            style="background-color: #D4A574; color: #1e1715;">
            + Tambah Menu
        </a>
    </div>

    <!-- Custom Filter Styles -->
    <style>
        .custom-search-container {
            background-color: #1e1715;
            border: 1px solid #3e302b;
            transition: all 0.3s ease;
        }

        .custom-search-container:focus-within {
            border-color: #f0f2bd;
            box-shadow: 0 0 0 1px #f0f2bd;
        }

        .custom-search-input {
            color: #f0f2bd;
        }

        .custom-search-input::placeholder {
            color: rgba(240, 242, 189, 0.5);
            /* #f0f2bd with opacity */
        }

        .custom-search-icon {
            color: #6b7280;
            /* gray-500 */
            transition: color 0.3s ease;
        }

        .custom-search-container:focus-within .custom-search-icon {
            color: #f0f2bd;
        }
    </style>

    <!-- Filters -->
    <div class="mb-6 space-y-4">
        <!-- Search Bar -->
        <div class="relative">
            <form action="{{ route('admin.menus.index') }}" method="GET">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <div class="relative w-full">
                    <!-- Flex Container Search Bar -->
                    <div class="custom-search-container flex items-center w-full rounded-xl px-4 py-3 shadow-sm group">
                        <!-- Icon -->
                        <svg class="custom-search-icon h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>

                        <!-- Input -->
                        <input type="text" name="search" placeholder="Cari nama menu..." value="{{ request('search') }}"
                            class="custom-search-input w-full bg-transparent border-none focus:outline-none focus:ring-0 p-0 text-base font-medium">
                    </div>
                </div>
            </form>
        </div>

        <!-- Category Pills -->
        <div class="overflow-x-auto pb-2">
            <div class="flex gap-3 min-w-max">
                <!-- Semua Kategori Pill -->
                <a href="{{ route('admin.menus.index', ['search' => request('search')]) }}"
                    class="px-4 py-2 rounded-full text-sm font-semibold transition-all border whitespace-nowrap" style="{{ !request('category')
    ? 'background-color: transparent; border-color: #f0f2bd; color: #f0f2bd;'
    : 'background-color: #3e302b; border-color: #3e302b; color: #a89890;' }}">
                    Semua Produk
                </a>

                <!-- Category Items -->
                @foreach($categories as $key => $label)
                            <a href="{{ route('admin.menus.index', ['category' => $key, 'search' => request('search')]) }}"
                                class="px-4 py-2 rounded-full text-sm font-semibold transition-all border whitespace-nowrap" style="{{ request('category') == $key
                    ? 'background-color: transparent; border-color: #f0f2bd; color: #f0f2bd;'
                    : 'background-color: #3e302b; border-color: #3e302b; color: #a89890;' }}">
                                {{ $label }}
                            </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-20 left-1/2 z-50 transform -translate-x-1/2 transition-all duration-300 opacity-0"
        style="position: fixed !important; top: 5rem !important; left: 50% !important; transform: translateX(-50%) translateY(-100%) !important;">
        <div id="toast-container" class="flex items-center gap-4 px-6 py-4 rounded-xl shadow-2xl"
            style="background: linear-gradient(135deg, #3e302b 0%, #2b211e 100%); border: 2px solid #D4A574;">
            <div class="flex-shrink-0">
                <svg id="toast-icon-success" class="w-6 h-6" style="color: #f0f2bd !important;" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg id="toast-icon-error" class="w-6 h-6 hidden" style="color: #ef4444 !important;" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="font-semibold text-base" style="color: #f0f2bd !important;" id="toast-message"></p>
            <button onclick="hideToast()" class="ml-2 hover:text-white transition-colors"
                style="color: #f0f2bd !important;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Trigger Toast if Session has Success/Error -->
    @if(session('success') || session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const message = @json(session('success') ?? session('error'));
                const type = "{{ session('success') ? 'success' : 'error' }}";
                showToast(message, type);
            });
        </script>
    @endif

    <script>
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            const toastContainer = document.getElementById('toast-container');
            const iconSuccess = document.getElementById('toast-icon-success');
            const iconError = document.getElementById('toast-icon-error');

            toastMessage.textContent = message;

            // Change style based on type
            if (type === 'error') {
                toastContainer.style.border = '2px solid #ef4444';
                iconSuccess.classList.add('hidden');
                iconError.classList.remove('hidden');
            } else {
                toastContainer.style.border = '2px solid #D4A574';
                iconError.classList.add('hidden');
                iconSuccess.classList.remove('hidden');
            }

            // Show with fade in and slide down
            toast.style.transform = 'translateX(-50%) translateY(0)';
            toast.classList.remove('opacity-0');

            // Auto hide after 5 seconds for errors, 3 seconds for success
            setTimeout(() => {
                hideToast();
            }, type === 'error' ? 5000 : 3000);
        }

        function hideToast() {
            const toast = document.getElementById('toast');
            toast.style.transform = 'translateX(-50%) translateY(-100%)';
            toast.classList.add('opacity-0');
        }
    </script>

    <div class="rounded-xl border overflow-hidden" style="background-color: #2b211e; border-color: #3e302b;">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead style="background-color: #3e302b; color: #f0f2bd;">
                    <tr>
                        <th class="px-6 py-4 font-semibold align-middle">Image</th>
                        <th class="px-6 py-4 font-semibold align-middle">Name</th>
                        <th class="px-6 py-4 font-semibold align-middle">Description</th>
                        <th class="px-6 py-4 font-semibold align-middle">Price</th>
                        <th class="px-6 py-4 font-semibold align-middle">Status</th>
                        <th class="px-6 py-4 font-semibold text-right align-middle">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color: #3e302b; color: #f0f2bd;">
                    @forelse($menus as $menu)
                        <tr class="hover:bg-white/5 transition-colors" style="border-bottom: 1px solid #3e302b;">
                            <td class="px-6 py-4 align-middle">
                                @if($menu->image_path)
                                    <img src="{{ asset($menu->image_path) }}" alt="{{ $menu->name }}"
                                        class="w-12 h-12 rounded-lg object-cover">
                                @else
                                    <div
                                        class="w-12 h-12 rounded-lg flex items-center justify-center bg-gray-700 text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium align-middle">{{ $menu->name }}</td>
                            <td class="px-6 py-4 text-sm opacity-80 truncate max-w-xs align-middle">
                                {{ Str::limit($menu->description, 50) }}
                            </td>
                            <td class="px-6 py-4 align-middle">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 align-middle">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                    style="{{ $menu->is_available ? 'background-color: rgba(212, 165, 116, 0.2); color: #D4A574;' : 'background-color: rgba(239, 68, 68, 0.2); color: #ef4444;' }}">
                                    {{ $menu->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.menus.toggleVisibility', $menu->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 rounded-lg hover:bg-white/10 transition-colors"
                                            title="{{ $menu->is_available ? 'Hide Menu' : 'Show Menu' }}">
                                            @if($menu->is_available)
                                                <!-- Eye Icon (Visible) -->
                                                <svg class="w-5 h-5 text-gray-400 hover:text-white" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            @else
                                                <!-- Eye Slash Icon (Hidden) -->
                                                <svg class="w-5 h-5 text-gray-500 hover:text-white" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.menus.edit', $menu->id) }}"
                                        class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Edit">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <button type="button" onclick="showDeleteModal({{ $menu->id }}, '{{ $menu->name }}')"
                                        class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Delete">
                                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center" style="color: #f0f2bd; opacity: 0.6;">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-4 opacity-50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>No menu items found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t" style="border-color: #3e302b;">
            {{ $menus->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="hideDeleteModal()"></div>

        <!-- Modal Content -->
        <div class="relative w-full max-w-md mx-4 rounded-2xl p-6 shadow-2xl transform transition-all"
            style="background-color: #2b211e; border: 1px solid #3e302b;">

            <!-- Icon -->
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full flex items-center justify-center"
                    style="background-color: rgba(239, 68, 68, 0.15);">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-center mb-2" style="color: #f0f2bd;">Hapus Menu?</h3>

            <!-- Message -->
            <p class="text-center text-sm mb-6" style="color: #f0f2bd; opacity: 0.8;">
                Apakah Anda yakin ingin menghapus menu <span id="deleteMenuName" class="font-semibold"
                    style="color: #D4A574;"></span>?
                Tindakan ini tidak dapat dibatalkan.
            </p>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="hideDeleteModal()"
                    class="flex-1 px-4 py-3 rounded-xl font-semibold transition-all duration-200 hover:opacity-80"
                    style="background-color: #3e302b; color: #f0f2bd;">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full px-4 py-3 rounded-xl font-semibold transition-all duration-200 hover:opacity-90"
                        style="background-color: #ef4444; color: white;">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(menuId, menuName) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const nameSpan = document.getElementById('deleteMenuName');

            form.action = `/admin/menus/${menuId}`;
            nameSpan.textContent = `"${menuName}"`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
            }
        });
    </script>
</x-layouts.admin>