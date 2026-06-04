@extends('layouts.app')

@section('content')
    <div class="py-5" style="background-color: #f8fafc;">
        <div class="container py-lg-4">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $wahana->nama }}</li>
                </ol>
            </nav>

            <div class="row g-4">
                <!-- Left Column: Image & Description -->
                <div class="col-lg-8">
                    <!-- Image Card -->
                    <div class="rounded-4 overflow-hidden shadow-sm mb-4" style="height: 500px;">
                        @if($wahana->foto)
                            <img src="{{ asset('storage/' . $wahana->foto) }}" class="w-100 h-100 object-fit-cover"
                                alt="Foto {{ $wahana->nama }}">
                        @else
                            <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white text-center">
                                <div>
                                    <i class="bi bi-image fs-1 d-block mb-3"></i>
                                    <h4>Belum Ada Foto untuk Wahana Ini</h4>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Description Card -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5">
                        <h5 class="fw-bold mb-3">Deskripsi Wahana</h5>
                        <p class="text-muted lh-lg fs-6">
                            {{ $wahana->deskripsi ?? 'Nikmati pengalaman seru dan menyenangkan bersama kami di tengah keindahan alam tropis. Wahana ini sangat cocok untuk melengkapi liburan dan waktu santai Anda selama menginap di Anjalai Cabin.' }}
                        </p>

                        <hr class="my-4 opacity-10">

                        <h5 class="fw-bold mb-3">Informasi Aktivitas</h5>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4 col-6">
                                <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-2 h-100">
                                    <i class="bi bi-shield-fill-check text-primary fs-4"></i>
                                    <div>
                                        <span class="fw-bold d-block small text-dark">Keamanan Utama</span>
                                        <span class="text-muted small" style="font-size: 0.75rem;">Peralatan Lengkap</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-2 h-100">
                                    <i class="bi bi-people-fill text-primary fs-4"></i>
                                    <div>
                                        <span class="fw-bold d-block small text-dark">Pendampingan</span>
                                        <span class="text-muted small" style="font-size: 0.75rem;">Instruktur Ahli</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-2 h-100">
                                    <i class="bi bi-camera-fill text-primary fs-4"></i>
                                    <div>
                                        <span class="fw-bold d-block small text-dark">Spot Foto</span>
                                        <span class="text-muted small" style="font-size: 0.75rem;">Sangat Estetik</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Info & CTA Form -->
                <div class="col-lg-4">
                    <!-- Wahana Title & Pricing Card -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="fw-bold mb-1">{{ $wahana->nama }}</h4>
                                <div class="small">
                                    <span class="text-primary fw-bold"><i class="bi bi-star-fill text-warning me-1"></i> Aktivitas Pilihan</span>
                                    <span class="text-success fw-bold ms-2"><i class="bi bi-check-circle-fill"></i> Siap Dipesan</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary mb-1">Harga Tiket</span>
                                <div class="h5 fw-bold text-primary mb-0">Rp {{ number_format($wahana->harga, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <!-- Aesthetic Details Badge Table -->
                        <div class="card border-0 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%); color: white;">
                            <div class="card-body p-3">
                                <div class="row text-center g-2">
                                    <div class="col-6 border-end border-white border-opacity-25">
                                        <div class="text-uppercase fw-bold mb-1" style="font-size: 0.7rem; opacity: 0.8;"><i class="bi bi-clock me-1"></i> Durasi</div>
                                        <div class="fw-bold small">{{ $wahana->durasi ?? 'Fleksibel' }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-uppercase fw-bold mb-1" style="font-size: 0.7rem; opacity: 0.8;"><i class="bi bi-tag-fill me-1"></i> Kategori</div>
                                        <div class="fw-bold small">Wahana & Rekreasi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sticky CTA -->
                    <div class="sticky-top" style="top: 20px; z-index: 10;">
                        <div class="card border-0 rounded-4 shadow-lg overflow-hidden">
                            <div class="bg-primary p-3 text-white text-center">
                                <h5 class="mb-0 fw-bold">Pesan Sekarang</h5>
                                <p class="mb-0 opacity-75 small" style="font-size: 0.75rem;">Nikmati keseruan liburan Anda</p>
                            </div>
                            <div class="card-body p-4 text-center">
                                <div class="bg-light rounded-4 p-4 mb-4">
                                    <div class="display-6 text-primary mb-3"><i class="bi bi-stars"></i></div>
                                    <h6 class="fw-bold text-dark mb-2">Ingin Mencoba Wahana Ini?</h6>
                                    <p class="small text-muted mb-0">Lengkapi liburan tropis Anda dengan memesan cabin dan menikmati wahana menarik kami selama menginap.</p>
                                </div>

                                <a href="{{ route('welcome') }}#cabins" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm mb-3">
                                    <i class="bi bi-house-door me-2"></i> Pesan Cabin Sekarang
                                </a>

                                <a href="https://wa.me/6285161248112?text=Halo%20Admin,%20saya%20tertarik%20untuk%20bertanya%20mengenai%20wahana%20{{ urlencode($wahana->nama) }}%20di%20Anjalai%20Cabin."
                                    target="_blank" class="btn btn-outline-success w-100 rounded-pill py-2.5 fw-bold mb-2">
                                    <i class="bi bi-whatsapp me-2"></i> Tanya via WhatsApp
                                </a>
                            </div>
                        </div>

                        <div class="mt-4 p-4 rounded-4 bg-info bg-opacity-10 border border-info border-opacity-10">
                            <h6 class="fw-bold text-info mb-2"><i class="bi bi-info-circle-fill me-2"></i>Panduan Pengunjung</h6>
                            <p class="small text-muted mb-0">Wahana ini beroperasi setiap hari pukul 08:00 - 17:00 WIB. Pastikan mematuhi semua petunjuk dari instruktur demi keamanan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Other Wahanas Section --}}
    @if($otherWahanas->count() > 0)
        <div class="py-5 bg-white border-top">
            <div class="container py-3">
                <div class="text-center mb-5">
                    <h6 class="text-primary fw-bold text-uppercase" style="letter-spacing: 2px;">Rekomendasi Lainnya</h6>
                    <h3 class="fw-bold">Wahana Menarik Lainnya</h3>
                    <div class="mx-auto bg-primary mt-3 rounded" style="height: 4px; width: 60px;"></div>
                </div>
                <div class="row g-4">
                    @foreach($otherWahanas as $w)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-light rounded-4 overflow-hidden wahana-card shadow-sm"
                                style="transition: transform 0.3s, box-shadow 0.3s;">
                                <a href="{{ route('wahana.show', $w->id) }}" class="text-decoration-none text-dark">
                                    <div style="height: 200px; overflow: hidden;">
                                        @if($w->foto)
                                            <img src="{{ asset('storage/' . $w->foto) }}" class="w-100 h-100 object-fit-cover wahana-img"
                                                alt="{{ $w->nama }}" style="transition: transform 0.4s;">
                                        @else
                                            <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                                                <i class="bi bi-stars fs-1"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body p-4 text-center">
                                        <h5 class="fw-bold mb-2">{{ $w->nama }}</h5>
                                        <span class="fw-bold text-primary">Rp {{ number_format($w->harga, 0, ',', '.') }}</span>
                                    </div>
                                </a>
                                <div class="card-footer bg-light border-0 p-4 pt-0 text-center">
                                    <a href="{{ route('wahana.show', $w->id) }}" class="btn btn-outline-primary rounded-pill px-4 fw-medium w-100">
                                        <i class="bi bi-eye me-1"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .wahana-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        }

        .wahana-card:hover .wahana-img {
            transform: scale(1.06);
        }
    </style>
@endpush
