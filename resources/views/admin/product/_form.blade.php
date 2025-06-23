@csrf
<div class="space-y-4">
    <!-- Nama Produk -->
    <div>
        <label for="name" class="block text-gray-700 font-semibold">Nama Produk</label>
        <input type="text" name="name" id="name" class="w-full p-2 border border-gray-300 rounded-lg"
            value="{{ old('name', $product->name ?? '') }}" placeholder="Nama Produk" required>
    </div>

    <!-- Kategori -->
    <div>
        <label for="category_id" class="block text-gray-700 font-semibold">Kategori</label>
        <select name="category_id" id="category_id" class="w-full p-2 border border-gray-300 rounded-lg" required>
            <option value="" disabled {{ !isset($product) ? 'selected' : '' }}>Pilih Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Harga & Satuan dalam satu baris -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="price" class="block text-gray-700 font-semibold">Harga (Rp)</label>
            <input type="number" name="price" id="price" class="w-full p-2 border border-gray-300 rounded-lg"
                value="{{ old('price', $product->price ?? '') }}" placeholder="Contoh: 50000" required>
        </div>
        <div>
            <label for="unit" class="block text-gray-700 font-semibold">Satuan</label>
            <select name="unit" id="unit" class="w-full p-2 border border-gray-300 rounded-lg" required>
                <option value="" disabled {{ !isset($product) ? 'selected' : '' }}>Pilih Satuan</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit }}"
                        {{ old('unit', $product->unit ?? '') == $unit ? 'selected' : '' }}>
                        {{ $unit }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Deskripsi -->
    <div>
        <label for="description" class="block text-gray-700 font-semibold">Deskripsi</label>
        <textarea name="description" id="description" class="w-full p-2 border border-gray-300 rounded-lg"
            placeholder="Deskripsi singkat produk">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <!-- Gambar Produk -->
    <div>
        <label class="block text-gray-700 font-semibold">Gambar Produk</label>
        <input type="file" name="image" class="w-full p-2 border border-gray-300 rounded-lg">
        @isset($product)
            @if ($product->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="w-32 h-32 object-cover rounded-lg">
                    <small class="text-gray-500">Gambar saat ini. Upload file baru untuk mengganti.</small>
                </div>
            @endif
        @endisset
    </div>
</div>

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
