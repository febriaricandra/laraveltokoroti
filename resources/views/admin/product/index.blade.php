<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-500 text-white rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Daftar Produk</h3>
                    <a href="{{ route('admin.product.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Tambah
                        Produk</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">Nama</th>
                                <th class="px-4 py-2 border">Kategori</th>
                                <th class="px-4 py-2 border">Harga</th>
                                <th class="px-4 py-2 border">Gambar</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($products as $product)
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 border">{{ $product->name }}</td>
                                    <td class="px-4 py-2 border">{{ $product->category->name }}</td>
                                    <td class="px-4 py-2 border text-right">
                                        @if($product->sizes && $product->sizes->count() > 0)
                                            <div class="space-y-1">
                                                @foreach($product->sizes as $size)
                                                    @if($size->is_active)
                                                        <div class="flex justify-between">
                                                            <span class="font-medium">{{ $size->size }}</span>
                                                            <span>Rp{{ number_format($size->price, 0, ',', '.') }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                        <div class="text-xs text-gray-500 mt-1">Per {{ $product->unit }}</div>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400?text=Cake' }}"
                                            class="w-16 h-16 object-cover rounded-lg">
                                    </td>

                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('admin.product.edit', $product) }}"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">Edit</a>
                                        <form action="{{ route('admin.product.destroy', $product) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');"
                                            class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
