<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-orange-50 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-[#F26725] fixed top-0 left-0 w-full p-4 text-white shadow-lg z-50">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="{{ route('user.products') }}" class="flex items-center space-x-3">
                <div class="h-10 w-10  flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('bread.png') }}" alt="Toko Roti Dinar Logo" class="h-8 w-8 object-contain">
                </div>
                <span class="text-2xl font-bold">Toko Roti Dinar</span>
            </a>

            <button id="menuButton" class="block sm:hidden focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

            <div id="navMenu"
                class="hidden sm:flex flex-col sm:flex-row sm:items-center sm:space-x-4 w-full sm:w-auto mt-4 sm:mt-0">
                <a href="{{ route('user.products') }}" class="px-4 py-2 hover:bg-orange-600 rounded">Home</a>
                <a href="{{ route('user.menu') }}" class="px-4 py-2 hover:bg-orange-600 rounded">Menu</a>
                <a href="{{ route('user.contact') }}" class="px-4 py-2 hover:bg-orange-600 rounded">Contact</a>
                @auth
                    <a href="{{ route('cart.index') }}" class="px-4 py-2 hover:bg-orange-600 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l.4 2m0 0l1.6 8h10.4l1.6-8m-13.6 0h13.6m-4 10a2 2 0 11-4 0m6 0a2 2 0 11-4 0" />
                        </svg>
                        <span class="ml-1">Keranjang</span>
                    </a>
                    <a href="{{ route('user.order') }}" class="px-4 py-2 hover:bg-orange-600 rounded">Order</a>
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

    <!-- Konten -->
    <div class="container mx-auto px-4 py-24 flex-grow">
        <h2 class="text-3xl font-bold text-orange-600 mb-6 text-center">Detail Order</h2>
        @if (session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-500 text-white p-3 rounded mb-4 text-center">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <p><strong>Nama Pemesan:</strong> {{ $order->name }}</p>
            <p><strong>Alamat:</strong> {{ $order->address }}</p>
            <p><strong>No. Telepon:</strong> {{ $order->phone }}</p>
            <p><strong>Status:</strong> 
                <span class="
                    @if($order->status == 'pending') text-yellow-600 font-semibold
                    @elseif($order->status == 'processing') text-blue-600 font-semibold
                    @elseif($order->status == 'shipped') text-orange-600 font-semibold
                    @elseif($order->status == 'delivered') text-green-600 font-semibold
                    @elseif($order->status == 'cancelled') text-red-600 font-semibold
                    @elseif($order->status == 'paid') text-green-600 font-semibold
                    @endif
                ">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>
            <p><strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
            <p><strong>Bukti Pembayaran:</strong>
                @if ($order->payment_proof)
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                        class="text-blue-600 underline">
                        Lihat Bukti
                    </a>
                @else
                    <span class="text-gray-500 italic">Belum ada</span>
                @endif
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-orange-200">
                    <tr>
                        <th class="text-left px-6 py-3">Produk</th>
                        <th class="text-left px-6 py-3">Ukuran</th>
                        <th class="text-left px-6 py-3">Harga</th>
                        <th class="text-left px-6 py-3">Jumlah</th>
                        <th class="text-left px-6 py-3">Subtotal Item</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @php
                        $overallOriginalSubtotal = 0;
                    @endphp
                    @foreach ($order->orderDetails as $item)
                        @php
                            $itemSubtotal = $item->size->price * $item->quantity;
                            $overallOriginalSubtotal += $itemSubtotal;
                            
                            // Get size information
                            $sizeName = "Standard";
                            if ($item->product_size_id) {
                                $productSize = \App\Models\ProductSize::find($item->product_size_id);
                                if ($productSize) {
                                    $sizeName = $productSize->size;
                                }
                            }
                        @endphp
                        <tr class="border-b hover:bg-orange-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/600x400/F26725/FFFFFF?text=Roti' }}" 
                                         class="w-12 h-12 object-cover rounded mr-3">
                                    <span class="font-medium">{{ $item->product->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-orange-600 font-medium">{{ $sizeName }}</span>
                            </td>
                            <td class="px-6 py-4">Rp{{ number_format($item->size->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 font-semibold">
                                Rp{{ number_format($itemSubtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 bg-white p-4 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xl text-gray-700">Subtotal Awal:</span>
                <span class="text-xl font-semibold text-gray-700">Rp{{ number_format($overallOriginalSubtotal, 0, ',', '.') }}</span>
            </div>
            
            @if ($order->total_discount > 0)
                <div class="flex justify-between items-center mb-2">
                    <span class="text-lg text-green-600">Diskon ({{ $order->discount_percentage * 100 }}%):</span>
                    <span class="text-lg font-semibold text-green-600">- Rp{{ number_format($order->total_discount, 0, ',', '.') }}</span>
                </div>
            @endif
            
            <div class="flex justify-between items-center pt-2 border-t border-gray-200 mt-2">
                <span class="text-2xl font-bold text-orange-700">Total Akhir:</span>
                <span class="text-2xl font-extrabold text-orange-700">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="mt-6 flex flex-wrap gap-2">
            <a href="{{ route('user.order') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Order
            </a>
            <a href="{{ route('user.order.invoice', $order->id) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm" target="_blank">
                <i class="fas fa-download mr-1"></i> Unduh Invoice PDF
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-orange-500 text-white text-center p-4">
        <p>&copy; 2025 Toko Roti. All Rights Reserved.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuButton = document.getElementById("menuButton");
            const navMenu = document.getElementById("navMenu");
            const userMenuButton = document.getElementById("userMenuButton");
            const userDropdown = document.getElementById("userDropdown");

            // Toggle menu saat tombol hamburger diklik
            if (menuButton) {
                menuButton.addEventListener("click", function() {
                    navMenu.classList.toggle("hidden");
                });
            }

            // Toggle dropdown user
            if (userMenuButton) {
                userMenuButton.addEventListener("click", function(event) {
                    event.stopPropagation();
                    userDropdown.classList.toggle("hidden");
                });

                document.addEventListener("click", function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add("hidden");
                    }
                });
            }
        });
    </script>
</body>

</html>