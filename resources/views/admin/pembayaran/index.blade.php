@extends('admin.layouts.app')

@section('title', 'Manajemen Pembayaran')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="mb-0 fw-bold">Daftar Transaksi Pembayaran</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>ID Booking</th>
                        <th>Nama Pemesan</th>
                        <th>Metode</th>
                        <th>Jumlah Bayar</th>
                        <th>Tanggal Bayar</th>
                        <th>Status</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $index => $pembayaran)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="fw-bold">BKG-{{ str_pad($pembayaran->booking->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                        <td>{{ $pembayaran->booking->user->name }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="text-uppercase small fw-bold me-2">{{ $pembayaran->metode_pembayaran ?? 'Manual' }}</span>
                                @if($pembayaran->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-light border p-1 rounded" title="Lihat Bukti">
                                        <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" style="width: 30px; height: 30px; object-fit: cover;" class="rounded">
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td><div class="fw-bold">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</div></td>
                        <td>{{ $pembayaran->tanggal_pembayaran ? \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            @if($pembayaran->status_pembayaran === 'diterima')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2">Lunas</span>
                            @elseif($pembayaran->status_pembayaran === 'ditolak')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2">Ditolak</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3 py-2">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.pembayaran.show', $pembayaran->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @if($pembayaran->status_pembayaran !== 'diterima')
                                    <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_pembayaran" value="diterima">
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3" onclick="return confirm('Terima pembayaran ini?')" title="Terima">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_pembayaran" value="ditolak">
                                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3" onclick="return confirm('Tolak pembayaran ini?')" title="Tolak">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-cash fs-1 d-block mb-3 opacity-25"></i>
                            Belum ada riwayat pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
