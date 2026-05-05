@extends('admin.layouts.app')

@section('title', 'Tambah Cabin Baru')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.cabin.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
    <h5 class="fw-bold mb-0">Tambah Cabin Baru</h5>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.cabin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Cabin <span class="text-danger">*</span></label>
                        <input type="text" name="name_cabin" class="form-control @error('name_cabin') is-invalid @enderror" value="{{ old('name_cabin') }}" required placeholder="Contoh: Cabin Family Deluxe">
                        @error('name_cabin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" placeholder="Jelaskan keistimewaan cabin ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Kapasitas (Orang) <span class="text-danger">*</span></label>
                            <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" value="{{ old('kapasitas') }}" required placeholder="Contoh: 4">
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Harga Weekday (Minggu-Kamis) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_weekday" class="form-control @error('harga_weekday') is-invalid @enderror" value="{{ old('harga_weekday') }}" required placeholder="Contoh: 1275000">
                            @error('harga_weekday')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Harga Weekend (Jumat-Sabtu) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_weekend" class="form-control @error('harga_weekend') is-invalid @enderror" value="{{ old('harga_weekend') }}" required placeholder="Contoh: 1500000">
                            @error('harga_weekend')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Harga Couple / 2 Pax <span class="text-muted small">(Opsional)</span></label>
                            <input type="number" name="harga_couple" class="form-control @error('harga_couple') is-invalid @enderror" value="{{ old('harga_couple') }}" placeholder="Contoh: 975000">
                            @error('harga_couple')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="tidak tersedia" {{ old('status') == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Fasilitas Cabin</label>
                        <div id="facilities-container">
                            <div class="input-group mb-2 facility-item">
                                <input type="text" name="fasilitas[]" class="form-control" placeholder="Contoh: Sarapan">
                                <button type="button" class="btn btn-outline-danger remove-facility">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-facility">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Fasilitas
                        </button>
                    </div>

                    <script>
                        document.getElementById('add-facility').addEventListener('click', function() {
                            const container = document.getElementById('facilities-container');
                            const newItem = document.createElement('div');
                            newItem.className = 'input-group mb-2 facility-item';
                            newItem.innerHTML = `
                                <input type="text" name="fasilitas[]" class="form-control" placeholder="Contoh: Sarapan">
                                <button type="button" class="btn btn-outline-danger remove-facility">
                                    <i class="bi bi-trash"></i>
                                </button>
                            `;
                            container.appendChild(newItem);
                        });

                        document.addEventListener('click', function(e) {
                            if (e.target.closest('.remove-facility')) {
                                e.target.closest('.facility-item').remove();
                            }
                        });
                    </script>

                    <hr class="text-muted border-secondary opacity-25">

                    <div class="mb-4 mt-3">
                        <label class="form-label fw-medium">Upload Galeri Foto <span class="text-danger">*</span></label>
                        <div class="alert alert-light border text-muted small mb-2 d-flex align-items-center">
                            <i class="bi bi-info-circle text-primary me-2 fs-5"></i>
                            Kamu bisa memilih lebih dari satu foto sekaligus.
                        </div>
                        <input type="file" name="fotos[]" class="form-control @error('fotos.*') is-invalid @enderror" multiple required accept="image/*">
                        @error('fotos.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maksimal 5MB per foto. Format: JPG, PNG, WEBP.</div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Data Cabin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
