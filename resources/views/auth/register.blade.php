<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Anjalai Cabin</title>
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
            padding: 30px 20px;
        }

        .register-wrapper {
            width: 100%;
            max-width: 500px;
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-logo .icon {
            font-size: 2.8rem;
            display: block;
            margin-bottom: 8px;
        }

        .brand-logo h1 {
            color: #fff;
            font-size: 1.7rem;
            font-weight: 700;
            margin: 0;
        }

        .brand-logo p {
            color: rgba(255,255,255,0.6);
            font-size: 0.875rem;
            margin: 4px 0 0;
        }

        .card-register {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }

        .card-register h2 {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .card-register .subtitle {
            color: rgba(255,255,255,0.55);
            font-size: 0.875rem;
            margin-bottom: 26px;
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

        .btn-register {
            background: linear-gradient(135deg, #10b981, #059669);
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

        .btn-register:hover {
            background: linear-gradient(135deg, #34d399, #10b981);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(16,185,129,0.4);
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

        .login-link {
            text-align: center;
            color: rgba(255,255,255,0.55);
            font-size: 0.875rem;
        }

        .login-link a {
            color: #4e9af1;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .login-link a:hover { color: #93c5fd; }

        .invalid-feedback-custom {
            color: #f87171;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .form-control.is-invalid {
            border-color: rgba(239,68,68,0.5);
            background: rgba(239,68,68,0.08);
        }

        .password-hint {
            color: rgba(255,255,255,0.35);
            font-size: 0.78rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<div class="register-wrapper">

    {{-- Logo / Brand --}}
    <div class="brand-logo">
        <span class="icon">🏕️</span>
        <h1>Anjalai Cabin</h1>
        <p>Daftar dan mulai reservasi cabin impianmu</p>
    </div>

    {{-- Card Register --}}
    <div class="card-register">
        <h2>Buat Akun Baru</h2>
        <p class="subtitle">Isi data diri kamu untuk mendaftar</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Nama Lengkap --}}
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Nama lengkap kamu"
                        value="{{ old('name') }}"
                        required
                        autofocus
                    >
                </div>
                @error('name')
                    <div class="invalid-feedback-custom">{{ $message }}</div>
                @enderror
            </div>

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
                    >
                </div>
                @error('email')
                    <div class="invalid-feedback-custom">{{ $message }}</div>
                @enderror
            </div>

            {{-- No HP --}}
            <div class="mb-3">
                <label class="form-label">Nomor HP <span style="color:rgba(255,255,255,0.3); font-size:0.8rem;">(opsional)</span></label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-telephone"></i>
                    </span>
                    <input
                        type="text"
                        name="no_hp"
                        class="form-control @error('no_hp') is-invalid @enderror"
                        placeholder="08xxxxxxxxxx"
                        value="{{ old('no_hp') }}"
                    >
                </div>
                @error('no_hp')
                    <div class="invalid-feedback-custom">{{ $message }}</div>
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
                        placeholder="Minimal 8 karakter"
                        required
                    >
                    <span class="input-group-text" style="border-radius:0 10px 10px 0; border-left:none; cursor:pointer;" onclick="togglePassword('passwordField','eyeIcon1')">
                        <i class="bi bi-eye" id="eyeIcon1"></i>
                    </span>
                </div>
                <div class="password-hint"><i class="bi bi-info-circle"></i> Minimal 8 karakter</div>
                @error('password')
                    <div class="invalid-feedback-custom">{{ $message }}</div>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
                <label class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-shield-lock"></i>
                    </span>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="confirmField"
                        class="form-control"
                        placeholder="Ulangi password"
                        required
                    >
                    <span class="input-group-text" style="border-radius:0 10px 10px 0; border-left:none; cursor:pointer;" onclick="togglePassword('confirmField','eyeIcon2')">
                        <i class="bi bi-eye" id="eyeIcon2"></i>
                    </span>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-register">
                <i class="bi bi-person-plus me-2"></i>Buat Akun
            </button>
        </form>



        <div class="login-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon  = document.getElementById(iconId);
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
