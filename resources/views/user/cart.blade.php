@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Keranjang Pesanan</h2>
            <p class="text-muted">Periksa kembali kamar yang ingin Anda pesan sebelum melakukan checkout.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            @if(count($cart) > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        @foreach($cart as $item)
                            <div class="row align-items-center mb-4 pb-4 border-bottom">
                                <div class="col-md-3">
                                    @if($item['foto'])
                                        <img src="{{ asset('storage/' . $item['foto']) }}" alt="{{ $item['cabin_name'] }}" class="img-fluid rounded" style="object-fit: cover; height: 100px; width: 100%;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                            <i class="bi bi-image text-muted fs-3"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <h5 class="fw-bold text-primary mb-1">{{ $item['cabin_name'] }}</h5>
                                    <p class="small text-muted mb-2">
                                        <i class="bi bi-calendar-check"></i> {{ \Carbon\Carbon::parse($item['tanggal_checkin'])->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($item['tanggal_checkout'])->format('d M Y') }}
                                    </p>
                                    {{-- Kontrol Jumlah Kamar --}}
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="text-muted small"><i class="bi bi-door-open"></i> Kamar:</span>
                                        <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="btn btn-outline-secondary btn-sm rounded-circle" style="width:30px;height:30px;padding:0;line-height:1;">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                        </form>
                                        <span class="fw-bold fs-6 mx-1">{{ $item['jumlah_kamar'] }}</span>
                                        <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="btn btn-outline-primary btn-sm rounded-circle" style="width:30px;height:30px;padding:0;line-height:1;">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </form>
                                        <span class="text-muted small ms-1">
                                            <i class="bi bi-people"></i> {{ $item['jumlah_tamu'] }} Tamu
                                        </span>
                                    </div>
                                    @if($item['is_couple'])
                                        <span class="badge bg-danger rounded-pill"><i class="bi bi-heart-fill"></i> Harga Couple</span>
                                    @endif
                                </div>
                                <div class="col-md-3 text-md-end mt-3 mt-md-0 d-flex flex-column justify-content-between h-100">
                                    <h5 class="fw-bold text-dark mb-3">Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</h5>
                                    
                                    <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="mt-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-5 bg-white rounded shadow-sm border-0">
                    <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-dark fw-bold">Keranjang Kosong</h4>
                    <p class="text-muted">Anda belum memilih kamar untuk dipesan.</p>
                    <a href="{{ route('welcome') }}" class="btn btn-primary mt-2 rounded-pill px-4">
                        Cari Kamar
                    </a>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Item</span>
                        <span class="fw-bold">{{ count($cart) }} Kabin</span>
                    </div>
                    
                    <hr>
                    
                    @if(count($cart) > 0)
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small">Pilihan Pembayaran</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="jenis_pembayaran" id="bayarLunas" value="lunas" checked onchange="updateTotal()">
                                    <label class="form-check-label" for="bayarLunas">
                                        Bayar Lunas (100%)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_pembayaran" id="bayarDp" value="dp" onchange="updateTotal()">
                                    <label class="form-check-label" for="bayarDp">
                                        Bayar DP (50%)
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5">Total Bayar Sekarang</span>
                                <span class="fw-bold fs-5 text-primary" id="totalBayarSekarang">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                            <div class="alert alert-info py-2 small d-none" id="sisaPembayaranAlert">
                                Sisa Pembayaran: <strong id="sisaPembayaranText">Rp 0</strong> (Dibayar saat check-in atau pelunasan online)
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">
                                <i class="bi bi-check-circle me-2"></i> Lanjut Checkout
                            </button>
                        </form>

                        <script>
                            function updateTotal() {
                                const total = {{ $totalHarga }};
                                const isDp = document.getElementById('bayarDp').checked;
                                const bayarSekarang = isDp ? (total / 2) : total;
                                const sisa = isDp ? (total / 2) : 0;
                                
                                document.getElementById('totalBayarSekarang').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(bayarSekarang);
                                
                                const alertBox = document.getElementById('sisaPembayaranAlert');
                                if (isDp) {
                                    alertBox.classList.remove('d-none');
                                    document.getElementById('sisaPembayaranText').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(sisa);
                                } else {
                                    alertBox.classList.add('d-none');
                                }
                            }
                        </script>
                    @else
                        <button disabled class="btn btn-secondary w-100 py-3 rounded-pill fw-bold">
                            Lanjut Checkout
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Rekomendasi Cabin Lain -->
    @php
        $rekomendasiCabins = \App\Models\Cabin::inRandomOrder()->take(3)->get();
    @endphp
    @if($rekomendasiCabins->count() > 0)
    <hr class="my-5 opacity-10">
    <div class="mb-5 mt-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h6 class="text-primary fw-bold text-uppercase tracking-wider mb-1">Tambah Pengalaman</h6>
                <h4 class="fw-bold mb-0">Rekomendasi Cabin Untuk Anda</h4>
            </div>
            <a href="{{ url('/#cabins') }}" class="btn btn-outline-primary rounded-pill btn-sm px-3 d-none d-md-inline-flex">Lihat Semua</a>
        </div>
        
        <div class="row g-4">
            @foreach($rekomendasiCabins as $rek)
            <div class="col-md-4">
                <div class="card h-100 border-0 rounded-4 overflow-hidden cabin-card shadow-sm" style="transition: transform 0.3s, box-shadow 0.3s;">
                    <div class="position-relative overflow-hidden" style="height: 200px;">
                        @if($rek->galeris->count() > 0)
                            <img src="{{ asset('storage/' . $rek->galeris->first()->foto) }}" class="w-100 h-100 object-fit-cover cabin-img" alt="{{ $rek->name_cabin }}" style="transition: transform 0.5s ease;">
                        @else
                            <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-2 z-2">
                            <span class="badge bg-white text-dark py-1 px-2 rounded-pill shadow-sm small fw-bold">
                                <i class="bi bi-people-fill text-primary"></i> Maks. {{ $rek->kapasitas }}
                            </span>
                        </div>
                        <div class="position-absolute bottom-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                            <h5 class="text-white fw-bold mb-0">{{ $rek->name_cabin }}</h5>
                        </div>
                    </div>
                    <div class="card-body p-3 bg-white">
                        <p class="text-muted mb-3 small line-clamp-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $rek->deskripsi ?? 'Nikmati kenyamanan kabin premium kami yang dirancang menyatu dengan alam bebas.' }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light border">
                            <div class="fw-bold text-primary">Rp {{ number_format($rek->harga_weekday, 0, ',', '.') }}<span class="small text-muted fw-normal">/mlm</span></div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-3 pt-0">
                        <a href="{{ route('cabin.show', $rek->id) }}" class="btn btn-outline-primary w-100 rounded-pill py-2 small fw-medium btn-hover-fill">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-3 text-center d-md-none">
            <a href="{{ url('/#cabins') }}" class="btn btn-outline-primary rounded-pill px-4">Lihat Semua</a>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* CSS for Cabin Recommendation Cards */
    .cabin-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important; }
    .cabin-card:hover .cabin-img { transform: scale(1.05); }
    .btn-hover-fill { transition: all 0.3s; }
    .btn-hover-fill:hover { background: linear-gradient(135deg, #2563eb, #3b82f6) !important; color: white !important; border-color: transparent !important; }
</style>
@endpush
