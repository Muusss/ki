<!-- resources/views/auth/login.blade.php -->
@extends('dashboard.layouts.auth')

@section('title', 'Masuk')

@section('css')
<style>
    /* Tema Pink Muda untuk Login */
    :root {
        --pink-50: #FDF2F8;
        --pink-100: #FCE7F3;
        --pink-200: #FBCFE8;
        --pink-300: #F9A8D4;
        --pink-400: #F472B6;
        --pink-500: #EC4899;
        --pink-600: #DB2777;
        --pink-700: #BE185D;
    }

    body {
        background: linear-gradient(135deg, #FDF2F8 0%, #FCE7F3 50%, #FBCFE8 100%);
        min-height: 100vh;
    }

    .auth-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 2rem;
    }

    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(236, 72, 153, 0.1);
        padding: 2.5rem;
        width: 100%;
        max-width: 420px;
        border: 1px solid rgba(251, 207, 232, 0.3);
    }

    .auth-logo {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #F9A8D4, #FBCFE8);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 15px -3px rgba(236, 72, 153, 0.2);
    }

    .auth-title {
        color: #BE185D;
        font-size: 1.875rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-align: center;
    }

    .auth-subtitle {
        color: #6B7280;
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-label {
        color: #374151;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #FBCFE8;
        border-radius: 0.75rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: #F472B6;
        box-shadow: 0 0 0 3px rgba(244, 114, 182, 0.1);
    }

    .input-with-icon {
        position: relative;
    }

    .toggle-visibility {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9CA3AF;
        cursor: pointer;
        padding: 0.25rem;
    }

    .toggle-visibility:hover {
        color: #EC4899;
    }

    .btn-primary {
        background: linear-gradient(135deg, #EC4899 0%, #F9A8D4 100%);
        color: white;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 9999px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        box-shadow: 0 4px 6px -1px rgba(236, 72, 153, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(236, 72, 153, 0.3);
    }

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #FBCFE8;
        border-radius: 0.25rem;
        cursor: pointer;
    }

    .form-check-input:checked {
        background: #EC4899;
        border-color: #EC4899;
    }

    .link-accent {
        color: #EC4899;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .link-accent:hover {
        color: #DB2777;
        text-decoration: underline;
    }

    .alert-danger {
        background: #FEE2E2;
        border: 1px solid #FCA5A5;
        color: #991B1B;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #D1FAE5;
        border: 1px solid #6EE7B7;
        color: #065F46;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }

    /* Floating elements animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .floating-shape {
        position: absolute;
        opacity: 0.1;
        animation: float 6s ease-in-out infinite;
    }

    .shape-1 {
        top: 10%;
        left: 10%;
        width: 100px;
        height: 100px;
        background: #EC4899;
        border-radius: 50%;
    }

    .shape-2 {
        bottom: 10%;
        right: 10%;
        width: 150px;
        height: 150px;
        background: #F9A8D4;
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        animation-delay: 2s;
    }

    .shape-3 {
        top: 50%;
        right: 5%;
        width: 80px;
        height: 80px;
        background: #FBCFE8;
        border-radius: 50%;
        animation-delay: 4s;
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <!-- Floating Shapes -->
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    <div class="floating-shape shape-3"></div>

    <div class="auth-card">
        <!-- Logo -->
        <div class="auth-logo">
            <img src="{{ asset('img/logo-ss.png') }}" alt="ReGlow" style="width: 60px; height: 60px; object-fit: contain;">
        </div>

        <!-- Title -->
        <h2 class="auth-title">Selamat Datang</h2>
        <p class="auth-subtitle">Silakan masuk untuk melanjutkan</p>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div style="margin-bottom: 1.25rem;">
                <label for="email" class="form-label">Email</label>
                <input type="email" 
                       class="form-control" 
                       id="email" 
                       name="email"
                       value="{{ old('email') }}" 
                       required 
                       autocomplete="username" 
                       autofocus
                       placeholder="nama@email.com">
            </div>

            <!-- Password -->
            <div style="margin-bottom: 1rem;">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="input-with-icon">
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password"
                           required 
                           autocomplete="current-password"
                           placeholder="••••••••">
                    <button type="button" class="toggle-visibility" id="togglePassword">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center;">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="remember" 
                           name="remember"
                           style="margin-right: 0.5rem;">
                    <label for="remember" style="color: #6B7280; font-size: 0.875rem; cursor: pointer;">
                        Ingat saya
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="link-accent" style="font-size: 0.875rem;">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary">
                Masuk
            </button>

        </form>


    </div>
</div>
@endsection

@section('js')
<script>
    // Toggle Password Visibility
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icon
        if (type === 'text') {
            this.innerHTML = `
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                </svg>
            `;
        } else {
            this.innerHTML = `
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            `;
        }
    });
</script>
@endsection