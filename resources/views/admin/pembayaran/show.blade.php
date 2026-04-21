@extends('admin.layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.pembayaran.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
    <h5 class="fw-bold mb-0">Rincian Pembayaran #BKG-{{ str_pad($pembayaran->booking->id, 5, '0', STR_PAD_LEFT) }}</h5>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm border-start border-4 border-primary p-4">
            <h6 class="fw-bold text-muted text-uppercase mb-4">Informasi Transaksi</h6>
            <div class="card-body p-0">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">ID Booking</div>
                    <div class="col-sm-8 fw-bold">#BKG-{{ str_pad($pembayaran->booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Nama Pemesan</div>
                    <div class="col-sm-8 h6 mb-0 fw-bold">{{ $pembayaran->booking->user->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Metode Bayar</div>
                    <div class="col-sm-8 fw-bold text-dark">{{ strtoupper($pembayaran->metode_pembayaran ?? 'Midtrans / Belum Ditentukan') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Jumlah Bayar</div>
                    <div class="col-sm-8 h4 fw-bold text-success mb-0">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 text-muted small">Status</div>
                    <div class="col-sm-8">
                        @if($pembayaran->status_pembayaran === 'diterima')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Lunas / Berhasil</span>
                        @elseif($pembayaran->status_pembayaran === 'ditolak')
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">Ditolak</span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill">Menunggu Pembayaran</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4 p-4" id="manualProofSection">
            <h6 class="fw-bold text-muted text-uppercase mb-3">Bukti Pembayaran (Manual)</h6>
            @if($pembayaran->bukti_pembayaran)
                <div class="rounded-4 overflow-hidden border">
                    <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" class="img-fluid w-100" alt="Bukti Transfer">
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-light rounded-pill">
                        <i class="bi bi-zoom-in"></i> Perbesar Gambar
                    </a>
                </div>
            @else
                <div class="text-center py-5 bg-light rounded-4">
                    <i class="bi bi-image fs-1 opacity-10"></i>
                    <p class="text-muted small mt-2">Pihak pengunjung belum mengunggah foto bukti transfer.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-5">
        @if($pembayaran->status_pembayaran !== 'diterima')
        <div class="card border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold text-muted text-uppercase mb-4">Verifikasi Manual</h6>
            <p class="small text-muted mb-4">Anda dapat mengubah status pembayaran secara manual jika pembayaran dilakukan di luar sistem Midtrans (misalnya via Transfer Langsung ke Admin atau Tunai).</p>
            
            <div class="d-grid gap-3">
                <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status_pembayaran" value="diterima">
                    <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-bold" onclick="return confirm('Apakah Anda yakin ingin menerima pembayaran ini?')">
                        <i class="bi bi-check-circle me-1"></i> Terima Pembayaran
                    </button>
                </form>

                <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status_pembayaran" value="ditolak">
                    <button type="submit" class="btn btn-danger w-100 rounded-pill py-3 fw-bold" onclick="return confirm('Apakah Anda yakin ingin menolak pembayaran ini?')">
                        <i class="bi bi-x-circle me-1"></i> Tolak Pembayaran
                    </button>
                </form>

                <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status_pembayaran" value="pending">
                    <button type="submit" class="btn btn-outline-warning w-100 rounded-pill py-2" onclick="return confirm('Set status ke pending?')">
                        <i class="bi bi-clock-history me-1"></i> Set ke Pending
                    </button>
                </form>
            </div>

            <div class="form-text mt-4 small text-warning">
                <i class="bi bi-exclamation-triangle-fill"></i> Catatan: Mengubah status menjadi 'Diterima' juga akan otomatis menyetujui data reservasi yang bersangkutan.
            </div>
        </div>
        @else
        <div class="card border-0 shadow-sm p-4 h-100 bg-success bg-opacity-10 border-success border-opacity-25">
            <div class="text-center py-4">
                <i class="bi bi-check-circle-fill text-success fs-1"></i>
                <h6 class="fw-bold text-success mt-3 text-uppercase">Pembayaran Telah Divalidasi</h6>
                <p class="small text-muted mb-0">Transaksi ini sudah selesai dan tidak dapat diubah lagi melalui fitur verifikasi manual.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
