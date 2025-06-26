@csrf

@php
    // Pastikan selalu ada minimal satu kolom ukuran saat create
    $oldSizes = old('sizes', isset($product) ? $product->sizes->toArray() : [['size' => '', 'price' => '', 'is_active' => true]]);
@endphp

<div class="space-y-4">

    {{-- Nama Produk --}}
    <div>
        <label for="name" class="block text-gray-700 font-semibold">Nama Produk</label>
        <input type="text" name="name" id="name"
            class="w-full p-2 border border-gray-300 rounded-lg"
            value="{{ old('name', $product->name ?? '') }}"
            placeholder="Nama Produk" required>
    </div>

    {{-- Kategori --}}
    <div>
        <label for="category_id" class="block text-gray-700 font-semibold">Kategori</label>
        <select name="category_id" id="category_id"
            class="w-full p-2 border border-gray-300 rounded-lg" required>
            <option value="" disabled {{ !isset($product) ? 'selected' : '' }}>Pilih Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Satuan --}}
    {{-- <div>
        <label for="unit" class="block text-gray-700 font-semibold">Satuan</label>
        <select name="unit" id="unit"
            class="w-full p-2 border border-gray-300 rounded-lg" required>
            <option value="" disabled {{ !isset($product) ? 'selected' : '' }}>Pilih Satuan</option>
            @foreach ($units as $unit)
                <option value="{{ $unit }}"
                    {{ old('unit', $product->unit ?? '') == $unit ? 'selected' : '' }}>
                    {{ $unit }}
                </option>
            @endforeach
        </select>
    </div> --}}

    {{-- Deskripsi --}}
    <div>
        <label for="description" class="block text-gray-700 font-semibold">Deskripsi</label>
        <textarea name="description" id="description"
            class="w-full p-2 border border-gray-300 rounded-lg"
            placeholder="Deskripsi singkat produk">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    {{-- Gambar Produk --}}
    <div>
        <label class="block text-gray-700 font-semibold">Gambar Produk</label>
        <input type="file" name="image" class="w-full p-2 border border-gray-300 rounded-lg">
        @isset($product)
            @if ($product->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="w-32 h-32 object-cover rounded-lg">
                    <small class="text-gray-500">Gambar saat ini. Upload file baru untuk mengganti.</small>
                </div>
            @endif
        @endisset
    </div>

    {{-- Ukuran dan Harga --}}
    <div>
        <label class="block text-gray-700 font-semibold mb-2">Ukuran & Harga</label>
        <div id="size-container" class="space-y-4">
            @foreach ($oldSizes as $i => $size)
                <div class="size-item grid grid-cols-1 md:grid-cols-4 gap-4 items-end border p-4 rounded-lg bg-white shadow-sm">
                    <input type="hidden" name="sizes[{{ $i }}][id]" value="{{ $size['id'] ?? '' }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ukuran</label>
                        <input type="text" name="sizes[{{ $i }}][size]" value="{{ $size['size'] ?? '' }}"
                            class="w-full p-2 border border-gray-300 rounded-lg" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="number" name="sizes[{{ $i }}][price]" value="{{ $size['price'] ?? '' }}"
                            class="w-full p-2 border border-gray-300 rounded-lg" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Satuan</label>
                        <select name="sizes[{{ $i }}][unit]" class="w-full p-2 border border-gray-300 rounded-lg" required>
                            <option value="" disabled>Pilih Satuan</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit }}"
                                    {{ (isset($size['unit']) && $size['unit'] == $unit) ? 'selected' : '' }}>
                                    {{ $unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Aktif?</label>
                        <select name="sizes[{{ $i }}][is_active]" class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="1" {{ ($size['is_active'] ?? true) ? 'selected' : '' }}>Ya</option>
                            <option value="0" {{ !($size['is_active'] ?? true) ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>

                    <div class="text-right">
                        <button type="button"
                            class="remove-size-btn px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                            Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Tombol Tambah Ukuran --}}
        <div class="mt-4">
            <button type="button" id="add-size-btn"
                class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                + Tambah Ukuran
            </button>
        </div>
    </div>

</div>

{{-- Tombol Aksi --}}
<div class="flex justify-end mt-6">
    <a href="{{ route('admin.product.index') }}"
        class="mr-4 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
        Batal
    </a>
    <button type="submit"
        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
        {{ isset($product) ? 'Update Produk' : 'Simpan Produk' }}
    </button>
</div>

{{-- JavaScript Dinamis --}}
@push('scripts')
<script>
    let sizeIndex = {{ count($oldSizes) }};
    const container = document.getElementById('size-container');
    const addBtn = document.getElementById('add-size-btn');

    addBtn.addEventListener('click', () => {
        const div = document.createElement('div');
        div.className = 'size-item grid grid-cols-1 md:grid-cols-4 gap-4 items-end border p-4 rounded-lg bg-white shadow-sm';
        div.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700">Ukuran</label>
                <input type="text" name="sizes[${sizeIndex}][size]" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" name="sizes[${sizeIndex}][price]" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Aktif?</label>
                <select name="sizes[${sizeIndex}][is_active]" class="w-full p-2 border border-gray-300 rounded-lg">
                    <option value="1" selected>Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Satuan</label>
                <select name="sizes[${sizeIndex}][unit]" class="w-full p-2 border border-gray-300 rounded-lg" required>
                    <option value="" disabled>Pilih Satuan</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit }}"
                            {{ (isset($size['unit']) && $size['unit'] == $unit) ? 'selected' : '' }}>
                            {{ $unit }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="text-right">
                <button type="button" class="remove-size-btn px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                    Hapus
                </button>
            </div>
        `;
        container.appendChild(div);
        sizeIndex++;
    });

    // Delegasi event hapus
    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-size-btn')) {
            const items = container.querySelectorAll('.size-item');
            if (items.length > 1) {
                e.target.closest('.size-item').remove();
            } else {
                alert('Minimal satu ukuran harus tersedia.');
            }
        }
    });
</script>
@endpush
