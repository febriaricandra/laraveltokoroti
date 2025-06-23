<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Banner') }}
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
                    <h3 class="text-lg font-semibold">Daftar Banner</h3>
                    <a href="{{ route('admin.banners.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Tambah
                        Banner</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">Gambar</th>
                                <th class="px-4 py-2 border">Deskripsi</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse ($banners as $banner)
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-4 py-2 border">{{ $loop->iteration + $banners->firstItem() - 1 }}</td>
                                    <td class="px-4 py-2 border">
                                        @if ($banner->image)
                                            <img src="{{ Storage::url($banner->image) }}" alt="Banner Image"
                                                class="w-24 h-auto mx-auto object-cover rounded">
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border text-left">{{ $banner->description ?? '-' }}</td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('admin.banners.edit', $banner) }}"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition mb-1 sm:mb-0">Edit</a>
                                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?');"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 border text-center text-gray-500">
                                        Belum ada data banner.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $banners->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
