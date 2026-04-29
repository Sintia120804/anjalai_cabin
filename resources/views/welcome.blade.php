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

<!-- Why Choose Us Section -->
<div class="py-5" style="background-color: #fff;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase tracking-wider">Keunggulan Kami</h6>
            <h2 class="display-5 fw-bold">Kenapa Memilih Anjalai Cabin?</h2>
            <div class="mx-auto bg-primary mt-3 rounded" style="height: 4px; width: 60px;"></div>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-wind fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Ketenangan Hakiki</h4>
                    <p class="text-muted">Jauh dari kebisingan kota, memberikan ketenangan mental yang maksimal di tengah alam.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Kenyamanan Modern</h4>
                    <p class="text-muted">Meskipun berada di tengah alam, fasilitas kabin tetap modern, bersih, dan mewah.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-tags fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Harga Terjangkau</h4>
                    <p class="text-muted">Nikmati pengalaman menginap premium dengan harga yang sangat bersaing dan transparan.</p>
                </div>
            </div>
        </div>
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
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-1">{{ $w->nama }}</h5>
                        <div class="text-primary fw-bold mb-3">Rp {{ number_format($w->harga, 0, ',', '.') }}</div>
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




<!-- ① Fasilitas Unggulan Section -->
<div class="py-5" style="background-color: #fff;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase tracking-wider">Kenyamanan Tamu</h6>
            <h2 class="display-5 fw-bold">Fasilitas Unggulan</h2>
            <div class="mx-auto bg-primary mt-3 rounded" style="height: 4px; width: 60px;"></div>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-lg-3">
                <div class="p-3 bg-light rounded-4 border d-flex align-items-center gap-3 shadow-hover">
                    <div class="bg-white p-2 rounded shadow-sm">
                        <i class="bi bi-cup-hot text-primary fs-3"></i>
                    </div>
                    <span class="fw-bold">Sarapan Pagi</span>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="p-3 bg-light rounded-4 border d-flex align-items-center gap-3 shadow-hover">
                    <div class="bg-white p-2 rounded shadow-sm">
                        <i class="bi bi-thermometer-half text-primary fs-3"></i>
                    </div>
                    <span class="fw-bold">Water Heater</span>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="p-3 bg-light rounded-4 border d-flex align-items-center gap-3 shadow-hover">
                    <div class="bg-white p-2 rounded shadow-sm">
                        <i class="bi bi-fire text-primary fs-3"></i>
                    </div>
                    <span class="fw-bold">BBQ & Api Unggun</span>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="p-3 bg-light rounded-4 border d-flex align-items-center gap-3 shadow-hover">
                    <div class="bg-white p-2 rounded shadow-sm">
                        <i class="bi bi-droplet text-primary fs-3"></i>
                    </div>
                    <span class="fw-bold">Water Dispenser</span>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="p-3 bg-light rounded-4 border d-flex align-items-center gap-3 shadow-hover">
                    <div class="bg-white p-2 rounded shadow-sm">
                        <i class="bi bi-bag-check text-primary fs-3"></i>
                    </div>
                    <span class="fw-bold">Alat Mandi</span>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="p-3 bg-light rounded-4 border d-flex align-items-center gap-3 shadow-hover">
                    <div class="bg-white p-2 rounded shadow-sm">
                        <i class="bi bi-camera-video text-primary fs-3"></i>
                    </div>
                    <span class="fw-bold">Dokumentasi</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ⑤ Map Location Section -->

<div class="py-5" style="background-color: #f8fafc;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase tracking-wider">Lokasi Kami</h6>
            <h2 class="display-5 fw-bold">Temukan Jalan Menuju Ketenangan</h2>
            <div class="mx-auto bg-primary mt-3 rounded" style="height: 4px; width: 60px;"></div>
        </div>
        <div class="rounded-4 overflow-hidden shadow-lg border border-white border-4" style="height: 450px;">
            <iframe 
                src="https://maps.google.com/maps?q=Anjalai%20Nature%20Cabin%20Solok&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        <div class="text-center mt-4">
            <p class="text-muted"><i class="bi bi-geo-alt-fill text-primary me-2"></i> Taluak anjalai, Kec. Lembah Gumanti, Kabupaten Solok, Sumatera Barat 27371</p>
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
                <div class="position-relative overflow-hidden rounded-3 galeri-item" 
                     style="height: 200px; cursor: pointer;"
                     onclick="showGalleryDetail('{{ asset('storage/' .$g->foto) }}', '{{ $g->caption }}')">
                    <img src="{{ asset('storage/' .$g->foto) }}" class="w-100 h-100 object-fit-cover" alt="Galeri" style="transition: transform 0.4s;">
                    <div class="galeri-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50" style="opacity:0; transition: opacity 0.3s;">
                        <div class="text-center">
                            <i class="bi bi-zoom-in text-white fs-2 mb-2"></i>
                            <span class="small text-white d-block px-3">{{ $g->caption }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 opacity-50 fst-italic py-5">Belum ada foto galeri umum.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Gallery Detail Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center">
                    <img src="" id="modalImage" class="img-fluid rounded-3 shadow-lg" style="max-height: 85vh;">
                    <div class="bg-dark bg-opacity-75 text-white p-3 mt-2 rounded-3">
                        <p id="modalCaption" class="mb-0"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showGalleryDetail(imgSrc, caption) {
        document.getElementById('modalImage').src = imgSrc;
        document.getElementById('modalCaption').innerText = caption;
        var myModal = new bootstrap.Modal(document.getElementById('galleryModal'));
        myModal.show();
    }
</script>

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
    .amenity-card {
        transition: all 0.3s ease;
        background-color: #fff;
    }
    .amenity-card:hover {
        transform: translateY(-5px);
        border-color: #2563eb !important;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.1) !important;
    }
</style>
@endpush
