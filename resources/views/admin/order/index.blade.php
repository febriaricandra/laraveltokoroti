<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Order') }}
        </h2>
    </x-slot>

    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @endpush

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
                                <th class="px-3 py-2 border text-sm w-32">Down Payment</th>
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
                                        Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 border capitalize">{{ ucfirst($order->payment_method) }}</td>
                                    <td class="px-4 py-2 border">
                                        <span class="
                                            @if($order->status == 'pending') text-yellow-600 font-semibold
                                            @elseif($order->status == 'processing') text-blue-600 font-semibold
                                            @elseif($order->status == 'shipped') text-orange-600 font-semibold
                                            @elseif($order->status == 'delivered') text-green-600 font-semibold
                                            @elseif($order->status == 'cancelled') text-red-600 font-semibold
                                            @elseif($order->status == 'paid') text-green-600 font-semibold
                                            @elseif($order->status == 'dp_paid') text-blue-600 font-semibold
                                            @endif
                                        ">
                                            @if($order->status == 'dp_paid')
                                                DP Paid
                                            @else
                                                {{ ucfirst($order->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        @if($order->is_down_payment)
                                            <div class="text-xs">
                                                <div class="text-green-600 font-semibold">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    DP: Rp{{ number_format($order->down_payment_amount, 0, ',', '.') }}
                                                </div>
                                                <div class="text-orange-600">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Sisa: Rp{{ number_format($order->remaining_amount, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-500 text-xs">-</span>
                                        @endif
                                    </td>
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
                                                method="POST" class="inline-block" id="statusForm{{ $order->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="handleStatusChange(this, {{ $order->id }})"
                                                    class="block w-full bg-white border border-gray-300 text-gray-700 py-1 px-2 rounded shadow text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                    <option {{ $order->status === 'pending' ? 'selected' : '' }}
                                                        value="pending">Pending</option>
                                                    <option {{ $order->status === 'dp_paid' ? 'selected' : '' }}
                                                        value="dp_paid">DP Paid</option>
                                                    <option {{ $order->status === 'paid' ? 'selected' : '' }}
                                                        value="paid">Paid</option>
                                                    <option {{ $order->status === 'confirmed' ? 'selected' : '' }}
                                                        value="confirmed">Confirmed</option>
                                                    <option {{ $order->status === 'processing' ? 'selected' : '' }}
                                                        value="processing">Processing</option>
                                                    <option {{ $order->status === 'shipped' ? 'selected' : '' }}
                                                        value="shipped">Shipped</option>
                                                    <option {{ $order->status === 'delivered' ? 'selected' : '' }}
                                                        value="delivered">Delivered</option>
                                                    <option {{ $order->status === 'completed' ? 'selected' : '' }}
                                                        value="completed">Completed</option>
                                                    <option {{ $order->status === 'cancelled' ? 'selected' : '' }}
                                                        value="cancelled">Cancelled</option>
                                                </select>
                                                <input type="hidden" name="tracking_number" id="trackingInput{{ $order->id }}">
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

    <!-- Modal for Tracking Number -->
    <div id="trackingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900">Masukkan Nomor Resi</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Order akan diubah status menjadi "Shipped". Silakan masukkan nomor resi tracking:</p>
                    <input type="text" id="modalTrackingInput" 
                           class="mt-3 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nomor resi...">
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmTracking" 
                            class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        OK
                    </button>
                    <button id="cancelTracking" 
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentOrderId = null;
        let currentForm = null;
        let originalStatus = null;

        function handleStatusChange(selectElement, orderId) {
            const newStatus = selectElement.value;
            originalStatus = selectElement.dataset.originalValue || selectElement.value;
            
            if (newStatus === 'shipped') {
                currentOrderId = orderId;
                currentForm = document.getElementById('statusForm' + orderId);
                
                // Reset select to original value temporarily
                selectElement.value = originalStatus;
                
                // Show modal
                document.getElementById('trackingModal').classList.remove('hidden');
                document.getElementById('modalTrackingInput').focus();
            } else {
                // Submit form directly for other status changes
                selectElement.form.submit();
            }
        }

        // Handle modal confirm
        document.getElementById('confirmTracking').addEventListener('click', function() {
            const trackingNumber = document.getElementById('modalTrackingInput').value.trim();
            
            if (trackingNumber === '') {
                alert('Nomor resi tidak boleh kosong!');
                return;
            }
            
            // Set tracking number and status
            document.getElementById('trackingInput' + currentOrderId).value = trackingNumber;
            currentForm.querySelector('select[name="status"]').value = 'shipped';
            
            // Submit form
            currentForm.submit();
        });

        // Handle modal cancel
        document.getElementById('cancelTracking').addEventListener('click', function() {
            // Reset select to original value
            if (currentForm) {
                currentForm.querySelector('select[name="status"]').value = originalStatus;
            }
            
            // Hide modal
            document.getElementById('trackingModal').classList.add('hidden');
            document.getElementById('modalTrackingInput').value = '';
            
            currentOrderId = null;
            currentForm = null;
        });

        // Handle escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('trackingModal').classList.contains('hidden')) {
                document.getElementById('cancelTracking').click();
            }
        });

        // Store original values
        document.addEventListener('DOMContentLoaded', function() {
            const selects = document.querySelectorAll('select[name="status"]');
            selects.forEach(select => {
                select.dataset.originalValue = select.value;
            });
        });
    </script>
</x-app-layout>
