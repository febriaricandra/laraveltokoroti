<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Roti Dinar - Cake & Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
</head>

<style>
    .swiper-pagination-bullet {
        background: rgba(255, 255, 255, 0.5);
        opacity: 1;
    }

    .swiper-pagination-bullet-active {
        background: #F26725;
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: #F26725 !important;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        width: 40px !important;
        height: 40px !important;
        margin-top: -20px !important;
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 18px !important;
        font-weight: bold;
    }

    .swiper-container:hover .swiper-button-next,
    .swiper-container:hover .swiper-button-prev {
        opacity: 1;
    }

    .swiper-button-next,
    .swiper-button-prev {
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }
</style>

<body class="bg-orange-50 flex flex-col min-h-screen">

    <nav class="bg-[#F26725] fixed top-0 left-0 w-full p-4 text-white shadow-lg z-50">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="{{ route('user.products') }}" class="flex items-center space-x-3">
                <div class="h-10 w-24  flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('bread.png') }}" alt="Toko Roti Dinar Logo" class="h-8 w-24 object-contain">
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

    <main class="flex-grow pt-16">
        <div class="swiper-container w-full aspect-[16/9] relative">
            <div class="swiper-wrapper">
                @forelse ($banners as $banner)
                    <div class="swiper-slide relative">
                        @if ($banner->image)
                            <img src="{{ Storage::url($banner->image) }}" class="w-full h-full object-cover"
                                alt="{{ 'Banner Image' }}">
                        @else
                            <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-600 text-xl">No Image Available</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                            <div class="text-center text-white p-4">
                                <h1 class="text-2xl md:text-4xl font-bold mb-2 break-words">
                                    {{-- {{ $banner->title ?? 'Selamat Datang!' }} --}}
                                </h1>
                                <p class="text-lg md:text-xl break-words whitespace-normal">
                                    {{ $banner->description ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide relative">
                        <img src="{{ asset('hero.jpg') }}" class="w-full h-full object-cover" alt="Roti Segar Default">
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                            <div class="text-center text-white">
                                <h1 class="text-2xl md:text-4xl font-bold mb-2">Selamat Datang di Toko Roti Kami</h1>
                                <p class="text-lg md:text-xl">Nikmati kelezatan roti buatan tangan setiap hari.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Elemen Navigasi & Paginasi Swiper --}}
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next text-white"></div>
            <div class="swiper-button-prev text-white"></div>
        </div>
        <div class="text-center my-12">
            <a href="{{ route('user.menu') }}"
                class=" text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-orange-100 transition duration-300 ease-in-out text-lg shadow-md">
                Lihat Semua Menu
            </a>
        </div>


        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Kualitas yang Bisa Anda Rasakan</h2>
                <p class="text-center text-gray-600 mb-12">Kami berkomitmen untuk memberikan yang terbaik di setiap
                    gigitan.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
                    <div class="p-4">
                        <div
                            class="flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mx-auto mb-4">
                            <svg class="w-8 h-8 text-[#F26725]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-12v4m-2-2h4m5 4v4m-2-2h4M5 3a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2H5z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-gray-800">Fresh Setiap Hari</h3>
                        <p class="text-gray-600">Semua kue dan roti kami dibuat pada hari yang sama untuk menjamin
                            kesegaran dan rasa terbaik saat Anda nikmati.</p>
                    </div>
                    <div class="p-4">
                        <div
                            class="flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mx-auto mb-4">
                            <svg class="w-8 h-8 text-[#F26725]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6a2 2 0 100-4 2 2 0 000 4zm0 12a2 2 0 100-4 2 2 0 000 4z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-gray-800">Bahan Baku Premium</h3>
                        <p class="text-gray-600">Kami hanya menggunakan bahan-bahan pilihan berkualitas tinggi, tanpa
                            bahan pengawet buatan.</p>
                    </div>
                    <div class="p-4">
                        <div
                            class="flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mx-auto mb-4">
                            <svg class="w-8 h-8 text-[#F26725]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-gray-800">Resep Keluarga Asli</h3>
                        <p class="text-gray-600">Setiap produk dibuat dari resep warisan keluarga yang telah teruji
                            kelezatannya dari generasi ke generasi.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-orange-50">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Choose Your Moment</h2>
                <p class="text-center text-gray-600 mb-12">Temukan produk kami yang sempurna untuk setiap momen</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="{{ route('user.menu') }}?category=coffee-break" class="group">
                        <div
                            class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 group-hover:scale-105">
                            <div class="h-48 relative">
                                <img src="{{ asset('coffee.jpg') }}" alt="Coffee Break Background"
                                    class="absolute inset-0 w-full h-full object-cover filter blur-sm opacity-90">
                                <div
                                    class="absolute inset-0 bg-orange-500 bg-opacity-20 flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <span class="text-3xl block mb-2">☕</span>
                                        <h3 class="text-xl font-bold text-white mb-1 drop-shadow-lg">Coffee Break</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('user.menu') }}?category=gathering" class="group">
                        <div
                            class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 group-hover:scale-105">
                            <div class="h-48 relative">
                                <img src="{{ asset('gathering.jpg') }}" alt="Gathering Background"
                                    class="absolute inset-0 w-full h-full object-cover filter blur-sm opacity-90">
                                <div
                                    class="absolute inset-0 bg-orange-500 bg-opacity-20 flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <span class="text-3xl block mb-2">🍰</span>
                                        <h3 class="text-xl font-bold text-white mb-1 drop-shadow-lg">Gathering</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('user.menu') }}?category=snack" class="group">
                        <div
                            class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 group-hover:scale-105">
                            <div class="h-48 relative">
                                <img src="{{ asset('snack.jpg') }}" alt="Snack Background"
                                    class="absolute inset-0 w-full h-full object-cover filter blur-sm opacity-90">
                                <div
                                    class="absolute inset-0 bg-orange-500 bg-opacity-20 flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <span class="text-3xl block mb-2">🥐</span>
                                        <h3 class="text-xl font-bold text-white mb-1 drop-shadow-lg">Snack</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('user.menu') }}?category=dessert" class="group">
                        <div
                            class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 group-hover:scale-105">
                            <div class="h-48 relative">
                                <img src="{{ asset('dough.png') }}" alt="Dessert Background"
                                    class="absolute inset-0 w-full h-full object-cover filter blur-sm opacity-90">
                                <div
                                    class="absolute inset-0 bg-orange-500 bg-opacity-20 flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <span class="text-3xl block mb-2">🍮</span>
                                        <h3 class="text-xl font-bold text-white mb-1 drop-shadow-lg">Dessert</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Apa Kata Pelanggan Kami</h2>
                <p class="text-center text-gray-600 mb-12">Pengalaman pelanggan yang merasakan produk kami</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-orange-50 p-6 rounded-lg shadow-md">
                        <div class="flex items-center mb-4">
                            <div class="h-12 w-12 rounded-full bg-orange-200 flex items-center justify-center mr-4">
                                <span class="text-orange-600 font-bold text-xl">A</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Andi Pratama</h3>
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"Roti dan kue dari Toko Roti Dinar selalu menjadi favorit
                            keluarga kami. Rasa yang konsisten dan kualitas yang terjaga membuat kami selalu kembali
                            untuk membeli lagi."</p>
                    </div>

                    <div class="bg-orange-50 p-6 rounded-lg shadow-md">
                        <div class="flex items-center mb-4">
                            <div class="h-12 w-12 rounded-full bg-orange-200 flex items-center justify-center mr-4">
                                <span class="text-orange-600 font-bold text-xl">S</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Sinta Dewi</h3>
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"Saya sering memesan kue untuk acara kantor, dan respons dari
                            rekan-rekan selalu positif. Pelayanannya juga sangat ramah dan profesional."</p>
                    </div>

                    <div class="bg-orange-50 p-6 rounded-lg shadow-md">
                        <div class="flex items-center mb-4">
                            <div class="h-12 w-12 rounded-full bg-orange-200 flex items-center justify-center mr-4">
                                <span class="text-orange-600 font-bold text-xl">B</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Budi Santoso</h3>
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"Cake Tape di sini adalah yang terbaik di kota! Lembut di
                            dalam, renyah di luar. Sempurna untuk memulai hari dengan secangkir kopi."</p>
                    </div>
                </div>

                {{-- <div class="text-center mt-12">
                    <a href="{{ route('user.contact') }}"
                        class="inline-block bg-[#F26725] text-white font-semibold py-3 px-8 rounded-lg hover:bg-orange-600 transition duration-300 ease-in-out shadow-md">
                        Berikan Review Anda
                    </a>
                </div> --}}
            </div>
        </section>

    </main>

    <div id="alert-success"
        class="hidden fixed top-20 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
        Produk berhasil ditambahkan ke keranjang!
    </div>

    <footer class="bg-orange-500 text-white text-center p-4 w-full">
        <p>&copy; 2025 Toko Roti Dinar. All Rights Reserved.</p>
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
                            window.location.href = "{{ route('login') }}";
                        } else {
                            alert('Terjadi kesalahan, coba lagi!');
                        }
                    }
                });
            });
        });

        var swiper = new Swiper('.swiper-container', {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                bulletClass: 'swiper-pagination-bullet',
                bulletActiveClass: 'swiper-pagination-bullet-active',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            speed: 800,
            breakpoints: {
                640: {
                    effect: 'slide',
                },
                768: {
                    effect: 'fade',
                }
            }
        });
    </script>
</body>

</html>
