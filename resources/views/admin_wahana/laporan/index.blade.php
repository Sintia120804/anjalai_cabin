@extends('admin_wahana.layouts.app')

@section('title', 'Laporan Wahana')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Laporan Wahana</h4>
    <a href="{{ request()->fullUrlWithQuery(['export_pdf' => 1]) }}"
        class="btn btn-danger rounded-pill px-4 shadow-sm">
        <i class="bi bi-file-earmark-pdf-fill me-2"></i>Export PDF
    </a>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin_wahana.laporan.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold">Jenis Laporan</label>
                <select name="filter_type" class="form-select" onchange="this.form.submit()">
                    <option value="weekly"  {{ $filterType == 'weekly'  ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly" {{ $filterType == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly"  {{ $filterType == 'yearly'  ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>

            @if($filterType == 'weekly')
            <div class="col-md-3">
                <label class="form-label fw-bold">Pilih Tanggal (Dalam Minggu)</label>
                <input type="date" name="week_date" class="form-control" value="{{ $request->week_date ?? date('Y-m-d') }}">
            </div>
            @elseif($filterType == 'yearly')
            <div class="col-md-3">
                <label class="form-label fw-bold">Pilih Tahun</label>
                <select name="year" class="form-select">
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ ($request->year ?? date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            @else
            <div class="col-md-3">
                <label class="form-label fw-bold">Pilih Bulan</label>
                <input type="month" name="month" class="form-control" value="{{ $request->month ?? date('Y-m') }}">
            </div>
            @endif

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 h-100"
            style="background: linear-gradient(135deg, #2563eb, #0ea5e9) !important;">
            <h5 class="opacity-75 mb-1">Total Pendapatan</h5>
            <div class="display-6 fw-bold mb-3">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="bg-white bg-opacity-10 p-2 rounded-3">
                <p class="mb-0 small text-center">{{ $title }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
            <h6 class="text-muted mb-2">Total Wahana Terdaftar</h6>
            <h3 class="fw-bold">{{ $totalWahana }} Wahana</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
            <h6 class="text-muted mb-2">Transaksi Periode Ini</h6>
            <h3 class="fw-bold">{{ $bookings->count() }} Booking</h3>
        </div>
    </div>
</div>

<!-- Tab Laporan -->
<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="fw-bold mb-0">Rincian Laporan — {{ $title }}</h5>
    </div>
    <div class="card-body px-4 pb-4">
        <ul class="nav nav-tabs mb-3" id="laporanTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="booking-tab" data-bs-toggle="tab"
                    data-bs-target="#bookingTab" type="button" role="tab">Rincian Booking</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="wahana-tab" data-bs-toggle="tab"
                    data-bs-target="#wahanaTab" type="button" role="tab">Daftar Wahana</button>
            </li>
        </ul>

        <div class="tab-content" id="laporanTabsContent">
            <!-- Booking Tab -->
            <div class="tab-pane fade show active" id="bookingTab" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tgl Kunjungan</th>
                                <th>Pengunjung</th>
                                <th>Wahana</th>
                                <th>Tiket</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $i => $b)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($b->tanggal_kunjungan)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $b->nama_pengunjung }}</div>
                                    <small class="text-muted">{{ $b->no_hp }}</small>
                                </td>
                                <td>{{ $b->wahana->nama ?? '-' }}</td>
                                <td><span class="badge bg-primary">{{ $b->jumlah_tiket }}</span></td>
                                <td>
                                    @if($b->status_booking == 'cancelled')
                                        <span class="text-muted text-decoration-line-through">Rp {{ number_format($b->total_harga, 0, ',', '.') }}</span>
                                    @else
                                        <strong class="text-success">Rp {{ number_format($b->total_harga, 0, ',', '.') }}</strong>
                                    @endif
                                </td>
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
                                <td colspan="7" class="text-center py-5 text-muted">
                                    Tidak ada data booking pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($bookings->count() > 0)
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="5" class="text-end">Total Pendapatan:</td>
                                <td class="text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Wahana Tab -->
            <div class="tab-pane fade" id="wahanaTab" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Wahana</th>
                                <th>Durasi</th>
                                <th>Harga Tiket</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wahanas as $i => $w)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $w->nama }}</strong></td>
                                <td><span class="badge bg-light text-dark border">{{ $w->durasi ?? '-' }}</span></td>
                                <td><strong class="text-success">Rp {{ number_format($w->harga, 0, ',', '.') }}</strong></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Tidak ada data wahana.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
