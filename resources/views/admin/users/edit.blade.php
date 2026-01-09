<x-layouts.admin :title="'Edit User'">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 text-sm hover:underline"
                style="color: #f0f2bd;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar User
            </a>
        </div>

        <div class="rounded-xl border p-6" style="background-color: #2b211e; border-color: #3e302b;">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold"
                    style="background-color: #6b4d3a; color: #f0f2bd;">
                    {{ $user->initials() }}
                </div>
                <div>
                    <h2 class="text-xl font-bold" style="color: #f0f2bd;">Edit User</h2>
                    <p class="text-sm opacity-70" style="color: #f0f2bd;">{{ $user->email }}</p>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none"
                        style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #4e3d36;"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Email Address <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none"
                        style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #4e3d36;"
                        placeholder="contoh@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="mb-5">
                    <label for="role" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Role <span class="text-red-400">*</span>
                    </label>
                    <select name="role" id="role" required {{ $user->id === auth()->id() ? 'disabled' : '' }}
                        class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none {{ $user->id === auth()->id() ? 'opacity-50 cursor-not-allowed' : '' }}"
                        style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #4e3d36;">
                        <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer
                        </option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @if($user->id === auth()->id())
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <p class="mt-1 text-xs opacity-60 flex items-center gap-1" style="color: #f0f2bd;">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Anda tidak dapat mengubah role akun sendiri.
                        </p>
                    @endif
                    @error('role')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Password Baru <span class="text-xs opacity-60 font-normal">(Opsional)</span>
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none"
                        style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #4e3d36;"
                        placeholder="Biarkan kosong jika tidak ingin mengubah password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t" style="border-color: #3e302b;">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-6 py-2.5 rounded-lg font-medium transition-colors hover:bg-white/5"
                        style="color: #f0f2bd;">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-lg font-bold shadow-lg transition-all hover:opacity-90 active:scale-95"
                        style="background-color: #D4A574; color: #1e1715;">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>