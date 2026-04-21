@extends('admin.layouts.app')

@section('title', 'Kelola Fasilitas Tambahan')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Daftar Fasilitas Tambahan (Add-ons)</h5>
            <a href="{{ route('admin.fasilitas_tambahan.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg"></i> Tambah Baru
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Fasilitas</th>
                            <th>Harga (Rp)</th>
                            <th>Deskripsi</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fasilitas as $index => $item)
                            <tr>
                                <td>{{ $fasilitas->firstItem() + $index }}</td>
                                <td>
                                    <div class="fw-bold">{{ $item->nama }}</div>
                                </td>
                                <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td><small class="text-muted line-clamp-2">{{ $item->deskripsi ?: '-' }}</small></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.fasilitas_tambahan.edit', $item->id) }}"
                                            class="btn btn-sm btn-info text-white rounded-circle">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.fasilitas_tambahan.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus fasilitas tambahan ini?')">
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
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada fasilitas tambahan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $fasilitas->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection