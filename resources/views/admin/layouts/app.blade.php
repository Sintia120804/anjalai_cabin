<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Anjalai Cabin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            overflow-x: hidden;
            background-color: #f8fafc;
        }

        /* ===== SIDEBAR ===== */
        #sidebar {
            width: 260px;
            min-height: 100vh;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1050;
            scrollbar-width: none;
        }

        #sidebar::-webkit-scrollbar {
            display: none;
        }

        /* Desktop: sidebar always visible, push content */
        @media (min-width: 992px) {
            #sidebar {
                transform: translateX(0);
            }

            #main-content {
                margin-left: 260px;
            }

            #sidebar-overlay {
                display: none !important;
            }

            #hamburger-btn {
                display: none !important;
            }

            #sidebar-close-btn {
                display: none !important;
            }
        }

        /* Mobile: sidebar hidden off-screen by default */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.sidebar-open {
                transform: translateX(0);
            }

            #main-content {
                margin-left: 0;
            }
        }

        /* ===== OVERLAY ===== */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        #sidebar-overlay.show {
            display: block;
        }

        /* ===== HAMBURGER BUTTON ===== */
        #hamburger-btn {
            background: none;
            border: none;
            padding: 6px 10px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #343a40;
            transition: background 0.2s;
        }

        #hamburger-btn:hover {
            background-color: #e9ecef;
        }

        #hamburger-btn .bi {
            font-size: 1.4rem;
            line-height: 1;
        }

        /* ===== SIDEBAR CLOSE BTN ===== */
        #sidebar-close-btn {
            background: none;
            border: none;
            padding: 4px 8px;
            border-radius: 6px;
            cursor: pointer;
            color: rgba(255,255,255,0.7);
            font-size: 1.2rem;
            transition: color 0.2s, background 0.2s;
            line-height: 1;
        }

        #sidebar-close-btn:hover {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }

        /* ===== NAV LINKS ===== */
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* ===== NAVBAR ===== */
        #top-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Overlay (mobile only) -->
    <div id="sidebar-overlay"></div>

    <!-- ===== SIDEBAR ===== -->
    <div id="sidebar" class="bg-dark text-white p-4 shadow-lg d-flex flex-column">

        <!-- Sidebar Header -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0 fw-bold">🏕️ ANJALAI ADMIN</h5>
            <button id="sidebar-close-btn" title="Tutup sidebar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <hr class="border-secondary opacity-25 mt-0">

        <!-- Nav Menu -->
        <ul class="nav flex-column gap-2 mt-3 mb-4">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('admin.dashboard') ? 'bg-primary shadow-sm' : '' }}">
                    <i class="bi bi-speedometer2 fs-5"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.cabin.index') }}"
                    class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('admin.cabin.*') ? 'bg-primary shadow-sm' : '' }}">
                    <i class="bi bi-house-door fs-5"></i> Kelola Cabin
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.booking.index') }}"
                    class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center justify-content-between {{ request()->routeIs('admin.booking.*') ? 'bg-primary shadow-sm' : '' }}">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-calendar-check fs-5"></i>
                        <span>Pesanan Online</span>
                    </div>
                    @php
                        $pendingBookingCount = \App\Models\Booking::where('status_booking', 'pending')->count();
                    @endphp
                    @if($pendingBookingCount > 0)
                        <span class="badge bg-danger rounded-pill">{{ $pendingBookingCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.booking_manual.index') }}"
                    class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('admin.booking_manual.*') ? 'bg-primary shadow-sm' : '' }}">
                    <i class="bi bi-person-plus fs-5"></i> Booking Manual
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.wahana.index') }}"
                    class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('admin.wahana.*') ? 'bg-primary shadow-sm' : '' }}">
                    <i class="bi bi-flag fs-5"></i> Kelola Wahana
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.fasilitas_tambahan.index') }}"
                    class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('admin.fasilitas_tambahan*') ? 'bg-primary shadow-sm' : '' }}">
                    <i class="bi bi-box-seam fs-5"></i> Fasilitas Tambahan
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.galeri_umum.index') }}"
                    class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('admin.galeri_umum.*') ? 'bg-primary shadow-sm' : '' }}">
                    <i class="bi bi-images fs-5"></i> Galeri Umum
                </a>
            </li>
        </ul>

        <!-- Logout -->
        <div class="mt-auto">
            <hr class="border-secondary opacity-25">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- ===== KONTEN UTAMA ===== -->
    <div id="main-content">

        {{-- Navbar Atas --}}
        <nav id="top-navbar" class="navbar bg-white shadow-sm px-3 px-md-4">
            <div class="d-flex align-items-center gap-2">
                <!-- Hamburger (mobile only) -->
                <button id="hamburger-btn" aria-label="Toggle sidebar" title="Buka menu">
                    <i class="bi bi-list"></i>
                </button>
                <span class="fw-bold text-primary d-lg-none">🏕️ ANJALAI</span>
            </div>

            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="d-none d-md-block text-end">
                    <div class="fw-bold text-dark small">{{ auth()->user()->name }}</div>
                    <div class="text-muted" style="font-size: 0.7rem;">Administrator</div>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm"
                    style="width: 40px; height: 40px; flex-shrink:0;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </nav>

        {{-- Konten Halaman --}}
        <div class="p-3 p-md-5">
            <div class="mb-4 d-lg-none">
                <h4 class="fw-bold">@yield('title', 'Dashboard')</h4>
            </div>
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const closeBtn     = document.getElementById('sidebar-close-btn');
            const sidebar      = document.getElementById('sidebar');
            const overlay      = document.getElementById('sidebar-overlay');
            const navLinks     = sidebar.querySelectorAll('.nav-link');

            function openSidebar() {
                sidebar.classList.add('sidebar-open');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('sidebar-open');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            function toggleSidebar() {
                sidebar.classList.contains('sidebar-open') ? closeSidebar() : openSidebar();
            }

            hamburgerBtn.addEventListener('click', toggleSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);

            // Auto-close sidebar on mobile when a nav link is clicked
            navLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 992) {
                        closeSidebar();
                    }
                });
            });

            // Reset on window resize (e.g. phone rotated to landscape/desktop)
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 992) {
                    closeSidebar();
                }
            });
        });
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session("error") }}',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif
    @stack('scripts')
</body>

</html>