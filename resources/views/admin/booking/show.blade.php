@extends('admin.layouts.app')

@section('title', 'Detail Pesanan Online')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.booking.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        <h5 class="fw-bold mb-0">Detail Reservasi #BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</h5>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Informasi Reservasi</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Nama Pemesan</div>
                        <div class="col-sm-8 fw-bold">{{ $booking->user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Email Pemesan</div>
                        <div class="col-sm-8">{{ $booking->user->email ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">No. HP Pemesan</div>
                        <div class="col-sm-8">{{ $booking->user->no_hp ?? '-' }}</div>
                    </div>
                    <hr class="my-4 opacity-10">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Nama Cabin</div>
                        <div class="col-sm-8 fw-bold">{{ $booking->cabin->name_cabin }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Jadwal Menginap</div>
                        <div class="col-sm-8">
                            <span
                                class="fw-bold">{{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M Y') }}</span>
                            s/d
                            <span
                                class="fw-bold">{{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Durasi & Tamu</div>
                        <div class="col-sm-8">
                            {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->diffInDays(\Carbon\Carbon::parse($booking->tanggal_checkout)) }}
                            Malam,
                            {{ $booking->jumlah_tamu }} Orang
                        </div>
                    </div>
                    @php $fasilitas = json_decode($booking->fasilitas_tambahan, true); @endphp
                    @if($fasilitas && count($fasilitas) > 0)
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">Fasilitas Tambahan</div>
                            <div class="col-sm-8">
                                <ul class="list-unstyled mb-0 d-flex flex-column gap-1">
                                    @foreach($fasilitas as $item)
                                        <li><i class="bi bi-check2 text-success me-1"></i> {{ $item['nama'] }} <span
                                                class="badge bg-light text-dark border ms-1">Rp{{ number_format($item['harga'], 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Total Bayar</div>
                        <div class="col-sm-8 h5 fw-bold text-primary">Rp
                            {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-4 text-muted">Status Booking</div>
                        <div class="col-sm-8">
                            @if($booking->status_booking == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Persetujuan</span>
                            @elseif($booking->status_booking == 'diterima')
                                <span class="badge bg-success px-3 py-2 rounded-pill">Diterima</span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill">Ditolak</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-4">
            <!-- Update Status Action Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Aksi Admin</h6>
                    @if($booking->status_booking !== 'diterima')
                        <form action="{{ route('admin.booking.updateStatus', $booking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label small text-muted text-uppercase fw-bold">Ubah Status</label>
                                <select name="status_booking" class="form-select rounded-pill">
                                    <option value="pending" {{ $booking->status_booking == 'pending' ? 'selected' : '' }}>Pending
                                        (Menunggu)</option>
                                    <option value="diterima" {{ $booking->status_booking == 'diterima' ? 'selected' : '' }}>Terima
                                        (Valid)</option>
                                    <option value="ditolak" {{ $booking->status_booking == 'ditolak' ? 'selected' : '' }}>Tolak
                                        (Batalkan)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">Update Status
                                Pesanan</button>
                            @if($booking->pembayaran && $booking->pembayaran->status_pembayaran === 'pending')
                                <div class="alert alert-warning small mt-3 border-0">
                                    Pesanan ini sudah dibayar melalui Payment Gateway tetapi belum masuk konfirmasi lunas otomatis
                                    atau sedang pending. Anda dapat mengubah status ke 'Terima' jika pembayaran sudah valid masuk ke
                                    rekening bisnis.
                                </div>
                            @endif
                        </form>
                    @else
                        <div class="text-center py-2">
                            <i class="bi bi-check-circle-fill text-success fs-2"></i>
                            <p class="small fw-bold text-success mt-2 mb-0">Pesanan Telah Divalidasi</p>
                            <p class="text-muted extra-small">Status pesanan ini sudah final (Diterima).</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Informasi Pembayaran</h6>
                </div>
                <div class="card-body p-4 text-center">
                    @if($booking->pembayaran)
                        <div class="display-6 mb-2"><i class="bi bi-wallet2 text-primary"></i></div>
                        <h5 class="fw-bold mb-1">{{ strtoupper($booking->pembayaran->metode_pembayaran ?? 'Midtrans Gateway') }}
                        </h5>
                        <div class="mb-3">
                            @if($booking->pembayaran->status_pembayaran === 'diterima')
                                <span class="badge bg-success px-3 py-2 rounded-pill">Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                            @endif
                        </div>
                        <div class="fw-bold small">
                            {{ $booking->pembayaran->tanggal_pembayaran ? \Carbon\Carbon::parse($booking->pembayaran->tanggal_pembayaran)->format('d/m/Y H:i') : '-' }}
                        </div>

                        {{-- Bukti Pembayaran --}}
                        @if ($booking->pembayaran->bukti_pembayaran)
                            <hr class="my-3 opacity-10">
                            <h6 class="small fw-bold text-muted text-uppercase mb-2 text-start">Bukti Pembayaran</h6>
                            <div class="rounded border overflow-hidden mb-2">
                                <img src="{{ asset('storage/' . $booking->pembayaran->bukti_pembayaran) }}" class="img-fluid" alt="Bukti Transfer">
                            </div>
                            <a href="{{ asset('storage/' . $booking->pembayaran->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100 rounded-pill">
                                <i class="bi bi-zoom-in"></i> Perbesar Gambar
                            </a>
                        @endif
                    @else
                        <div class="text-muted py-3">Data pembayaran belum tersedia.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection