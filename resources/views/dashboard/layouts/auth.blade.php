<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>ReGlow Beauty | @yield('title','Auth')</title>

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/logo-ss.png') }}" />
  <link rel="icon" type="image/png" href="{{ asset('img/logo-ss.png') }}" />

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">

  <style>
    :root{
      --primary:#ff6b9d;
      --primary-600:#ff4d7d;
      --primary-200:#ffc8dd;
      --primary-100:#fff0f5;

      --surface:#ffffff;
      --surface-2:#fafafa;
      --text:#1f2937;
      --text-muted:#6b7280;
      --ring:#ff6b9d;

      --radius:16px;
      --radius-sm:12px;
      --shadow-sm:0 1px 3px rgba(17,24,39,.07),0 1px 2px rgba(17,24,39,.05);
      --shadow-md:0 8px 24px rgba(255,107,157,.12);
      --shadow-lg:0 16px 40px rgba(255,107,157,.18);
      --ease:cubic-bezier(.2,.8,.2,1);
    }

    html, body { height:100% }
    body{
      font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
      color:var(--text);
      background:linear-gradient(160deg,var(--primary-100) 0%, var(--surface) 45%, var(--primary-100) 100%);
    }

    /* Left hero panel */
    .hero{
      background: linear-gradient(145deg, var(--primary) 0%, var(--primary-200) 100%);
      color:#fff; min-height:100vh; padding:3rem; position:relative; overflow:hidden;
    }
    .hero::after{
      content:""; position:absolute; inset:-20% -20% auto auto; height:65%; width:65%;
      background: radial-gradient(closest-side, rgba(255,255,255,.25), transparent 65%);
      filter: blur(20px);
    }
    .brand-logo{
      width:56px; height:56px; border-radius:14px; background:#fff; padding:8px; object-fit:contain;
      box-shadow:var(--shadow-sm);
    }
    .brand-title{ font-weight:800; letter-spacing:.2px }
    .brand-sub{ opacity:.9; font-size:.9rem }
    .footer-note{ color:rgba(255,255,255,.75); font-size:.9rem }

    /* Card */
    .auth-card{
      background:var(--surface); border-radius:var(--radius);
      box-shadow:var(--shadow-lg); padding:2rem;
      border:1px solid color-mix(in oklab, var(--primary-200) 25%, transparent);
    }
    .auth-title{ color:var(--text); font-weight:800 }
    .auth-sub{ color:var(--text-muted) }

    /* Inputs */
    .form-control{
      background:var(--surface-2); border:2px solid color-mix(in oklab, var(--primary) 20%, transparent);
      border-radius:12px; padding:.7rem .9rem;
    }
    .form-control:focus{
      background:#fff; border-color: var(--primary);
      box-shadow: 0 0 0 4px color-mix(in oklab, var(--ring) 25%, transparent);
    }
    .input-with-icon{ position:relative }
    .input-with-icon .toggle-visibility{
      position:absolute; right:.5rem; top:50%; transform:translateY(-50%);
      border:0; background:transparent; color:var(--text-muted);
      padding:.4rem .6rem; border-radius:8px; cursor:pointer;
    }
    .input-with-icon .toggle-visibility:hover{ color:var(--primary-600) }

    /* Buttons */
    .btn-primary{
      --bs-btn-color:#fff; --bs-btn-bg: var(--primary); --bs-btn-border-color: var(--primary);
      --bs-btn-hover-bg: var(--primary-600); --bs-btn-hover-border-color: var(--primary-600);
      border-radius:12px; font-weight:700; box-shadow:var(--shadow-sm);
      padding:.8rem 1rem;
    }
    .link-accent{ color: var(--primary-600); text-decoration: none; font-weight:600 }
    .link-accent:hover{ color:#e64070; text-decoration: underline }

    @media (max-width: 991.98px){
      .hero{ min-height:auto; padding:2rem }
      .auth-wrap{ padding:2rem 1.25rem }
    }
  </style>

  @yield('css')
</head>
<body>
  <div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">
      <!-- Left: Hero / Branding -->
      <aside class="col-lg-6 d-none d-lg-flex flex-column justify-content-between hero">
        <div class="d-flex align-items-center gap-3">
          <img src="{{ asset('img/logo-ss.png') }}" alt="ReGlow Logo" class="brand-logo">
          <div>
            <div class="brand-title h5 mb-0">SMART ReGlow</div>
            <div class="brand-sub">Beauty Inside & Outside</div>
          </div>
        </div>

        <div class="mt-4" style="max-width:560px">
          <h1 class="display-6 fw-bold lh-sm mb-3" style="font-family:'Dancing Script', cursive;">
            Rekomendasi Sunscreen – <span class="text-decoration-underline" style="text-underline-offset:6px">SMART + ROC</span>
          </h1>
          <p class="mb-0" style="max-width: 520px">
            Sistem pendukung keputusan untuk memilih sunscreen terbaik dengan bobot ROC dan utility SMART.
          </p>
        </div>

        <div class="footer-note">&copy; {{ date('Y') }} • Cirebon</div>
      </aside>

      <!-- Right: Content -->
      <main class="col-lg-6 d-flex align-items-center justify-content-center auth-wrap p-4">
        <div class="w-100" style="max-width: 460px">
          @yield('content')
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @yield('js')
</body>
</html>
