<x-layouts.admin :title="'Edit Meja'">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.tables.index') }}" 
                class="inline-flex items-center gap-2 text-sm hover:opacity-80 transition-opacity whitespace-nowrap" 
                style="color: #D4A574;">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali ke Daftar Meja</span>
            </a>
        </div>

        <div class="rounded-xl p-6" style="background-color: #2b211e; border: 1px solid #3e302b;">
            <h2 class="text-xl font-bold mb-6" style="color: #f0f2bd;">Edit Meja {{ $table->table_number }}</h2>

            <form action="{{ route('admin.tables.update', $table->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Table Number -->
                <div class="mb-5">
                    <label for="table_number" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Nomor Meja <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="table_number" id="table_number" 
                        value="{{ old('table_number', $table->table_number) }}"
                        class="w-full px-4 py-3 rounded-lg border-none focus:ring-2 focus:ring-amber-500"
                        style="background-color: #3e302b; color: #f0f2bd;"
                        placeholder="Contoh: A1, T01, VIP1" required>
                    @error('table_number')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacity -->
                <div class="mb-5">
                    <label for="capacity" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Kapasitas (Jumlah Kursi) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="capacity" id="capacity" 
                        value="{{ old('capacity', $table->capacity) }}"
                        min="1" max="20"
                        class="w-full px-4 py-3 rounded-lg border-none focus:ring-2 focus:ring-amber-500"
                        style="background-color: #3e302b; color: #f0f2bd;" required>
                    @error('capacity')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>



                <!-- Status -->
                <div class="mb-5">
                    <label for="status" class="block text-sm font-medium mb-2" style="color: #f0f2bd;">
                        Status <span class="text-red-400">*</span>
                    </label>
                    <select name="status" id="status"
                        class="w-full px-4 py-3 rounded-lg border-none focus:ring-2 focus:ring-amber-500"
                        style="background-color: #3e302b; color: #f0f2bd;" required>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $table->status) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Toggle -->
                <div class="mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" 
                            {{ old('is_active', $table->is_active) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-none focus:ring-2 focus:ring-amber-500"
                            style="background-color: #3e302b; color: #D4A574;">
                        <span class="text-sm font-medium" style="color: #f0f2bd;">Meja Aktif</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 px-6 py-3 rounded-lg font-semibold transition-colors hover:opacity-90"
                        style="background-color: #D4A574; color: #1e1715;">
                        Update Meja
                    </button>
                    <a href="{{ route('admin.tables.index') }}"
                        class="px-6 py-3 rounded-lg font-semibold transition-colors hover:opacity-80 text-center"
                        style="background-color: #3e302b; color: #f0f2bd;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
