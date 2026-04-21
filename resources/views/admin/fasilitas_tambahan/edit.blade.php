@extends('admin.layouts.app')

@section('title', 'Edit Fasilitas Tambahan')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Edit Fasilitas Tambahan: {{ $fasilitas_tambahan->nama }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.fasilitas_tambahan.update', $fasilitas_tambahan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Fasilitas <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $fasilitas_tambahan->nama) }}" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                        value="{{ old('harga', intval($fasilitas_tambahan->harga)) }}" min="0" required>
                    @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                        rows="3">{{ old('deskripsi', $fasilitas_tambahan->deskripsi) }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.fasilitas_tambahan.index') }}"
                        class="btn btn-light rounded-pill px-4">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update Fasilitas</button>
                </div>
            </form>
        </div>
    </div>
@endsection