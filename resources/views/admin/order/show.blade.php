<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Order #') . $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Informasi Order</h3>
                    <p><strong>Pemesan:</strong> {{ $order->name }}</p>
                    <p><strong>Alamat:</strong> {{ $order->address }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                    <p><strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Detail Produk</h3>
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">Nama Produk</th>
                                <th class="px-4 py-2 border">Harga</th>
                                <th class="px-4 py-2 border">Jumlah</th>
                                <th class="px-4 py-2 border">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $detail)
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 border">{{ $detail->product->name }}</td>
                                    <td class="px-4 py-2 border">Rp{{ number_format($detail->price, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 border">{{ $detail->quantity }}</td>
                                    <td class="px-4 py-2 border">
                                        Rp{{ number_format($detail->quantity * $detail->price, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <h3 class="text-lg font-semibold">Total Pembayaran</h3>
                    <p class="font-bold">
                        Rp{{ number_format($order->orderDetails->sum(function ($detail) {return $detail->quantity * $detail->price;}),2,',','.') }}
                    </p>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.order.index') }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        Kembali ke Daftar Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
