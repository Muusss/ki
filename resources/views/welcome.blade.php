<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ReGlow Beauty - Smart Sunscreen Recommendation</title>
    
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
        
        .float-animation { animation: float 6s ease-in-out infinite; }
        .blob-animation { animation: blob 7s infinite; }
        .sparkle { animation: sparkle 2s ease-in-out infinite; }
        
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
        
        /* Sunscreen bottle animation */
        .sunscreen-float {
            animation: float 4s ease-in-out infinite;
            animation-delay: calc(var(--i) * 0.5s);
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
    </style>
</head>
<body class="font-['Poppins'] overflow-x-hidden">
    <!-- Background with gradient and pattern -->
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-pink-100 via-pink-50 to-white"></div>
        <div class="absolute inset-0 opacity-10">
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
                    <a href="#home" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium">Beranda</a>
                    <a href="{{ route('public.jenis-kulit') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium">Jenis Kulit</a>
                    <a href="{{ route('public.permintaan') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium">Permintaan</a>
                    <a href="{{ route('public.hasil-spk') }}" class="text-gray-700 hover:text-pink-500 transition-colors duration-300 font-medium">Hasil SPK</a>
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
                <a href="#home" class="block py-2 text-gray-700 hover:text-pink-500">Beranda</a>
                <a href="{{ route('public.jenis-kulit') }}" class="block py-2 text-gray-700 hover:text-pink-500">Jenis Kulit</a>
                <a href="{{ route('public.permintaan') }}" class="block py-2 text-gray-700 hover:text-pink-500">Permintaan</a>
                <a href="{{ route('public.hasil-spk') }}" class="block py-2 text-gray-700 hover:text-pink-500">Hasil SPK</a>
                <a href="{{ route('login') }}" class="block py-2 text-pink-500 font-medium">Login Admin</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center pt-20 px-6">
        <div class="container mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="text-center md:text-left" data-aos="fade-right">
                    <div class="inline-block mb-4">
                        <span class="bg-pink-100 text-pink-600 px-4 py-2 rounded-full text-sm font-semibold sparkle">
                            ✨ Smart Beauty Solution
                        </span>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold mb-6">
                        <span class="gradient-text">Temukan Sunscreen</span>
                        <br>
                        <span class="text-gray-800">Terbaik Untukmu</span>
                    </h1>
                    <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                        Sistem rekomendasi cerdas menggunakan metode ROC & SMART untuk membantu Anda menemukan sunscreen yang sempurna sesuai jenis kulit Anda.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="{{ route('public.hasil-spk') }}" class="bg-gradient-to-r from-pink-500 to-pink-400 text-white px-8 py-4 rounded-full hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold flex items-center justify-center">
                            Lihat Rekomendasi
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="{{ route('public.jenis-kulit') }}" class="bg-white text-pink-500 px-8 py-4 rounded-full border-2 border-pink-200 hover:bg-pink-50 hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-semibold">
                            Kenali Jenis Kulitmu
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mt-12">
                        <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                            <h3 class="text-3xl font-bold text-pink-500">50+</h3>
                            <p class="text-gray-600 text-sm">Produk Sunscreen</p>
                        </div>
                        <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                            <h3 class="text-3xl font-bold text-pink-500">4</h3>
                            <p class="text-gray-600 text-sm">Jenis Kulit</p>
                        </div>
                        <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                            <h3 class="text-3xl font-bold text-pink-500">7</h3>
                            <p class="text-gray-600 text-sm">Kriteria Penilaian</p>
                        </div>
                    </div>
                </div>
                
                <!-- Image/Animation Content -->
                <div class="relative" data-aos="fade-left">
                    <!-- Main sunscreen bottle -->
                    <div class="relative z-10">
                        <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?w=500" alt="Sunscreen" class="w-full max-w-md mx-auto rounded-3xl shadow-2xl float-animation">
                    </div>
                    
                    <!-- Floating sunscreen products -->
                    <div class="absolute top-0 right-0 w-24 h-24 sunscreen-float" style="--i: 1;">
                        <div class="bg-gradient-to-br from-yellow-200 to-yellow-100 rounded-2xl p-3 shadow-lg">
                            <span class="text-2xl">☀️</span>
                            <p class="text-xs font-semibold text-gray-700">SPF 50</p>
                        </div>
                    </div>
                    
                    <div class="absolute bottom-0 left-0 w-24 h-24 sunscreen-float" style="--i: 2;">
                        <div class="bg-gradient-to-br from-blue-200 to-blue-100 rounded-2xl p-3 shadow-lg">
                            <span class="text-2xl">💧</span>
                            <p class="text-xs font-semibold text-gray-700">Hydrating</p>
                        </div>
                    </div>
                    
                    <div class="absolute top-1/2 -left-8 w-24 h-24 sunscreen-float" style="--i: 3;">
                        <div class="bg-gradient-to-br from-green-200 to-green-100 rounded-2xl p-3 shadow-lg">
                            <span class="text-2xl">🌿</span>
                            <p class="text-xs font-semibold text-gray-700">Natural</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-6">
        <div class="container mx-auto">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4">
                    <span class="gradient-text">Fitur Unggulan</span>
                </h2>
                <p class="text-gray-600 text-lg">Temukan berbagai fitur yang memudahkan Anda</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass rounded-3xl p-8 hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gradient-to-br from-pink-400 to-pink-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3">Analisis Jenis Kulit</h3>
                    <p class="text-gray-600 text-center">Identifikasi jenis kulit Anda dengan panduan lengkap dari para ahli</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="glass rounded-3xl p-8 hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gradient-to-br from-purple-400 to-purple-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3">Metode ROC & SMART</h3>
                    <p class="text-gray-600 text-center">Algoritma cerdas untuk rekomendasi sunscreen yang akurat</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="glass rounded-3xl p-8 hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3">Hasil Real-time</h3>
                    <p class="text-gray-600 text-center">Dapatkan rekomendasi terkini dengan data yang selalu diperbarui</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-6">
        <div class="container mx-auto">
            <div class="glass rounded-3xl p-12 text-center" data-aos="zoom-in">
                <h2 class="text-4xl font-bold mb-6">
                    <span class="gradient-text">Siap Menemukan Sunscreen Impianmu?</span>
                </h2>
                <p class="text-gray-600 text-lg mb-8 max-w-2xl mx-auto">
                    Jangan biarkan kulit Anda terpapar sinar UV berbahaya. Temukan perlindungan terbaik dengan sistem rekomendasi kami.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('public.hasil-spk') }}" class="bg-gradient-to-r from-pink-500 to-pink-400 text-white px-8 py-4 rounded-full hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold">
                        Mulai Sekarang
                    </a>
                    <a href="{{ route('public.permintaan') }}" class="bg-white text-pink-500 px-8 py-4 rounded-full border-2 border-pink-200 hover:bg-pink-50 hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-semibold">
                        Ajukan Permintaan
                    </a>
                </div>
            </div>
        </div>
    </section>

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

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
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