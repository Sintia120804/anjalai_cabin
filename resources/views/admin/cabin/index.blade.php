@extends('admin.layouts.app')

@section('title', 'Kelola Cabin')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Daftar Cabin</h5>
        <a href="{{ route('admin.cabin.create') }}" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-lg"></i> Tambah Cabin
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Foto Utama</th>
                        <th>Nama Cabin</th>
                        <th>Harga / Malam</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cabins as $index => $cabin)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($cabin->galeris->count() > 0)
                                <img src="{{ asset('storage/' . $cabin->galeris->first()->foto) }}" alt="Foto" class="rounded" style="width: 80px; height: 50px; object-fit: cover;">
                            @else
                                <span class="badge bg-secondary">No Photo</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $cabin->name_cabin }}</div>
                            <small class="text-muted line-clamp-1" style="-webkit-line-clamp: 1; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $cabin->deskripsi }}
                            </small>
                        </td>
                        <td>Rp {{ number_format($cabin->harga_per_malam, 0, ',', '.') }}</td>
                        <td>{{ $cabin->kapasitas }} Orang</td>
                        <td>
                            @if($cabin->status == 'tersedia')
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Tersedia</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">Tidak Tersedia</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.cabin.edit', $cabin->id) }}" class="btn btn-sm btn-info text-white rounded-circle" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.cabin.destroy', $cabin->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus cabin ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger rounded-circle" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-house-exclamation fs-1 d-block mb-3 opacity-25"></i>
                            Belum ada data cabin.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
