<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Toko Roti Dinar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23374151' fill-opacity='0.1'%3E%3Cpath d='M10 10h40v40H10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
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

<body class="bg-gradient-to-br from-gray-100 via-gray-50 to-blue-50 min-h-screen bg-pattern">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-gray-700 rounded-full flex items-center justify-center mb-6 floating-animation shadow-lg">
                    <i class="fas fa-user-shield text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                    Admin Dashboard
                </h2>
                <p class="text-gray-600">Masuk ke panel administrasi</p>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500">Login sebagai Customer? </span>
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                        Klik di sini
                    </a>
                </div>
            </div>

            <!-- Alerts -->
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border-2 border-gray-200">
                <div class="px-8 py-10">
                    <div class="text-center mb-6">
                        <div class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold inline-block mb-2">
                            <i class="fas fa-lock mr-1"></i>
                            AREA TERBATAS
                        </div>
                        <p class="text-sm text-gray-600">
                            Hanya untuk administrator yang berwenang
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-gray-500 mr-2"></i>Email Admin
                            </label>
                            <input id="email" name="email" type="email" required autofocus autocomplete="username"
                                class="appearance-none relative block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:z-10 transition duration-200"
                                placeholder="admin@rotidinar.com" value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock text-gray-500 mr-2"></i>Password Admin
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required autocomplete="current-password"
                                    class="appearance-none relative block w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 focus:z-10 transition duration-200"
                                    placeholder="Masukkan password admin">
                                <button type="button" onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-500" id="password-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                Ingat sesi admin ini
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transform hover:scale-105 transition duration-200 shadow-lg">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-sign-in-alt text-gray-300 group-hover:text-gray-200"></i>
                                </span>
                                Masuk sebagai Admin
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Card -->
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                            Area Admin
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-cog text-gray-500 mr-2"></i>
                            Kelola Sistem
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-chart-bar text-green-500 mr-2"></i>
                            Dashboard
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 mt-0.5"></i>
                    <div class="text-sm text-yellow-700">
                        <strong>Penting:</strong> Pastikan Anda memiliki akses administrator sebelum mencoba login. 
                        Semua aktivitas login admin akan dicatat untuk keamanan.
                    </div>
                </div>
            </div>
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

        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = `
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-spinner fa-spin text-gray-300"></i>
                </span>
                Memverifikasi akses...
            `;
            submitBtn.disabled = true;
            
            // Re-enable if validation fails
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