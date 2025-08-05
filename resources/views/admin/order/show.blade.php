<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Order #') . $order->id }}
        </h2>
    </x-slot>

    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Informasi Order</h3>
                    <p><strong>Pemesan:</strong> {{ $order->name }}</p>
                    <p><strong>Alamat:</strong> {{ $order->address }}</p>
                    <p><strong>No. Telepon:</strong> {{ $order->phone }}</p>
                    <p><strong>Status:</strong> 
                        <span class="
                            @if($order->status == 'pending') text-yellow-600 font-semibold
                            @elseif($order->status == 'processing') text-blue-600 font-semibold
                            @elseif($order->status == 'shipped') text-orange-600 font-semibold
                            @elseif($order->status == 'delivered') text-green-600 font-semibold
                            @elseif($order->status == 'completed') text-emerald-600 font-semibold
                            @elseif($order->status == 'cancelled') text-red-600 font-semibold
                            @elseif($order->status == 'paid') text-green-600 font-semibold
                            @elseif($order->status == 'dp_paid') text-blue-600 font-semibold
                            @elseif($order->status == 'confirmed') text-indigo-600 font-semibold
                            @endif
                        ">
                            @if($order->status == 'dp_paid')
                                Down Payment Paid
                            @else
                                {{ ucfirst($order->status) }}
                            @endif
                        </span>
                    </p>
                    <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>
                    @if($order->payment_proof)
                        <p><strong>Bukti Pembayaran:</strong> 
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" 
                               class="text-blue-600 hover:underline">
                                Lihat Bukti Pembayaran
                            </a>
                        </p>
                    @endif
                    
                    @if($order->tracking_number)
                        <p><strong>Nomor Resi:</strong> 
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $order->tracking_number }}</span>
                        </p>
                    @endif
                    
                    @if($order->delivery_proof)
                        <p><strong>Bukti Penerimaan:</strong> 
                            <a href="{{ asset('storage/' . $order->delivery_proof) }}" target="_blank" 
                               class="text-green-600 hover:underline">
                                <i class="fas fa-image mr-1"></i>Lihat Bukti Penerimaan
                            </a>
                            @if($order->delivered_at)
                                <span class="text-sm text-gray-600 block mt-1">
                                    <i class="fas fa-clock mr-1"></i>Dikonfirmasi: {{ $order->delivered_at->format('d M Y H:i') }}
                                </span>
                            @endif
                        </p>
                    @endif
                    <p><strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                    
                    @if($order->is_down_payment)
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-2">
                                <i class="fas fa-info-circle mr-1"></i> Down Payment Order
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p><strong>DP Amount:</strong> 
                                        <span class="text-green-600 font-semibold">
                                            Rp{{ number_format($order->down_payment_amount, 0, ',', '.') }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p><strong>Sisa Pembayaran:</strong> 
                                        <span class="text-orange-600 font-semibold">
                                            Rp{{ number_format($order->remaining_amount, 0, ',', '.') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @if($order->status == 'dp_paid')
                                <p class="text-sm text-blue-600 mt-2">
                                    * Customer perlu menyelesaikan pembayaran sisa saat pengambilan/pengiriman
                                </p>
                            @endif
                        </div>
                    @endif
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
                                    <td class="px-4 py-2 border">Rp{{ number_format($detail->size->price, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 border">{{ $detail->quantity }}</td>
                                    <td class="px-4 py-2 border">
                                        Rp{{ number_format($detail->quantity * $detail->size->price, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <h3 class="text-lg font-semibold">Ringkasan Pembayaran</h3>
                    @php
                        $subtotal = $order->orderDetails->sum(function ($detail) {
                            return $detail->quantity * $detail->size->price;
                        });
                    @endphp
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($order->total_discount > 0)
                            <div class="flex justify-between mb-2 text-green-600">
                                <span>Diskon ({{ $order->discount_percentage * 100 }}%):</span>
                                <span>- Rp{{ number_format($order->total_discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between font-bold text-lg border-t pt-2">
                            <span>Total Akhir:</span>
                            <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($order->is_down_payment)
                            <div class="mt-4 pt-4 border-t border-gray-300">
                                <div class="bg-gradient-to-r from-blue-50 to-green-50 p-3 rounded">
                                    <h5 class="font-semibold text-gray-800 mb-2">Rincian Down Payment:</h5>
                                    <div class="space-y-1 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-green-700">✓ DP yang sudah dibayar:</span>
                                            <span class="font-semibold text-green-700">Rp{{ number_format($order->down_payment_amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-orange-700">○ Sisa yang harus dibayar:</span>
                                            <span class="font-semibold text-orange-700">Rp{{ number_format($order->remaining_amount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
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
