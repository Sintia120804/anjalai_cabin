@extends('admin.layouts.app')

@section('title', 'Kelola Pesanan Online')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0 fw-bold">Daftar Pesanan Online</h5>
            <form action="{{ route('admin.booking.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control rounded-pill" placeholder="Cari nama tamu / email..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary rounded-pill px-3"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Kode</th>
                            <th>Pemesan</th>
                            <th>Cabin</th>
                            <th>Check-in / Check-out</th>
                            <th>Total Harga</th>
                            <th>Bukti</th>
                            <th>Status Booking</th>
                            <th>Pembayaran</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $index => $booking)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="fw-bold">BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $booking->user->name }}</div>
                                    <small class="text-muted">{{ $booking->user->email }}</small>
                                </td>
                                <td>{{ $booking->cabin->name_cabin }}</td>
                                <td>
                                    <div class="small fw-medium">
                                        {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d/m/Y') }}</div>
                                    <div class="small text-muted">
                                        {{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d/m/Y') }}</div>
                                </td>
                                <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($booking->pembayaran && $booking->pembayaran->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $booking->pembayaran->bukti_pembayaran) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $booking->pembayaran->bukti_pembayaran) }}" class="rounded shadow-sm" style="width: 45px; height: 30px; object-fit: cover;">
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->status_booking == 'pending')
                                        <span
                                            class="badge bg-warning text-dark border border-warning border-opacity-25 rounded-pill px-3 py-2">Menunggu</span>
                                    @elseif($booking->status_booking == 'diterima')
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2">Diterima</span>
                                    @else
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->pembayaran && $booking->pembayaran->status_pembayaran === 'diterima')
                                        <span class="badge bg-success rounded-pill p-1 px-2 small">Lunas</span>
                                    @else
                                        <span class="badge bg-light text-muted border rounded-pill p-1 px-2 small">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.booking.show', $booking->id) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-25"></i>
                                    Belum ada riwayat pesanan online.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 px-3">
                {{ $bookings->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection