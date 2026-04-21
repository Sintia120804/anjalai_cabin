@extends('admin.layouts.app')

@section('title', 'Tambah Informasi Tentang')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.tentang.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h5 class="fw-bold mt-2">Tambah Informasi Tentang Resort</h5>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.tentang.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Informasi <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Sejarah Singkat Anjalai" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Konten / Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="6" placeholder="Tuliskan cerita atau informasi detail di sini..." required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Foto Pendukung</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text">Maksimal 5MB. Format: JPG, PNG, WEBP.</div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">Simpan Informasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
