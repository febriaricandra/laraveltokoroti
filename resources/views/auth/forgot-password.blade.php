<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Toko Roti Dinar</title>
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
                    Lupa Password?
                </h2>
                <p class="text-gray-600">Jangan khawatir, kami akan bantu Anda</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
                <div class="px-8 py-10">
                    <!-- Description -->
                    <div class="mb-6 text-center">
                        <div class="mx-auto h-16 w-16 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-key text-orange-500 text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-600">
                            Masukkan email Anda dan kami akan mengirimkan link untuk reset password ke email Anda.
                        </p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                Link reset password telah dikirim ke email Anda!
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-orange-500 mr-2"></i>Email
                            </label>
                            <input id="email" name="email" type="email" required autofocus
                                class="appearance-none relative block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200"
                                placeholder="nama@email.com" value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transform hover:scale-105 transition duration-200 shadow-lg">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-paper-plane text-orange-300 group-hover:text-orange-200"></i>
                                </span>
                                Kirim Link Reset Password
                            </button>
                        </div>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" 
                                class="inline-flex items-center text-sm text-orange-600 hover:text-orange-500 font-medium transition duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Login
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Footer Card -->
                <div class="px-8 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="text-center">
                        <p class="text-xs text-gray-500">
                            Tidak menerima email? Periksa folder spam atau 
                            <button onclick="resendEmail()" class="text-orange-600 hover:text-orange-500 font-medium">
                                kirim ulang
                            </button>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="text-center">
                <div class="bg-white/80 backdrop-blur-sm rounded-lg p-4 shadow-lg">
                    <p class="text-sm text-gray-600 mb-2"><strong>Butuh bantuan?</strong></p>
                    <div class="text-xs text-gray-500">
                        <p>Hubungi customer service: <a href="tel:+62211234567" class="text-orange-600">+62 21 1234 567</a></p>
                        <p>Email: <a href="mailto:help@rotidinar.com" class="text-orange-600">help@rotidinar.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resendEmail() {
            // Logic untuk kirim ulang email
            alert('Fitur kirim ulang akan segera tersedia');
        }

        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = `
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <i class="fas fa-spinner fa-spin text-orange-300"></i>
                </span>
                Mengirim email...
            `;
            submitBtn.disabled = true;
        });
    </script>
</body>

</html>