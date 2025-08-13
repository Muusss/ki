<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel SMART | {{ $title ?? 'Dashboard' }}</title>
    
    <!-- Update favicon dengan logo baru -->
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
    
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #ff99cc;   /* Pink muda utama */
            --primary-dark: #ff66b2;    /* Pink sedikit lebih gelap */
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #2c3e50;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--light-color);
            color: #333;
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .logo-section {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar .logo-section img {
            width: 70px; /* Lebih besar untuk logo YAC */
            height: 70px;
            border-radius: 10px;
            margin-bottom: 10px;
            background: white; /* Tambah background putih */
            padding: 5px;
        }

        .sidebar .logo-section h5 {
            color: white;
            font-weight: 700;
            margin: 0;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .logo-section h5 {
            display: none;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu .menu-header {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0 1.5rem;
            margin: 1rem 0 0.5rem;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .menu-header {
            display: none;
        }

        .sidebar-menu .nav-item {
            margin: 0.25rem 0;
        }

        .sidebar-menu .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            text-decoration: none;
        }

        .sidebar-menu .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left-color: white;
        }

        .sidebar-menu .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.15);
            border-left-color: white;
        }

        .sidebar-menu .nav-link i {
            font-size: 1.1rem;
            width: 30px;
            text-align: center;
            margin-right: 10px;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-wrapper.expanded {
            margin-left: 80px;
        }

        /* Top Bar */
        .topbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar .toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--secondary-color);
            cursor: pointer;
            transition: color 0.3s;
        }

        .topbar .toggle-sidebar:hover {
            color: var(--primary-color);
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
            flex: 1;
        }

        /* Footer */
        .footer {
            background: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-logo img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: white;
            padding: 3px;
        }

        .footer-logo-text h6 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 0.9rem;
        }

        .footer-logo-text p {
            margin: 0;
            font-size: 0.75rem;
            color: var(--secondary-color);
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .footer-links a {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .footer-right {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .footer-social {
            display: flex;
            gap: 0.75rem;
        }

        .footer-social a {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--light-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
            transition: all 0.3s;
            text-decoration: none;
        }

        .footer-social a:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }

        .footer-copyright {
            color: var(--secondary-color);
            font-size: 0.875rem;
        }

        .footer-divider {
            border-top: 1px solid #e3e6f0;
            margin: 1rem 0;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid var(--primary-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card.primary .icon {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .stat-card.success .icon {
            background: linear-gradient(135deg, var(--success-color), #17a673);
            color: white;
        }

        .stat-card.warning .icon {
            background: linear-gradient(135deg, var(--warning-color), #f4b619);
            color: white;
        }

        .stat-card.info .icon {
            background: linear-gradient(135deg, var(--info-color), #2c9faf);
            color: white;
        }

        /* Tables */
        .custom-table {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .custom-table table {
            width: 100%;
        }

        .custom-table thead th {
            background: var(--light-color);
            color: var(--dark-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 1rem;
            border: none;
        }

        .custom-table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #e3e6f0;
        }

        /* Buttons */
        .btn-custom {
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-custom-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
        }

        .btn-custom-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 153, 204, 0.3);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-wrapper {
                margin-left: 0;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-left {
                flex-direction: column;
            }

            .footer-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .footer-right {
                flex-direction: column;
            }
        }
    </style>
    @yield('css')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="logo-section">
            <!-- Update logo di sidebar -->
            <img src="{{ asset('img/logo-ss.png') }}" alt="Logo SS">
            <h5>ReGlow</h5>
            <small style="color: rgba(255,255,255,0.8); font-size: 0.75rem;">Beauty Inside and Outside</small>
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-header">Menu Utama</div>
            
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="menu-header">Data Master</div>
            
            <div class="nav-item">
                <a href="{{ route('kriteria') }}" class="nav-link {{ request()->routeIs('kriteria*') ? 'active' : '' }}">
                    <i class="bi bi-list-check"></i>
                    <span>Kriteria</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('subkriteria') }}" class="nav-link {{ request()->routeIs('subkriteria*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3"></i>
                    <span>Sub Kriteria</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('alternatif') }}" class="nav-link {{ request()->routeIs('alternatif*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Data Produk</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('permintaan') }}" class="nav-link {{ request()->routeIs('permintaan*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Permintaan</span>
                </a>
            </div>
            
            <div class="menu-header">SMART</div>
            
            <div class="nav-item">
                <a href="{{ route('penilaian') }}" class="nav-link {{ request()->routeIs('penilaian*') ? 'active' : '' }}">
                    <i class="bi bi-pencil-square"></i>
                    <span>Penilaian</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('perhitungan') }}" class="nav-link {{ request()->routeIs('perhitungan*') ? 'active' : '' }}">
                    <i class="bi bi-calculator"></i>
                    <span>Perhitungan</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('hasil-akhir') }}" class="nav-link {{ request()->routeIs('hasil-akhir*') ? 'active' : '' }}">
                    <i class="bi bi-trophy"></i>
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

    <!-- Main Content Wrapper -->
    <div class="main-wrapper" id="mainContent">
        <!-- Top Bar -->
        <div class="topbar">
            <button class="toggle-sidebar" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            
            <div class="user-info">
                <span class="d-none d-md-inline">
                    Hi, <strong>{{ auth()->user()->name }}</strong>
                    @if(auth()->user()->role === 'wali_kelas')
                        <span class="badge bg-info ms-2">Wali Kelas {{ auth()->user()->kelas }}</span>
                    @elseif(auth()->user()->role === 'admin')
                        <span class="badge bg-danger ms-2">Admin</span>
                    @endif
                </span>
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-left">
                <div class="footer-logo">
                    <!-- Update logo di footer -->
                    <img src="{{ asset('img/logo-ss.png') }}" alt="Logo YAC">
                    <div class="footer-logo-text">
                        <h6>ReGlow</h6>
                        <p>Beuty Inside and Outside</p>
                    </div>
                </div>
                    
                </div>
                
                <div class="footer-right">
                    <div class="footer-social">
                        <a href="https://www.instagram.com/creme.boba29?igsh=MWIwaDBjaDY1cnlrNQ%3D%3D&utm_source=qr" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://youtube.com/@selvyajyt4062?si=j1tdCzgXoK6I6I2w" title="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                    
                    <div class="footer-copyright">
                        <strong>© {{ date('Y') }} Laravel SMART</strong>
                    </div>
                </div>
            </div>
            
            <div class="footer-divider"></div>
            
            <div class="text-center">
                <small class="text-muted">
                    <i class="bi bi-code-slash me-1"></i>
                    Developed with <i></i> by 
                    <strong>Selvya</strong> | 
                    Metode ROC & SMART | 
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                </small>
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
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('expanded');
        }
        
        // Initialize DataTables globally
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });

        // Add active class to current page in footer
        $(document).ready(function() {
            $('.footer-links a').each(function() {
                if ($(this).attr('href') === window.location.pathname) {
                    $(this).css('color', 'var(--primary-color)').css('font-weight', '600');
                }
            });
        });
    </script>
    @yield('js')
</body>
</html>