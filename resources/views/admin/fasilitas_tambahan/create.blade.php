@extends('admin.layouts.app')

@section('title', 'Tambah Fasilitas Tambahan')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Tambah Fasilitas Tambahan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.fasilitas_tambahan.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Fasilitas <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}" required placeholder="Contoh: Alat BBQ, Extra Bed">
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                        value="{{ old('harga') }}" min="0" required placeholder="Contoh: 50000">
                    @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3"
                        placeholder="Contoh: Termasuk 1 set alat panggang dan arang">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.fasilitas_tambahan.index') }}"
                        class="btn btn-light rounded-pill px-4">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Fasilitas</button>
                </div>
            </form>
        </div>
    </div>
@endsection