<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Buri Umah | {{ $title ?? 'Dashboard' }}</title>

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/buri-umah.jpeg') }}" />
  <link rel="icon" type="image/jpeg" href="{{ asset('img/buri-umah.jpeg') }}" />

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
  <!-- AOS (tetap untuk konten) -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <!-- ==== Custom Theme (reworked) ==== -->
  <style>
    :root{
      /* Palette */
      --primary:#ff6b9d;
      --primary-600:#ff4d7d;
      --primary-200:#ffc8dd;
      --primary-100:#fff0f5;

      --surface:#ffffff;
      --surface-2:#fafafa;
      --text:#1f2937;        /* slate-800 */
      --text-muted:#6b7280;  /* slate-500 */
      --ring:#ff6b9d;

      --radius:16px;
      --radius-sm:12px;
      --shadow-sm:0 1px 3px rgba(17,24,39,.07),0 1px 2px rgba(17,24,39,.05);
      --shadow-md:0 8px 24px rgba(255,107,157,.12);
      --shadow-lg:0 16px 40px rgba(255,107,157,.18);
      --ease:cubic-bezier(.2,.8,.2,1);
    }

    *{box-sizing:border-box}
    html{scroll-behavior:smooth}
    body{
      font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
      color:var(--text);
      background:linear-gradient(160deg,var(--primary-100) 0%, var(--surface) 45%, var(--primary-100) 100%);
      min-height:100vh; overflow-x:hidden;
    }

    /* Scrollbar */
    ::-webkit-scrollbar{width:10px;height:10px}
    ::-webkit-scrollbar-track{background:var(--primary-100);border-radius:10px}
    ::-webkit-scrollbar-thumb{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-200) 100%);border-radius:10px}

    /* Accessiblity helpers */
    .focus-ring:focus{outline:none;box-shadow:0 0 0 4px color-mix(in oklab, var(--ring) 35%, transparent);transition:box-shadow .25s var(--ease)}
    .skip-link{
      position:absolute;left:-999px;top:auto;width:1px;height:1px;overflow:hidden;
    }
    .skip-link:focus{
      left:12px;top:12px;width:auto;height:auto;background:#000;color:#fff;padding:.6rem .9rem;border-radius:10px;z-index:2000
    }

    /* Sidebar */
    .sidebar{
      position:fixed;inset:auto auto 0 0;top:0;height:100vh;width:280px;
      background:linear-gradient(145deg,var(--primary) 0%,var(--primary-200) 100%);
      box-shadow:var(--shadow-lg);z-index:1040;transition:width .28s var(--ease),transform .28s var(--ease);
      overflow-y:auto; will-change:width,transform;
    }
    .sidebar.collapsed{width:88px}
    .sidebar::before{
      content:"";position:absolute;inset:0;opacity:.08;pointer-events:none;
      background:radial-gradient(1200px 400px at -10% -10%,#fff 0%,transparent 60%);
      mix-blend-mode:overlay;
    }
    .logo-section{padding:1.75rem 1.25rem;text-align:center;border-bottom:1px solid rgba(255,255,255,.2)}
    .logo-section img{width:76px;height:76px;border-radius:20px;background:#fff;padding:10px;box-shadow:var(--shadow-sm);transition:transform .25s var(--ease)}
    .logo-section img:hover{transform:scale(1.06) rotate(3deg)}
    .brand-name{font-family:'Dancing Script',cursive;font-size:1.75rem;color:#fff;margin:.5rem 0 0}
    .brand-tagline{color:#fff;opacity:.9;font-size:.85rem}
    .sidebar.collapsed .brand-name,.sidebar.collapsed .brand-tagline{display:none}

    .sidebar-menu{padding:1rem 0}
    .menu-header{
      color:#fff;opacity:.8;font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;
      padding:.25rem 1.25rem;margin:1rem 0 .5rem;position:relative
    }
    .menu-header::after{content:"";position:absolute;left:1.25rem;right:1.25rem;bottom:-.4rem;height:1px;background:rgba(255,255,255,.25)}
    .sidebar.collapsed .menu-header{display:none}

    .nav-item{margin:.25rem .6rem}
    .nav-link{
      display:flex;align-items:center;gap:.75rem;color:#fff;text-decoration:none;padding:.7rem .9rem;border-radius:12px;position:relative;
      transition:transform .18s var(--ease),background .18s var(--ease),opacity .18s var(--ease);
      outline-offset:2px;
    }
    .nav-link:hover{background:rgba(255,255,255,.18);transform:translateX(4px)}
    .nav-link.active{background:rgba(255,255,255,.28);box-shadow:0 8px 24px rgba(0,0,0,.12)}
    .nav-link i{font-size:1.22rem;width:28px;text-align:center}
    .sidebar.collapsed .nav-link{justify-content:center;padding:.8rem}
    .sidebar.collapsed .nav-link span{display:none}

    /* Reveal animation */
    @keyframes sidebarIn{from{opacity:0;transform:translateX(-10px)}to{opacity:1;transform:translateX(0)}}
    .sidebar .nav-item{opacity:0;transform:translateX(-10px)}
    .sidebar.reveal .nav-item{animation:sidebarIn .3s ease forwards;animation-delay:var(--d,0ms)}
    @media (prefers-reduced-motion:reduce){.sidebar .nav-item,.sidebar.reveal .nav-item{animation:none!important;opacity:1!important;transform:none!important}}

    /* Main wrapper */
    .main-wrapper{margin-left:280px;min-height:100vh;display:flex;flex-direction:column;transition:margin-left .28s var(--ease)}
    .main-wrapper.expanded{margin-left:88px}

    /* Topbar */
    .topbar{
      position:sticky;top:0;z-index:1020;
      background:color-mix(in oklab, var(--surface) 95%, transparent);backdrop-filter:saturate(1.2) blur(8px);
      box-shadow:var(--shadow-sm);padding:.9rem 1.25rem;
    }
    .topbar-content{display:flex;justify-content:space-between;align-items:center;gap:1rem}
    .toggle-sidebar{
      width:44px;height:44px;border-radius:12px;border:0;background:var(--primary-100);color:var(--primary);
      display:grid;place-items:center;cursor:pointer;transition:transform .25s var(--ease),background .25s var(--ease)
    }
    .toggle-sidebar:hover{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-200) 100%);color:#fff;transform:rotate(180deg)}
    .user-menu{display:flex;align-items:center;gap:.8rem}
    .user-profile{
      display:flex;align-items:center;gap:.65rem;padding:.5rem .75rem;border-radius:999px;background:var(--primary-100);
      cursor:pointer;transition:background .2s var(--ease),color .2s var(--ease)
    }
    .user-profile:hover{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-200) 100%);color:#fff}
    .user-avatar{
      width:40px;height:40px;border-radius:50%;display:grid;place-items:center;font-weight:700;color:#fff;
      background:linear-gradient(135deg,var(--primary) 0%,var(--primary-200) 100%);box-shadow:var(--shadow-sm)
    }
    .user-name{font-weight:600;font-size:.92rem}
    .user-role{font-size:.75rem;opacity:.8}

    /* Content */
    .content-area{padding:1.5rem}
    .page-transition{animation:fade .4s ease}
    @keyframes fade{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}

    /* Cards */
    .card{border:none;border-radius:var(--radius);box-shadow:var(--shadow-sm);transition:transform .18s var(--ease),box-shadow .18s var(--ease);overflow:hidden;background:var(--surface)}
    .card:hover{transform:translateY(-3px);box-shadow:var(--shadow-md)}
    .card-header{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-200) 100%);color:#fff;border:none;padding:1.05rem 1.25rem;font-weight:600}

    /* Buttons */
    .btn-primary{
      background:linear-gradient(135deg,var(--primary) 0%,var(--primary-200) 100%);
      border:none;border-radius:12px;padding:.7rem 1.2rem;font-weight:600;box-shadow:var(--shadow-sm);transition:transform .18s var(--ease),box-shadow .18s var(--ease)
    }
    .btn-primary:hover{transform:translateY(-1px);box-shadow:var(--shadow-md)}
    .btn-outline-primary{
      color:var(--primary);border:2px solid var(--primary);border-radius:12px;padding:.7rem 1.2rem;font-weight:600
    }
    .btn-outline-primary:hover{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-200) 100%);border-color:transparent;color:#fff}

    /* Tables */
    .table{border-radius:14px;overflow:hidden;background:var(--surface)}
    .table thead{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-200) 100%);color:#fff}
    .table thead th{border:none;padding:1rem;text-transform:uppercase;font-weight:600;letter-spacing:.04em;font-size:.82rem}
    .table tbody tr{transition:background .15s var(--ease)}
    .table tbody tr:hover{background:color-mix(in oklab,var(--primary-100) 65%, transparent)}
    .table tbody td{padding:1rem;vertical-align:middle;border-color:color-mix(in oklab,var(--primary-200) 30%, transparent)}

    /* DataTables tweaks */
    .dataTables_wrapper .dataTables_filter input{
      border:2px solid color-mix(in oklab,var(--primary) 30%, transparent);
      border-radius:999px;padding:.5rem 1rem;background:var(--surface-2);color:var(--text)
    }
    .dataTables_wrapper .dataTables_length select{
      border:1px solid color-mix(in oklab,var(--primary) 25%, transparent);
      border-radius:10px;padding:.35rem .6rem;background:var(--surface-2);color:var(--text)
    }
    .page-link{border-radius:10px!important}
    .page-item.active .page-link{background:var(--primary);border-color:var(--primary)}

    /* Alerts */
    .alert{border:none;border-radius:14px;padding:1rem 1.1rem}
    .alert-success{background:linear-gradient(135deg,#a8e6cf 0%,#dcedc1 100%);color:#1b5e20}
    .alert-danger{background:linear-gradient(135deg,#ffcdd2 0%,#ef9a9a 100%);color:#8b1c1c}

    /* Footer */
    .footer{background:var(--surface);padding:1.5rem;box-shadow:0 -4px 16px rgba(0,0,0,.05);margin-top:auto}
    .footer-content{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1.25rem}
    .footer-logo img{width:56px;height:56px;border-radius:14px;background:#fff;padding:8px;box-shadow:var(--shadow-sm)}
    .footer-social{display:flex;gap:.75rem}
    .social-link{
      width:40px;height:40px;border-radius:50%;display:grid;place-items:center;
      background:var(--primary-100);color:var(--primary);text-decoration:none;transition:transform .18s var(--ease),background .18s var(--ease),color .18s var(--ease)
    }
    .social-link:hover{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-200) 100%);color:#fff;transform:translateY(-3px)}

    /* Mobile toggle */
    .mobile-menu-toggle{
      display:none;position:fixed;bottom:20px;right:20px;z-index:1050;
      width:48px;height:48px;border:0;border-radius:14px;background:linear-gradient(135deg,var(--primary) 0%,var(--primary-200) 100%);
      color:#fff;box-shadow:var(--shadow-lg);align-items:center;justify-content:center
    }

    /* Responsive */
    @media(max-width:1024px){
      .sidebar{width:250px}
      .main-wrapper{margin-left:250px}
    }
    @media(max-width:768px){
      .sidebar{transform:translateX(-100%);width:280px}
      .sidebar.show{transform:translateX(0)}
      .main-wrapper{margin-left:0}
      .mobile-menu-toggle{display:flex}
      .content-area{padding:1rem}
      .user-role,.brand-tagline{display:none}
    }
    @media(max-width:576px){
      .card{border-radius:12px}
      .btn{padding:.55rem 1rem;font-size:.92rem}
      .table{font-size:.88rem}
    }

    /* Loading spinner */
    .loading-spinner{
      width:40px;height:40px;border:4px solid var(--primary-200);border-top:4px solid var(--primary);border-radius:50%;
      animation:spin 1s linear infinite
    }
    /* === Theme toggle button === */
    .theme-toggle {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    border: none;
    background: var(--primary-100);
    color: var(--primary);
    display: grid;
    place-items: center;
    cursor: pointer;
    transition: transform 0.25s var(--ease), background 0.25s var(--ease), color 0.25s var(--ease);
    }
    .theme-toggle:hover {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-200) 100%);
    color: #fff;
    }
    @keyframes spin{to{transform:rotate(360deg)}}
  </style>
  @yield('css')
</head>
<body>
  <!-- Skip link -->
  <a href="#mainContent" class="skip-link">Skip to content</a>

  <!-- Sidebar -->
  <nav class="sidebar" id="sidebar" aria-label="Primary">
    <div class="logo-section">
      <img src="{{ asset('img/buri-umah.jpeg') }}" alt="Buri Umah Logo">
      <h2 class="brand-name">Buri Umah</h2>
      <p class="brand-tagline">Cafe & Restaurant</p>
    </div>

    <div class="sidebar-menu">
      <div class="menu-header">Menu Utama</div>
      <div class="nav-item">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <i class="bi bi-house-heart-fill" aria-hidden="true"></i><span>Dashboard</span>
        </a>
      </div>

      <div class="menu-header">Data Master</div>
      <div class="nav-item">
        <a href="{{ route('kriteria') }}" class="nav-link {{ request()->routeIs('kriteria*') ? 'active' : '' }}">
          <i class="bi bi-list-stars" aria-hidden="true"></i><span>Kriteria</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="{{ route('subkriteria') }}" class="nav-link {{ request()->routeIs('subkriteria*') ? 'active' : '' }}">
          <i class="bi bi-diagram-3-fill" aria-hidden="true"></i><span>Sub Kriteria</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="{{ route('alternatif') }}" class="nav-link {{ request()->routeIs('alternatif*') ? 'active' : '' }}">
          <i class="bi bi-box-seam-fill" aria-hidden="true"></i><span>Data Produk</span>
        </a>
      </div>

      <div class="menu-header">Sistem Pendukung</div>
      <div class="nav-item">
        <a href="{{ route('penilaian') }}" class="nav-link {{ request()->routeIs('penilaian*') ? 'active' : '' }}">
          <i class="bi bi-clipboard2-data-fill" aria-hidden="true"></i><span>Penilaian</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="{{ route('perhitungan') }}" class="nav-link {{ request()->routeIs('perhitungan*') ? 'active' : '' }}">
          <i class="bi bi-calculator-fill" aria-hidden="true"></i><span>Perhitungan</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="{{ route('hasil-akhir') }}" class="nav-link {{ request()->routeIs('hasil-akhir*') ? 'active' : '' }}">
          <i class="bi bi-trophy-fill" aria-hidden="true"></i><span>Hasil Akhir</span>
        </a>
      </div>

      <div class="menu-header">Pengaturan</div>
      <div class="nav-item">
        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
          <i class="bi bi-person-circle" aria-hidden="true"></i><span>Profile</span>
        </a>
      </div>
      <div class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="nav-link w-100 text-start">
            <i class="bi bi-box-arrow-right" aria-hidden="true"></i><span>Logout</span>
          </button>
        </form>
      </div>
    </div>
  </nav>

  <!-- Mobile Menu Toggle -->
  <button class="mobile-menu-toggle focus-ring" onclick="toggleMobileSidebar()" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle menu">
    <i class="bi bi-list" aria-hidden="true"></i>
  </button>

  <!-- Main Content -->
  <div class="main-wrapper" id="mainContent" tabindex="-1">
    <!-- Topbar -->
    <div class="topbar">
      <div class="topbar-content">
        <button class="toggle-sidebar focus-ring" onclick="toggleSidebar()" aria-controls="sidebar" aria-pressed="false" aria-label="Collapse sidebar">
          <i class="bi bi-list" aria-hidden="true"></i>
        </button>

        <div class="user-menu">
          <div class="user-profile">
            <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
            <div class="user-info">
              <span class="user-name">{{ auth()->user()->name }}</span>
              <span class="user-role"></span>
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
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down">
          <i class="bi bi-exclamation-circle-fill me-2"></i>
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-logo">
          <img src="{{ asset('img/buri-umah.jpeg') }}" alt="Buri Umah">
          <div>
            <h5 class="mb-0" style="color: var(--primary); font-family: 'Dancing Script', cursive;">Buri Umah</h5>
            <small class="text-muted">Smart Menu Recommendation System</small>
          </div>
        </div>

        <div class="footer-social">
          <a href="https://www.instagram.com/creme.boba29" class="social-link" target="_blank" aria-label="Instagram">
            <i class="bi bi-instagram" aria-hidden="true"></i>
          </a>
          <a href="https://youtube.com/@selvyajyt4062" class="social-link" target="_blank" aria-label="YouTube">
            <i class="bi bi-youtube" aria-hidden="true"></i>
          </a>
          <a href="#" class="social-link" aria-label="TikTok">
            <i class="bi bi-tiktok" aria-hidden="true"></i>
          </a>
          <a href="#" class="social-link" aria-label="WhatsApp">
            <i class="bi bi-whatsapp" aria-hidden="true"></i>
          </a>
        </div>

        <div class="text-center">
          <p class="mb-0" style="color: var(--primary);">
            <strong>© {{ date('Y') }} Buri Umah</strong>
          </p>
          <small class="text-muted">
            Developed with <i class="bi bi-heart-fill" style="color:#ff1744" aria-hidden="true"></i> by Selvya
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
    // Init AOS untuk area konten (bukan sidebar)
    AOS.init({ duration:800, easing:'ease-in-out', once:true });

    // Sidebar reveal tanpa AOS
    function runSidebarReveal() {
      const sidebar = document.getElementById('sidebar');
      if (!sidebar) return;
      const items = sidebar.querySelectorAll('.nav-item');
      items.forEach((el, i) => el.style.setProperty('--d', (i * 60) + 'ms'));
      sidebar.classList.remove('reveal');
      void sidebar.offsetWidth; // reflow
      sidebar.classList.add('reveal');
    }

    function syncAria(){
      const sidebar=document.getElementById('sidebar');
      const mobileBtn=document.querySelector('.mobile-menu-toggle');
      if(mobileBtn){
        mobileBtn.setAttribute('aria-expanded', sidebar.classList.contains('show'));
      }
      const desktopBtn=document.querySelector('.toggle-sidebar');
      if(desktopBtn){
        desktopBtn.setAttribute('aria-pressed', document.getElementById('mainContent').classList.contains('expanded'));
      }
    }

    // Toggle Sidebar (desktop)
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('mainContent');
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
      localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
      if (!sidebar.classList.contains('collapsed')) runSidebarReveal();
      syncAria();
    }

    // Mobile Sidebar Toggle
    function toggleMobileSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('show');
      const btn = document.querySelector('.mobile-menu-toggle i');
      btn.classList.toggle('bi-x', sidebar.classList.contains('show'));
      btn.classList.toggle('bi-list', !sidebar.classList.contains('show'));
      if (sidebar.classList.contains('show')) runSidebarReveal();
      syncAria();
    }

    // Close mobile sidebar when clicking outside
    document.addEventListener('click', function(event) {
      const sidebar = document.getElementById('sidebar');
      const mobileToggle = document.querySelector('.mobile-menu-toggle');
      if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
          sidebar.classList.remove('show');
          const btn = mobileToggle.querySelector('i');
          btn.classList.remove('bi-x'); btn.classList.add('bi-list');
          syncAria();
        }
      }
    });

    // Load state + DataTables
    document.addEventListener('DOMContentLoaded', function() {
      const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
      if (collapsed && window.innerWidth > 768) {
        document.getElementById('sidebar').classList.add('collapsed');
        document.getElementById('mainContent').classList.add('expanded');
      }
      runSidebarReveal();
      syncAria();

      // Init DataTables (tema selaras)
      $('.datatable').each(function() {
        if (!$.fn.DataTable.isDataTable(this)) {
          $(this).DataTable({
            responsive:true,
            language:{
              search:"Cari:",
              lengthMenu:"Tampilkan _MENU_ data",
              info:"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
              paginate:{first:"Pertama", last:"Terakhir", next:"Berikutnya", previous:"Sebelumnya"},
              emptyTable:"Tidak ada data tersedia",
              zeroRecords:"Tidak ada data yang cocok"
            },
            pageLength:10,
            lengthMenu:[[10,25,50,-1],[10,25,50,"Semua"]],
            dom:'<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
          });
        }
      });

      // Tambahkan focus ring pada input search DataTables
      $('.datatable').on('init.dt', function(){
        $(this).closest('.dataTables_wrapper').find('.dataTables_filter input').addClass('focus-ring');
      });

      // Tandai link aktif untuk pembaca layar
      document.querySelectorAll('.sidebar .nav-link.active')?.forEach(a => a.setAttribute('aria-current','page'));
    });

    // Smooth anchor scroll (tetap)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          target.focus?.();
        }
      });
    });

    // Loading state untuk semua form
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
          submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
          submitBtn.disabled = true;
        }
      });
    });

    // SweetAlert error dari session (tetap)
    @if(session('error'))
      Swal.fire({
        icon:'error',
        title:'Oops...',
        text:'{{ session("error") }}',
        showConfirmButton:false,
        timer:3000,
        toast:true,
        position:'top-end',
        background:'linear-gradient(135deg, #ffcdd2 0%, #ef9a9a 100%)',
        color:'#c62828'
      });
    @endif
  </script>
  @yield('js')
</body>
</html>
