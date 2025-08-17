<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Buri Umah</title>
    
    <link rel="icon" type="image/jpeg" href="{{ asset('img/buri-umah.jpeg') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .logo-container {
            background: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-container">
            <img src="{{ asset('img/buri-umah.jpeg') }}" alt="Buri Umah" class="mb-3" style="width: 80px; height: 80px; border-radius: 10px;">
            <h4 class="fw-bold text-primary">Buri Umah</h4>
            <p class="text-muted mb-0">Cafe Management System</p>
        </div>
        
        <div class="card-body p-4">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
                
                <div class="text-center">
                    <a href="{{ route('password.request') }}" class="text-decoration-none">Lupa Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
