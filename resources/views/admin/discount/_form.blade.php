@csrf
<div class="space-y-4">
    <!-- Nama Diskon -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Diskon</label>
        <input type="text" name="name" id="name" value="{{ old('name', $discount->name ?? '') }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required>
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Minimum Order -->
    <div>
        <label for="minimum_order" class="block text-sm font-medium text-gray-700">Minimum Order (Rp)</label>
        <input type="number" name="minimum_order" id="minimum_order"
            value="{{ old('minimum_order', $discount->minimum_order ?? '0') }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required step="1">
        @error('minimum_order')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Persentase Diskon -->
    <div>
        <label for="discount_percentage" class="block text-sm font-medium text-gray-700">Persentase Diskon (%)</label>
        <input type="number" name="discount_percentage" id="discount_percentage"
            value="{{ old('discount_percentage', $discount->discount_percentage ?? '') }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required step="0.01" min="0" max="100">
        @error('discount_percentage')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Status Aktif -->
    <div class="flex items-center">
        <input id="is_active" name="is_active" type="checkbox" value="1"
            @if (old('is_active', $discount->is_active ?? true)) checked @endif
            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
        <label for="is_active" class="ml-2 block text-sm text-gray-900">
            Aktifkan Diskon
        </label>
    </div>
</div>

<div class="mt-6 flex justify-end">
    <a href="{{ route('admin.discounts.index') }}"
        class="mr-4 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
        Batal
    </a>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
        {{ isset($discount) ? 'Perbarui Diskon' : 'Simpan Diskon' }}
    </button>
</div>
