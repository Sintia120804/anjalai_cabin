@extends('admin_wahana.layouts.app')

@section('title', 'Input Booking Manual Wahana')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin_wahana.booking_manual.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
    <h5 class="fw-bold mb-0">Tambah Booking Wahana Manual (Offline)</h5>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm border-start border-4 border-success">
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin_wahana.booking_manual.store') }}" method="POST">
                    @csrf

                    <!-- Informasi Pengunjung -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Informasi Pengunjung</label>
                        <hr class="mt-1 mb-3 opacity-10">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pengunjung" class="form-control @error('nama_pengunjung') is-invalid @enderror"
                                    placeholder="Contoh: Budi Santoso" required value="{{ old('nama_pengunjung') }}">
                                @error('nama_pengunjung') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">No. WhatsApp/HP <span class="text-danger">*</span></label>
                                <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                    placeholder="Contoh: 081234567890" required value="{{ old('no_hp') }}">
                                @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tiket -->
                    <div class="mb-4 mt-5">
                        <label class="form-label fw-bold small text-muted text-uppercase">Informasi Tiket Wahana</label>
                        <hr class="mt-1 mb-3 opacity-10">

                        <div class="mb-3">
                            <label class="form-label fw-medium">Pilih Wahana <span class="text-danger">*</span></label>
                            <select name="wahana_id" id="wahanaSelect" class="form-select @error('wahana_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih wahana yang tersedia...</option>
                                @foreach($wahanas as $w)
                                    <option value="{{ $w->id }}"
                                        data-harga="{{ $w->harga }}"
                                        {{ old('wahana_id') == $w->id ? 'selected' : '' }}>
                                        {{ $w->nama }} — Rp {{ number_format($w->harga, 0, ',', '.') }} / tiket
                                        {{ $w->durasi ? '(' . $w->durasi . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('wahana_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Jumlah Tiket <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_tiket" id="jumlahTiket"
                                    class="form-control @error('jumlah_tiket') is-invalid @enderror"
                                    min="1" max="999" value="{{ old('jumlah_tiket', 1) }}" required>
                                @error('jumlah_tiket') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Tanggal Kunjungan <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_kunjungan"
                                    class="form-control @error('tanggal_kunjungan') is-invalid @enderror"
                                    min="{{ date('Y-m-d') }}" value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}" required>
                                @error('tanggal_kunjungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                            <select name="status_booking" class="form-select @error('status_booking') is-invalid @enderror" required>
                                <option value="booked" {{ old('status_booking') == 'booked' ? 'selected' : '' }}>Booked</option>
                                <option value="selesai" {{ old('status_booking') == 'selesai' ? 'selected' : '' }}>Check-in (Selesai)</option>
                                <option value="cancelled" {{ old('status_booking') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status_booking') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-medium">Catatan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="2"
                                placeholder="Catatan tambahan jika ada...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    <!-- Estimasi Total -->
                    <div id="priceSection" class="p-4 bg-light rounded-4 border border-success border-opacity-25 mb-4 d-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0">Estimasi Total Pembayaran (Tunai)</h6>
                                <div id="tiketDetail" class="small text-muted">0 tiket</div>
                            </div>
                            <div class="h2 fw-bold text-success mb-0" id="totalHargaDisplay">Rp 0</div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success px-5 py-2 fw-bold rounded-pill">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Konfirmasi Booking Offline
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Sidebar -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-flag-fill text-primary me-2"></i>Daftar Harga Wahana</h6>
                @foreach($wahanas as $w)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <div class="fw-bold small">{{ $w->nama }}</div>
                        <small class="text-muted">{{ $w->durasi ?? '-' }}</small>
                    </div>
                    <span class="fw-bold text-primary small">Rp {{ number_format($w->harga, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wahanaSelect  = document.getElementById('wahanaSelect');
        const jumlahTiket   = document.getElementById('jumlahTiket');
        const priceSection  = document.getElementById('priceSection');
        const tiketDetail   = document.getElementById('tiketDetail');
        const totalDisplay  = document.getElementById('totalHargaDisplay');

        function calculateTotal() {
            const opt    = wahanaSelect.options[wahanaSelect.selectedIndex];
            const harga  = parseFloat(opt?.dataset?.harga || 0);
            const jumlah = parseInt(jumlahTiket.value || 0);

            if (harga > 0 && jumlah > 0) {
                const total = harga * jumlah;
                tiketDetail.innerText = jumlah + ' tiket × Rp ' + new Intl.NumberFormat('id-ID').format(harga);
                totalDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                priceSection.classList.remove('d-none');
            } else {
                priceSection.classList.add('d-none');
            }
        }

        wahanaSelect.addEventListener('change', calculateTotal);
        jumlahTiket.addEventListener('input', calculateTotal);

        // Auto-calculate on load if old values exist
        calculateTotal();
    });
</script>
@endpush
