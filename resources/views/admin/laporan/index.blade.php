@extends('admin.layouts.app')

@section('title', 'Laporan Pendapatan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Laporan Pendapatan</h4>
    <a href="{{ request()->fullUrlWithQuery(['export_pdf' => 1]) }}" class="btn btn-danger rounded-pill px-4 shadow-sm">
        <i class="bi bi-file-earmark-pdf-fill me-2"></i>Export PDF
    </a>
</div>

<!-- Filter Section -->
<div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.dashboard.report') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold">Jenis Laporan</label>
                <select name="filter_type" class="form-select" onchange="this.form.submit()">
                    <option value="weekly" {{ $filterType == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly" {{ $filterType == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ $filterType == 'yearly' ? 'selected' : '' }}>Tahunan</option>
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

<!-- Summary Card -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 h-100" style="background: linear-gradient(135deg, #2563eb, #0ea5e9);">
            <div class="d-flex flex-column justify-content-between h-100">
                <div>
                    <h5 class="opacity-75 mb-1">Total Pendapatan</h5>
                    <div class="display-6 fw-bold mb-3">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white bg-opacity-10 p-2 rounded-3">
                    <p class="mb-0 small text-center">{{ $title }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
            <h6 class="text-muted mb-2">Total Transaksi Online</h6>
            <h3 class="fw-bold">{{ $onlineBookings->count() }} Transaksi</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
            <h6 class="text-muted mb-2">Total Transaksi Manual</h6>
            <h3 class="fw-bold">{{ $manualBookings->count() }} Transaksi</h3>
        </div>
    </div>
</div>

<!-- Table Transaksi -->
<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="fw-bold mb-0">Rincian Transaksi - {{ $title }}</h5>
    </div>
    <div class="card-body px-4 pb-4">
        <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="online-tab" data-bs-toggle="tab" data-bs-target="#online" type="button" role="tab" aria-controls="online" aria-selected="true">Online</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab" aria-controls="manual" aria-selected="false">Manual (Offline)</button>
            </li>
        </ul>
        <div class="tab-content" id="reportTabsContent">
            <!-- Online Tab -->
            <div class="tab-pane fade show active" id="online" role="tabpanel" aria-labelledby="online-tab">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>TGL CHECK-IN</th>
                                <th>PENGUNJUNG</th>
                                <th>CABIN</th>
                                <th>KETERANGAN</th>
                                <th>NOMINAL TRANSAKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($onlineBookings as $b)
                            @php
                                $pembayaran = \App\Models\Pembayaran::where('order_id', $b->order_id)->first();
                                $isCancel = in_array(strtolower($b->status_booking), ['ditolak', 'batal', 'cancelled']);
                                
                                if ($isCancel) {
                                    $label = '<span class="badge bg-danger">Pembatalan</span>';
                                    $nominal = 0;
                                } elseif (!$pembayaran || $pembayaran->status_pembayaran !== 'diterima') {
                                    $label = '<span class="badge bg-secondary">Menunggu Pembayaran</span>';
                                    $nominal = 0;
                                } else {
                                    $nominal = $b->total_harga - $b->sisa_pembayaran;
                                    
                                    if (strtolower($b->jenis_pembayaran) === 'dp') {
                                        if ($b->sisa_pembayaran > 0) {
                                            $label = '<span class="badge bg-warning text-dark">Pembayaran DP 50%</span>';
                                        } else {
                                            $label = '<span class="badge bg-info">Pelunasan Sisa Bayar</span>';
                                            $nominal = $b->total_harga; // Kalau sudah lunas, nominal total masuk
                                        }
                                    } else {
                                        $label = '<span class="badge bg-success">Pembayaran Lunas 100%</span>';
                                        $nominal = $b->total_harga;
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($b->tanggal_checkin)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $b->user->name ?? '-' }}</div>
                                </td>
                                <td>{{ $b->cabin->name_cabin ?? '-' }}</td>
                                <td>{!! $label !!}</td>
                                <td class="fw-bold">Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tidak ada data transaksi online pada periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Manual Tab -->
            <div class="tab-pane fade" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>TGL CHECK-IN</th>
                                <th>PENGUNJUNG</th>
                                <th>CABIN</th>
                                <th>TOTAL HARGA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($manualBookings as $m)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($m->tanggal_checkin)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $m->nama_pengunjung }}</div>
                                </td>
                                <td>{{ $m->cabin->name_cabin ?? '-' }}</td>
                                <td class="fw-bold">Rp {{ number_format($m->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tidak ada data transaksi manual pada periode ini.</td>
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
