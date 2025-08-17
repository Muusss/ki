<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buri Umah - Cafe & Restaurant</title>
    
    <link rel="icon" type="image/jpeg" href="{{ asset('img/buri-umah.jpeg') }}" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero-gradient {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 50%, #DEB887 100%);
        }
    </style>
</head>
<body class="bg-amber-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <img src="{{ asset('img/buri-umah.jpeg') }}" alt="Buri Umah" class="h-10 w-10 rounded-lg mr-3">
                    <span class="text-2xl font-bold text-amber-800">Buri Umah</span>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('login') }}" class="px-4 py-2 text-amber-800 hover:text-amber-600 transition">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a href="{{ route('alternatifs.index') }}" class="px-6 py-2 bg-amber-600 text-white rounded-full hover:bg-amber-700 transition">
                        <i class="bi bi-cup-hot"></i> Lihat Menu
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <div class="hero-gradient min-h-screen flex items-center justify-center pt-16">
        <div class="text-center text-white px-6">
            <img src="{{ asset('img/buri-umah.jpeg') }}" alt="Buri Umah" class="w-32 h-32 mx-auto mb-6 rounded-2xl shadow-2xl">
            <h1 class="text-5xl font-bold mb-4">Buri Umah</h1>
            <p class="text-xl mb-8 text-amber-100">Cafe & Restaurant Management System</p>
            
            <div class="space-y-4">
                <p class="text-lg max-w-2xl mx-auto text-amber-50">
                    Sistem manajemen menu cafe modern dengan fitur penilaian dan rekomendasi menggunakan metode ROC + SMART
                </p>
                
                <div class="flex justify-center space-x-4 mt-8">
                    <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-amber-800 rounded-full font-semibold hover:bg-amber-50 transition transform hover:scale-105">
                        <i class="bi bi-person"></i> Login Admin
                    </a>
                    <a href="{{ route('alternatifs.index') }}" class="px-8 py-3 bg-amber-800 text-white rounded-full font-semibold hover:bg-amber-900 transition transform hover:scale-105">
                        <i class="bi bi-menu-button-wide"></i> Eksplorasi Menu
                    </a>
                </div>
            </div>
            
            <!-- Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16 max-w-4xl mx-auto">
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <i class="bi bi-cup-hot text-4xl mb-3"></i>
                    <h3 class="text-xl font-semibold mb-2">Beragam Menu</h3>
                    <p class="text-amber-100">Makanan, Cemilan, Coffee, dan Minuman Segar</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <i class="bi bi-star text-4xl mb-3"></i>
                    <h3 class="text-xl font-semibold mb-2">Rating & Review</h3>
                    <p class="text-amber-100">Sistem penilaian dengan metode ROC + SMART</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <i class="bi bi-graph-up text-4xl mb-3"></i>
                    <h3 class="text-xl font-semibold mb-2">Analisis Data</h3>
                    <p class="text-amber-100">Rekomendasi menu terbaik berdasarkan kriteria</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
