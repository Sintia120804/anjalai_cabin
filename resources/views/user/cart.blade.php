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
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total Bayar</span>
                        <span class="fw-bold fs-5 text-primary">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>

                    @if(count($cart) > 0)
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">
                                <i class="bi bi-check-circle me-2"></i> Lanjut Checkout
                            </button>
                        </form>
                    @else
                        <button disabled class="btn btn-secondary w-100 py-3 rounded-pill fw-bold">
                            Lanjut Checkout
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
