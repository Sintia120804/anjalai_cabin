<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Anjalai Nature Cabin') }} - Ekstase Alam Tropis</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #0ea5e9;
            --bg-body: #f8fafc;
            --glass-bg: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        .navbar {
            backdrop-filter: blur(10px);
            background-color: var(--glass-bg);
            border-bottom: 1px solid var(--glass-border);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            font-weight: 500;
            color: #475569 !important;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        .backdrop-blur {
            backdrop-filter: blur(8px);
        }

        footer {
            background-color: #0f172a;
            color: #94a3b8;
        }

        .footer-link {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: white;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @yield('custom_css')
    </style>
    @stack('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light sticky-top shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="bi bi-tree-fill me-2 fs-3 text-primary"></i>
                    ANJALAI NATURE CABIN
                </a>
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto ms-lg-4">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                                href="{{ url('/') }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/#wahana') }}">Wahana</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/#tentang') }}">Tentang Kami</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/#galeri') }}">Galeri</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item ms-md-2">
                                    <a class="btn btn-primary rounded-pill px-4"
                                        href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Panel Admin</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                                        href="{{ route('user.dashboard') }}">Pesanan Saya</a>
                                </li>
                            @endif
                            <li class="nav-item dropdown ms-md-3">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                                    href="#" role="button" data-bs-toggle="dropdown">
                                    <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center fw-bold"
                                        style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2"
                                    aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item rounded-3 py-2" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2 text-danger"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>


            @yield('content')
        </main>

        <footer class="py-5 mt-5">
            <div class="container py-4">
                <div class="row g-4 justify-content-between">
                    <div class="col-lg-4">
                        <h4 class="text-white fw-bold mb-4">ANJALAI NATURE CABIN</h4>
                        <p class="mb-4">Eksplorasi ketenangan alam tropis dengan fasilitas modern. Kami berkomitmen
                            memberikan pengalaman menginap tak terlupakan untuk Anda dan keluarga.</p>
                        <div class="d-flex gap-3">
                            <a href="https://wa.me/6281266880007" target="_blank" class="footer-link fs-4"><i
                                    class="bi bi-whatsapp"></i></a>
                            <a href="#" class="footer-link fs-4"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="footer-link fs-4"><i class="bi bi-tiktok"></i></a>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <h6 class="text-white fw-bold mb-4">Navigasi</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ url('/') }}" class="footer-link">Beranda</a></li>
                            <li class="mb-2"><a href="#cabins" class="footer-link">Semua Cabin</a></li>
                            <li class="mb-2"><a href="#" class="footer-link">Tentang Kami</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-3 col-lg-3">
                        <h6 class="text-white fw-bold mb-4">Kontak & Lokasi</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-geo-alt me-2 text-primary"></i> <span class="small">Taluak
                                    anjalai, Kec. Lembah Gumanti, Kabupaten Solok, Sumatera Barat 27371</span></li>
                            <li class="mb-2"><i class="bi bi-whatsapp me-2 text-success"></i> <a
                                    href="https://wa.me/6281266880007" target="_blank"
                                    class="footer-link">0812-6688-0007</a></li>
                            <li class="mb-3"><a
                                    href="https://www.google.com/maps/place/Anjalai+Nature+Cabin/@-1.092301,100.7396474,14z"
                                    target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 mt-2">Buka
                                    Google Maps</a></li>
                        </ul>
                    </div>
                </div>
                <hr class="my-5 opacity-10">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="mb-0">&copy; {{ date('Y') }} Anjalai Cabin. All rights reserved.</p>
                    <div class="d-flex gap-4">
                        <a href="#" class="footer-link small">Privacy Policy</a>
                        <a href="#" class="footer-link small">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session("error") }}',
                confirmButtonColor: '#2563eb'
            });
        </script>
    @endif
    @stack('scripts')
</body>

</html>