@extends('admin.layouts.app')

@section('title', 'Kelola Tentang Kami')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Kelola Tentang Kami</h5>
        <a href="{{ route('admin.tentang.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg"></i> Tambah Informasi
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Foto/Icon</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tentangs as $index => $tentang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($tentang->foto)
                                <img src="{{ asset('storage/' . $tentang->foto) }}" alt="Tentang" class="rounded" style="width: 80px; height: 50px; object-fit: cover;">
                            @else
                                <span class="badge bg-secondary">No Image</span>
                            @endif
                        </td>
                        <td><div class="fw-bold">{{ $tentang->judul }}</div></td>
                        <td><small class="text-muted line-clamp-2" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;">{{ $tentang->deskripsi }}</small></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.tentang.edit', $tentang->id) }}" class="btn btn-sm btn-info text-white rounded-circle">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.tentang.destroy', $tentang->id) }}" method="POST" onsubmit="return confirm('Hapus informasi ini?')">
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
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada konten Tentang Kami.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
