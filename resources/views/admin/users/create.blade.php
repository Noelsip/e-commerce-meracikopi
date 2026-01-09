<x-layouts.admin :title="'Tambah User Baru'">
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
            <h2 class="text-xl font-bold mb-6" style="color: #f0f2bd;">Form Tambah User</h2>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <!-- Name -->
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
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
                    <select name="role" id="role" required
                        class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none"
                        style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #4e3d36;">
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Password <span class="text-red-400">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none"
                        style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #4e3d36;"
                        placeholder="Minimal 8 karakter">
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
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>