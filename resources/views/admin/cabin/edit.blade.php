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
                    <form action="{{ route('admin.cabin.update', $cabin->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-medium">Nama Cabin <span class="text-danger">*</span></label>
                            <input type="text" name="name_cabin"
                                class="form-control @error('name_cabin') is-invalid @enderror"
                                value="{{ old('name_cabin', $cabin->name_cabin) }}" required>
                            @error('name_cabin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                rows="4">{{ old('deskripsi', $cabin->deskripsi) }}</textarea>
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
                                    value="{{ old('kapasitas', $cabin->kapasitas) }}" required>
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
                                    value="{{ old('harga_weekday', (int) $cabin->harga_weekday) }}" required
                                    placeholder="Contoh: 1275000">
                                @error('harga_weekday')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Harga Weekend (Jumat-Sabtu) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="harga_weekend"
                                    class="form-control @error('harga_weekend') is-invalid @enderror"
                                    value="{{ old('harga_weekend', (int) $cabin->harga_weekend) }}" required
                                    placeholder="Contoh: 1500000">
                                @error('harga_weekend')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Harga Couple / 2 Pax <span
                                        class="text-muted small">(Opsional)</span></label>
                                <input type="number" name="harga_couple"
                                    class="form-control @error('harga_couple') is-invalid @enderror"
                                    value="{{ old('harga_couple', $cabin->harga_couple ? (int) $cabin->harga_couple : '') }}"
                                    placeholder="Contoh: 975000">
                                @error('harga_couple')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="tersedia" {{ old('status', $cabin->status) == 'tersedia' ? 'selected' : '' }}>
                                    Tersedia</option>
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
                            <select name="fasilitas[]"
                                class="form-select choices-fasilitas @error('fasilitas') is-invalid @enderror" multiple
                                required data-placeholder="Pilih atau ketik fasilitas baru...">
                                @foreach($allFasilitas as $item)
                                    <option value="{{ $item }}" {{ in_array($item, $currentFasilitas) ? 'selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Bisa pilih dari daftar atau ketik sendiri lalu tekan Enter.</div>
                            @error('fasilitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SECTION: KELOLA KAMAR/UNIT --}}
                        <hr class="text-muted border-secondary opacity-25">

                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <label class="form-label fw-medium mb-0">
                                    <i class="bi bi-door-open me-1 text-warning"></i>
                                    Kamar / Unit
                                    <span class="badge bg-warning text-dark ms-1">{{ $cabin->units->count() }} Unit</span>
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-warning fw-medium" id="btn-tambah-kamar">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Kamar Baru
                                </button>
                            </div>

                            {{-- Kamar yang sudah ada --}}
                            @if($cabin->units->count() > 0)
                                <div class="mb-3">
                                    <p class="small text-muted mb-2 fw-medium">Unit yang sudah ada:</p>
                                    @foreach($cabin->units as $unit)
                                        <div class="row g-2 mb-2 align-items-center existing-unit-row">
                                            <div class="col-md-7">
                                                <input type="text"
                                                    name="existing_units[{{ $unit->id }}][unit_name]"
                                                    class="form-control form-control-sm"
                                                    value="{{ $unit->unit_name }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <select name="existing_units[{{ $unit->id }}][status]" class="form-select form-select-sm">
                                                    <option value="available" {{ $unit->status == 'available' ? 'selected' : '' }}>Tersedia (Available)</option>
                                                    <option value="maintenance" {{ $unit->status == 'maintenance' ? 'selected' : '' }}>Perbaikan (Maintenance)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input border-danger" type="checkbox"
                                                        name="delete_units[]" value="{{ $unit->id }}"
                                                        id="delUnit{{ $unit->id }}"
                                                        title="Centang untuk hapus unit ini"
                                                        onchange="toggleDeleteUnit(this)">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="form-text text-danger">
                                        <i class="bi bi-info-circle me-1"></i> Centang kolom paling kanan untuk menghapus unit tersebut.
                                    </div>
                                </div>
                            @endif

                            {{-- Area tambah unit baru secara dinamis --}}
                            <div id="kamar-list"></div>
                            <div id="kamar-empty-hint" class="text-muted small text-center py-2 border rounded bg-light {{ $cabin->units->count() > 0 ? 'd-none' : '' }}">
                                <i class="bi bi-info-circle me-1"></i> Klik "Tambah Kamar Baru" untuk menambahkan unit kamar.
                            </div>
                        </div>

                        <hr class="text-muted border-secondary opacity-25">

                        <h6 class="fw-bold mb-3 mt-4">Manajemen Galeri Foto</h6>
                        <div class="row g-3 mb-4">
                            @forelse($cabin->galeris as $galeri)
                                <div class="col-md-4 col-sm-6">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $galeri->foto) }}"
                                            class="rounded w-100 object-fit-cover shadow-sm" style="height: 120px;">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <div class="form-check bg-white rounded px-2 py-1 shadow-sm border">
                                                <input class="form-check-input" type="checkbox" name="delete_fotos[]"
                                                    value="{{ $galeri->id }}" id="foto{{ $galeri->id }}">
                                                <label class="form-check-label small text-danger fw-bold"
                                                    for="foto{{ $galeri->id }}">Hapus</label>
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
                            <input type="file" name="fotos[]" class="form-control @error('fotos.*') is-invalid @enderror"
                                multiple accept="image/*">
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

        .choices[data-type*="select-multiple"] .choices__button,
        .choices[data-type*="text"] .choices__button {
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
        document.addEventListener('DOMContentLoaded', function () {
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

            // === Logika Tambah Kamar Baru Dinamis ===
            const btnTambah = document.getElementById('btn-tambah-kamar');
            const kamarList = document.getElementById('kamar-list');
            const emptyHint = document.getElementById('kamar-empty-hint');
            let kamarIndex  = 0;

            function updateHint() {
                if (emptyHint) {
                    emptyHint.style.display = kamarList.children.length === 0 ? 'block' : 'none';
                }
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
                if (emptyHint) emptyHint.style.display = 'none';
            });

            updateHint();
        });

        // Coret baris unit yang akan dihapus
        function toggleDeleteUnit(checkbox) {
            const row = checkbox.closest('.existing-unit-row');
            const inputs = row.querySelectorAll('input[type="text"], select');
            if (checkbox.checked) {
                row.style.opacity = '0.4';
                row.style.textDecoration = 'line-through';
                inputs.forEach(el => el.disabled = true);
            } else {
                row.style.opacity = '1';
                row.style.textDecoration = 'none';
                inputs.forEach(el => el.disabled = false);
            }
        }
    </script>
@endpush