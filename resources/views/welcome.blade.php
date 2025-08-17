<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buri Umah - Cafe & Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/buri-umah.jpeg') }}" />
    <link rel="icon" type="image/jpeg" href="{{ asset('img/buri-umah.jpeg') }}" /> 
    <style>
        :root {
            --primary: #8B4513;
            --secondary: #D2691E;
            --accent: #FFD700;
            --dark: #2c1810;
            --line: #e8ecf2;
            --shadow-sm: 0 4px 10px rgba(17,24,39,.08);
            --shadow-md: 0 8px 24px rgba(17,24,39,.12);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(139, 69, 19, 0.8), rgba(210, 105, 30, 0.8)),
                        url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=1600') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }
        
        .hero-content {
            max-width: 800px;
            padding: 0 20px;
            animation: fadeInUp 1s ease;
        }
        
        .brand-name {
            font-family: 'Dancing Script', cursive;
            font-size: 5rem;
            margin-bottom: 20px;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 30px;
            opacity: 0.95;
        }
        
        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .btn-primary-hero {
            background: var(--accent);
            color: var(--dark);
            border: 2px solid var(--accent);
        }
        
        .btn-primary-hero:hover {
            background: transparent;
            color: var(--accent);
            transform: translateY(-3px);
        }
        
        .btn-secondary-hero {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-secondary-hero:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-3px);
        }
        
        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 50px;
            z-index: 1000;
            transition: all 0.3s;
        }
        
        nav.scrolled {
            background: rgba(139, 69, 19, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 50px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .nav-brand {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            color: white;
            text-decoration: none;
        }
        
        /* Features Section */
        .features {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(139, 69, 19, 0.2);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        /* Menu Preview - Updated Styles */
        .menu-preview {
            padding: 80px 0;
            background: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        /* Featured Menu Card dengan Gambar */
        .menu-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
            margin-bottom: 30px;
            border: 1px solid var(--line);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
        }
        
        /* Image Container */
        .menu-image {
            height: 250px;
            position: relative;
            overflow: hidden;
            background: #f6f8fb;
        }
        
        .menu-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .menu-card:hover .menu-image img {
            transform: scale(1.1);
        }
        
        .menu-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f6f8fb, #e8ecf2);
            color: #cdd5df;
        }
        
        .menu-image-placeholder i {
            font-size: 4rem;
            margin-bottom: 10px;
        }
        
        /* Rank Badge */
        .rank-badge-overlay {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 2;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .rank-1 {
            background: linear-gradient(135deg, var(--accent), #FFA500);
            color: var(--dark);
        }
        
        .rank-2 {
            background: linear-gradient(135deg, #C0C0C0, #808080);
            color: white;
        }
        
        .rank-3 {
            background: linear-gradient(135deg, #CD7F32, #8B4513);
            color: white;
        }
        
        /* Menu Content */
        .menu-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .menu-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 12px;
            font-weight: 500;
            width: fit-content;
        }
        
        .badge-makanan { background: #d4edda; color: #155724; }
        .badge-cemilan { background: #fff3cd; color: #856404; }
        .badge-coffee { background: #f8d7da; color: #721c24; }
        .badge-milkshake { background: #d1ecf1; color: #0c5460; }
        .badge-mojito { background: #e2e3e5; color: #383d41; }
        .badge-yakult { background: #cce5ff; color: #004085; }
        .badge-tea { background: #e7e8ea; color: #495057; }
        
        .menu-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .menu-price {
            color: var(--primary);
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        
        .menu-score {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid var(--line);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .score-value {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.1rem;
            color: var(--primary);
            font-weight: 600;
        }
        
        .score-value i {
            color: var(--accent);
        }
        
        /* Statistics */
        .stats {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 60px 0;
            color: white;
        }
        
        .stat-box {
            text-align: center;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: linear-gradient(rgba(139, 69, 19, 0.9), rgba(210, 105, 30, 0.9)),
                        url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1600') center/cover;
            color: white;
            text-align: center;
        }
        
        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 40px 0 20px;
            text-align: center;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            color: white;
            font-size: 1.5rem;
            margin: 0 15px;
            transition: color 0.3s;
        }
        
        .social-links a:hover {
            color: var(--accent);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* View All Button */
        .view-all-btn {
            text-align: center;
            margin-top: 40px;
        }
        
        .view-all-btn a {
            display: inline-block;
            padding: 15px 40px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .view-all-btn a:hover {
            background: var(--secondary);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(139, 69, 19, 0.3);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .brand-name { font-size: 3rem; }
            .hero-subtitle { font-size: 1.2rem; }
            nav { padding: 15px 20px; }
            .feature-card { margin-bottom: 30px; }
            .menu-image { height: 200px; }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav id="navbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a href="#" class="nav-brand">Buri Umah</a>
            <div class="d-flex gap-3">
                <a href="{{ route('hasil-spk') }}" class="btn btn-outline-light">Rekomendasi</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-warning">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-warning">Login Admin</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="brand-name">Buri Umah</h1>
            <p class="hero-subtitle">Cafe & Restaurant dengan Sistem Rekomendasi Menu Pintar</p>
            <div class="cta-buttons">
                <a href="{{ route('hasil-spk') }}" class="btn-hero btn-primary-hero">
                    <i class="bi bi-search"></i> Lihat Rekomendasi
                </a>
                <a href="#features" class="btn-hero btn-secondary-hero">
                    <i class="bi bi-arrow-down"></i> Pelajari Lebih
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-title">
                <h2>Mengapa Buri Umah?</h2>
                <p class="text-muted">Kami menggunakan teknologi untuk memberikan rekomendasi terbaik</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-graph-up feature-icon"></i>
                        <h4>Sistem Rekomendasi Pintar</h4>
                        <p>Menggunakan metode ROC + SMART untuk memberikan rekomendasi menu terbaik sesuai preferensi Anda</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-cup-hot-fill feature-icon"></i>
                        <h4>Menu Berkualitas</h4>
                        <p>Berbagai pilihan menu makanan dan minuman yang telah teruji kualitas dan rasanya</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-cash-coin feature-icon"></i>
                        <h4>Harga Terjangkau</h4>
                        <p>Pengelompokan harga yang jelas memudahkan Anda memilih sesuai budget</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="stats">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">{{ $stats['total_menu'] ?? '50' }}+</div>
                        <div>Total Menu</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">{{ $stats['makanan'] ?? '20' }}+</div>
                        <div>Menu Makanan</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">{{ $stats['minuman'] ?? '25' }}+</div>
                        <div>Menu Minuman</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">{{ $stats['cemilan'] ?? '15' }}+</div>
                        <div>Menu Cemilan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Preview dengan Gambar -->
    @if(isset($featuredMenu) && $featuredMenu->count() > 0)
    <section class="menu-preview">
        <div class="container">
            <div class="section-title">
                <h2>Menu Unggulan Kami</h2>
                <p class="text-muted">Top 3 rekomendasi terbaik berdasarkan sistem penilaian ROC + SMART</p>
            </div>
            <div class="row">
                @foreach($featuredMenu->take(3) as $item)
                    @php
                        $alt = $item->alternatif;
                        $rank = $loop->iteration;
                        
                        // Determine badge color based on jenis_menu
                        $badgeClass = match($alt->jenis_menu ?? '') {
                            'makanan' => 'badge-makanan',
                            'cemilan' => 'badge-cemilan',
                            'coffee' => 'badge-coffee',
                            'milkshake' => 'badge-milkshake',
                            'mojito' => 'badge-mojito',
                            'yakult' => 'badge-yakult',
                            'tea' => 'badge-tea',
                            default => 'badge-secondary'
                        };
                        
                        // Icon for menu type
                        $menuIcon = match($alt->jenis_menu ?? '') {
                            'makanan' => 'bi-egg-fried',
                            'cemilan' => 'bi-cookie',
                            'coffee' => 'bi-cup-hot-fill',
                            'milkshake' => 'bi-cup-straw',
                            'mojito' => 'bi-tropical-storm',
                            'yakult' => 'bi-cup',
                            'tea' => 'bi-cup-fill',
                            default => 'bi-cup-hot'
                        };
                    @endphp
                    <div class="col-md-4">
                        <div class="menu-card">
                            <!-- Image Section -->
                            <div class="menu-image">
                                <!-- Rank Badge -->
                                <div class="rank-badge-overlay rank-{{ $rank }}">
                                    <i class="bi bi-trophy-fill"></i> #{{ $rank }}
                                </div>
                                
                                @if($alt->gambar && file_exists(public_path('img/menu/'.$alt->gambar)))
                                    <img src="{{ asset('img/menu/'.$alt->gambar) }}" 
                                         alt="{{ $alt->nama_menu }}"
                                         loading="lazy">
                                @else
                                    <div class="menu-image-placeholder">
                                        <i class="bi {{ $menuIcon }}"></i>
                                        <span>No Image</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Content Section -->
                            <div class="menu-content">
                                <span class="menu-badge {{ $badgeClass }}">
                                    <i class="bi {{ $menuIcon }}"></i> {{ ucfirst($alt->jenis_menu ?? '') }}
                                </span>
                                <h5 class="menu-title">{{ $alt->nama_menu ?? 'Menu' }}</h5>
                                <div class="menu-price">{{ $alt->harga_label ?? '' }}</div>
                                
                                <div class="menu-score">
                                    <div class="score-value">
                                        <i class="bi bi-star-fill"></i>
                                        <span>{{ number_format($item->total ?? 0, 3) }}</span>
                                    </div>
                                    <span class="badge bg-{{ $rank == 1 ? 'warning text-dark' : ($rank == 2 ? 'secondary' : 'danger') }}">
                                        {{ $rank == 1 ? 'TERBAIK' : ($rank == 2 ? 'EXCELLENT' : 'RECOMMENDED') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- View All Button -->
            <div class="view-all-btn">
                <a href="{{ route('hasil-spk') }}">
                    Lihat Semua Menu <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="mb-4">Temukan Menu Favorit Anda</h2>
            <p class="lead mb-4">Sistem rekomendasi kami siap membantu Anda menemukan menu terbaik</p>
            <a href="{{ route('hasil-spk') }}" class="btn btn-warning btn-lg">
                <i class="bi bi-search"></i> Mulai Pencarian
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <h4 class="mb-3" style="font-family: 'Dancing Script', cursive;">Buri Umah</h4>
            <p>Cafe & Restaurant dengan Sistem Rekomendasi Menu Pintar</p>
            <div class="social-links">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-whatsapp"></i></a>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
            <p class="mb-0">&copy; 2024 Buri Umah. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>