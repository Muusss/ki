<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ReGlow Beauty | {{ $title ?? 'Dashboard' }}</title>
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/logo-ss.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('img/logo-ss.png') }}" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <!-- AOS Animation (biarkan untuk konten lain, bukan sidebar) -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            /* Pink Theme Colors */
            --primary-pink: #ff6b9d;
            --primary-pink-dark: #ff4d7d;
            --primary-pink-light: #ffb3d0;
            --secondary-pink: #ffc8dd;
            --accent-pink: #ff1744;
            --soft-pink: #fff0f5;
            --gradient-pink: linear-gradient(135deg, #ff6b9d 0%, #ffc8dd 100%);
            --gradient-pink-hover: linear-gradient(135deg, #ff4d7d 0%, #ffb3d0 100%);
            
            /* Supporting Colors */
            --text-dark: #2d3436;
            --text-light: #636e72;
            --bg-light: #fdf6f8;
            --white: #ffffff;
            --shadow-sm: 0 2px 8px rgba(255, 107, 157, 0.1);
            --shadow-md: 0 4px 16px rgba(255, 107, 157, 0.15);
            --shadow-lg: 0 8px 32px rgba(255, 107, 157, 0.2);
            --border-radius: 15px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fff0f5 0%, #fff 50%, #fff0f5 100%);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: var(--soft-pink); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--gradient-pink); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gradient-pink-hover); }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh; width: 280px;
            background: var(--gradient-pink);
            box-shadow: var(--shadow-lg);
            z-index: 1040;
            transition: var(--transition);
            overflow-y: auto;
        }
        .sidebar.collapsed { width: 85px; }

        .sidebar::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .sidebar .logo-section {
            padding: 2rem 1.5rem;
            text-align: center;
            position: relative;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar .logo-section img {
            width: 80px; height: 80px; border-radius: 20px; background: white; padding: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            transition: var(--transition);
        }
        .sidebar .logo-section img:hover { transform: scale(1.05) rotate(5deg); }

        .sidebar .brand-name {
            font-family: 'Dancing Script', cursive; font-size: 2rem; color: white; margin-top: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .sidebar.collapsed .brand-name { display: none; }

        .sidebar .brand-tagline { color: rgba(255,255,255,0.9); font-size: 0.85rem; font-style: italic; margin-top: 0.25rem; }
        .sidebar.collapsed .brand-tagline { display: none; }

        /* Sidebar Menu */
        .sidebar-menu { padding: 1.5rem 0; }

        .sidebar-menu .menu-header {
            color: rgba(255,255,255,0.7); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;
            padding: 0 1.5rem; margin: 1.5rem 0 1rem; position: relative;
        }
        .sidebar-menu .menu-header::after {
            content: ''; position: absolute; left: 1.5rem; right: 1.5rem; bottom: -0.5rem; height: 1px;
            background: rgba(255,255,255,0.2);
        }
        .sidebar.collapsed .menu-header { display: none; }

        .sidebar-menu .nav-item { margin: 0.5rem 0.75rem; }

        .sidebar-menu .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 0.875rem 1.25rem; display: flex; align-items: center;
            border-radius: var(--border-radius); transition: var(--transition);
            text-decoration: none; position: relative; overflow: hidden;
        }
        .sidebar-menu .nav-link::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: rgba(255,255,255,0.1); transition: var(--transition);
        }
        .sidebar-menu .nav-link:hover::before { left: 0; }
        .sidebar-menu .nav-link:hover { background: rgba(255,255,255,0.15); color: white; transform: translateX(5px); }
        .sidebar-menu .nav-link.active { background: rgba(255,255,255,0.25); color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

        .sidebar-menu .nav-link i { font-size: 1.25rem; width: 35px; text-align: center; margin-right: 12px; }
        .sidebar.collapsed .nav-link span { display: none; }
        .sidebar.collapsed .nav-link i { margin-right: 0; }
        .sidebar.collapsed .nav-link { justify-content: center; padding: 0.875rem; }

        /* --- Sidebar reveal without AOS --- */
        @keyframes sidebarIn {
          from { opacity: 0; transform: translateX(-12px); }
          to   { opacity: 1; transform: translateX(0); }
        }
        /* default: sembunyikan item, nanti di-reveal dengan kelas .reveal */
        .sidebar .nav-item { opacity: 0; transform: translateX(-12px); }
        /* Saat .reveal aktif, tiap item akan animasi masuk (delay diisi via JS) */
        .sidebar.reveal .nav-item { animation: sidebarIn 0.35s ease forwards; animation-delay: var(--d, 0ms); }

        @media (prefers-reduced-motion: reduce) {
          .sidebar .nav-item, .sidebar.reveal .nav-item {
            animation: none !important; opacity: 1 !important; transform: none !important;
          }
        }

        /* Main Content */
        .main-wrapper {
            flex: 1; margin-left: 280px; transition: var(--transition);
            min-height: 100vh; display: flex; flex-direction: column;
        }
        .main-wrapper.expanded { margin-left: 85px; }

        /* Topbar */
        .topbar {
            background: white; box-shadow: var(--shadow-md); padding: 1rem 1.5rem;
            position: sticky; top: 0; z-index: 1020; backdrop-filter: blur(10px); background: rgba(255,255,255,0.95);
        }
        .topbar-content { display: flex; justify-content: space-between; align-items: center; }

        .toggle-sidebar {
            width: 45px; height: 45px; border-radius: 12px; background: var(--soft-pink);
            border: none; color: var(--primary-pink); display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: var(--transition);
        }
        .toggle-sidebar:hover { background: var(--gradient-pink); color: white; transform: rotate(180deg); }
        .toggle-sidebar i { font-size: 1.25rem; }

        /* Search Bar */
        .search-bar { position: relative; max-width: 400px; flex: 1; margin: 0 2rem; }
        .search-bar input {
            width: 100%; padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid var(--secondary-pink); border-radius: 50px; background: var(--soft-pink); transition: var(--transition);
        }
        .search-bar input:focus { outline: none; border-color: var(--primary-pink); background: white; box-shadow: var(--shadow-md); }
        .search-bar i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary-pink); }

        /* User Menu */
        .user-menu { display: flex; align-items: center; gap: 1rem; }
        .notification-btn {
            width: 45px; height: 45px; border-radius: 12px; background: var(--soft-pink);
            border: none; color: var(--primary-pink); position: relative; transition: var(--transition);
        }
        .notification-btn:hover { background: var(--gradient-pink); color: white; }
        .notification-badge {
            position: absolute; top: -5px; right: -5px; width: 20px; height: 20px; background: var(--accent-pink);
            color: white; border-radius: 50%; font-size: 0.7rem; display: flex; align-items: center; justify-content: center;
        }
        .user-profile {
            display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1rem; background: var(--soft-pink);
            border-radius: 50px; cursor: pointer; transition: var(--transition);
        }
        .user-profile:hover { background: var(--gradient-pink); color: white; }
        .user-avatar {
            width: 40px; height: 40px; border-radius: 50%; background: var(--gradient-pink);
            color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; box-shadow: var(--shadow-sm);
        }
        .user-info { display: flex; flex-direction: column; }
        .user-name { font-weight: 600; font-size: 0.9rem; }
        .user-role { font-size: 0.75rem; opacity: 0.8; }

        /* Content Area */
        .content-area { padding: 2rem; flex: 1; animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Cards */
        .card { border: none; border-radius: var(--border-radius); box-shadow: var(--shadow-md); transition: var(--transition); overflow: hidden; }
        .card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); }
        .card-header { background: var(--gradient-pink); color: white; border: none; padding: 1.25rem; font-weight: 600; }

        /* Buttons */
        .btn-primary { background: var(--gradient-pink); border: none; border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 500; transition: var(--transition); box-shadow: var(--shadow-sm); }
        .btn-primary:hover { background: var(--gradient-pink-hover); transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .btn-outline-primary { color: var(--primary-pink); border: 2px solid var(--primary-pink); border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 500; transition: var(--transition); }
        .btn-outline-primary:hover { background: var(--gradient-pink); border-color: transparent; color: white; }

        /* Tables */
        .table { border-radius: var(--border-radius); overflow: hidden; }
        .table thead { background: var(--gradient-pink); color: white; }
        .table thead th { border: none; padding: 1rem; font-weight: 500; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .table tbody tr { transition: var(--transition); }
        .table tbody tr:hover { background: var(--soft-pink); }
        .table tbody td { padding: 1rem; vertical-align: middle; border-color: var(--secondary-pink); }

        /* Badges */
        .badge { padding: 0.5rem 1rem; border-radius: 50px; font-weight: 500; font-size: 0.8rem; }
        .badge.bg-primary { background: var(--gradient-pink) !important; }

        /* Alerts */
        .alert { border: none; border-radius: var(--border-radius); padding: 1rem 1.25rem; }
        .alert-success { background: linear-gradient(135deg, #a8e6cf 0%, #dcedc1 100%); color: #2d7a3e; }
        .alert-danger { background: linear-gradient(135deg, #ffcdd2 0%, #ef9a9a 100%); color: #c62828; }

        /* Footer */
        .footer { background: white; padding: 2rem; box-shadow: 0 -4px 16px rgba(255, 107, 157, 0.1); margin-top: auto; }
        .footer-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem; }
        .footer-logo { display: flex; align-items: center; gap: 1rem; }
        .footer-logo img { width: 60px; height: 60px; border-radius: 15px; background: white; padding: 8px; box-shadow: var(--shadow-sm); }
        .footer-social { display: flex; gap: 1rem; }
        .social-link {
            width: 40px; height: 40px; border-radius: 50%;
            background: var(--soft-pink); display: flex; align-items: center; justify-content: center;
            color: var(--primary-pink); text-decoration: none; transition: var(--transition);
        }
        .social-link:hover { background: var(--gradient-pink); color: white; transform: translateY(-5px) rotate(360deg); }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none; width: 45px; height: 45px; border-radius: 12px; background: var(--gradient-pink);
            border: none; color: white; align-items: center; justify-content: center; cursor: pointer;
            position: fixed; bottom: 20px; right: 20px; z-index: 1050; box-shadow: var(--shadow-lg);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar { width: 250px; }
            .main-wrapper { margin-left: 250px; }
            .search-bar { margin: 0 1rem; }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 280px; }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .mobile-menu-toggle { display: flex; }
            .search-bar { display: none; }
            .user-info { display: none; }
            .topbar-content { padding: 0; }
            .content-area { padding: 1rem; }
            .footer-content { flex-direction: column; text-align: center; }
        }
        @media (max-width: 576px) {
            .sidebar { width: 100%; }
            .card { border-radius: 10px; }
            .btn { padding: 0.5rem 1rem; font-size: 0.9rem; }
            .table { font-size: 0.85rem; }
        }

        /* Loading Animation */
        .loading-spinner {
            width: 40px; height: 40px; border: 4px solid var(--secondary-pink);
            border-top: 4px solid var(--primary-pink); border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Tooltip */
        .tooltip-custom { position: relative; }
        .tooltip-custom::after {
            content: attr(data-tooltip); position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%);
            background: var(--gradient-pink); color: white; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8rem; white-space: nowrap;
            opacity: 0; pointer-events: none; transition: var(--transition);
        }
        .tooltip-custom:hover::after { opacity: 1; bottom: calc(100% + 10px); }

        /* Page Transitions */
        .page-transition { animation: slideIn 0.5s ease-out; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
    </style>
    @yield('css')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- HAPUS data-aos di logo-section -->
        <div class="logo-section">
            <img src="{{ asset('img/logo-ss.png') }}" alt="ReGlow Logo">
            <h2 class="brand-name">ReGlow Beauty</h2>
            <p class="brand-tagline">Beauty Inside & Outside</p>
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-header">Menu Utama</div>
            <!-- HAPUS semua data-aos di nav-item sidebar -->
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-heart-fill"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="menu-header">Data Master</div>
            <div class="nav-item">
                <a href="{{ route('kriteria') }}" class="nav-link {{ request()->routeIs('kriteria*') ? 'active' : '' }}">
                    <i class="bi bi-list-stars"></i>
                    <span>Kriteria</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('subkriteria') }}" class="nav-link {{ request()->routeIs('subkriteria*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3-fill"></i>
                    <span>Sub Kriteria</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('alternatif') }}" class="nav-link {{ request()->routeIs('alternatif*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i>
                    <span>Data Produk</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('permintaan') }}" class="nav-link {{ request()->routeIs('permintaan*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check-fill"></i>
                    <span>Permintaan</span>
                </a>
            </div>
            
            <div class="menu-header">Sistem Pendukung</div>
            <div class="nav-item">
                <a href="{{ route('penilaian') }}" class="nav-link {{ request()->routeIs('penilaian*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-data-fill"></i>
                    <span>Penilaian</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('perhitungan') }}" class="nav-link {{ request()->routeIs('perhitungan*') ? 'active' : '' }}">
                    <i class="bi bi-calculator-fill"></i>
                    <span>Perhitungan</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('hasil-akhir') }}" class="nav-link {{ request()->routeIs('hasil-akhir*') ? 'active' : '' }}">
                    <i class="bi bi-trophy-fill"></i>
                    <span>Hasil Akhir</span>
                </a>
            </div>
            
            <div class="menu-header">Pengaturan</div>
            <div class="nav-item">
                <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i>
                    <span>Profile</span>
                </a>
            </div>
            <div class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleMobileSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Main Content -->
    <div class="main-wrapper" id="mainContent">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-content">
                <button class="toggle-sidebar" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="user-menu">
                    <div class="user-profile">
                        <div class="user-avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ auth()->user()->name }}</span>
                            <span class="user-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area page-transition">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="{{ asset('img/logo-ss.png') }}" alt="ReGlow">
                    <div>
                        <h5 class="mb-0" style="color: var(--primary-pink); font-family: 'Dancing Script', cursive;">ReGlow Beauty</h5>
                        <small class="text-muted">Smart Sunscreen Recommendation System</small>
                    </div>
                </div>
                
                <div class="footer-social">
                    <a href="https://www.instagram.com/creme.boba29" class="social-link" target="_blank">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://youtube.com/@selvyajyt4062" class="social-link" target="_blank">
                        <i class="bi bi-youtube"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="bi bi-tiktok"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
                
                <div class="text-center">
                    <p class="mb-0" style="color: var(--primary-pink);">
                        <strong>© {{ date('Y') }} ReGlow Beauty</strong>
                    </p>
                    <small class="text-muted">
                        Developed with <i class="bi bi-heart-fill" style="color: var(--accent-pink);"></i> by Selvya
                    </small>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    
    <script>
        // Initialize AOS (biarkan untuk area konten, bukan sidebar)
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // ===== Sidebar Reveal Helper =====
        function runSidebarReveal() {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            const items = sidebar.querySelectorAll('.nav-item');
            items.forEach((el, i) => {
                el.style.setProperty('--d', (i * 60) + 'ms'); // 60ms antar item
            });

            // restart animasi
            sidebar.classList.remove('reveal');
            void sidebar.offsetWidth; // force reflow
            sidebar.classList.add('reveal');
        }

        // Toggle Sidebar (desktop)
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Save state to localStorage
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));

            // Jika di-expand kembali, jalankan reveal
            if (!sidebar.classList.contains('collapsed')) {
                runSidebarReveal();
            }
        }

        // Mobile Sidebar Toggle
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
            
            // Update button icon
            const btn = document.querySelector('.mobile-menu-toggle i');
            if (sidebar.classList.contains('show')) {
                btn.classList.remove('bi-list');
                btn.classList.add('bi-x');

                // Sidebar terlihat -> animasikan item
                runSidebarReveal();
            } else {
                btn.classList.remove('bi-x');
                btn.classList.add('bi-list');
            }
        }

        // Close mobile sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                    const btn = mobileToggle.querySelector('i');
                    btn.classList.remove('bi-x');
                    btn.classList.add('bi-list');
                }
            }
        });

        // Load sidebar state from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed && window.innerWidth > 768) {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('expanded');
            }

            // Jalankan animasi awal sidebar saat halaman siap
            runSidebarReveal();
            
            // Initialize DataTables with custom styling
            $('.datatable').each(function() {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        responsive: true,
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            paginate: {
                                first: "Pertama",
                                last: "Terakhir",
                                next: "Berikutnya",
                                previous: "Sebelumnya"
                            },
                            emptyTable: "Tidak ada data tersedia",
                            zeroRecords: "Tidak ada data yang cocok"
                        },
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    });
                }
            });

            // Sweet Alert for success/error messages
            // @if(session('success'))
            //     Swal.fire({
            //         icon: 'success',
            //         title: 'Berhasil!',
            //         text: '{{ session("success") }}',
            //         showConfirmButton: false,
            //         timer: 3000,
            //         toast: true,
            //         position: 'top-end',
            //         background: 'linear-gradient(135deg, #a8e6cf 0%, #dcedc1 100%)',
            //         color: '#2d7a3e'
            //     });
            // @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session("error") }}',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'top-end',
                    background: 'linear-gradient(135deg, #ffcdd2 0%, #ef9a9a 100%)',
                    color: '#c62828'
                });
            @endif
        });

        // Add smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Add loading state for forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                    submitBtn.disabled = true;
                }
            });
        });
    </script>
    @yield('js')
</body>
</html>
