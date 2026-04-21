@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                    <i class="bi bi-calendar-check fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Booking Online</h6>
                    <h4 class="fw-bold mb-0 text-dark">{{ $totalBookingOnline }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-3">
                    <i class="bi bi-person-plus fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Booking Manual</h6>
                    <h4 class="fw-bold mb-0 text-dark">{{ $totalBookingManual }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center">
                <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle me-3">
                    <i class="bi bi-house-door fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Total Cabin</h6>
                    <h4 class="fw-bold mb-0 text-dark">{{ $totalCabin }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle me-3">
                    <i class="bi bi-person fs-4"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Total Pengunjung</h6>
                    <h4 class="fw-bold mb-0 text-dark">{{ $totalUser }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue and Analytics -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4" style="background: linear-gradient(135deg, #2563eb, #0ea5e9);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="opacity-75 mb-1">Total Estimasi Pendapatan</h5>
                    <div class="display-4 fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    <p class="mb-0 mt-2 small opacity-75">Gabungan pembayaran lunas online dan seluruh data reservasi manual offline.</p>
                </div>
                <div class="col-md-4 text-md-end mt-4 mt-md-0 d-none d-md-block">
                    <i class="bi bi-currency-dollar fs-1 opacity-25" style="font-size: 8rem !important;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Latest Online Bookings -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 px-2">Pesanan Online Terbaru</h6>
                <a href="{{ route('admin.booking.index') }}" class="btn btn-sm text-primary small fw-bold">Lihat Semua</a>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <tbody>
                            @forelse($latestBookings as $b)
                            <tr>
                                <td width="50">
                                    <div class="bg-primary bg-opacity-10 text-primary small p-2 rounded-circle text-center" style="width: 35px; height: 35px; line-height: 20px;">
                                        {{ substr($b->user->name, 0, 1) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $b->user->name }}</div>
                                    <div class="text-muted small">{{ $b->cabin->name_cabin }}</div>
                                </td>
                                <td class="text-end fw-bold">Rp {{ number_format($b->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-4 small">Belum ada pesanan online.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Manual Bookings -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 px-2">Booking Offline Terbaru</h6>
                <a href="{{ route('admin.booking_manual.index') }}" class="btn btn-sm text-success small fw-bold">Lihat Semua</a>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <tbody>
                            @forelse($latestManuals as $m)
                            <tr>
                                <td width="50">
                                    <div class="bg-success bg-opacity-10 text-success small p-2 rounded-circle text-center" style="width: 35px; height: 35px; line-height: 20px;">
                                        <i class="bi bi-person-check-fill"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $m->nama_pengunjung }}</div>
                                    <div class="text-muted small">{{ $m->cabin->name_cabin }}</div>
                                </td>
                                <td class="text-end fw-bold">Rp {{ number_format($m->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-4 small">Belum ada booking offline.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
