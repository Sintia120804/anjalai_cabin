@extends('admin.layouts.app')

@section('title', 'Booking Manual (Offline)')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0 fw-bold">Daftar Booking Manual (Offline)</h5>
        <a href="{{ route('admin.booking_manual.create') }}" class="btn btn-success border-0 rounded-pill px-4">
            <i class="bi bi-plus-lg"></i> Tambah Tamu Offline
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
                        <th>Cabin</th>
                        <th>Check-in / Check-out</th>
                        <th>Total Bayar</th>
                        <th>Admin Penginput</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($book_manuals as $index => $manual)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><div class="fw-bold">{{ $manual->nama_pengunjung }}</div></td>
                        <td>{{ $manual->no_hp }}</td>
                        <td>{{ $manual->cabin->name_cabin }}</td>
                        <td>
                            <div class="small fw-medium">{{ \Carbon\Carbon::parse($manual->tanggal_checkin)->format('d/m/Y') }}</div>
                            <div class="small text-muted">{{ \Carbon\Carbon::parse($manual->tanggal_checkout)->format('d/m/Y') }}</div>
                        </td>
                        <td><div class="fw-bold text-success">Rp {{ number_format($manual->total_harga, 0, ',', '.') }}</div></td>
                        <td><span class="badge bg-light text-dark">{{ $manual->admin->name }}</span></td>
                        <td>
                            <form action="{{ route('admin.booking_manual.destroy', $manual->id) }}" method="POST" onsubmit="return confirm('Hapus data booking offline ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-person-slash fs-1 d-block mb-3 opacity-25"></i>
                            Belum ada riwayat booking manual/offline.
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
    <p class="small text-muted mb-0">Booking Manual digunakan untuk mencatat tamu yang memesan langsung di tempat (Walk-in). Sistem akan otomatis memblokir tanggal ini dari pemesanan online.</p>
</div>
@endsection
