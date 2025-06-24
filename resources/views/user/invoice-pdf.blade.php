<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Order #{{ $order->id }}</title>
    <style>
        /* Basic styling for PDF - keep it simple for compatibility */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        h1,
        h2,
        h3 {
            color: #E53E3E;
            /* A shade of orange/red */
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-details,
        .order-summary {
            margin-bottom: 20px;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #FEEBCF;
            /* Light orange */
            color: #8B4513;
            /* Darker orange */
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-orange-600 {
            color: #DD6B20;
        }

        .text-green-600 {
            color: #38A169;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 class="text-orange-600">INVOICE</h1>
            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
        </div>

        <div class="invoice-details">
            <h3>Detail Pelanggan</h3>
            <p><strong>Nama Pemesan:</strong> {{ $order->name }}</p>
            <p><strong>Alamat:</strong> {{ $order->address }}</p>
            <p><strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>
            <p><strong>Status Order:</strong> {{ ucfirst($order->status) }}</p>
            @if ($order->payment_proof)
                <p><strong>Bukti Pembayaran:</strong> Terlampir</p>
            @endif
        </div>

        <div class="order-summary">
            <h3>Ringkasan Produk</h3>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal Item</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>Rp{{ number_format($item->size->price, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp{{ number_format($item->size->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right">
                <p class="font-bold">Subtotal Awal: Rp{{ number_format($overallOriginalSubtotal, 0, ',', '.') }}</p>
                @if ($order->total_discount > 0)
                    <p class="text-green-600 font-bold">Diskon ({{ $order->discount_percentage * 100 }}%): -
                        Rp{{ number_format($order->total_discount, 0, ',', '.') }}</p>
                @endif
                <p class="font-bold text-orange-600" style="font-size: 1.3em;">Total Akhir:
                    Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih telah berbelanja dengan kami!</p>
            <p>Dibuat pada: {{ date('d M Y H:i') }}</p>
        </div>
    </div>
</body>

</html>
