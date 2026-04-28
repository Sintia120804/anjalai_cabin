@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="position-relative overflow-hidden text-center text-white min-vh-100 d-flex align-items-center justify-content-center" 
     style="background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.9)), url('https://images.unsplash.com/photo-1542315191-76673dd49733?q=80&w=2070') center/cover fixed;">
    <div class="col-md-8 p-lg-5 mx-auto my-5 z-1">
        <span class="badge bg-primary px-3 py-2 rounded-pill text-uppercase tracking-wider mb-4 shadow-sm" style="letter-spacing: 2px;">
            <i class="bi bi-star-fill text-warning me-1"></i> Premium Escape
        </span>
        <h1 class="display-3 fw-bold mb-3 lh-tight" style="text-shadow: 0 4px 20px rgba(0,0,0,0.5);">
            Menyatu dengan Alam, <br>
            <span class="text-primary" style="background: -webkit-linear-gradient(45deg, #4e9af1, #0ea5e9); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Tanpa Batas.</span>
        </h1>
        <p class="lead fw-light mb-5 fs-4 text-light opacity-75">
            Rasakan ketenangan Anjalai Cabin. Dirancang eksklusif untuk memberikan kenyamanan modern di tengah kesejukan alam tropis.
        </p>
        <div class="d-flex justify-content-center gap-3 mb-5">
            <a class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-lg fw-bold" href="#cabins" style="background: linear-gradient(135deg, #2563eb, #3b82f6); border:none;">
                Eksplorasi Cabin
            </a>
            <a class="btn btn-outline-light btn-lg rounded-pill px-5 py-3 fw-medium backdrop-blur" href="#galeri" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);">
                <i class="bi bi-images me-2"></i> Lihat Galeri
            </a>
        </div>

        <!-- Cek Ketersediaan Form -->
        <div class="bg-white p-3 p-md-4 rounded-4 shadow-lg text-start mx-auto" style="max-width: 850px; backdrop-filter: blur(10px); background: rgba(255,255,255,0.95); transform: translateY(50px); transition: transform 0.3s ease;">
            <form action="{{ route('welcome') }}#cabins" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-5">
                        <label for="checkin" class="form-label text-dark fw-bold small"><i class="bi bi-calendar-check text-primary me-1"></i> Check-in</label>
                        <input type="date" class="form-control form-control-lg bg-light border-0 shadow-none text-dark" id="checkin" name="checkin" value="{{ request('checkin') }}" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="checkout" class="form-label text-dark fw-bold small"><i class="bi bi-calendar-x text-primary me-1"></i> Check-out</label>
                        <input type="date" class="form-control form-control-lg bg-light border-0 shadow-none text-dark" id="checkout" name="checkout" value="{{ request('checkout') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #2563eb, #3b82f6); border:none; height: 48px;">
                            <i class="bi bi-search me-2"></i>
                            <span>Cari</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Wave Shape Divider -->
    <div class="position-absolute bottom-0 w-100 overflow-hidden" style="line-height: 0;">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width: calc(100% + 1.3px); height: 80px;">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,119.93,197.36,97.77,256.47,78.29,310.87,60.84,321.39,56.44Z" fill="#f8fafc"></path>
        </svg>
    </div>
</div>

