<x-layouts.admin :title="'Add New Menu'">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-bold" style="color: #f0f2bd;">Tambah Menu Baru</h2>
        <a href="{{ route('admin.menus.index') }}" class="px-4 py-2 rounded-lg font-semibold transition-colors hover:bg-white/10"
            style="color: #f0f2bd; border: 1px solid #3e302b;">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-xl border p-6 max-w-2xl" style="background-color: #2b211e; border-color: #3e302b;">
        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">Nama Menu</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                    style="background-color: #3e302b; border: 1px solid #5a4032; color: #f0f2bd;"
                    placeholder="Contoh: Kopi Susu Aren">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">Kategori</label>
                <select id="category" name="category" required
                    class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                    style="background-color: #3e302b; border: 1px solid #5a4032; color: #f0f2bd;">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $value => $label)
                        <option value="{{ $value }}" {{ old('category') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                    style="background-color: #3e302b; border: 1px solid #5a4032; color: #f0f2bd;"
                    placeholder="Deskripsi singkat menu...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">Harga (Rp)</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0"
                    class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                    style="background-color: #3e302b; border: 1px solid #5a4032; color: #f0f2bd;"
                    placeholder="Contoh: 18000">
                @error('price')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div>
                <label for="image" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">Foto Menu</label>
                <input type="file" id="image" name="image" accept="image/*"
                    class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-500 file:text-white hover:file:bg-amber-600"
                    style="color: #f0f2bd;">
                <p class="text-xs mt-1 opacity-60" style="color: #f0f2bd;">Format: JPG, PNG, GIF. Max: 2MB.</p>
                @error('image')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Availability -->
            <div class="flex items-center">
                <input type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', 1) ? 'checked' : '' }}
                    class="w-5 h-5 rounded focus:ring-amber-500 text-amber-600"
                    style="background-color: #3e302b; border-color: #5a4032;">
                <label for="is_available" class="ml-2 text-sm font-medium" style="color: #f0f2bd;">
                    Tesedia (Tampilkan di menu pelanggan)
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" class="w-full py-3 rounded-lg font-semibold transition-colors hover:opacity-90"
                    style="background-color: #D4A574; color: #1e1715;">
                    Simpan Menu
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
