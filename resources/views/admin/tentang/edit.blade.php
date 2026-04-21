@extends('admin.layouts.app')

@section('title', 'Edit Tentang')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.tentang.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h5 class="fw-bold mt-2">Edit Informasi Tentang Resort</h5>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm border-start border-4 border-info">
            <div class="card-body p-4">
                <form action="{{ route('admin.tentang.update', $tentang->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Informasi <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" value="{{ $tentang->judul }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Konten / Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="6" required>{{ $tentang->deskripsi }}</textarea>
                    </div>

                    <div class="mb-4 mt-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Foto Pendukung</label>
                        <hr class="mt-1 mb-3 opacity-10">
                        @if($tentang->foto)
                            <div class="mb-3 text-center p-3 bg-light rounded-4">
                                <img src="{{ asset('storage/' . $tentang->foto) }}" class="rounded shadow-sm" style="max-height: 200px; width: auto;">
                            </div>
                        @endif
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text mt-1">Kosongkan jika tidak ingin mengubah foto.</div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-info px-4 py-2 rounded-pill fw-bold text-white shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
