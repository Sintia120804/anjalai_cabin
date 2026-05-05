@extends('admin.layouts.app')

@section('title', 'Input Reservasi Manual')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.booking_manual.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
    <h5 class="fw-bold mb-0">Tambah Reservasi Secara Manual (Offline)</h5>
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
                        <label class="form-label fw-bold small text-muted text-uppercase">Informasi Room</label>
                        <hr class="mt-1 mb-3 opacity-10">
                        <div class="mb-3">
                            <label class="form-label">Pilih Cabin <span class="text-danger">*</span></label>
                            <select name="cabin_id" id="cabinSelect" class="form-select @error('cabin_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Cabin yang tersedia...</option>
                                @foreach($cabins as $cabin)
                                    <option value="{{ $cabin->id }}" {{ old('cabin_id') == $cabin->id ? 'selected' : '' }}>{{ $cabin->name_cabin }} - Rp {{ number_format($cabin->harga_weekday, 0, ',', '.') }} (WD)</option>
                                @endforeach
                            </select>
                            @error('cabin_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-check form-switch p-3 bg-light rounded-4 border d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <label class="form-check-label fw-bold mb-0" for="isCouple">Paket Couple (2 Pax)</label>
                                        <small class="text-muted d-block mt-1">Gunakan harga khusus couple</small>
                                    </div>
                                    <input class="form-check-input me-0" type="checkbox" name="is_couple" id="isCouple" style="width: 3rem; height: 1.5rem;">
                                </div>
                            </div>
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
                        <div class="mt-4">
                            <label class="form-label">Status Reservasi <span class="text-danger">*</span></label>
                            <select name="status_booking" class="form-select @error('status_booking') is-invalid @enderror" required>
                                <option value="booked" {{ old('status_booking') == 'booked' ? 'selected' : '' }}>Booked</option>
                                <option value="check-in" {{ old('status_booking') == 'check-in' ? 'selected' : '' }}>Check-in</option>
                                <option value="check-out" {{ old('status_booking') == 'check-out' ? 'selected' : '' }}>Check-out</option>
                                <option value="cancelled" {{ old('status_booking') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status_booking')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <i class="bi bi-cloud-arrow-up me-1"></i> Konfirmasi Reservasi Offline
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
        const isCoupleSwitch = document.getElementById('isCouple');
        
        // Data tanggal yang sudah dipesan per cabin
        const bookedDatesByCabin = @json($bookedDates);
        const cabinsData = @json($cabins->keyBy('id'));

        const fpCheckin = flatpickr("#checkinInput", {
            minDate: "today",
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
            onChange: function(selectedDates) {
                fpCheckout.set('minDate', selectedDates[0]);
                calculateTotal();
            }
        });

        const fpCheckout = flatpickr("#checkoutInput", {
            minDate: "today",
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
            onChange: function() {
                calculateTotal();
            }
        });

        cabinSelect.addEventListener('change', function() {
            const cabinId = this.value;
            const cabin = cabinsData[cabinId];
            const disabledDates = bookedDatesByCabin[cabinId] || [];

            // Handle couple toggle visibility/availability
            if (cabin && cabin.harga_couple) {
                isCoupleSwitch.disabled = false;
                isCoupleSwitch.closest('.form-check').classList.remove('opacity-50');
            } else {
                isCoupleSwitch.checked = false;
                isCoupleSwitch.disabled = true;
                isCoupleSwitch.closest('.form-check').classList.add('opacity-50');
            }
            
            // Update tanggal yang dilarang pilih
            fpCheckin.set('disable', disabledDates);
            fpCheckout.set('disable', disabledDates);
            
            // Reset input tanggal jika ganti cabin agar tidak bentrok
            checkinInput.value = '';
            checkoutInput.value = '';
            fpCheckout.set('minDate', 'today');
            calculateTotal();
        });

        isCoupleSwitch.addEventListener('change', calculateTotal);

        function calculateTotal() {
            const inDate = checkinInput.value;
            const outDate = checkoutInput.value;
            const cabinId = cabinSelect.value;
            
            if (inDate && outDate && cabinId) {
                const checkIn = new Date(inDate);
                const checkOut = new Date(outDate);
                const cabin = cabinsData[cabinId];
                const isCouple = isCoupleSwitch.checked;

                let totalPrice = 0;
                let currentDate = new Date(checkIn);
                currentDate.setHours(0,0,0,0);
                let endDateTime = new Date(checkOut);
                endDateTime.setHours(0,0,0,0);

                let nights = 0;
                while (currentDate < endDateTime) {
                    nights++;
                    if (isCouple) {
                        totalPrice += parseFloat(cabin.harga_couple);
                    } else {
                        const day = currentDate.getDay();
                        if (day >= 0 && day <= 4) { // Sun-Thu
                            totalPrice += parseFloat(cabin.harga_weekday);
                        } else { // Fri-Sat
                            totalPrice += parseFloat(cabin.harga_weekend);
                        }
                    }
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                if (nights > 0) {
                    document.getElementById('malamDetail').innerText = nights + ' Malam menginap';
                    document.getElementById('totalPriceManualDisplay').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
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
