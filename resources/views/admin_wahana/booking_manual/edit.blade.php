@extends('admin_wahana.layouts.app')

@section('title', 'Edit Booking Manual Wahana')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin_wahana.booking_manual.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
    <h5 class="fw-bold mb-0">Edit Booking Manual Wahana</h5>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm border-start border-4 border-primary">
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger rounded-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin_wahana.booking_manual.update', $booking_manual->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Pengunjung -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Informasi Pengunjung</label>
                        <hr class="mt-1 mb-3 opacity-10">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pengunjung" class="form-control"
                                    value="{{ old('nama_pengunjung', $booking_manual->nama_pengunjung) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">No. WhatsApp/HP <span class="text-danger">*</span></label>
                                <input type="text" name="no_hp" class="form-control"
                                    value="{{ old('no_hp', $booking_manual->no_hp) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Reservasi (readonly) -->
                    <div class="mb-4 mt-5">
                        <label class="form-label fw-bold small text-muted text-uppercase">Informasi Reservasi</label>
                        <hr class="mt-1 mb-3 opacity-10">

                        <div class="mb-3">
                            <label class="form-label fw-medium">Wahana</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ $booking_manual->wahana->nama ?? '-' }}" readonly>
                            <div class="form-text">Wahana tidak dapat diubah dari halaman edit ini.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Tanggal Kunjungan</label>
                                <input type="text" class="form-control bg-light"
                                    value="{{ \Carbon\Carbon::parse($booking_manual->tanggal_kunjungan)->format('d M Y') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Jumlah Tiket</label>
                                <input type="text" class="form-control bg-light"
                                    value="{{ $booking_manual->jumlah_tiket }} tiket" readonly>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                            <select name="status_booking" class="form-select @error('status_booking') is-invalid @enderror" required>
                                <option value="booked"    {{ old('status_booking', $booking_manual->status_booking) == 'booked'    ? 'selected' : '' }}>Booked</option>
                                <option value="selesai"   {{ old('status_booking', $booking_manual->status_booking) == 'selesai'   ? 'selected' : '' }}>Check-in (Selesai)</option>
                                <option value="cancelled" {{ old('status_booking', $booking_manual->status_booking) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status_booking') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-medium">Catatan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="2"
                                placeholder="Catatan tambahan jika ada...">{{ old('catatan', $booking_manual->catatan) }}</textarea>
                        </div>
                    </div>

                    <!-- Total Harga -->
                    <div class="p-4 bg-light rounded-4 border border-primary border-opacity-25 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0">Total Pembayaran (Offline/Tunai)</h6>
                                <div class="small text-muted">
                                    {{ $booking_manual->jumlah_tiket }} tiket ×
                                    Rp {{ number_format($booking_manual->wahana->harga ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="h2 fw-bold text-primary mb-0">
                                Rp {{ number_format($booking_manual->total_harga, 0, ',', '.') }}
                            </div>
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
