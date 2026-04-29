@extends('admin.layouts.app')

@section('title', 'Kelola Wahana')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Daftar Wahana & Atraksi</h5>
        <a href="{{ route('admin.wahana.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg"></i> Tambah Wahana
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Foto</th>
                        <th>Nama Wahana</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wahanas as $index => $wahana)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($wahana->foto)
                                <img src="{{ asset('storage/' . $wahana->foto) }}" alt="Wahana" class="rounded" style="width: 80px; height: 50px; object-fit: cover;">
                            @else
                                <span class="badge bg-secondary">No Photo</span>
                            @endif
                        </td>
                        <td><div class="fw-bold">{{ $wahana->nama }}</div></td>
                        <td><div class="text-primary fw-bold">Rp {{ number_format($wahana->harga, 0, ',', '.') }}</div></td>
                        <td><small class="text-muted line-clamp-2" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;">{{ $wahana->deskripsi }}</small></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.wahana.edit', $wahana->id) }}" class="btn btn-sm btn-info text-white rounded-circle">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.wahana.destroy', $wahana->id) }}" method="POST" onsubmit="return confirm('Hapus wahana ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger rounded-circle">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada data wahana.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
