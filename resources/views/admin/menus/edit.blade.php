<x-layouts.admin :title="'Edit Menu'">
    <div class="mb-6 flex justify-between items-center max-w-2xl mx-auto">
        <h2 class="text-xl font-bold" style="color: #f0f2bd;">Edit Menu: {{ $menu->name }}</h2>
        <a href="{{ route('admin.menus.index') }}"
            class="px-4 py-2 rounded-lg font-semibold transition-colors hover:bg-white/10"
            style="color: #f0f2bd; border: 1px solid #3e302b;">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-xl border p-6 max-w-2xl mx-auto" style="background-color: #2b211e; border-color: #3e302b;">
        <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">Nama Menu</label>
                <input type="text" id="name" name="name" value="{{ old('name', $menu->name) }}" required
                    class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                    style="background-color: #3e302b; border: 1px solid #5a4032; color: #f0f2bd;"
                    placeholder="Contoh: Kopi Susu Aren">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium mb-2"
                    style="color: #f0f2bd;">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                    style="background-color: #3e302b; border: 1px solid #5a4032; color: #f0f2bd;">{{ old('description', $menu->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">Harga (Rp)</label>
                <input type="number" id="price" name="price" value="{{ old('price', $menu->price) }}" required min="0"
                    class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                    style="background-color: #3e302b; border: 1px solid #5a4032; color: #f0f2bd;">
                @error('price')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #f0f2bd;">Foto Menu</label>
                @if($menu->image_path)
                    <div class="mb-2">
                        <img src="{{ asset($menu->image_path) }}" alt="Current Image"
                            class="w-20 h-20 rounded-lg object-cover border" style="border-color: #3e302b;">
                        <p class="text-xs mt-1 opacity-60" style="color: #f0f2bd;">Foto saat ini</p>
                    </div>
                @endif
                <div class="flex items-center gap-3">
                    <label for="image"
                        class="inline-block py-2 px-4 rounded-full text-sm font-semibold cursor-pointer transition-opacity hover:opacity-90"
                        style="background-color: #D4A574; color: #1e1715;">
                        Pilih File
                    </label>
                    <span id="file-name" class="text-sm" style="color: #f0f2bd;">Tidak ada file dipilih</span>
                    <input type="file" id="image" name="image" accept="image/*" class="hidden"
                        onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'Tidak ada file dipilih'">
                </div>
                <p class="text-xs mt-2 opacity-60" style="color: #f0f2bd;">Biarkan kosong jika tidak ingin mengubah
                    foto.</p>
                @error('image')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Availability -->
            <div class="flex items-center">
                <input type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', $menu->is_available) ? 'checked' : '' }} class="w-5 h-5 rounded focus:ring-amber-500 text-amber-600"
                    style="background-color: #3e302b; border-color: #5a4032;">
                <label for="is_available" class="ml-2 text-sm font-medium" style="color: #f0f2bd;">
                    Tesedia (Tampilkan di menu pelanggan)
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex gap-4">
                <button type="submit" class="flex-1 py-3 rounded-lg font-semibold transition-colors hover:opacity-90"
                    style="background-color: #D4A574; color: #1e1715;">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.menus.index') }}"
                    class="px-6 py-3 rounded-lg font-semibold transition-colors hover:bg-white/10 text-center"
                    style="color: #f0f2bd; border: 1px solid #3e302b;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>