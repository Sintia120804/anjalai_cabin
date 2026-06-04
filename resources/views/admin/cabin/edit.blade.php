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
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Kapasitas (Orang) <span class="text-danger">*</span></label>
                            <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" value="{{ old('kapasitas', $cabin->kapasitas) }}" required>
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Harga Weekday (Minggu-Kamis) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_weekday" class="form-control @error('harga_weekday') is-invalid @enderror" value="{{ old('harga_weekday', (int)$cabin->harga_weekday) }}" required placeholder="Contoh: 1275000">
                            @error('harga_weekday')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Harga Weekend (Jumat-Sabtu) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_weekend" class="form-control @error('harga_weekend') is-invalid @enderror" value="{{ old('harga_weekend', (int)$cabin->harga_weekend) }}" required placeholder="Contoh: 1500000">
                            @error('harga_weekend')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Harga Couple / 2 Pax <span class="text-muted small">(Opsional)</span></label>
                            <input type="number" name="harga_couple" class="form-control @error('harga_couple') is-invalid @enderror" value="{{ old('harga_couple', $cabin->harga_couple ? (int)$cabin->harga_couple : '') }}" placeholder="Contoh: 975000">
                            @error('harga_couple')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="tersedia" {{ old('status', $cabin->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="tidak tersedia" {{ old('status', $cabin->status) == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Fasilitas Cabin <span class="text-danger">*</span></label>
                        @php
                            $defaultFasilitas = [
                                'Sarapan', 
                                'Balcone', 
                                'Water Heater', 
                                'Water Dispenser', 
                                'Teh dan Kopi', 
                                'Peralatan Mandi', 
                                'Tempat BBQ (belum termasuk gas dan arang)', 
                                'Tempat api unggun'
                            ];
                            $currentFasilitas = $cabin->fasilitas ?? [];
                            // Gabungkan default dengan yang sudah ada di database (untuk tag kustom lama)
                            $allFasilitas = array_unique(array_merge($defaultFasilitas, $currentFasilitas));
                        @endphp
                        <select name="fasilitas[]" class="form-select choices-fasilitas @error('fasilitas') is-invalid @enderror" multiple required data-placeholder="Pilih atau ketik fasilitas baru...">
                            @foreach($allFasilitas as $item)
                                <option value="{{ $item }}" {{ in_array($item, $currentFasilitas) ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Bisa pilih dari daftar atau ketik sendiri lalu tekan Enter.</div>
                        @error('fasilitas')
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

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <style>
        .choices__inner {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            min-height: 44px;
        }
        .choices[data-type*="select-multiple"] .choices__button, .choices[data-type*="text"] .choices__button {
            border-left: 1px solid rgba(255, 255, 255, 0.5);
            margin-left: 5px;
        }
        .choices__list--multiple .choices__item {
            background-color: #0d6efd;
            border: 1px solid #0d6efd;
            border-radius: 4px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var element = document.querySelector('.choices-fasilitas');
            if (element) {
                new Choices(element, {
                    removeItemButton: true,
                    placeholder: true,
                    placeholderValue: 'Pilih atau ketik fasilitas baru...',
                    searchPlaceholderValue: 'Cari fasilitas...',
                    itemSelectText: 'Klik untuk memilih'
                });
            }
        });
    </script>
@endpush
