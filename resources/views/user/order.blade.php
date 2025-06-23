<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-orange-50 min-h-screen flex flex-col">

    <nav class="bg-[#F26725] fixed top-0 left-0 w-full p-4 text-white shadow-lg z-50">
        <div class="container mx-auto flex justify-between items-center px-4">
            <!-- Logo -->
            <a href="{{ route('user.products') }}" class="flex items-center space-x-3">
                <div class="h-10 w-10  flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('bread.png') }}" alt="Toko Roti Dinar Logo" class="h-8 w-8 object-contain">
                </div>
                <span class="text-2xl font-bold">Toko Roti Dinar</span>
            </a>

            <!-- Tombol Hamburger (Mobile) -->
            <button id="menuButton" class="block sm:hidden focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

            <!-- Menu Utama -->
            <div id="navMenu"
                class="hidden sm:flex flex-col sm:flex-row sm:items-center sm:space-x-4 w-full sm:w-auto mt-4 sm:mt-0">
                <a href="{{ route('user.products') }}" class="px-4 py-2 hover:bg-orange-600 rounded">Home</a>
                <a href="{{ route('user.menu') }}" class="px-4 py-2 hover:bg-orange-600 rounded">Menu</a>
                <a href="{{ route('user.contact') }}" class="px-4 py-2 hover:bg-orange-600 rounded">Contact</a>
                @auth
                    <!-- Keranjang dengan Icon -->
                    <a href="{{ route('cart.index') }}" class="px-4 py-2 hover:bg-orange-600 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l.4 2m0 0l1.6 8h10.4l1.6-8m-13.6 0h13.6m-4 10a2 2 0 11-4 0m6 0a2 2 0 11-4 0" />
                        </svg>
                        <span class="ml-1">Keranjang</span>
                    </a>
                    <a href="{{ route('user.order') }}" class="px-4 py-2 hover:bg-orange-600 rounded">Order</a>
                    <!-- Dropdown User -->
                    <div class="relative">
                        <button id="userMenuButton"
                            class="px-4 py-2 bg-orange-600 rounded hover:bg-orange-700 flex items-center space-x-2 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.121 17.804A9.003 9.003 0 0112 15a9.003 9.003 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ Auth::user()->name }}</span>
                        </button>
                        <div id="userDropdown" class="absolute right-0 mt-2 bg-white text-black rounded shadow-lg hidden">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 w-full text-left hover:bg-gray-200">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-orange-500 rounded hover:bg-orange-600">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 flex-grow">
        <h2 class="text-3xl font-bold text-orange-600 mb-6 text-center">Daftar Order</h2>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-500 text-white rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">

                    <thead class="bg-orange-200">
                        <tr>
                            <th class="text-left px-6 py-3">No</th>
                            <th class="text-left px-6 py-3">Pemesan</th>
                            <th class="text-left px-6 py-3">Alamat</th>
                            <th class="text-left px-6 py-3">Jumlah Item</th>
                            <th class="text-left px-6 py-3">Subtotal Awal</th>
                            <th class="text-left px-6 py-3">Diskon (%)</th>
                            <th class="text-left px-6 py-3">Total Diskon</th>
                            <th class="text-left px-6 py-3">Total Akhir</th>
                            <th class="text-left px-6 py-3">Metode Pembayaran</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-left px-6 py-3">Bukti Pembayaran</th>
                            <th class="text-left px-6 py-3">Tanggal</th>
                            <th class="text-left px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach ($orders as $order)
                            <tr class="border-b hover:bg-orange-50">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 capitalize">{{ $order->name }}</td>
                                <td class="px-6 py-4 capitalize">{{ $order->address }}</td>
                                <td class="px-6 py-4">{{ $order->orderDetails->sum('quantity') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $originalSubtotal = $order->orderDetails->sum(
                                            fn($item) => $item->price * $item->quantity,
                                        );
                                    @endphp
                                    Rp{{ number_format($originalSubtotal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->discount_percentage > 0 ? $order->discount_percentage * 100 . '%' : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($order->total_discount > 0)
                                        Rp{{ number_format($order->total_discount, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold">
                                    Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 capitalize">{{ ucfirst($order->payment_method) }}</td>
                                <td class="px-6 py-4 capitalize">{{ $order->status }}</td>
                                <td class="px-6 py-4">
                                    @if ($order->payment_proof)
                                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                                            class="text-blue-600 underline">
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-500 italic">Belum ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2">
                                        <a href="{{ route('user.order.details', $order->id) }}"
                                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm text-center w-full sm:w-auto">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @else
            <p class="text-center text-gray-600 mt-8">Anda belum memiliki order.</p>
        @endif
    </div>>

    <!-- Footer -->
    <footer class="bg-orange-500 text-white text-center p-4">
        <p>&copy; 2025 Toko Roti. All Rights Reserved.</p>
    </footer>
</body>

</html>
