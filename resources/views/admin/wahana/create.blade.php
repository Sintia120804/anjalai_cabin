@extends('admin.layouts.app')

@section('title', 'Tambah Wahana')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.wahana.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h5 class="fw-bold mt-2">Tambah Wahana & Atraksi Baru</h5>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.wahana.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Wahana <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Kolam Renang Infinity" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan keseruan wahana ini..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Foto Wahana</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text">Maksimal 5MB. Format: JPG, PNG, WEBP.</div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">Simpan Wahana</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
