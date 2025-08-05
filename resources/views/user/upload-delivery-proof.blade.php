<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Penerimaan - Order #{{ $order->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-orange-50 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-[#F26725] fixed top-0 left-0 w-full p-4 text-white shadow-lg z-50">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="{{ route('user.products') }}" class="flex items-center space-x-3">
                <div class="h-10 w-10 flex items-center justify-center overflow-hidden">
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
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div id="userDropdown"
                            class="hidden absolute top-full right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-gray-800 hover:bg-gray-100 first:rounded-t-lg">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 last:rounded-b-lg">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 bg-white text-orange-700 rounded-lg hover:bg-gray-100">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-4 pt-24 max-w-2xl">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center mb-6">
                <i class="fas fa-upload text-2xl text-green-600 mr-3"></i>
                <h1 class="text-2xl font-bold text-gray-800">Upload Bukti Penerimaan</h1>
            </div>

            <!-- Order Info Summary -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Order
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                    <p><span class="font-medium">Order ID:</span> #{{ $order->id }}</p>
                    <p><span class="font-medium">Status:</span> 
                        <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p><span class="font-medium">Total:</span> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <p><span class="font-medium">Tanggal Order:</span> {{ $order->created_at->format('d M Y') }}</p>
                </div>
                @if($order->tracking_number)
                    <p class="mt-2"><span class="font-medium">Nomor Resi:</span> 
                        <span class="font-mono bg-white px-2 py-1 rounded text-sm">{{ $order->tracking_number }}</span>
                    </p>
                @endif
            </div>

            <!-- Upload Form -->
            <form action="{{ route('user.order.upload-delivery-proof.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-800 mb-2">
                        <i class="fas fa-lightbulb mr-2"></i>Petunjuk Upload Bukti
                    </h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Upload foto/gambar yang menunjukkan bahwa barang sudah diterima</li>
                        <li>• Contoh: foto produk yang diterima, foto kemasan, atau foto dengan penerima</li>
                        <li>• Format file yang diperbolehkan: JPG, PNG, JPEG</li>
                        <li>• Ukuran maksimal: 2MB</li>
                    </ul>
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label for="delivery_proof" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-camera mr-1"></i>Pilih Foto Bukti Penerimaan <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                        <input type="file" id="delivery_proof" name="delivery_proof" accept="image/*" required
                            class="hidden" onchange="previewImage(this)">
                        <label for="delivery_proof" class="cursor-pointer">
                            <div id="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600">Klik untuk memilih foto</p>
                                <p class="text-sm text-gray-500">atau drag & drop foto di sini</p>
                            </div>
                            <div id="image-preview" class="hidden">
                                <img id="preview-img" class="max-w-full max-h-64 mx-auto rounded-lg shadow-md">
                                <p class="mt-2 text-sm text-gray-600">Klik untuk mengganti foto</p>
                            </div>
                        </label>
                    </div>
                    @error('delivery_proof')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Optional Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-1"></i>Catatan Tambahan (Opsional)
                    </label>
                    <textarea id="notes" name="notes" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('user.order.details', $order->id) }}"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white text-center px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition-colors font-semibold">
                        <i class="fas fa-upload mr-2"></i>Upload Bukti Penerimaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-orange-500 text-white text-center p-4 mt-auto">
        <p>&copy; 2025 Toko Roti Dinar. All Rights Reserved.</p>
    </footer>

    <script>
        function previewImage(input) {
            const placeholder = document.getElementById('upload-placeholder');
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    placeholder.classList.add('hidden');
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                placeholder.classList.remove('hidden');
                preview.classList.add('hidden');
            }
        }

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

            // Drag and drop functionality
            const dropZone = document.querySelector('[for="delivery_proof"]').parentElement;
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.classList.add('border-orange-400', 'bg-orange-50');
            }

            function unhighlight(e) {
                dropZone.classList.remove('border-orange-400', 'bg-orange-50');
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                const input = document.getElementById('delivery_proof');
                
                input.files = files;
                previewImage(input);
            }
        });
    </script>
</body>

</html>
