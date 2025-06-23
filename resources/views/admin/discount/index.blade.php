<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Diskon') }}
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
                    <h3 class="text-lg font-semibold">Daftar Diskon</h3>
                    <a href="{{ route('admin.discounts.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Tambah
                        Diskon</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">Nama Diskon</th>
                                <th class="px-4 py-2 border">Minimum Order</th>
                                <th class="px-4 py-2 border">Persentase Diskon</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse ($discounts as $discount)
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-4 py-2 border">{{ $loop->iteration + $discounts->firstItem() - 1 }}
                                    </td>
                                    <td class="px-4 py-2 border text-left">{{ $discount->name }}</td>
                                    <td class="px-4 py-2 border">
                                        Rp{{ number_format($discount->minimum_order, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 border">{{ $discount->discount_percentage }}%</td>
                                    <td class="px-4 py-2 border">
                                        @if ($discount->is_active)
                                            <span
                                                class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">Aktif</span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('admin.discounts.edit', $discount) }}"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition mb-1 sm:mb-0">Edit</a>
                                        <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus diskon ini?');"
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
                                    <td colspan="6" class="px-4 py-4 border text-center text-gray-500">
                                        Belum ada data diskon.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $discounts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
