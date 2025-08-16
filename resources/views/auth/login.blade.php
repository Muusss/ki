<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin | ReGlow Beauty - Smart Sunscreen Recommendation</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo-ss.png') }}" />
    
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
    </style>
</head>
<body class="font-['Poppins'] overflow-x-hidden">
    <!-- Background with gradient and pattern -->
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-pink-100 via-pink-50 to-white"></div>
        
        <!-- Sunscreen Products Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4 p-4 rotate-12 scale-110">
                @for ($i = 0; $i < 48; $i++)
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?w=200" 
                         alt="" 
                         class="w-full h-32 object-cover rounded-lg shadow-lg"
                         style="animation-delay: {{ $i * 0.1 }}s">
                </div>
                @endfor
            </div>
        </div>
        
        <!-- Overlay gradient for better readability -->
        <div class="absolute inset-0 bg-gradient-to-b from-white/70 via-pink-50/80 to-white/70"></div>
        
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
                    <img src="{{ asset('img/logo-ss.png') }}" alt="ReGlow" class="w-12 h-12 rounded-xl shadow-lg">
                    <div>
                        <h1 class="text-2xl font-bold gradient-text font-['Dancing_Script']">ReGlow Beauty</h1>
                        <p class="text-xs text-pink-600">Smart Sunscreen Recommendation</p>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6" data-aos="fade-left">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium">Beranda</a>
                    <a href="{{ route('public.jenis-kulit') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium">Jenis Kulit</a>
                    <a href="{{ route('public.permintaan') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium">Permintaan</a>
                    <a href="{{ route('public.hasil-spk') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium">Hasil SPK</a>
                    <div class="bg-gradient-to-r from-pink-500 to-pink-400 text-white px-6 py-2 rounded-full font-medium">
                        Login Admin
                    </div>
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
                <a href="{{ route('public.jenis-kulit') }}" class="block py-2 text-gray-700 hover:text-pink-500">Jenis Kulit</a>
                <a href="{{ route('public.permintaan') }}" class="block py-2 text-gray-700 hover:text-pink-500">Permintaan</a>
                <a href="{{ route('public.hasil-spk') }}" class="block py-2 text-gray-700 hover:text-pink-500">Hasil SPK</a>
                <div class="block py-2 text-pink-500 font-medium">Login Admin</div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen flex items-center justify-center px-6 py-12 pt-24">
        <div class="container mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center max-w-6xl mx-auto">
                <!-- Left Side - Product Showcase -->
                <div class="hidden lg:block" data-aos="fade-right">
                    <div class="relative">
                        <!-- Main Featured Product -->
                        <div class="relative z-10">
                            <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?w=500" 
                                 alt="Sunscreen Products" 
                                 class="w-full max-w-md mx-auto rounded-3xl shadow-2xl float-animation">
                            <div class="absolute -bottom-4 -right-4 bg-gradient-to-r from-pink-500 to-pink-400 text-white px-4 py-2 rounded-full font-semibold shadow-lg">
                                50+ Produk Tersedia
                            </div>
                        </div>
                        
                        <!-- Floating Product Cards -->
                        <div class="absolute top-0 -right-10 w-32 h-32 float-animation" style="animation-delay: 0.5s;">
                            <div class="glass rounded-2xl p-3 shadow-lg">
                                <img src="https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=100" 
                                     alt="Sunscreen" 
                                     class="w-full h-20 object-cover rounded-lg mb-2">
                                <p class="text-xs font-semibold text-gray-700">SPF 50+ PA++++</p>
                            </div>
                        </div>
                        
                        <div class="absolute bottom-0 -left-10 w-32 h-32 float-animation" style="animation-delay: 1s;">
                            <div class="glass rounded-2xl p-3 shadow-lg">
                                <img src="https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=100" 
                                     alt="Sunscreen" 
                                     class="w-full h-20 object-cover rounded-lg mb-2">
                                <p class="text-xs font-semibold text-gray-700">Hydrating Formula</p>
                            </div>
                        </div>
                        
                        <div class="absolute top-1/2 -left-16 w-32 h-32 float-animation" style="animation-delay: 1.5s;">
                            <div class="glass rounded-2xl p-3 shadow-lg">
                                <img src="https://images.unsplash.com/photo-1556228852-80b6bb58b699?w=100" 
                                     alt="Sunscreen" 
                                     class="w-full h-20 object-cover rounded-lg mb-2">
                                <p class="text-xs font-semibold text-gray-700">Natural & Organic</p>
                            </div>
                        </div>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2">
                            <span class="text-6xl opacity-20 float-animation">☀️</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="w-full max-w-md mx-auto">
                    <!-- Login Card -->
                    <div class="glass rounded-3xl shadow-2xl p-8 relative gradient-border" data-aos="zoom-in">
                        <!-- Logo and Title -->
                        <div class="text-center mb-8">
                            <div class="inline-block mb-4 pulse-animation">
                                <img src="{{ asset('img/logo-ss.png') }}" alt="ReGlow Beauty" class="w-24 h-24 rounded-2xl shadow-lg mx-auto">
                            </div>
                            <h1 class="text-3xl font-bold gradient-text font-['Dancing_Script'] mb-2">ReGlow Beauty</h1>
                            <p class="text-gray-600">Smart Sunscreen Recommendation</p>
                            <div class="inline-block mt-4">
                                <span class="bg-pink-100 text-pink-600 px-4 py-2 rounded-full text-sm font-semibold">
                                    ✨ Admin Login
                                </span>
                            </div>
                        </div>

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl p-4 mb-6" data-aos="shake">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold">Terjadi kesalahan:</span>
                                </div>
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false, loading: false }" @submit="loading = true">
                            @csrf
                            
                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Email
                                </label>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autofocus
                                    class="w-full px-4 py-3 rounded-2xl border-2 border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all duration-300 hover:border-pink-300"
                                    placeholder="admin@reglow.com"
                                >
                            </div>

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="block text-gray-700 font-medium mb-2 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Kata Sandi
                                </label>
                                <div class="relative">
                                    <input 
                                        id="password" 
                                        :type="showPassword ? 'text' : 'password'"
                                        name="password" 
                                        required
                                        class="w-full px-4 py-3 pr-12 rounded-2xl border-2 border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all duration-300 hover:border-pink-300"
                                        placeholder="••••••••"
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
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember" class="w-4 h-4 text-pink-500 border-pink-300 rounded focus:ring-pink-200">
                                    <span class="ml-2 text-gray-600 text-sm">Ingat saya</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-pink-500 hover:text-pink-600 transition-colors">
                                        Lupa kata sandi?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="w-full bg-gradient-to-r from-pink-500 to-pink-400 text-white py-3 rounded-2xl hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-semibold flex items-center justify-center gap-2"
                                :disabled="loading"
                            >
                                <span x-show="!loading" class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Masuk
                                </span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <div class="w-5 h-5 loading-spinner"></div>
                                    Memproses...
                                </span>
                            </button>
                        </form>

                        <!-- Features Grid -->
                        <div class="grid grid-cols-3 gap-4 mt-8 pt-8 border-t border-pink-100">
                            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                                <div class="bg-gradient-to-br from-pink-100 to-pink-50 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-600 font-medium">Aman</p>
                            </div>
                            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                                <div class="bg-gradient-to-br from-purple-100 to-purple-50 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-600 font-medium">Cepat</p>
                            </div>
                            <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                                <div class="bg-gradient-to-br from-yellow-100 to-yellow-50 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-600 font-medium">Akurat</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-8 text-gray-600 text-sm" data-aos="fade-up">
                        <p>© {{ date('Y') }} ReGlow Beauty. Made with <span class="text-pink-500">❤</span> by Selvya</p>
                        <p class="mt-2">Powered by Laravel {{ Illuminate\Foundation\Application::VERSION }}</p>
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