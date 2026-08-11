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
                            <input type="text" name="name_cabin"
                                class="form-control @error('name_cabin') is-invalid @enderror"
                                value="{{ old('name_cabin') }}" required placeholder="Contoh: Cabin Family Deluxe">
                            @error('name_cabin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                rows="4" placeholder="Jelaskan keistimewaan cabin ini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-medium">Kapasitas (Orang) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="kapasitas"
                                    class="form-control @error('kapasitas') is-invalid @enderror"
                                    value="{{ old('kapasitas') }}" required placeholder="Contoh: 4">
                                @error('kapasitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Harga Weekday (Minggu-Kamis) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="harga_weekday"
                                    class="form-control @error('harga_weekday') is-invalid @enderror"
                                    value="{{ old('harga_weekday') }}" required placeholder="Contoh: 1275000">
                                @error('harga_weekday')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Harga Weekend (Jumat-Sabtu) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="harga_weekend"
                                    class="form-control @error('harga_weekend') is-invalid @enderror"
                                    value="{{ old('harga_weekend') }}" required placeholder="Contoh: 1500000">
                                @error('harga_weekend')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Harga Couple / 2 Pax <span
                                        class="text-muted small">(Opsional)</span></label>
                                <input type="number" name="harga_couple"
                                    class="form-control @error('harga_couple') is-invalid @enderror"
                                    value="{{ old('harga_couple') }}" placeholder="Contoh: 975000">
                                @error('harga_couple')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="tidak tersedia" {{ old('status') == 'tidak tersedia' ? 'selected' : '' }}>Tidak
                                    Tersedia</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Fasilitas Cabin <span class="text-danger">*</span></label>
                            <select name="fasilitas[]"
                                class="form-select choices-fasilitas @error('fasilitas') is-invalid @enderror" multiple
                                required data-placeholder="Pilih atau ketik fasilitas baru...">
                                <option value="Sarapan">Sarapan</option>
                                <option value="Balcone">Balcone</option>
                                <option value="Water Heater">Water Heater</option>
                                <option value="Water Dispenser">Water Dispenser</option>
                                <option value="Teh dan Kopi">Teh dan Kopi</option>
                                <option value="Peralatan Mandi">Peralatan Mandi</option>
                                <option value="Tempat BBQ (belum termasuk gas dan arang)">Tempat BBQ (belum termasuk gas dan
                                    arang)</option>
                                <option value="Tempat api unggun">Tempat api unggun</option>
                            </select>
                            <div class="form-text">Bisa pilih dari daftar atau ketik sendiri lalu tekan Enter.</div>
                            @error('fasilitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="text-muted border-secondary opacity-25">

                        {{-- SECTION: TAMBAH KAMAR/UNIT --}}
                        <div class="mb-4 mt-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label fw-medium mb-0">
                                    <i class="bi bi-door-open me-1 text-warning"></i>
                                    Kamar / Unit <span class="text-muted small fw-normal">(Opsional, bisa ditambah nanti)</span>
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-warning fw-medium" id="btn-tambah-kamar">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Kamar
                                </button>
                            </div>
                            <div id="kamar-list">
                                {{-- Baris kamar akan ditambahkan di sini secara dinamis --}}
                            </div>
                            <div id="kamar-empty-hint" class="text-muted small text-center py-2 border rounded bg-light">
                                <i class="bi bi-info-circle me-1"></i> Klik "Tambah Kamar" untuk menambahkan unit kamar.
                            </div>
                        </div>

                        <hr class="text-muted border-secondary opacity-25">

                        <div class="mb-4 mt-3">
                            <label class="form-label fw-medium">Upload Galeri Foto <span
                                    class="text-danger">*</span></label>
                            <div class="alert alert-light border text-muted small mb-2 d-flex align-items-center">
                                <i class="bi bi-info-circle text-primary me-2 fs-5"></i>
                                Kamu bisa memilih lebih dari satu foto sekaligus.
                            </div>
                            <input type="file" name="fotos[]" class="form-control @error('fotos.*') is-invalid @enderror"
                                multiple required accept="image/*">
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
            // Choices.js untuk fasilitas
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

            // === Logika Tambah Kamar Dinamis ===
            const btnTambah   = document.getElementById('btn-tambah-kamar');
            const kamarList   = document.getElementById('kamar-list');
            const emptyHint   = document.getElementById('kamar-empty-hint');
            let kamarIndex    = 0;

            function updateHint() {
                emptyHint.style.display = kamarList.children.length === 0 ? 'block' : 'none';
            }

            btnTambah.addEventListener('click', function () {
                const i = kamarIndex++;
                const row = document.createElement('div');
                row.className = 'row g-2 mb-2 align-items-center kamar-row';
                row.innerHTML = `
                    <div class="col-md-7">
                        <input type="text" name="units[${i}][unit_name]"
                               class="form-control form-control-sm"
                               placeholder="Nama kamar, cth: Kamar 01" required>
                    </div>
                    <div class="col-md-4">
                        <select name="units[${i}][status]" class="form-select form-select-sm">
                            <option value="available">Tersedia (Available)</option>
                            <option value="maintenance">Perbaikan (Maintenance)</option>
                        </select>
                    </div>
                    <div class="col-md-1 text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle btn-hapus-kamar" title="Hapus">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                `;
                row.querySelector('.btn-hapus-kamar').addEventListener('click', function () {
                    row.remove();
                    updateHint();
                });
                kamarList.appendChild(row);
                updateHint();
            });

            updateHint();
        });
    </script>
@endpush