<!-- ① Cabin List Section (paling atas setelah hero) -->
<div class="py-5" id="cabins" style="background-color: #f8fafc;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase tracking-wider">Akomodasi Kami</h6>
            <h2 class="display-5 fw-bold text-dark">Pilih Ruang Santaimu</h2>
            <div class="mx-auto bg-primary mt-3 mb-4 rounded" style="height: 4px; width: 60px;"></div>
            
            @if(request('checkin') && request('checkout'))
                <div class="d-inline-flex align-items-center bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill border border-primary border-opacity-25 shadow-sm">
                    <i class="bi bi-calendar-check-fill me-2"></i>
                    <span>Tersedia untuk: <strong>{{ \Carbon\Carbon::parse(request('checkin'))->format('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse(request('checkout'))->format('d M Y') }}</strong></span>
                    <a href="{{ route('welcome') }}#cabins" class="ms-3 text-primary text-decoration-none fw-bold">
                        <i class="bi bi-x-circle-fill"></i> Reset
                    </a>
                </div>
            @endif
        </div>

        <div class="row g-4">
            @forelse($cabins as $cabin)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 rounded-4 overflow-hidden cabin-card" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: transform 0.3s, box-shadow 0.3s;">
                        <div class="position-relative overflow-hidden" style="height: 250px;">
                            @if($cabin->galeris->count() > 0)
                                <img src="{{ asset('storage/' .$cabin->galeris->first()->foto) }}" class="w-100 h-100 object-fit-cover cabin-img" alt="{{ $cabin->name_cabin }}">
                            @else
                                <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 end-0 m-3 z-2">
                                <span class="badge bg-white text-dark py-2 px-3 rounded-pill shadow-sm fw-bold">
                                    Maks. {{ $cabin->kapasitas }} Orang
                                </span>
                            </div>
                            <div class="position-absolute bottom-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                <h4 class="text-white fw-bold mb-0">{{ $cabin->name_cabin }}</h4>
                            </div>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <p class="text-muted mb-4 small line-clamp-3">
                                {{ $cabin->deskripsi ?? 'Nikmati kenyamanan kabin premium kami yang dirancang menyatu dengan alam bebas.' }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light border">
                                <div class="text-muted small">Harga Mulai</div>
                                <div class="fs-5 fw-bold text-primary">Rp {{ number_format($cabin->harga_per_malam, 0, ',', '.') }}<span class="fs-6 text-muted fw-normal">/mlm</span></div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 p-4 pt-0">
                            <a href="{{ route('cabin.show', ['cabin' => $cabin->id, 'checkin' => request('checkin'), 'checkout' => request('checkout')]) }}" class="btn btn-outline-primary w-100 rounded-pill py-2 fw-medium btn-hover-fill">
                                Lihat Detail & Pesan
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-4 shadow-sm border border-light">
                        <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                        <h4 class="text-dark fw-bold">Tidak ada Cabin tersedia</h4>
                        <p class="text-muted">Maaf, tidak ada unit yang tersedia untuk tanggal yang Anda pilih. Silakan coba tanggal lain.</p>
                        <a href="{{ route('welcome') }}#cabins" class="btn btn-primary rounded-pill px-4 mt-2 shadow-sm">Lihat Semua Cabin</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ② Wahana Section -->
<div class="py-5 bg-white" id="wahana">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase tracking-wider">Aktivitas & Wahana</h6>
            <h2 class="display-5 fw-bold">Keseruan Tanpa Henti</h2>
            <div class="mx-auto bg-primary mt-3 rounded" style="height: 4px; width: 60px;"></div>
        </div>

        <div class="row g-4">
            @forelse($wahanas as $w)
            <div class="col-md-4">
                <div class="card h-100 border-0 bg-light rounded-4 overflow-hidden" style="transition: 0.3s;">
                    <div style="height: 200px; overflow: hidden;">
                        @if($w->foto)
                            <img src="{{ asset('storage/' .$w->foto) }}" class="w-100 h-100 object-fit-cover" alt="{{ $w->nama }}">
                        @else
                            <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                                <i class="bi bi-stars fs-1"></i>
                            </div>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-2">{{ $w->nama }}</h5>
                        <p class="text-muted small mb-0">{{ $w->deskripsi }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4 text-muted opacity-50">
                <i class="bi bi-bicycle fs-1 d-block mb-3"></i>
                Belum ada wahana tersedia.
            </div>
            @endforelse
        </div>
    </div>
</div>


<!-- ④ Gallery Section (paling bawah) -->
<div class="py-5" id="galeri" style="background-color: #0f172a; color: white;">
    <div class="container py-5 text-center">
        <div class="mb-5">
            <h6 class="text-primary fw-bold text-uppercase tracking-wider">Galeri Resort</h6>
            <h2 class="display-5 fw-bold text-white">Sudut Estetik Kami</h2>
            <p class="opacity-75">Intip setiap sudut keindahan yang siap menyambut kedatangan Anda.</p>
        </div>

        <div class="row g-3">
            @forelse($galeriUmum as $g)
            <div class="col-6 col-md-3">
                <div class="position-relative overflow-hidden rounded-3 galeri-item" style="height: 200px; cursor: pointer;">
                    <img src="{{ asset('storage/' .$g->foto) }}" class="w-100 h-100 object-fit-cover" alt="Galeri" style="transition: transform 0.4s;">
                    <div class="galeri-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50" style="opacity:0; transition: opacity 0.3s;">
                        <span class="small text-white px-3">{{ $g->caption }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 opacity-50 fst-italic py-5">Belum ada foto galeri umum.</div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .cabin-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    .cabin-card:hover .cabin-img {
        transform: scale(1.05);
        transition: transform 0.5s ease;
    }
    .cabin-img {
        transition: transform 0.5s ease;
    }
    .btn-hover-fill {
        transition: all 0.3s;
    }
    .btn-hover-fill:hover {
        background: linear-gradient(135deg, #2563eb, #3b82f6) !important;
        color: white !important;
        border-color: transparent !important;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }

    /* Galeri hover */
    .galeri-item:hover img {
        transform: scale(1.08);
    }
    .galeri-item:hover .galeri-overlay {
        opacity: 1 !important;
    }
</style>
@endpush
