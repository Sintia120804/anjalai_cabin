@extends('admin.layouts.app')

@section('title', 'Edit Wahana')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.wahana.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h5 class="fw-bold mt-2">Edit Wahana & Atraksi</h5>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm border-start border-4 border-info">
            <div class="card-body p-4">
                <form action="{{ route('admin.wahana.update', $wahana->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Informasi Dasar</label>
                        <hr class="mt-1 mb-3 opacity-10">
                        <label class="form-label fw-bold">Nama Wahana <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="{{ $wahana->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" class="form-control" value="{{ $wahana->harga }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4">{{ $wahana->deskripsi }}</textarea>
                    </div>
                    <div class="mb-4 mt-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Foto Wahana</label>
                        <hr class="mt-1 mb-3 opacity-10">
                        @if($wahana->foto)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $wahana->foto) }}" class="rounded shadow-sm" style="width: 200px; height: 120px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto.</div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-info px-4 py-2 rounded-pill fw-bold text-white shadow-sm">Perbarui Wahana</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
