@extends('admin.layouts.app')

@section('title', 'Kelola Unit Cabin')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.cabin.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Cabin
    </a>
</div>

<div class="row">
    <!-- Form Tambah Unit -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Tambah Unit Baru</h5>
                <small class="text-muted">Untuk Kategori: {{ $cabin->name_cabin }}</small>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.cabin.units.store', $cabin->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama/Nomor Unit</label>
                        <input type="text" name="unit_name" class="form-control" placeholder="Contoh: Kamar 01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Awal</label>
                        <select name="status" class="form-select" required>
                            <option value="available">Tersedia (Available)</option>
                            <option value="maintenance">Perbaikan (Maintenance)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Unit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Daftar Unit -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Daftar Unit</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama/Nomor Unit</th>
                                <th>Status</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $index => $unit)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $unit->unit_name }}</td>
                                <td>
                                    @if($unit->status == 'available')
                                        <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Tersedia</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill">Maintenance</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.cabin.units.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus unit ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger rounded-circle" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-door-closed fs-1 d-block mb-3 opacity-25"></i>
                                    Belum ada unit yang ditambahkan.
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
