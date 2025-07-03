<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Order #{{ $order->id }}</title>
    <style>
        /* Basic styling for PDF - keep it simple for compatibility */
        @page {
            size: A5;
            margin: 10mm;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 10px;
        }

        h1,
        h2,
        h3 {
            color: #E53E3E;
            margin: 5px 0;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
            display: block;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 8px;
        }

        .invoice-details,
        .order-summary {
            margin-bottom: 15px;
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }

        table th {
            background-color: #FEEBCF;
            color: #8B4513;
            font-size: 8px;
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

        h3 {
            font-size: 12px;
            margin: 8px 0;
        }

        p {
            margin: 3px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('logo-dinar.jpeg') }}" alt="Dinar Logo" class="logo">
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
                <p class="font-bold text-orange-600" style="font-size: 1.2em;">Total Akhir:
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