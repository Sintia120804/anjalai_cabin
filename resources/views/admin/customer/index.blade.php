@extends('admin.layouts.app')

@section('title', 'Kelola Customer')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-people me-2"></i>Kelola Data Customer</h5>
            <div class="text-muted small">Daftar semua pengunjung yang terdaftar di sistem</div>
        </div>
    </div>
    <div class="card-body px-4 pb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 rounded-start-3">USER</th>
                        <th class="border-0">KONTAK</th>
                        <th class="border-0">TOTAL BOOKING</th>
                        <th class="border-0">BERGABUNG</th>
                        <th class="border-0 rounded-end-3 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($c->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $c->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small">{{ $c->email }}</div>
                            <div class="small text-muted">{{ $c->no_hp ?? '-' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-2">
                                {{ $c->bookings_count }}x
                            </span>
                        </td>
                        <td>{{ $c->created_at->format('d-m-y') }}</td>
                        <td class="text-center">
                            <form action="{{ route('admin.customer.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data customer ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data customer.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
