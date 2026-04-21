@extends('admin.layouts.app')

@section('title', 'Edit Cabin')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.cabin.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
    <h5 class="fw-bold mb-0">Edit Cabin: {{ $cabin->name_cabin }}</h5>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.cabin.update', $cabin->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Cabin <span class="text-danger">*</span></label>
                        <input type="text" name="name_cabin" class="form-control @error('name_cabin') is-invalid @enderror" value="{{ old('name_cabin', $cabin->name_cabin) }}" required>
                        @error('name_cabin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi', $cabin->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Harga per Malam (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_per_malam" class="form-control @error('harga_per_malam') is-invalid @enderror" value="{{ old('harga_per_malam', (int)$cabin->harga_per_malam) }}" required>
                            @error('harga_per_malam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Kapasitas (Orang) <span class="text-danger">*</span></label>
                            <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" value="{{ old('kapasitas', $cabin->kapasitas) }}" required>
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="tersedia" {{ old('status', $cabin->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="tidak tersedia" {{ old('status', $cabin->status) == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="text-muted border-secondary opacity-25">

                    <h6 class="fw-bold mb-3 mt-4">Manajemen Galeri Foto</h6>
                    <div class="row g-3 mb-4">
                        @forelse($cabin->galeris as $galeri)
                            <div class="col-md-4 col-sm-6">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $galeri->foto) }}" class="rounded w-100 object-fit-cover shadow-sm" style="height: 120px;">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <div class="form-check bg-white rounded px-2 py-1 shadow-sm border">
                                            <input class="form-check-input" type="checkbox" name="delete_fotos[]" value="{{ $galeri->id }}" id="foto{{ $galeri->id }}">
                                            <label class="form-check-label small text-danger fw-bold" for="foto{{ $galeri->id }}">Hapus</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted small fst-italic">Belum ada foto galeri.</div>
                        @endforelse
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Tambah Foto Baru</label>
                        <input type="file" name="fotos[]" class="form-control @error('fotos.*') is-invalid @enderror" multiple accept="image/*">
                        @error('fotos.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                            <i class="bi bi-save me-1"></i> Perbarui Data Cabin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
