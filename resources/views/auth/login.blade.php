<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Toko Roti Dinar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f97316' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-orange-100 via-orange-50 to-yellow-50 min-h-screen bg-pattern">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-orange-500 rounded-full flex items-center justify-center mb-6 floating-animation shadow-lg">
                    <i class="fas fa-birthday-cake text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                    Selamat Datang Kembali
                </h2>
                <p class="text-gray-600">Masuk ke akun Anda untuk menikmati roti segar</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
                <div class="px-8 py-10">
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-orange-500 mr-2"></i>Email
                            </label>
                            <input id="email" name="email" type="email" required autofocus autocomplete="username"
                                class="appearance-none relative block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:z-10 transition duration-200"
                                placeholder="nama@email.com" value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock text-orange-500 mr-2"></i>Password
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required autocomplete="current-password"
                                    class="appearance-none relative block w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 focus:z-10 transition duration-200"
                                    placeholder="Masukkan password Anda">
                                <button type="button" onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-orange-500" id="password-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-sm text-orange-600 hover:text-orange-500 font-medium transition duration-200">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="space-y-4">
                            <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transform hover:scale-105 transition duration-200 shadow-lg">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-sign-in-alt text-orange-300 group-hover:text-orange-200"></i>
                                </span>
                                Masuk
                            </button>

                            <!-- Register Link -->
                            @if (Route::has('register'))
                                <div class="text-center">
                                    <span class="text-gray-600">Belum punya akun? </span>
                                    <a href="{{ route('register') }}" 
                                        class="font-medium text-orange-600 hover:text-orange-500 transition duration-200">
                                        Daftar di sini
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">atau</span>
                            </div>
                        </div>

                        <!-- Guest Login -->
                        <div>
                            <a href="{{ route('user.products') }}"
                                class="group relative w-full flex justify-center py-3 px-4 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-user text-gray-400 group-hover:text-gray-500"></i>
                                </span>
                                Lihat Menu Tanpa Login
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Footer Card -->
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                        
                        <div class="flex items-center">
                            Toko Roti Dinar &copy; {{ date('Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Info -->
            <!-- <div class="text-center">
                <div class="bg-white/80 backdrop-blur-sm rounded-lg p-4 shadow-lg">
                    <p class="text-sm text-gray-600 mb-2"><strong>Demo Login:</strong></p>
                    <div class="text-xs text-gray-500 space-y-1">
                        <p>Admin: admin@example.com / password</p>
                        <p>Customer: customer@example.com / password</p>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

    <script>
        function togglePassword() {
            const field = document.getElementById('password');
            const icon = document.getElementById('password-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Auto-focus email on load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });

        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = `
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-spinner fa-spin text-orange-300"></i>
                </span>
                Sedang masuk...
            `;
            submitBtn.disabled = true;
            
            // Re-enable if form validation fails
            setTimeout(() => {
                if (!this.checkValidity()) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }, 100);
        });
    </script>
</body>

</html>