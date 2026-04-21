@extends('admin.layouts.app')

@section('title', 'Input Booking Manual')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.booking_manual.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
    <h5 class="fw-bold mb-0">Tambah Pemesanan Secara Manual (Offline)</h5>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm border-start border-4 border-success">
            <div class="card-body p-4">
                <form action="{{ route('admin.booking_manual.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Informasi Pengunjung</label>
                        <hr class="mt-1 mb-3 opacity-10">
                        <div class="row g-3">
                            <div class="col-md-6 text-dark fw-medium">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pengunjung" class="form-control" placeholder="Contoh: Budi Santoso" required value="{{ old('nama_pengunjung') }}">
                            </div>
                            <div class="col-md-6 text-dark fw-medium">
                                <label class="form-label">No. WhatsApp/HP <span class="text-danger">*</span></label>
                                <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 081234567890" required value="{{ old('no_hp') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 mt-5">
                        <label class="form-label fw-bold small text-muted text-uppercase">Informasi Akomodasi</label>
                        <hr class="mt-1 mb-3 opacity-10">
                        <div class="mb-3">
                            <label class="form-label">Pilih Cabin <span class="text-danger">*</span></label>
                            <select name="cabin_id" id="cabinSelect" class="form-select @error('cabin_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Cabin yang tersedia...</option>
                                @foreach($cabins as $cabin)
                                    <option value="{{ $cabin->id }}" data-price="{{ (int)$cabin->harga_per_malam }}" {{ old('cabin_id') == $cabin->id ? 'selected' : '' }}>{{ $cabin->name_cabin }} - Rp {{ number_format($cabin->harga_per_malam, 0, ',', '.') }}/malam</option>
                                @endforeach
                            </select>
                            @error('cabin_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Check-in <span class="text-danger">*</span></label>
                                <input type="text" name="tanggal_checkin" id="checkinInput" class="form-control @error('tanggal_checkin') is-invalid @enderror" required placeholder="YYYY-MM-DD" value="{{ old('tanggal_checkin') }}">
                                @error('tanggal_checkin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check-out <span class="text-danger">*</span></label>
                                <input type="text" name="tanggal_checkout" id="checkoutInput" class="form-control @error('tanggal_checkout') is-invalid @enderror" required placeholder="YYYY-MM-DD" value="{{ old('tanggal_checkout') }}">
                                @error('tanggal_checkout')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="priceCalculationSection" class="p-4 bg-light rounded-4 border border-success border-opacity-25 mb-4 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <div>
                                <h6 class="fw-bold mb-0">Estimasi Total Pembayaran (Offline/Tunai)</h6>
                                <div id="malamDetail" class="small text-muted">0 Malam menginap</div>
                            </div>
                            <div class="h2 fw-bold text-success mb-0" id="totalPriceManualDisplay">Rp 0</div>
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
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkinInput = document.getElementById('checkinInput');
        const checkoutInput = document.getElementById('checkoutInput');
        const cabinSelect = document.getElementById('cabinSelect');
        const priceSection = document.getElementById('priceCalculationSection');

        const fpCheckin = flatpickr("#checkinInput", {
            minDate: "today",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates) {
                fpCheckout.set('minDate', selectedDates[0]);
                calculateTotal();
            }
        });

        const fpCheckout = flatpickr("#checkoutInput", {
            minDate: "today",
            dateFormat: "Y-m-d",
            onChange: function() {
                calculateTotal();
            }
        });

        cabinSelect.addEventListener('change', calculateTotal);

        function calculateTotal() {
            const inDate = checkinInput.value;
            const outDate = checkoutInput.value;
            const selectedCabinOp = cabinSelect.options[cabinSelect.selectedIndex];
            
            if (inDate && outDate && selectedCabinOp.value) {
                const checkIn = new Date(inDate);
                const checkOut = new Date(outDate);
                const diffTime = Math.abs(checkOut - checkIn);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const harga = selectedCabinOp.dataset.price;

                if (diffDays > 0) {
                    const total = diffDays * harga;
                    document.getElementById('malamDetail').innerText = diffDays + ' Malam menginap';
                    document.getElementById('totalPriceManualDisplay').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                    document.getElementById('priceCalculationSection').classList.remove('d-none');
                } else {
                    document.getElementById('priceCalculationSection').classList.add('d-none');
                }
            } else {
                document.getElementById('priceCalculationSection').classList.add('d-none');
            }
        }
    });
</script>
@endpush
