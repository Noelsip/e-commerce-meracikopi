<x-layouts.admin :title="'Catalog / Menu Management'">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold" style="color: #f0f2bd;">Daftar Menu</h2>
        <a href="{{ route('admin.menus.create') }}"
            class="px-4 py-2 rounded-lg font-semibold transition-colors hover:opacity-90"
            style="background-color: #D4A574; color: #1e1715;">
            + Tambah Menu
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg" style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #D4A574;">
            {{ session('success') }}
        </div>
    @endif

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