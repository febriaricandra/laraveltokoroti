<html>

<head>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            width: 100%;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid black;
            padding: 3px 5px;
        }

        td>.details-table {
            margin-top: 5px;
        }

        tr {
            page-break-inside: avoid;
        }

        .nowrap {
            white-space: nowrap;
        }

        .payment-method {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .cash {
            background-color: #d4edda;
            color: #155724;
        }

        .transfer {
            background-color: #cce5ff;
            color: #004085;
        }
    </style>
</head>

<body>
    <h2>Daftar Order Toko Roti</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Pelanggan</th>
                <th>Jumlah Item</th>
                <th>Total Harga</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
                {{-- <th>Bukti Pembayaran</th> --}}
                <th>Detail Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td class="nowrap">{{ $loop->iteration }}</td>
                    <td class="nowrap">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td class="nowrap">{{ $order->orderDetails->sum('quantity') }}</td>
                    <td class="nowrap">
                        Rp{{ number_format(
                            $order->orderDetails->sum(function ($item) {
                                return $item->price * $item->quantity;
                            }),
                            2,
                            ',',
                            '.',
                        ) }}
                    </td>
                    <td class="nowrap">
                        <span class="payment-method {{ $order->payment_method === 'cash' ? 'cash' : 'transfer' }}">
                            {{ $order->payment_method === 'cash' ? 'CASH' : 'TRANSFER' }}
                        </span>
                    </td>
                    <td class="nowrap">{{ ucfirst($order->status) }}</td>
                    {{-- <td class="nowrap">
                        @if ($order->payment_proof)
                            Lihat
                        @else
                            Belum ada
                        @endif
                    </td> --}}
                    <td>
                        <table class="details-table">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderDetails as $detail)
                                    <tr>
                                        <td>{{ $detail->product->name }}</td>
                                        <td class="nowrap">Rp{{ number_format($detail->price, 2, ',', '.') }}</td>
                                        <td class="nowrap">{{ $detail->quantity }}</td>
                                        <td class="nowrap">
                                            Rp{{ number_format($detail->price * $detail->quantity, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Informasi tambahan untuk metode pembayaran -->
                        <div style="margin-top: 5px; font-size: 9px;">
                            <strong>Alamat:</strong> {{ $order->address ?? 'Tidak ada alamat' }}<br>
                            @if($order->payment_method === 'transfer')
                                <strong>Bukti Transfer:</strong> {{ $order->payment_proof ? 'Tersedia' : 'Tidak ada' }}
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary/Footer -->
    <div style="margin-top: 20px; font-size: 10px;">
        <strong>Ringkasan Metode Pembayaran:</strong><br>
        @php
            $cashCount = $orders->where('payment_method', 'cash')->count();
            $transferCount = $orders->where('payment_method', 'transfer')->count();
            $totalOrders = $orders->count();
        @endphp
        
        <table style="width: 300px; margin-top: 5px;">
            <tr>
                <td>Total Order:</td>
                <td><strong>{{ $totalOrders }}</strong></td>
            </tr>
            <tr>
                <td>Pembayaran Cash:</td>
                <td><strong>{{ $cashCount }} order</strong></td>
            </tr>
            <tr>
                <td>Pembayaran Transfer:</td>
                <td><strong>{{ $transferCount }} order</strong></td>
            </tr>
        </table>
    </div>
    
    <div style="margin-top: 30px; text-align: right; font-size: 10px;">
        <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>

</html>
