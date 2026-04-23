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

        #sidebar {
            width: 260px;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1050;
            scrollbar-width: none;
            /* Hide scrollbar for Chrome/Safari/Firefox */
        }

        #sidebar::-webkit-scrollbar {
            display: none;
            /* Hide scrollbar for Chrome/Safari */
        }

        @media (max-width: 991.98px) {
            #sidebar {
                margin-left: -260px;
                position: fixed;
            }

            #sidebar.active {
                margin-left: 0;
            }

            #sidebar-overlay {
                display: none;
                position: fixed;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                backdrop-filter: blur(2px);
            }

            #sidebar-overlay.show {
                display: block;
            }
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .navbar-brand-mobile {
            display: none;
            font-weight: 700;
        }

        @media (max-width: 991.98px) {
            .navbar-brand-mobile {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <div id="sidebar-overlay"></div>

    <div class="d-flex">

        {{-- ===== SIDEBAR ===== --}}
        <div id="sidebar" class="bg-dark text-white p-4 shadow-lg d-flex flex-column">
            <div class="mb-4 text-center">
                <h5 class="mb-0 fw-bold">🏕️ ANJALAI ADMIN</h5>
            </div>
            <hr class="border-secondary opacity-25">

            <ul class="nav flex-column gap-2 mt-4 mb-4">
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
                <!-- <li class="nav-item">
                    <a href="{{ route('admin.fasilitas_tambahan.index') }}"
                        class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('admin.fasilitas_tambahan*') ? 'bg-primary shadow-sm' : '' }}">
                        <i class="bi bi-box-seam fs-5"></i> Fasilitas Tambahan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.tentang.index') }}"
                        class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('admin.tentang.*') ? 'bg-primary shadow-sm' : '' }}">
                        <i class="bi bi-info-circle fs-5"></i> Kelola Tentang
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.galeri_umum.index') }}"
                        class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ request()->routeIs('admin.galeri_umum.*') ? 'bg-primary shadow-sm' : '' }}">
                        <i class="bi bi-images fs-5"></i> Galeri Umum
                    </a>
                </li> -->
            </ul>

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

        {{-- ===== KONTEN UTAMA ===== --}}
        <div class="flex-grow-1" style="width: 0;">
            {{-- Navbar Atas --}}
            <nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 sticky-top">
                {{-- Sidebar Toggle Removed --}}

                <span class="navbar-brand-mobile mb-0 text-primary">🏕️ ANJALAI</span>

                <div class="ms-auto d-flex align-items-center">
                    <div class="d-none d-md-block text-end me-3">
                        <div class="fw-bold text-dark small">{{ auth()->user()->name }}</div>
                        <div class="text-muted small" style="font-size: 0.7rem;">Administrator</div>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm"
                        style="width: 40px; height: 40px;">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </nav>

            {{-- Konten Halaman --}}
            <div class="p-3 p-md-5">
                <div class="mb-4 d-md-none">
                    <h4 class="fw-bold">@yield('title', 'Dashboard')</h4>
                </div>
                @yield('content')
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('sidebar-toggle');
            const closeBtn = document.getElementById('sidebar-close');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('show');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            if (overlay) overlay.addEventListener('click', toggleSidebar);
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