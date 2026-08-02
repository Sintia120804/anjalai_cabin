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

    <div class="row mb-5">
        <!-- Total Revenue Card -->
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4 h-100"
                style="background: linear-gradient(135deg, #2563eb, #0ea5e9);">
                <div class="d-flex flex-column justify-content-between h-100">
                    <div>
                        <h5 class="opacity-75 mb-1">Total Estimasi Pendapatan</h5>
                        <div class="display-6 fw-bold mb-3">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    </div>

                    <div class="bg-white bg-opacity-10 p-3 rounded-3">
                        <p class="mb-1 small opacity-75">Pendapatan Bulan Ini
                            ({{ \Carbon\Carbon::now()->translatedFormat('F') }})</p>
                        <div class="d-flex justify-content-between align-items-end">
                            <h4 class="fw-bold mb-0">Rp {{ number_format($revenueCurrentMonth, 0, ',', '.') }}</h4>
                            @if($revenueGrowth > 0)
                                <span class="badge bg-success bg-opacity-25 text-white border border-success"><i
                                        class="bi bi-arrow-up-short"></i> +{{ number_format($revenueGrowth, 1) }}%</span>
                            @elseif($revenueGrowth < 0)
                                <span class="badge bg-danger bg-opacity-25 text-white border border-danger"><i
                                        class="bi bi-arrow-down-short"></i> {{ number_format($revenueGrowth, 1) }}%</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-25 text-white border border-secondary">- 0%</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-4">
                <h6 class="fw-bold mb-3">Grafik Pendapatan Tahun {{ date('Y') }}</h6>
                <div style="position: relative; height:250px; width:100%">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Cabin Status -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 px-2">📦 Status Cabin Hari Ini (Real-time)</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        @foreach($cabinStatuses as $cabin)
                            <div class="col-md-3">
                                <div
                                    class="p-3 rounded-4 border {{ $cabin->available_units > 0 ? 'border-success bg-success bg-opacity-10' : 'border-danger bg-danger bg-opacity-10' }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6
                                            class="fw-bold mb-0 {{ $cabin->available_units > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $cabin->name_cabin }}</h6>
                                        @if($cabin->available_units > 0)
                                            <span class="badge bg-success rounded-pill">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill">Penuh</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted">
                                        <div class="d-flex justify-content-between">
                                            <span>Tersedia:</span>
                                            <span class="fw-bold">{{ $cabin->available_units }} Unit</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Terisi:</span>
                                            <span class="fw-bold">{{ $cabin->booked_units }} Unit</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-top mt-1 pt-1">
                                            <span>Total:</span>
                                            <span class="fw-bold">{{ $cabin->total_units }} Unit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Latest Online Bookings -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 px-2">Pesanan Online Terbaru</h6>
                    <a href="{{ route('admin.booking.index') }}" class="btn btn-sm text-primary small fw-bold">Lihat
                        Semua</a>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle small">
                            <tbody>
                                @forelse($latestBookings as $b)
                                    <tr>
                                        <td width="50">
                                            <div class="bg-primary bg-opacity-10 text-primary small p-2 rounded-circle text-center"
                                                style="width: 35px; height: 35px; line-height: 20px;">
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
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4 small">Belum ada pesanan online.</td>
                                    </tr>
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
                    <a href="{{ route('admin.booking_manual.index') }}" class="btn btn-sm text-success small fw-bold">Lihat
                        Semua</a>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle small">
                            <tbody>
                                @forelse($latestManuals as $m)
                                    <tr>
                                        <td width="50">
                                            <div class="bg-success bg-opacity-10 text-success small p-2 rounded-circle text-center"
                                                style="width: 35px; height: 35px; line-height: 20px;">
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
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4 small">Belum ada booking offline.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Latest Registered Users -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 px-2">👥 Tamu Terbaru Daftar</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle small">
                            <tbody>
                                @forelse($latestUsers as $user)
                                    <tr>
                                        <td width="50">
                                            <div class="bg-warning bg-opacity-10 text-warning small p-2 rounded-circle text-center fw-bold"
                                                style="width: 35px; height: 35px; line-height: 20px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </td>
                                        <td class="text-end text-muted small">
                                            {{ $user->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4 small">Belum ada tamu terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Cabins -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 px-2">🏆 Cabin Terpopuler</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle small">
                            <tbody>
                                @forelse($popularCabins as $cabin)
                                    <tr>
                                        <td width="50">
                                            <div class="bg-info bg-opacity-10 text-info small p-2 rounded-circle text-center fw-bold"
                                                style="width: 35px; height: 35px; line-height: 20px;">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $cabin->name_cabin }}</div>
                                            <div class="text-muted small">Kapasitas: {{ $cabin->kapasitas }} Orang</div>
                                        </td>
                                        <td class="text-end fw-bold text-primary">
                                            {{ $cabin->total_bookings }} x Dipesan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4 small">Belum ada data pesanan cabin.
                                        </td>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const revenueData = @json($monthlyRevenue);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: revenueData,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000) + ' Jt';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000) + ' Rb';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush