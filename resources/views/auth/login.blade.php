@extends('dashboard.layouts.auth')

@section('title', 'Masuk')

@section('content')
  <!-- Logo + title -->
  <div class="text-center mb-4">
    <img src="{{ asset('img/logo-ss.png') }}" alt="ReGlow" width="72" height="72"
         class="rounded-4 p-2 bg-white shadow-sm" style="object-fit:contain">
    <h2 class="auth-title mt-3">Selamat Datang</h2>
    <p class="auth-sub mb-0">Silakan masuk untuk melanjutkan.</p>
  </div>

  <div class="auth-card">
    {{-- Notifikasi error validasi --}}
    @if ($errors->any())
      <div class="mb-4 rounded-3 border border-danger-subtle bg-danger-subtle p-3 text-danger d-flex gap-2">
        <i class="bi bi-exclamation-octagon-fill"></i>
        <div>
          <div class="fw-semibold">Terjadi kesalahan</div>
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li class="small">{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

    @if (session('status'))
      <div class="alert alert-success" role="alert">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
      @csrf

      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" class="form-control" id="email" name="email"
               value="{{ old('email') }}" required autocomplete="username" autofocus>
      </div>

      <!-- Password + toggle visibility -->
      <div class="mb-2">
        <label for="password" class="form-label fw-semibold">Kata Sandi</label>
        <div class="input-with-icon">
          <input type="password" class="form-control" id="password" name="password"
                 required autocomplete="current-password">
          <button type="button" class="toggle-visibility" id="togglePassword" aria-label="Tampilkan/Sembunyikan sandi">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <!-- Remember + forgot -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
          <label class="form-check-label" for="remember">Ingat saya</label>
        </div>

        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="link-accent">Lupa kata sandi?</a>
        @endif
      </div>

      <!-- Submit -->
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
        </button>
      </div>
    </form>
  </div>
@endsection

@section('js')
<script>
  // Toggle show/hide password
  (function(){
    const btn = document.getElementById('togglePassword');
    const input = document.getElementById('password');
    if(btn && input){
      btn.addEventListener('click', () => {
        const isText = input.getAttribute('type') === 'text';
        input.setAttribute('type', isText ? 'password' : 'text');
        btn.innerHTML = isText ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
      });
    }
  })();

  // (Opsional) autofill demo
  function fillDemo(email){
    const e=document.querySelector('input[name="email"]');
    const p=document.querySelector('input[name="password"]');
    if(e&&p){ e.value=email; p.value='password'; e.focus(); }
  }
</script>
@endsection
