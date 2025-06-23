<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Order') }}
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

                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Daftar Order</h3>

                    <a href="{{ route('admin.order.print') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                        <i class="fas fa-print mr-2"></i> Cetak PDF
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-2 py-2 border text-sm w-12">No</th>
                                <th class="px-3 py-2 border text-sm w-32">Nama Pelanggan</th>
                                <th class="px-2 py-2 border text-sm w-20">Jumlah Item</th>
                                <th class="px-3 py-2 border text-sm w-32">Total Harga</th>
                                <th class="px-3 py-2 border text-sm w-28">Metode Pembayaran</th>
                                <th class="px-2 py-2 border text-sm w-24">Status</th>
                                <th class="px-3 py-2 border text-sm w-28">Bukti Pembayaran</th>
                                <th class="px-4 py-2 border text-sm w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($orders as $order)
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 border">{{ $order->user->name }}</td>
                                    <td class="px-4 py-2 border">{{ $order->orderDetails->sum('quantity') }}</td>
                                    <td class="px-4 py-2 border">
                                        Rp{{ number_format(
                                            $order->orderDetails->sum(function ($item) {
                                                return $item->price * $item->quantity;
                                            }),
                                            2,
                                            ',',
                                            '.',
                                        ) }}
                                    </td>
                                    <td class="px-4 py-2 border capitalize">{{ ucfirst($order->payment_method) }}</td>
                                    <td class="px-4 py-2 border capitalize">{{ $order->status }}</td>
                                    <td class="px-4 py-2 border">
                                        @if ($order->payment_proof)
                                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                                                class="text-blue-600 underline">
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-gray-500 italic">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex flex-col sm:flex-row gap-2 items-center justify-center">
                                            <a href="{{ route('admin.order.show', $order) }}"
                                                class="inline-flex items-center justify-center px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition whitespace-nowrap">
                                                Detail
                                            </a>
                                            
                                            <form action="{{ route('admin.order.updateStatus', $order->id) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()"
                                                    class="block w-full bg-white border border-gray-300 text-gray-700 py-1 px-2 rounded shadow text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                    <option {{ $order->status === 'pending' ? 'selected' : '' }}
                                                        value="pending">Pending</option>
                                                    <option {{ $order->status === 'confirmed' ? 'selected' : '' }}
                                                        value="confirmed">Confirmed</option>
                                                    <option {{ $order->status === 'send' ? 'selected' : '' }}
                                                        value="send">Send</option>
                                                    <option {{ $order->status === 'finish' ? 'selected' : '' }}
                                                        value="finish">Finish</option>
                                                </select>
                                            </form>

                                            <form action="{{ route('admin.order.destroy', $order) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus order ini?');"
                                                class="inline-block">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition whitespace-nowrap">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
