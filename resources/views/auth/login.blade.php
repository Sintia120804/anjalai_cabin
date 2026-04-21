<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Anjalai Cabin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Outfit', sans-serif; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 40%, #0f3460 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 460px;
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo .icon {
            font-size: 3rem;
            display: block;
            margin-bottom: 8px;
        }

        .brand-logo h1 {
            color: #fff;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .brand-logo p {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
            margin: 4px 0 0;
        }

        .card-login {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }

        .card-login h2 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .card-login .subtitle {
            color: rgba(255,255,255,0.55);
            font-size: 0.9rem;
            margin-bottom: 28px;
        }

        .form-label {
            color: rgba(255,255,255,0.8);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .form-control {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            color: #fff;
            padding: 12px 16px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255,255,255,0.12);
            border-color: #4e9af1;
            color: #fff;
            box-shadow: 0 0 0 3px rgba(78, 154, 241, 0.2);
        }

        .form-control::placeholder { color: rgba(255,255,255,0.35); }

        .input-group-text {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.5);
            border-radius: 10px 0 0 10px;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
            border-left: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: #4e9af1;
            background: rgba(255,255,255,0.12);
        }

        .btn-login {
            background: linear-gradient(135deg, #4e9af1, #2563eb);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 13px;
            width: 100%;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37,99,235,0.4);
            color: #fff;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
            color: rgba(255,255,255,0.3);
            font-size: 0.8rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.12);
        }

        .register-link {
            text-align: center;
            color: rgba(255,255,255,0.55);
            font-size: 0.875rem;
        }

        .register-link a {
            color: #4e9af1;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .register-link a:hover { color: #93c5fd; }

        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .form-check-label {
            color: rgba(255,255,255,0.6);
            font-size: 0.875rem;
        }

        .alert-danger-custom {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            color: #fca5a5;
            padding: 12px 16px;
            font-size: 0.875rem;
            margin-bottom: 20px;
        }

        .is-invalid ~ .invalid-feedback { color: #f87171; }
        .form-control.is-invalid {
            border-color: rgba(239,68,68,0.5);
            background: rgba(239,68,68,0.08);
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    {{-- Logo / Brand --}}
    <div class="brand-logo">
        <span class="icon">🏕️</span>
        <h1>Anjalai Cabin</h1>
        <p>Reservasi cabin terbaik untuk liburanmu</p>
    </div>

    {{-- Card Login --}}
    <div class="card-login">
        <h2>Selamat Datang!</h2>
        <p class="subtitle">Masuk ke akun kamu untuk melanjutkan</p>

        {{-- Success Alert --}}
        @if (session('success'))
            <div class="alert alert-success border-0 rounded-4 small mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="alert-danger-custom">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="contoh@email.com"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>
                @error('email')
                    <div class="invalid-feedback d-block" style="color:#f87171; font-size:0.8rem; margin-top:5px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input
                        type="password"
                        name="password"
                        id="passwordField"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Masukkan password"
                        required
                    >
                    <span class="input-group-text" style="border-radius:0 10px 10px 0; border-left:none; cursor:pointer;" onclick="togglePassword()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block" style="color:#f87171; font-size:0.8rem; margin-top:5px;">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color:#4e9af1; font-size:0.875rem; text-decoration:none;">
                        Lupa password?
                    </a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>



        <div class="register-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword() {
        const field = document.getElementById('passwordField');
        const icon  = document.getElementById('eyeIcon');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
</body>
</html>
