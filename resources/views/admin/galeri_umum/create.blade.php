@extends('admin.layouts.app')

@section('title', 'Tambah Foto Galeri')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.galeri_umum.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h5 class="fw-bold mt-2">Tambah Foto ke Galeri Umum</h5>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.galeri_umum.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4 text-center p-4 border border-dashed rounded-4 bg-light">
                        <i class="bi bi-cloud-arrow-up fs-1 text-primary opacity-50 mb-2 d-block"></i>
                        <label class="form-label fw-bold">Pilih File Gambar <span class="text-danger">*</span></label>
                        <input type="file" name="foto" class="form-control" accept="image/*" required>
                        <div class="form-text mt-2 small">Format: JPG, PNG, WEBP. Maks 10MB.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Caption (Opsional)</label>
                        <input type="text" name="caption" class="form-control" placeholder="Tuliskan deskripsi singkat foto...">
                    </div>

                    <div class="text-end pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold">Unggah Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
