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
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,119.93,197.36,97.77,256.47,78.29,310.87,60.84,321.39,56.44Z" fill="#f1f5f9"></path>
        </svg>
    </div>
</div>

<!-- Tentang Kami Section -->
<div class="py-5" id="tentang" style="background-color: #f1f5f9;">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1470770841072-f978cf4d019e?q=80&w=2070" class="img-fluid rounded-4 shadow-lg" alt="About Anjalai">
                    <div class="position-absolute -bottom-10 -end-10 bg-white p-4 rounded-4 shadow-lg d-none d-md-block" style="bottom: -30px; right: -30px; width: 250px;">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-geo-alt-fill text-primary fs-3 me-2"></i>
                            <h6 class="fw-bold mb-0">Lokasi Strategis</h6>
                        </div>
                        <p class="small text-muted mb-0">Hanya 2 jam dari hiruk pikuk kota, temukan surga tersembunyi Anda.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h6 class="text-primary fw-bold text-uppercase tracking-wider">Tentang Kami</h6>
                <h2 class="display-5 fw-bold mb-4">Kembali ke Akar, <br>Menemukan Ketenangan.</h2>
                <div class="mb-4">
                    @forelse($tentangs as $t)
                        <div class="mb-4">
                            <h5 class="fw-bold d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-primary me-2"></i> {{ $t->judul }}
                            </h5>
                            <p class="text-muted">{{ $t->deskripsi }}</p>
                        </div>
                    @empty
                        <p class="text-muted">Anjalai Cabin didirikan dengan misi menyediakan tempat peristirahatan yang menghormati kemurnian alam tanpa mengorbankan kenyamanan mewah bintang lima.</p>
                        <p class="text-muted">Setiap unit kabin dirancang secara arsitektural untuk memberikan sirkulasi udara alami dan pencahayaan maksimal dari sinar matahari pagi.</p>
                    @endforelse
                </div>
                <a href="#cabins" class="btn btn-primary rounded-pill px-4 py-2">Lihat Kamar</a>
            </div>
        </div>
    </div>
</div>

<!-- Wahana Section -->
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
                <div class="card h-100 border-0 bg-light rounded-4 overflow-hidden shadow-hover" style="transition: 0.3s;">
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
            <div class="col-md-4">
                <div class="text-center p-4">
                    <i class="bi bi-bicycle fs-1 text-primary opacity-25"></i>
                    <h6 class="mt-3">Tracking Sepeda</h6>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Gallery Section -->
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
                <div class="position-relative overflow-hidden rounded-3" style="height: 200px; cursor: pointer;">
                    <img src="{{ asset('storage/' .$g->foto) }}" class="w-100 h-100 object-fit-cover transition-transform" alt="Galeri">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-0 hover-opacity-100 bg-dark bg-opacity-50 transition-all">
                        <span class="small">{{ $g->caption }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 opacity-50 fst-italic py-5">Belum ada foto galeri umum.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Cabin List Section -->
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
                    <a href="{{ route('welcome') }}#cabins" class="ms-3 text-primary text-decoration-none fw-bold hover-scale">
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
                            <!-- Image overlay dark gradient -->
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
                    <div class="p-5 bg-white rounded-4 shadow-sm border border-light animate__animated animate__fadeIn">
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
</style>
@endpush
