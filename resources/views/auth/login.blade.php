<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Buri Umah SPK</title>
    
    <link rel="icon" type="image/jpeg" href="{{ asset('img/buri-umah.jpeg') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .logo-container {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .logo-container img {
            width: 80px;
            height: 80px;
            border-radius: 15px;
        }
        
        .brand-name {
            font-family: 'Dancing Script', cursive;
            font-size: 2.5rem;
            margin-bottom: 5px;
        }
        
        .login-body {
            padding: 40px 35px;
        }
        
        .form-floating label {
            color: #666;
        }
        
        .form-control:focus {
            border-color: #D2691E;
            box-shadow: 0 0 0 0.2rem rgba(210, 105, 30, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(139, 69, 19, 0.4);
            color: white;
        }
        
        .form-check-input:checked {
            background-color: #D2691E;
            border-color: #D2691E;
        }
        
        .link-forgot {
            color: #8B4513;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .link-forgot:hover {
            color: #D2691E;
            text-decoration: underline;
        }
        
        .alert-custom {
            border-radius: 10px;
            border: none;
        }
        
        .demo-info {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .demo-info h6 {
            color: #856404;
            margin-bottom: 10px;
        }
        
        .demo-credentials {
            background: white;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
        }
        
        .demo-credentials code {
            color: #8B4513;
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <img src="{{ asset('img/buri-umah.jpeg') }}" alt="Buri Umah">
                </div>
                <h2 class="brand-name">Buri Umah</h2>
                <p class="mb-0">Sistem Pendukung Keputusan</p>
                <small>Menu Recommendation System</small>
            </div>
            
            <div class="login-body">

                <!-- Alert Messages -->
                @if(session('status'))
                    <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-floating mb-3">
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               placeholder="Email"
                               required 
                               autofocus>
                        <label for="email">
                            <i class="bi bi-envelope me-2"></i>Email Address
                        </label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password"
                               placeholder="Password"
                               required>
                        <label for="password">
                            <i class="bi bi-lock me-2"></i>Password
                        </label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="remember" 
                                   id="remember"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-login w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Masuk ke Dashboard
                    </button>
                    
                    <div class="text-center">
                        <a href="{{ route('home') }}" class="link-forgot">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali ke Beranda
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="text-center mt-4 text-white">
            <small>&copy; {{ date('Y') }} Buri Umah - SPK ROC + SMART</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>