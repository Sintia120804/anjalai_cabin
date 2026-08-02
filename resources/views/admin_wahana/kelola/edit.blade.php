@extends('admin_wahana.layouts.app')

@section('title', 'Edit Wahana')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin_wahana.kelola.index') }}" class="text-decoration-none text-success">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Wahana
    </a>
    <h5 class="fw-bold mt-2">Edit Wahana & Atraksi</h5>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success">
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin_wahana.kelola.update', $wahana->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Informasi Dasar</label>
                        <hr class="mt-1 mb-3 opacity-10">
                        <label class="form-label fw-bold">Nama Wahana <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="{{ $wahana->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Durasi <span class="text-muted small">(Contoh: 15 Menit)</span></label>
                        <input type="text" name="durasi" class="form-control" value="{{ $wahana->durasi }}" placeholder="Contoh: 15 Menit / 1 Jam">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga Tiket <span class="text-danger">*</span></label>
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
                                <img src="{{ asset('storage/' . $wahana->foto) }}" class="rounded-3 shadow-sm"
                                    style="width: 200px; height: 120px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto.</div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-check-circle me-1"></i> Perbarui Wahana
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
