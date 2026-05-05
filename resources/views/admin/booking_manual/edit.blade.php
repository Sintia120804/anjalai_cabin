@extends('admin.layouts.app')

@section('title', 'Edit Reservasi Manual')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.booking_manual.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
    <h5 class="fw-bold mb-0">Edit Reservasi Manual (Offline)</h5>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm border-start border-4 border-primary">
            <div class="card-body p-4">
                <form action="{{ route('admin.booking_manual.update', $booking_manual->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Informasi Pengunjung</label>
                        <hr class="mt-1 mb-3 opacity-10">
                        <div class="row g-3">
                            <div class="col-md-6 text-dark fw-medium">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pengunjung" class="form-control" placeholder="Contoh: Budi Santoso" required value="{{ old('nama_pengunjung', $booking_manual->nama_pengunjung) }}">
                            </div>
                            <div class="col-md-6 text-dark fw-medium">
                                <label class="form-label">No. WhatsApp/HP <span class="text-danger">*</span></label>
                                <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 081234567890" required value="{{ old('no_hp', $booking_manual->no_hp) }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 mt-5">
                        <label class="form-label fw-bold small text-muted text-uppercase">Informasi Reservasi</label>
                        <hr class="mt-1 mb-3 opacity-10">
                        
                        <div class="mb-3">
                            <label class="form-label">Cabin</label>
                            <input type="text" class="form-control bg-light" value="{{ $booking_manual->cabin->name_cabin }}" readonly>
                            <div class="form-text">Kabun/Cabin tidak dapat diubah dari halaman edit ini.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Check-in</label>
                                <input type="text" class="form-control bg-light" value="{{ \Carbon\Carbon::parse($booking_manual->tanggal_checkin)->format('d M Y H:i') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check-out</label>
                                <input type="text" class="form-control bg-light" value="{{ \Carbon\Carbon::parse($booking_manual->tanggal_checkout)->format('d M Y H:i') }}" readonly>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label">Status Reservasi <span class="text-danger">*</span></label>
                            <select name="status_booking" class="form-select @error('status_booking') is-invalid @enderror" required>
                                <option value="booked" {{ old('status_booking', $booking_manual->status_booking) == 'booked' ? 'selected' : '' }}>Booked</option>
                                <option value="check-in" {{ old('status_booking', $booking_manual->status_booking) == 'check-in' ? 'selected' : '' }}>Check-in</option>
                                <option value="check-out" {{ old('status_booking', $booking_manual->status_booking) == 'check-out' ? 'selected' : '' }}>Check-out</option>
                                <option value="cancelled" {{ old('status_booking', $booking_manual->status_booking) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status_booking')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="p-4 bg-light rounded-4 border border-primary border-opacity-25 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <div>
                                <h6 class="fw-bold mb-0">Total Pembayaran (Offline/Tunai)</h6>
                                <div class="small text-muted">Tetap/Sesuai input awal</div>
                            </div>
                            <div class="h2 fw-bold text-primary mb-0">Rp {{ number_format($booking_manual->total_harga, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
