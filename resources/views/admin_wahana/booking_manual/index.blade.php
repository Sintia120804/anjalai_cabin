@extends('admin_wahana.layouts.app')

@section('title', 'Booking Manual Wahana')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0 fw-bold">Daftar Booking Manual Wahana (Offline)</h5>
        <a href="{{ route('admin_wahana.booking_manual.create') }}" class="btn btn-success border-0 rounded-pill px-4">
            <i class="bi bi-plus-lg"></i> Tambah Pengunjung Offline
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Pengunjung</th>
                        <th>No. HP</th>
                        <th>Wahana</th>
                        <th>Tgl Kunjungan</th>
                        <th>Jumlah Tiket</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Admin Penginput</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $index => $booking)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><div class="fw-bold">{{ $booking->nama_pengunjung }}</div></td>
                        <td>{{ $booking->no_hp }}</td>
                        <td>
                            <div class="fw-bold">{{ $booking->wahana->nama ?? '-' }}</div>
                            <small class="text-muted">{{ $booking->wahana->durasi ?? '' }}</small>
                        </td>
                        <td>
                            <div class="small fw-medium">
                                {{ \Carbon\Carbon::parse($booking->tanggal_kunjungan)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td><span class="badge bg-primary rounded-pill">{{ $booking->jumlah_tiket }} tiket</span></td>
                        <td><div class="fw-bold text-success">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div></td>
                        <td>
                            @if($booking->status_booking == 'booked')
                                <span class="badge bg-primary rounded-pill">Booked</span>
                            @elseif($booking->status_booking == 'selesai')
                                <span class="badge bg-success rounded-pill">Selesai</span>
                            @elseif($booking->status_booking == 'cancelled')
                                <span class="badge bg-danger rounded-pill">Cancelled</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $booking->admin->name ?? '-' }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin_wahana.booking_manual.edit', $booking->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin_wahana.booking_manual.destroy', $booking->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus data booking wahana ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-flag fs-1 d-block mb-3 opacity-25"></i>
                            Belum ada riwayat booking manual wahana.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 alert alert-info bg-opacity-10 border-info border-opacity-10 rounded-4">
    <h6 class="fw-bold text-info"><i class="bi bi-info-circle-fill me-2"></i>Informasi</h6>
    <p class="small text-muted mb-0">Booking Manual Wahana digunakan untuk mencatat pengunjung yang membeli tiket wahana langsung di tempat (offline/tunai).</p>
</div>
@endsection
