<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi | Buri Umah - Smart Sunscreen Recommendation</title>
    
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
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #ffe0ec;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #ff6b9d, #ffc0cb);
            border-radius: 10px;
        }
        
        /* Particle effects */
        .particle {
            position: absolute;
            pointer-events: none;
            opacity: 0.6;
        }
        
        .particle-1 {
            width: 10px;
            height: 10px;
            background: #ff6b9d;
            border-radius: 50%;
            animation: float 8s infinite;
        }
        
        .particle-2 {
            width: 6px;
            height: 6px;
            background: #ffc0cb;
            border-radius: 50%;
            animation: float 10s infinite reverse;
        }
        
        .particle-3 {
            width: 8px;
            height: 8px;
            background: #ffb6c1;
            border-radius: 50%;
            animation: blob 12s infinite;
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
        
        /* Email sent animation */
        @keyframes emailFly {
            0% { transform: translateX(0) translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateX(200px) translateY(-200px) rotate(45deg); opacity: 0; }
        }
        
        .email-fly {
            animation: emailFly 1s ease-out;
        }
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
        
        <!-- Floating particles -->
        <div class="particle particle-1" style="top: 10%; left: 10%;"></div>
        <div class="particle particle-2" style="top: 20%; left: 80%;"></div>
        <div class="particle particle-3" style="top: 60%; left: 60%;"></div>
        <div class="particle particle-1" style="top: 80%; left: 30%;"></div>
        <div class="particle particle-2" style="top: 50%; left: 90%;"></div>
        <div class="particle particle-3" style="top: 30%; left: 50%;"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass shadow-lg" x-data="{ mobileMenu: false }">
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
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6" data-aos="fade-left">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium">Beranda</a>
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-pink-500 to-pink-400 text-white px-6 py-2 rounded-full font-medium hover:shadow-lg transition-all duration-300">
                        Login Admin
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden text-pink-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div x-show="mobileMenu" x-transition class="md:hidden mt-4 pb-4">
                <a href="{{ url('/') }}" class="block py-2 text-gray-700 hover:text-pink-500">Beranda</a>
                <a href="{{ route('login') }}" class="block py-2 text-pink-500 font-medium">Login Admin</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center px-6 py-12 pt-24">
        <div class="container mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center max-w-6xl mx-auto">
                <!-- Left Side - Illustration -->
                <div class="hidden lg:block" data-aos="fade-right">
                    <div class="relative">
                        <!-- Main Illustration -->
                        <div class="relative z-10">
                            <div class="bg-gradient-to-br from-pink-100 to-pink-50 rounded-3xl p-8 shadow-2xl">
                                <!-- Email Icon Animation -->
                                <div class="text-center mb-6">
                                    <div class="inline-block pulse-animation">
                                        <svg class="w-32 h-32 text-pink-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Info Cards -->
                                <div class="space-y-4">
                                    <div class="flex items-start gap-3">
                                        <div class="bg-pink-500 text-white w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-bold">1</span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Masukkan Email</h3>
                                            <p class="text-sm text-gray-600">Gunakan email yang terdaftar di sistem</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start gap-3">
                                        <div class="bg-pink-500 text-white w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-bold">2</span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Cek Inbox</h3>
                                            <p class="text-sm text-gray-600">Kami akan kirim link reset password</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start gap-3">
                                        <div class="bg-pink-500 text-white w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-bold">3</span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Reset Password</h3>
                                            <p class="text-sm text-gray-600">Buat password baru yang aman</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Security Badge -->
                                <div class="mt-6 text-center">
                                    <div class="inline-flex items-center gap-2 bg-white rounded-full px-4 py-2 shadow-md">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-700">100% Aman & Terenkripsi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2">
                            <span class="text-6xl opacity-20 float-animation">📧</span>
                        </div>
                        
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-pink-200 rounded-full opacity-20 blob-animation"></div>
                        <div class="absolute top-1/2 -left-8 w-16 h-16 bg-purple-200 rounded-full opacity-20 blob-animation" style="animation-delay: 1s;"></div>
                    </div>
                </div>

                <!-- Right Side - Reset Password Form -->
                <div class="w-full max-w-md mx-auto">
                    <!-- Reset Password Card -->
                    <div class="glass rounded-3xl shadow-2xl p-8 relative gradient-border" data-aos="zoom-in">
                        <!-- Back to Login Link -->
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-pink-500 transition-colors mb-6">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="text-sm font-medium">Kembali ke Login</span>
                        </a>
                        
                        <!-- Logo and Title -->
                        <div class="text-center mb-8">
                            <div class="inline-block mb-4">
                                <div class="relative">
                                    <img src="{{ asset('img/buri-umah.jpeg') }}" alt="Buri Umah" class="w-20 h-20 rounded-2xl shadow-lg mx-auto">
                                    <!-- Email badge -->
                                    <div class="absolute -bottom-2 -right-2 bg-pink-500 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-800 mb-2">Lupa Kata Sandi?</h1>
                            <p class="text-gray-600 text-sm">Jangan khawatir! Masukkan email Anda dan kami akan mengirimkan link untuk reset password.</p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 mb-6" data-aos="fade-down">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-semibold">Berhasil!</p>
                                        <p class="text-sm mt-1">{{ session('status') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                        <form method="POST" action="{{ route('password.email') }}" x-data="{ loading: false, emailSent: false }" @submit="loading = true">
                            @csrf
                            
                            <!-- Email Field -->
                            <div class="mb-6">
                                <label for="email" class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                    Alamat Email
                                </label>
                                <div class="relative">
                                    <input 
                                        id="email" 
                                        type="email" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        required 
                                        autofocus
                                        class="w-full px-4 py-3 pl-12 rounded-2xl border-2 border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all duration-300 hover:border-pink-300"
                                        placeholder="admin@Buri Umah.com"
                                    >
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">*Pastikan email yang Anda masukkan terdaftar di sistem</p>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="w-full bg-gradient-to-r from-pink-500 to-pink-400 text-white py-3 rounded-2xl hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-semibold flex items-center justify-center gap-2"
                                :disabled="loading"
                            >
                                <span x-show="!loading" class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Kirim Link Reset Password
                                </span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <div class="w-5 h-5 loading-spinner"></div>
                                    Mengirim...
                                </span>
                            </button>
                        </form>

                        <!-- Additional Info -->
                        <div class="mt-8 pt-8 border-t border-pink-100">
                            <div class="bg-pink-50 rounded-2xl p-4">
                                <h4 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Tips Keamanan
                                </h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li class="flex items-start gap-2">
                                        <span class="text-pink-500 mt-0.5">•</span>
                                        <span>Link reset password akan dikirim ke email Anda</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-pink-500 mt-0.5">•</span>
                                        <span>Link berlaku selama 60 menit</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-pink-500 mt-0.5">•</span>
                                        <span>Periksa folder spam jika email tidak masuk</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-pink-500 mt-0.5">•</span>
                                        <span>Gunakan password yang kuat dan unik</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Alternative Actions -->
                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-600">
                                Sudah ingat password? 
                                <a href="{{ route('login') }}" class="text-pink-500 hover:text-pink-600 font-medium transition-colors">
                                    Login di sini
                                </a>
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-8 text-gray-600 text-sm" data-aos="fade-up">
                        <p>© {{ date('Y') }} Buri Umah. Made with <span class="text-pink-500">❤</span> by Selvya</p>
                    </div>
                </div>
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

        // Add sparkle effects on mouse move
        document.addEventListener('mousemove', (e) => {
            if (Math.random() > 0.98) {
                createSparkle(e.pageX, e.pageY);
            }
        });

        function createSparkle(x, y) {
            const sparkle = document.createElement('div');
            sparkle.style.position = 'absolute';
            sparkle.style.left = x + 'px';
            sparkle.style.top = y + 'px';
            sparkle.style.width = '10px';
            sparkle.style.height = '10px';
            sparkle.style.background = 'radial-gradient(circle, #ff6b9d, transparent)';
            sparkle.style.borderRadius = '50%';
            sparkle.style.pointerEvents = 'none';
            sparkle.style.zIndex = '9999';
            sparkle.className = 'sparkle';
            document.body.appendChild(sparkle);
            
            setTimeout(() => {
                sparkle.remove();
            }, 2000);
        }
    </script>
</body>
</html>
