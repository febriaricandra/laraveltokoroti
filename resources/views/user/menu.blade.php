<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Toko Roti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
</head>

<body class="bg-orange-50 flex flex-col min-h-screen">

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

    <div id="alert-success"
        class="hidden fixed top-20 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
        Produk berhasil ditambahkan ke keranjang!
    </div>

    <main class="flex-grow">
        <div class="pt-24 pb-12">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block text-[#F26725]">Jelajahi Menu Kami</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Temukan kue dan roti favorit Anda, dibuat dengan cinta dan bahan-bahan terbaik.
                </p>
            </div>
        </div>

        <div class="container mx-auto my-12 px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach ($products as $product)
                    <div
                        class="bg-white shadow-lg rounded-xl overflow-hidden flex flex-col transform hover:-translate-y-2 transition-transform duration-300">
                        <div class="h-48 overflow-hidden">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400/F26725/FFFFFF?text=Roti' }}"
                                alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="flex justify-between items-start">
                                <h3 class="text-xl font-bold text-gray-800">{{ $product->name }}</h3>
                                <span class="text-sm text-orange-500 font-medium px-2 py-1 bg-orange-100 rounded-full">{{ $product->category->name }}</span>
                            </div>
                            <p class="text-gray-600 text-sm mt-2 flex-grow">{{ $product->description }}</p>

                            <form class="add-to-cart-form mt-4 pt-4 border-t border-gray-200">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                @if($product->sizes && $product->sizes->where('is_active', true)->count() > 0)
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Ukuran:</label>
                                        <select name="product_size_id" class="w-full p-2 border border-gray-300 rounded-md text-sm">
                                            @foreach($product->sizes->where('is_active', true) as $size)
                                                <option value="{{ $size->id }}" data-price="{{ $size->price }}">
                                                    {{ $size->size }} - Rp{{ number_format($size->price, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mt-3">
                                        <div class="flex items-center">
                                            <button type="button" class="quantity-btn minus bg-gray-200 px-2 py-1 rounded-l-md">-</button>
                                            <input type="number" name="quantity" value="1" min="1" class="quantity-input w-12 p-1 text-center border-t border-b border-gray-300">
                                            <button type="button" class="quantity-btn plus bg-gray-200 px-2 py-1 rounded-r-md">+</button>
                                        </div>
                                        
                                        <button type="submit"
                                            class="bg-orange-500 text-white py-2 px-4 rounded-md hover:bg-orange-600 transition-colors duration-300 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z" />
                                            </svg>
                                            Tambahkan
                                        </button>
                                    </div>
                                @else
                                    <p class="text-red-500 text-sm italic">Maaf, produk tidak tersedia</p>
                                @endif
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <footer class="bg-orange-500 text-white text-center p-4 w-full">
        <p>&copy; 2025 Toko Roti. All Rights Reserved.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuButton = document.getElementById("menuButton");
            const navMenu = document.getElementById("navMenu");
            const userMenuButton = document.getElementById("userMenuButton");
            const userDropdown = document.getElementById("userDropdown");

            if (menuButton) {
                menuButton.addEventListener("click", function() {
                    navMenu.classList.toggle("hidden");
                });
            }

            if (userMenuButton && userDropdown) {
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
            
            // Quantity buttons
            document.querySelectorAll('.quantity-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentNode.querySelector('.quantity-input');
                    const currentValue = parseInt(input.value);
                    
                    if (this.classList.contains('plus')) {
                        input.value = currentValue + 1;
                    } else if (this.classList.contains('minus') && currentValue > 1) {
                        input.value = currentValue - 1;
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.add-to-cart-form').submit(function(event) {
                event.preventDefault();

                let form = $(this);
                let formData = form.serialize();

                $.ajax({
                    url: "{{ route('cart.store') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        $('#alert-success').text(response.message).fadeIn().delay(2000)
                            .fadeOut();
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            alert("Silahkan Login terlebih dahulu.");
                            window.location.href = "{{ route('login') }}";
                        } else {
                            // Tampilkan pesan error dari server jika ada
                            let errorMsg = 'Terjadi kesalahan, coba lagi!';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            alert(errorMsg);
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>