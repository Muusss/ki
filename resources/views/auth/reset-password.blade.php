<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Kata Sandi | Buri Umah - Smart Sunscreen Recommendation</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('img/buri-umah.jpeg') }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js for interactions -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes blob {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        
        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 1; transform: scale(1); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .float-animation { animation: float 6s ease-in-out infinite; }
        .blob-animation { animation: blob 7s infinite; }
        .sparkle { animation: sparkle 2s ease-in-out infinite; }
        .pulse-animation { animation: pulse 2s ease-in-out infinite; }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #ff6b9d, #ffc0cb, #ff86a8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Glass morphism */
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Gradient border animation */
        .gradient-border::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b9d, #ffc0cb, #ff86a8);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
            border-radius: 24px 24px 0 0;
        }
        
        /* Loading spinner */
        @keyframes spinner {
            to { transform: rotate(360deg); }
        }
        
        .loading-spinner {
            border: 2px solid #fff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.6s linear infinite;
        }
        
        /* Password strength indicator */
        .strength-meter {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background: linear-gradient(90deg, #ff6b6b 0%, #ff6b6b 33%, #e0e0e0 33%); }
        .strength-medium { background: linear-gradient(90deg, #ffd93d 0%, #ffd93d 66%, #e0e0e0 66%); }
        .strength-strong { background: linear-gradient(90deg, #6bcf7f 0%, #6bcf7f 100%); }
    </style>
</head>
<body class="font-['Poppins'] overflow-x-hidden">
    <!-- Background with gradient and pattern -->
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-pink-100 via-pink-50 to-white"></div>
        
        <!-- Animated blobs -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl blob-animation"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl blob-animation animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl blob-animation animation-delay-4000"></div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-3" data-aos="fade-right">
                    <img src="{{ asset('img/buri-umah.jpeg') }}" alt="Buri Umah" class="w-12 h-12 rounded-xl shadow-lg">
                    <div>
                        <h1 class="text-2xl font-bold gradient-text font-['Dancing_Script']">Buri Umah</h1>
                        <p class="text-xs text-pink-600">Smart Sunscreen Recommendation</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center px-6 py-12 pt-24">
        <div class="container mx-auto max-w-lg">
            <!-- Reset Password Card -->
            <div class="glass rounded-3xl shadow-2xl p-8 relative gradient-border" data-aos="zoom-in">
                <!-- Logo and Title -->
                <div class="text-center mb-8">
                    <div class="inline-block mb-4">
                        <div class="relative">
                            <img src="{{ asset('img/buri-umah.jpeg') }}" alt="Buri Umah" class="w-20 h-20 rounded-2xl shadow-lg mx-auto">
                            <!-- Lock badge -->
                            <div class="absolute -bottom-2 -right-2 bg-gradient-to-r from-pink-500 to-pink-400 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Reset Kata Sandi</h1>
                    <p class="text-gray-600 text-sm">Buat kata sandi baru yang kuat dan aman untuk akun Anda</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl p-4 mb-6" data-aos="shake">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-semibold">Terjadi kesalahan:</p>
                                <ul class="list-disc list-inside text-sm mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Reset Password Form -->
                <form method="POST" action="{{ route('password.store') }}" 
                      x-data="{ 
                          showPassword: false, 
                          showPasswordConfirm: false,
                          loading: false,
                          password: '',
                          passwordStrength: 0,
                          strengthText: '',
                          strengthClass: ''
                      }" 
                      @submit="loading = true">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Field -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            Alamat Email
                        </label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $request->email) }}" 
                            required 
                            autofocus
                            readonly
                            class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 bg-gray-50 focus:outline-none cursor-not-allowed"
                        >
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Kata Sandi Baru
                        </label>
                        <div class="relative">
                            <input 
                                id="password" 
                                :type="showPassword ? 'text' : 'password'"
                                name="password" 
                                required
                                x-model="password"
                                @input="
                                    let strength = 0;
                                    if (password.length >= 8) strength++;
                                    if (/[A-Z]/.test(password)) strength++;
                                    if (/[0-9]/.test(password)) strength++;
                                    if (/[^A-Za-z0-9]/.test(password)) strength++;
                                    
                                    passwordStrength = strength;
                                    if (strength <= 1) {
                                        strengthText = 'Lemah';
                                        strengthClass = 'strength-weak';
                                    } else if (strength <= 3) {
                                        strengthText = 'Sedang';
                                        strengthClass = 'strength-medium';
                                    } else {
                                        strengthText = 'Kuat';
                                        strengthClass = 'strength-strong';
                                    }
                                "
                                class="w-full px-4 py-3 pr-12 rounded-2xl border-2 border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all duration-300"
                                placeholder="Minimal 8 karakter"
                            >
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-pink-500 transition-colors"
                            >
                                <svg x-show="!showPassword" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showPassword" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div x-show="password.length > 0" class="mt-2">
                            <div class="strength-meter" :class="strengthClass"></div>
                            <p class="text-xs mt-1" :class="{
                                'text-red-500': passwordStrength <= 1,
                                'text-yellow-500': passwordStrength > 1 && passwordStrength <= 3,
                                'text-green-500': passwordStrength > 3
                            }">
                                Kekuatan: <span x-text="strengthText"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Konfirmasi Kata Sandi
                        </label>
                        <div class="relative">
                            <input 
                                id="password_confirmation" 
                                :type="showPasswordConfirm ? 'text' : 'password'"
                                name="password_confirmation" 
                                required
                                class="w-full px-4 py-3 pr-12 rounded-2xl border-2 border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all duration-300"
                                placeholder="Masukkan ulang kata sandi"
                            >
                            <button 
                                type="button"
                                @click="showPasswordConfirm = !showPasswordConfirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-pink-500 transition-colors"
                            >
                                <svg x-show="!showPasswordConfirm" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showPasswordConfirm" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-pink-50 rounded-2xl p-4 mb-6">
                        <h4 class="font-semibold text-gray-800 mb-2 text-sm">Persyaratan Kata Sandi:</h4>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Minimal 8 karakter
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Mengandung huruf besar dan kecil
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Mengandung angka
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Disarankan menggunakan karakter khusus (!@#$%^&*)
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-pink-500 to-pink-400 text-white py-3 rounded-2xl hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-semibold flex items-center justify-center gap-2"
                        :disabled="loading"
                    >
                        <span x-show="!loading" class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Reset Kata Sandi
                        </span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <div class="w-5 h-5 loading-spinner"></div>
                            Memproses...
                        </span>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-gray-600 text-sm" data-aos="fade-up">
                <p>© {{ date('Y') }} Buri Umah. Made with <span class="text-pink-500">❤</span> by Selvya</p>
            </div>
        </div>
    </main>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>
