<x-layouts.admin :title="'Profile Settings'">
    <div class="max-w-2xl mx-auto">
        <div class="rounded-xl border p-6 mb-6" style="background-color: #2b211e; border-color: #3e302b;">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold"
                    style="background-color: #6b4d3a; color: #f0f2bd;">
                    {{ $user->initials() }}
                </div>
                <div>
                    <h2 class="text-xl font-bold" style="color: #f0f2bd;">{{ $user->name }}</h2>
                    <p class="text-sm opacity-70" style="color: #f0f2bd;">{{ $user->email }}</p>
                    <span
                        class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-500">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
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

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <h3 class="text-lg font-semibold mb-4 pb-2 border-b" style="color: #f0f2bd; border-color: #3e302b;">
                    Informasi Profile</h3>

                <!-- Name -->
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Nama Lengkap
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none"
                        style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #4e3d36;">
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Email Address
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none"
                        style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #4e3d36;">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <h3 class="text-lg font-semibold mb-4 pb-2 border-b pt-4"
                    style="color: #f0f2bd; border-color: #3e302b;">Update Password</h3>

                <!-- Current Password -->
                <div class="mb-5">
                    <label for="current_password" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Password Saat Ini <span class="text-xs opacity-60 font-normal">(Isi jika ingin mengubah
                            password)</span>
                    </label>
                    <input type="password" name="current_password" id="current_password"
                        class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none"
                        style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #4e3d36;">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Password Baru
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none"
                        style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #4e3d36;">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none"
                        style="background-color: #3e302b; color: #f0f2bd; border: 1px solid #4e3d36;">
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit"
                        class="px-6 py-2 rounded-lg font-bold shadow-lg transition-all hover:opacity-90 active:scale-95 tracking-wide"
                        style="background-color: #D4A574; color: #1e1715;">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>