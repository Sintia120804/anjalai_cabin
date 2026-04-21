@extends('admin.layouts.app')

@section('title', 'Kelola Galeri Umum')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Daftar Foto Galeri Resort</h5>
        <a href="{{ route('admin.galeri_umum.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-images me-1"></i> Tambah Foto
        </a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @forelse($galeris as $galeri)
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <img src="{{ asset('storage/' . $galeri->foto) }}" class="card-img-top object-fit-cover" style="height: 180px;">
                    <div class="card-body p-3">
                        <p class="card-text small text-muted mb-3">{{ $galeri->caption ?? 'Tanpa Caption' }}</p>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.galeri_umum.edit', $galeri->id) }}" class="btn btn-sm btn-info text-white rounded-circle">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.galeri_umum.destroy', $galeri->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger rounded-circle">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-images fs-1 d-block mb-3 opacity-25"></i>
                Belum ada foto galeri umum. Silakan tambahkan foto suasana resort.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
