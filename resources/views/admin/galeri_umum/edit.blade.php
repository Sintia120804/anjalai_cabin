@extends('admin.layouts.app')

@section('title', 'Edit Foto Galeri')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.galeri_umum.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h5 class="fw-bold mt-2">Edit Foto Galeri Umum</h5>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm border-start border-4 border-info">
            <div class="card-body p-4">
                <form action="{{ route('admin.galeri_umum.update', $galeri_umum->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4 text-center">
                        <label class="form-label d-block fw-bold mb-3">Pratinjau Foto Saat Ini</label>
                        <img src="{{ asset('storage/' . $galeri_umum->foto) }}" class="rounded shadow-sm img-fluid border p-2" style="max-height: 250px;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Ganti Foto (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text small mt-2">Kosongkan jika tidak ingin mengubah foto.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Caption</label>
                        <input type="text" name="caption" class="form-control" value="{{ $galeri_umum->caption }}" placeholder="Tuliskan deskripsi singkat foto...">
                    </div>

                    <div class="text-end pt-3 border-top">
                        <button type="submit" class="btn btn-info px-5 py-2 rounded-pill fw-bold text-white shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
