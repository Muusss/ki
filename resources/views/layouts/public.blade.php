<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ReGlow Beauty') - Smart Sunscreen Recommendation</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo-ss.png') }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <style>
        /* Reuse styles from welcome page */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes blob {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        
        .float-animation { animation: float 6s ease-in-out infinite; }
        .blob-animation { animation: blob 7s infinite; }
        
        .gradient-text {
            background: linear-gradient(135deg, #ff6b9d, #ffc0cb, #ff86a8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
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
    </style>
    
    @stack('styles')
</head>
<body class="font-['Poppins'] overflow-x-hidden">
    <!-- Background -->
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-pink-100 via-pink-50 to-white"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl blob-animation"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl blob-animation animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl blob-animation animation-delay-4000"></div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass shadow-lg" x-data="{ mobileMenu: false }">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ url('/') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('img/logo-ss.png') }}" alt="ReGlow" class="w-12 h-12 rounded-xl shadow-lg">
                        <div>
                            <h1 class="text-2xl font-bold gradient-text font-['Dancing_Script']">ReGlow Beauty</h1>
                            <p class="text-xs text-pink-600">Smart Sunscreen Recommendation</p>
                        </div>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium">Beranda</a>
                    <a href="{{ route('public.jenis-kulit') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium {{ request()->routeIs('public.jenis-kulit') ? 'text-pink-500' : '' }}">Jenis Kulit</a>
                    <a href="{{ route('public.permintaan') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium {{ request()->routeIs('public.permintaan*') ? 'text-pink-500' : '' }}">Permintaan</a>
                    <a href="{{ route('public.hasil-spk') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium {{ request()->routeIs('public.hasil-spk') ? 'text-pink-500' : '' }}">Hasil SPK</a>
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-pink-500 to-pink-400 text-white px-6 py-2 rounded-full hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-medium">
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
                <a href="{{ route('public.jenis-kulit') }}" class="block py-2 text-gray-700 hover:text-pink-500">Jenis Kulit</a>
                <a href="{{ route('public.permintaan') }}" class="block py-2 text-gray-700 hover:text-pink-500">Permintaan</a>
                <a href="{{ route('public.hasil-spk') }}" class="block py-2 text-gray-700 hover:text-pink-500">Hasil SPK</a>
                <a href="{{ route('login') }}" class="block py-2 text-pink-500 font-medium">Login Admin</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-pink-100 to-pink-50 py-12 px-6">
        <div class="container mx-auto text-center">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <img src="{{ asset('img/logo-ss.png') }}" alt="ReGlow" class="w-10 h-10 rounded-xl">
                <h3 class="text-2xl font-bold gradient-text font-['Dancing_Script']">ReGlow Beauty</h3>
            </div>
            <p class="text-gray-600 mb-6">Smart Sunscreen Recommendation System</p>
            <div class="flex justify-center space-x-6 mb-6">
                <a href="{{ route('public.jenis-kulit') }}" class="text-gray-600 hover:text-pink-500 transition-colors">Jenis Kulit</a>
                <a href="{{ route('public.permintaan') }}" class="text-gray-600 hover:text-pink-500 transition-colors">Permintaan</a>
                <a href="{{ route('public.hasil-spk') }}" class="text-gray-600 hover:text-pink-500 transition-colors">Hasil SPK</a>
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-pink-500 transition-colors">Admin</a>
            </div>
            <p class="text-gray-500 text-sm">
                © {{ date('Y') }} ReGlow Beauty. Made with 
                <span class="text-pink-500">❤</span> by Selvya
            </p>
        </div>
    </footer>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>
    
    @stack('scripts')
</body>
</html>