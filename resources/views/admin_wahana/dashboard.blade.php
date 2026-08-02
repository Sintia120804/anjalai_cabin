@extends('admin_wahana.layouts.app')

@section('title', 'Dashboard Admin Wahana')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">🎢 Dashboard Admin Wahana</h4>
        <p class="text-muted mb-0 small">Selamat datang, <strong>{{ auth()->user()->name }}</strong>! Kelola wahana Anjalai dari sini.</p>
    </div>
    <span class="badge bg-secondary rounded-pill px-3 py-2" style="font-size:0.8rem;">
        <i class="bi bi-person-badge me-1"></i> Admin Wahana
    </span>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Total Wahana</p>
                        <h2 class="fw-bold mb-0">{{ $totalWahana }}</h2>
                        <small class="text-muted">wahana aktif</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                        style="width:56px;height:56px;">
                        <i class="bi bi-flag-fill fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Booking Manual</p>
                        <h2 class="fw-bold mb-0">{{ $totalBooking }}</h2>
                        <small class="text-muted">total transaksi</small>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                        style="width:56px;height:56px;">
                        <i class="bi bi-person-plus fs-3 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Total Pendapatan</p>
                        <h5 class="fw-bold mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h5>
                        <small class="text-muted">dari tiket wahana</small>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                        style="width:56px;height:56px;">
                        <i class="bi bi-cash-coin fs-3 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small">Booking Hari Ini</p>
                        <h2 class="fw-bold mb-0">{{ $bookingHariIni }}</h2>
                        <small class="text-muted">{{ \Carbon\Carbon::now()->format('d M Y') }}</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                        style="width:56px;height:56px;">
                        <i class="bi bi-calendar-check fs-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Booking Terbaru -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Booking Terbaru</h5>
                <a href="{{ route('admin_wahana.booking_manual.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>Pengunjung</th>
                                <th>Wahana</th>
                                <th>Tgl Kunjungan</th>
                                <th>Tiket</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookingTerbaru as $b)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $b->nama_pengunjung }}</div>
                                    <small class="text-muted">{{ $b->no_hp }}</small>
                                </td>
                                <td>{{ $b->wahana->nama ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($b->tanggal_kunjungan)->format('d/m/Y') }}</td>
                                <td><span class="badge bg-primary">{{ $b->jumlah_tiket }}</span></td>
                                <td>
                                    @if($b->status_booking == 'booked')
                                        <span class="badge bg-primary rounded-pill">Booked</span>
                                    @elseif($b->status_booking == 'selesai')
                                        <span class="badge bg-success rounded-pill">Selesai</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Belum ada booking manual.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Wahana -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-flag-fill me-2 text-primary"></i>Daftar Wahana</h5>
            </div>
            <div class="card-body px-4 pb-4">
                @forelse($wahanas as $w)
                <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                    @if($w->foto)
                        <img src="{{ asset('storage/' . $w->foto) }}" class="rounded-3"
                            style="width:45px;height:35px;object-fit:cover;" alt="{{ $w->nama }}">
                    @else
                        <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center"
                            style="width:45px;height:35px;">
                            <i class="bi bi-flag-fill text-primary small"></i>
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <div class="fw-bold small">{{ $w->nama }}</div>
                        <small class="text-muted">{{ $w->durasi ?? '-' }}</small>
                    </div>
                    <span class="fw-bold text-primary small">Rp {{ number_format($w->harga, 0, ',', '.') }}</span>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    Belum ada wahana.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
