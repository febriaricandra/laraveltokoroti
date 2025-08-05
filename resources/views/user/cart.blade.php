<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Belanja - Toko Roti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
</head>

<body class="bg-orange-50 flex flex-col min-h-screen">

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

    <br>
    <br>
    <div class="container mx-auto my-8 px-4 flex-grow max-w-3xl">
        <h2 class="text-3xl font-bold text-center text-orange-600 mb-6">Keranjang Belanja</h2>

        @if (session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if ($cartItems->isEmpty())
            <div class="bg-white shadow-lg rounded-lg p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-orange-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-gray-500 text-lg">Keranjang belanja masih kosong.</p>
                <a href="{{ route('user.menu') }}" class="inline-block mt-4 px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                    Lihat Menu
                </a>
            </div>
        @else
            <div class="bg-white shadow-lg rounded-lg p-6 overflow-x-auto">
                <table class="w-full min-w-[600px]">
                    <thead>
                        <tr class="bg-orange-500 text-white">
                            <th class="p-3 text-left">Produk</th>
                            <th class="p-3 text-left hidden sm:table-cell">Harga</th>
                            <th class="p-3 text-left">Jumlah</th>
                            <th class="p-3 text-left hidden sm:table-cell">Subtotal</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($cartItems as $item)
                            @php 
                                // Get price from size, not from product
                                $price = $item->size ? $item->size->price : 0;
                                $subtotal = $price * $item->quantity; 
                            @endphp

                            <tr class="border-b flex flex-col sm:table-row">
                                <td class="p-3 flex items-center space-x-3">
                                    <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/600x400/F26725/FFFFFF?text=Roti' }}"
                                        class="w-16 h-16 rounded object-cover">
                                    <div>
                                        <span class="font-semibold">{{ $item->product->name }}</span>
                                        @if ($item->size)
                                            <p class="text-sm text-orange-500">Ukuran: {{ $item->size->size }}</p>
                                        @endif
                                        @if ($item->product->unit)
                                            <p class="text-xs text-gray-500">Unit: {{ $item->size->unit }}</p>
                                        @endif
                                        <p class="sm:hidden text-gray-600">
                                            Rp{{ number_format($price, 0, ',', '.') }}</p>
                                        <p class="sm:hidden font-bold">Subtotal:
                                            Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </td>
                                <td class="p-3 hidden sm:table-cell">
                                    Rp{{ number_format($price, 0, ',', '.') }}</td>
                                <td class="p-3">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                        class="flex items-center space-x-2">
                                        @csrf
                                        @method('PUT')
                                        <button type="button" onclick="decrementQuantity(this)" class="text-gray-700 hover:text-orange-500 bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center">
                                            <i class="fas fa-minus text-sm"></i>
                                        </button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}"
                                            min="1" class="w-14 p-1 border rounded text-center" readonly>
                                        <button type="button" onclick="incrementQuantity(this)" class="text-gray-700 hover:text-orange-500 bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center">
                                            <i class="fas fa-plus text-sm"></i>
                                        </button>
                                        <button type="submit" class="text-blue-500 hover:text-blue-700 ml-2">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3 hidden sm:table-cell font-bold">
                                    Rp{{ number_format($subtotal, 0, ',', '.') }}
                                </td>
                                <td class="p-3">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash-alt text-xl"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-8 p-6 bg-gray-50 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700">Total Belanja:</span>
                        <span
                            class="font-semibold text-gray-800">Rp{{ number_format($subtotalAmount, 0, ',', '.') }}</span>
                    </div>

                    @if ($appliedDiscount)
                        <div class="flex justify-between items-center mb-2 text-green-600">
                            <span class="font-bold">Diskon ({{ $appliedDiscount->name }} -
                                {{ $appliedDiscount->discount_percentage * 100 }}%):</span>
                            <span class="font-bold">- Rp{{ number_format($discountAmount, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <div class="mb-2 text-gray-500 text-sm">
                            @php
                                $nextDiscount = \App\Models\Discount::where('is_active', true)
                                    ->where('minimum_order', '>', $subtotalAmount)
                                    ->orderBy('minimum_order', 'asc')
                                    ->first();
                            @endphp

                            @if ($nextDiscount)
                                <p>Belanja lagi <span
                                        class="font-semibold text-orange-600">Rp{{ number_format($nextDiscount->minimum_order - $subtotalAmount, 0, ',', '.') }}</span>
                                    untuk mendapatkan diskon <span
                                        class="font-semibold text-orange-600">{{ $nextDiscount->discount_percentage * 100 }}%</span>
                                    (Min. order Rp{{ number_format($nextDiscount->minimum_order, 0, ',', '.') }})</p>
                            @else
                                <p>Tidak ada diskon yang berlaku saat ini atau semua diskon sudah tercapai.</p>
                            @endif
                        </div>
                    @endif

                    <!-- Shipping Cost Section -->
                    @if ($isShippingEnabled)
                        <div id="shipping_section" class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-gray-800 mb-3">Biaya Pengiriman</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label for="shipping_province" class="block text-sm font-medium text-gray-700 mb-1">
                                        Provinsi Tujuan
                                    </label>
                                    <select id="shipping_province" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach ($provincesData as $province)
                                            <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">
                                        Kota/Kabupaten
                                    </label>
                                    <select id="shipping_city" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" disabled>
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </div>
                            </div>

                            <div id="shipping_options" class="hidden mt-3">
                                <h5 class="font-medium text-gray-800 mb-2">Pilih Kurir:</h5>
                                <div id="shipping_choices" class="space-y-2">
                                    <!-- Shipping options will be populated here -->
                                </div>
                            </div>

                            <div id="shipping_cost_display" class="mt-3 hidden">
                                <div class="flex justify-between items-center text-orange-600">
                                    <span class="font-bold">Ongkos Kirim:</span>
                                    <span id="shipping_cost_amount" class="font-bold">Rp0</span>
                                </div>
                            </div>

                            <div id="same_city_message" class="mt-3 hidden">
                                <div class="flex items-center text-green-600">
                                    <i class="fas fa-truck mr-2"></i>
                                    <span class="font-bold">Gratis Ongkir - Satu kota dengan toko!</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-4">
                        <h3 class="text-xl font-bold text-orange-600">Total Akhir:</h3>
                        <h3 id="final_total_display" class="text-2xl font-extrabold text-orange-600">
                            Rp{{ number_format($finalTotal, 0, ',', '.') }}</h3>
                    </div>
                </div>


                <form action="{{ route('cart.checkout') }}" method="POST" enctype="multipart/form-data"
                    class="bg-white rounded-lg p-6 mt-6 shadow-md w-full max-w-xl mx-auto space-y-4">
                    @csrf

                    <!-- Hidden fields for shipping -->
                    <input type="hidden" name="customer_province_id" id="checkout_province_id">
                    <input type="hidden" name="customer_city_id" id="checkout_city_id">
                    <input type="hidden" name="shipping_cost" id="checkout_shipping_cost" value="0">
                    <input type="hidden" name="shipping_courier" id="checkout_shipping_courier">
                    <input type="hidden" name="shipping_service" id="checkout_shipping_service">
                    <input type="hidden" name="shipping_weight" id="checkout_shipping_weight" value="1000">

                    <div>
                        <label for="name" class="block font-semibold text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ Auth::user()->name ?? '' }}" required
                            class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-orange-500">
                    </div>

                    <div>
                        <label for="address" class="block font-semibold text-gray-700">Alamat Pengiriman</label>
                        <textarea name="address" id="address" rows="3" required
                            class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-orange-500"></textarea>
                    </div>
                    
                    <div>
                        <label for="phone" class="block font-semibold text-gray-700">No. Telepon</label>
                        <input type="text" name="phone" id="phone" required
                            class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-orange-500" 
                            placeholder="contoh: 08123456789">
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-2">Metode Pembayaran</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="cash" name="payment_method" value="cash"
                                    class="mr-2 text-orange-500 focus:ring-orange-500"
                                    onchange="togglePaymentProof()">
                                <label for="cash" class="text-gray-700">Cash (Bayar di Tempat)</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="transfer" name="payment_method" value="transfer" checked
                                    class="mr-2 text-orange-500 focus:ring-orange-500"
                                    onchange="togglePaymentProof()">
                                <label for="transfer" class="text-gray-700">Transfer Bank</label>
                            </div>
                        </div>
                    </div>

                    <div id="down_payment_section">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="is_down_payment" name="is_down_payment" value="1"
                                    class="mr-2 text-orange-500 focus:ring-orange-500"
                                    onchange="toggleDownPayment()">
                                <label for="is_down_payment" class="text-gray-700 font-semibold">
                                    Bayar Down Payment (DP) 50%
                                </label>
                            </div>
                            <div id="dp_info" class="mt-2 text-sm text-gray-600 hidden">
                                <p class="mb-1">
                                    <span class="font-semibold">DP Amount:</span> 
                                    <span id="dp_amount">Rp{{ number_format($finalTotal * 0.5, 0, ',', '.') }}</span>
                                </p>
                                <p>
                                    <span class="font-semibold">Sisa Pembayaran:</span> 
                                    <span id="remaining_amount">Rp{{ number_format($finalTotal * 0.5, 0, ',', '.') }}</span>
                                </p>
                                <p class="text-xs text-orange-600 mt-2">
                                    * Dengan memilih DP, Anda hanya perlu membayar 50% dari total belanja terlebih dahulu. 
                                    Sisa pembayaran dapat dilakukan saat pengambilan/pengiriman.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="payment_proof_section">
                        <label for="payment_proof" class="block font-semibold text-gray-700">Upload Bukti
                            Pembayaran</label>
                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*"
                            class="w-full mt-1 p-2 border border-gray-300 rounded bg-white focus:outline-none focus:ring focus:border-orange-500">
                        <p class="text-sm text-gray-500 mt-1">Transfer ke: BCA 1234567890 a.n Toko Roti</p>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t">
                        <h3 class="text-xl font-bold">
                            <span id="payment_label">Total yang harus dibayar:</span>
                            <span id="payment_amount" data-total="{{ $finalTotal }}">Rp{{ number_format($finalTotal, 0, ',', '.') }}</span>
                        </h3>
                        <button type="submit"
                            class="bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600 transition duration-200">
                            Checkout
                        </button>
                    </div>
                </form>

            </div>
        @endif
    </div>


    <footer class="bg-orange-500 text-white text-center p-4 mt-8 w-full">
        <p>&copy; 2025 Toko Roti. All Rights Reserved.</p>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userMenuButton = document.getElementById("userMenuButton");
            const userDropdown = document.getElementById("userDropdown");

            if (userMenuButton) {
                userMenuButton.addEventListener("click", function() {
                    userDropdown.classList.toggle("hidden");
                });

                document.addEventListener("click", function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add("hidden");
                    }
                });
            }
        });

        function togglePaymentProof() {
            const cashRadio = document.getElementById('cash');
            const transferRadio = document.getElementById('transfer');
            const paymentProofSection = document.getElementById('payment_proof_section');
            const paymentProofInput = document.getElementById('payment_proof');
            const downPaymentSection = document.getElementById('down_payment_section');

            if (cashRadio.checked) {
                paymentProofSection.style.display = 'none';
                paymentProofInput.removeAttribute('required');
                downPaymentSection.style.display = 'none';
                
                // Reset down payment checkbox when cash is selected
                const dpCheckbox = document.getElementById('is_down_payment');
                dpCheckbox.checked = false;
                toggleDownPayment();
            } else if (transferRadio.checked) {
                paymentProofSection.style.display = 'block';
                paymentProofInput.setAttribute('required', 'required');
                downPaymentSection.style.display = 'block';
            }
        }
        
        function toggleDownPayment() {
            const dpCheckbox = document.getElementById('is_down_payment');
            const dpInfo = document.getElementById('dp_info');
            const paymentLabel = document.getElementById('payment_label');
            const paymentAmount = document.getElementById('payment_amount');
            
            // Ambil nilai dari data attribute yang sudah disiapkan
            const finalTotal = parseInt(paymentAmount.getAttribute('data-total'));
            const dpAmount = Math.round(finalTotal * 0.5);
            
            if (dpCheckbox.checked) {
                dpInfo.classList.remove('hidden');
                paymentLabel.textContent = 'Down Payment yang harus dibayar:';
                paymentAmount.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(dpAmount);
            } else {
                dpInfo.classList.add('hidden');
                paymentLabel.textContent = 'Total yang harus dibayar:';
                paymentAmount.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(finalTotal);
            }
        }
        
        function decrementQuantity(button) {
            const input = button.nextElementSibling;
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
        
        function incrementQuantity(button) {
            const input = button.previousElementSibling;
            const currentValue = parseInt(input.value);
            input.value = currentValue + 1;
        }

        document.addEventListener('DOMContentLoaded', function() {
            togglePaymentProof();
            
            // Shipping functionality
            const provinceSelect = document.getElementById('shipping_province');
            const citySelect = document.getElementById('shipping_city');
            const shippingOptions = document.getElementById('shipping_options');
            const shippingChoices = document.getElementById('shipping_choices');
            const shippingCostDisplay = document.getElementById('shipping_cost_display');
            const shippingCostAmount = document.getElementById('shipping_cost_amount');
            const sameCityMessage = document.getElementById('same_city_message');
            const finalTotalDisplay = document.getElementById('final_total_display');
            
            let currentShippingCost = 0;
            const originalTotal = {{ $finalTotal }};

            if (provinceSelect) {
                provinceSelect.addEventListener('change', function() {
                    const provinceId = this.value;
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    citySelect.disabled = !provinceId;
                    
                    hideShippingElements();
                    
                    if (provinceId) {
                        loadCities(provinceId);
                    }
                });

                citySelect.addEventListener('change', function() {
                    const cityId = this.value;
                    
                    hideShippingElements();
                    
                    if (cityId) {
                        calculateShipping(cityId);
                        
                        // Update checkout hidden fields
                        document.getElementById('checkout_province_id').value = provinceSelect.value;
                        document.getElementById('checkout_city_id').value = cityId;
                    }
                });
            }

            function loadCities(provinceId) {
                fetch(`/cart/cities/${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Cities response:', data); // Debug log
                        
                        if (data.data && Array.isArray(data.data)) {
                            data.data.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name; // Remove type reference
                                citySelect.appendChild(option);
                            });
                        } else {
                            console.error('Invalid cities data:', data);
                            alert('Gagal memuat daftar kota. Format data tidak valid.');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading cities:', error);
                        alert('Gagal memuat daftar kota.');
                    });
            }

            function calculateShipping(cityId) {
                const weight = 1000; // Default weight 1kg, you can make this dynamic based on products
                
                fetch('/cart/shipping-cost', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        city_id: cityId,
                        weight: weight
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.same_city) {
                        showSameCityMessage();
                        updateShippingCost(0, '', '');
                    } else if (data.shipping_options) {
                        showShippingOptions(data.shipping_options);
                    }
                })
                .catch(error => {
                    console.error('Error calculating shipping:', error);
                    alert('Gagal menghitung ongkos kirim.');
                });
            }

            function showSameCityMessage() {
                sameCityMessage.classList.remove('hidden');
                shippingOptions.classList.add('hidden');
                shippingCostDisplay.classList.add('hidden');
            }

            function showShippingOptions(options) {
                shippingChoices.innerHTML = '';
                
                Object.keys(options).forEach(courier => {
                    const courierOptions = options[courier];
                    if (courierOptions && courierOptions.length > 0) {
                        courierOptions.forEach(option => {
                            const optionDiv = document.createElement('div');
                            optionDiv.className = 'flex items-center p-3 border border-gray-200 rounded hover:bg-gray-50 cursor-pointer';
                            
                            // Handle the new API response structure
                            const cost = option.cost || 0;
                            const etd = option.etd || '-';
                            const service = option.service || '';
                            const description = option.description || '';
                            const name = option.name || courier.toUpperCase();
                            
                            optionDiv.innerHTML = `
                                <input type="radio" name="shipping_option" value="${courier}-${service}" 
                                       data-cost="${cost}" 
                                       data-courier="${courier.toUpperCase()}" 
                                       data-service="${service}"
                                       class="mr-3">
                                <div class="flex-1">
                                    <div class="font-medium">${name} - ${service}</div>
                                    <div class="text-sm text-gray-600">${description}</div>
                                    <div class="text-sm text-gray-500">Estimasi: ${etd}</div>
                                </div>
                                <div class="font-bold text-orange-600">
                                    Rp${new Intl.NumberFormat('id-ID').format(cost)}
                                </div>
                            `;
                            
                            optionDiv.addEventListener('click', function() {
                                const radio = this.querySelector('input[type="radio"]');
                                radio.checked = true;
                                
                                const cost = parseInt(radio.dataset.cost);
                                const courier = radio.dataset.courier;
                                const service = radio.dataset.service;
                                
                                updateShippingCost(cost, courier, service);
                            });
                            
                            shippingChoices.appendChild(optionDiv);
                        });
                    }
                });
                
                shippingOptions.classList.remove('hidden');
                sameCityMessage.classList.add('hidden');
            }

            function updateShippingCost(cost, courier, service) {
                currentShippingCost = cost;
                
                if (cost > 0) {
                    shippingCostAmount.textContent = `Rp${new Intl.NumberFormat('id-ID').format(cost)}`;
                    shippingCostDisplay.classList.remove('hidden');
                } else {
                    shippingCostDisplay.classList.add('hidden');
                }
                
                // Update final total
                const newTotal = originalTotal + cost;
                finalTotalDisplay.textContent = `Rp${new Intl.NumberFormat('id-ID').format(newTotal)}`;
                
                // Update hidden form fields
                document.getElementById('checkout_shipping_cost').value = cost;
                document.getElementById('checkout_shipping_courier').value = courier;
                document.getElementById('checkout_shipping_service').value = service;
                
                // Update payment amount display
                updatePaymentAmount();
            }

            function hideShippingElements() {
                shippingOptions.classList.add('hidden');
                shippingCostDisplay.classList.add('hidden');
                sameCityMessage.classList.add('hidden');
                currentShippingCost = 0;
                finalTotalDisplay.textContent = `Rp${new Intl.NumberFormat('id-ID').format(originalTotal)}`;
                updatePaymentAmount();
            }

            function updatePaymentAmount() {
                const dpCheckbox = document.getElementById('is_down_payment');
                const paymentAmount = document.getElementById('payment_amount');
                const totalWithShipping = originalTotal + currentShippingCost;
                
                if (dpCheckbox && dpCheckbox.checked) {
                    const dpAmount = Math.round(totalWithShipping * 0.5);
                    paymentAmount.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(dpAmount);
                } else {
                    paymentAmount.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(totalWithShipping);
                }
                
                // Update payment amount data attribute
                paymentAmount.setAttribute('data-total', totalWithShipping);
            }
        });
    </script>
</body>

</html>