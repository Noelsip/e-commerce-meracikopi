    <x-layouts.admin :title="'User Management'">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold" style="color: #f0f2bd;">Daftar User</h2>
        <a href="{{ route('admin.users.create') }}"
            class="px-4 py-2 rounded-lg font-semibold transition-colors hover:opacity-90"
            style="background-color: #D4A574; color: #1e1715;">
            + Tambah User
        </a>
    </div>

    <div class="mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
            <div class="relative">
                <svg class="w-5 h-5 opacity-50"
                    style="color: #f0f2bd; position: absolute; top: 50%; transform: translateY(-50%); left: 12px; pointer-events: none;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user..."
                    class="pl-12 pr-4 py-2 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none w-full md:w-auto"
                    style="background-color: #3e302b; color: #f0f2bd; border: none; min-width: 250px; padding-left: 3rem;">
            </div>

            <select name="role"
                class="px-4 py-2 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none"
                style="background-color: #3e302b; color: #f0f2bd; border: none;">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors hover:opacity-90"
                style="background-color: #D4A574; color: #1e1715;">
                Filter
            </button>

            @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 rounded-lg text-sm transition-colors hover:bg-white/10" style="color: #f0f2bd;">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg flex items-center gap-3"
            style="background-color: rgba(212, 165, 116, 0.1); color: #D4A574; border: 1px solid rgba(212, 165, 116, 0.2);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-lg flex items-center gap-3"
            style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-xl border overflow-hidden" style="background-color: #2b211e; border-color: #3e302b;">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead style="background-color: #3e302b; color: #f0f2bd;">
                    <tr>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold">Joined At</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color: #3e302b; color: #f0f2bd;">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/5 transition-colors" style="border-bottom: 1px solid #3e302b;">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold"
                                        style="background-color: #6b4d3a; color: #f0f2bd;">
                                        {{ $user->initials() }}
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $user->name }}</p>
                                        <p class="text-sm opacity-70">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-amber-500/20 text-amber-500' : 'bg-blue-500/20 text-blue-400' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm opacity-80">
                                {{ $user->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Edit">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <button type="button" onclick="showDeleteModal({{ $user->id }}, '{{ $user->name }}')"
                                            class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Delete">
                                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center" style="color: #f0f2bd; opacity: 0.6;">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-4 opacity-50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <p>Tidak ada user ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t" style="border-color: #3e302b;">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="hideDeleteModal()"></div>
        <div class="relative w-full max-w-md mx-4 rounded-2xl p-6 shadow-2xl transform transition-all"
            style="background-color: #2b211e; border: 1px solid #3e302b;">

            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full flex items-center justify-center"
                    style="background-color: rgba(239, 68, 68, 0.15);">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
            </div>

            <h3 class="text-xl font-bold text-center mb-2" style="color: #f0f2bd;">Hapus User?</h3>
            <p class="text-center text-sm mb-6" style="color: #f0f2bd; opacity: 0.8;">
                Apakah Anda yakin ingin menghapus user <span id="deleteUserName" class="font-semibold"
                    style="color: #D4A574;"></span>?
                Tindakan ini tidak dapat dibatalkan.
            </p>

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
        function showDeleteModal(userId, userName) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const nameSpan = document.getElementById('deleteUserName');

            form.action = `/admin/users/${userId}`;
            nameSpan.textContent = `"${userName}"`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') hideDeleteModal();
        });
    </script>
</x-layouts.admin>