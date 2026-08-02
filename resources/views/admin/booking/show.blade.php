@extends('admin.layouts.app')

@section('title', 'Detail Pesanan Online')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.booking.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        <h5 class="fw-bold mb-0">Detail Reservasi NO-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }} <span class="text-primary ms-2">({{ $booking->order_id }})</span></h5>
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
                    
                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-door-open-fill me-1"></i> Rincian Kamar yang Dipesan ({{ count($allBookings) }} Kamar)</h6>
                    
                    <div class="table-responsive mb-4 border rounded-3 overflow-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th class="py-3 ps-3 small fw-bold text-muted">NAMA KAMAR / KATEGORI</th>
                                    <th class="py-3 small fw-bold text-muted">JADWAL MENGINAP</th>
                                    <th class="py-3 small fw-bold text-muted text-center">DURASI</th>
                                    <th class="py-3 text-end pe-3 small fw-bold text-muted">HARGA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalOrder = 0; @endphp
                                @foreach($allBookings as $b)
                                    @php
                                        $checkin = \Carbon\Carbon::parse($b->tanggal_checkin);
                                        $checkout = \Carbon\Carbon::parse($b->tanggal_checkout);
                                        $nights = ceil($checkin->diffInHours($checkout) / 24);
                                        $totalOrder += $b->total_harga;
                                    @endphp
                                    <tr class="border-bottom">
                                        <td class="py-3 ps-3">
                                            <div class="fw-bold text-dark">{{ $b->cabin->name_cabin }}</div>
                                            <span class="badge bg-light text-muted border extra-small">#BKG-{{ str_pad($b->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td class="py-3">
                                            <span class="small d-block fw-bold text-secondary">{{ $checkin->format('d M Y H:i') }}</span>
                                            <span class="small text-muted d-block my-1 text-center" style="width: fit-content; margin-left: 2px;">s/d</span>
                                            <span class="small d-block fw-bold text-secondary">{{ $checkout->format('d M Y H:i') }}</span>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="small d-block fw-bold text-dark">{{ $nights }} Malam</span>
                                            <span class="small text-muted d-block">{{ $b->jumlah_tamu }} Orang</span>
                                        </td>
                                        <td class="py-3 text-end pe-3">
                                            <span class="fw-bold text-primary">Rp {{ number_format($b->total_harga, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Total Bayar</div>
                        <div class="col-sm-8 h4 fw-bold text-primary">Rp {{ number_format($totalOrder, 0, ',', '.') }}</div>
                    </div>
                    
                    <div class="row mb-0">
                        <div class="col-sm-4 text-muted">Status Booking</div>
                        <div class="col-sm-8">
                            @if($booking->status_booking == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Persetujuan</span>
                            @elseif($booking->status_booking == 'diterima')
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $checkin = \Carbon\Carbon::parse($booking->tanggal_checkin);
                                    $checkout = \Carbon\Carbon::parse($booking->tanggal_checkout);
                                @endphp
                                @if($now->greaterThanOrEqualTo($checkout))
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">Selesai</span>
                                @elseif($now->greaterThanOrEqualTo($checkin) && $now->lessThan($checkout))
                                    <span class="badge bg-success px-3 py-2 rounded-pill">Check-in</span>
                                @else
                                    <span class="badge bg-primary px-3 py-2 rounded-pill">Booking</span>
                                @endif
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
                    @if(!in_array($booking->status_booking, ['diterima', 'ditolak']))
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
                    @elseif($booking->status_booking === 'diterima')
                        <div class="text-center py-2">
                            <i class="bi bi-check-circle-fill text-success fs-2"></i>
                            <p class="small fw-bold text-success mt-2 mb-0">Pesanan Telah Divalidasi</p>
                            <p class="text-muted extra-small">Status pesanan ini sudah final (Diterima).</p>
                        </div>
                    @elseif($booking->status_booking === 'ditolak')
                        <div class="text-center py-2">
                            <i class="bi bi-x-circle-fill text-danger fs-2"></i>
                            <p class="small fw-bold text-danger mt-2 mb-0">Pesanan Telah Ditolak</p>
                            <p class="text-muted extra-small">Status pesanan ini sudah final (Ditolak).</p>
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
                                <span class="badge bg-success px-3 py-2 rounded-pill">Lunas & Terkonfirmasi</span>
                            @elseif($booking->pembayaran->status_pembayaran === 'menunggu_konfirmasi')
                                <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Lunas (Menunggu Konfirmasi)</span>
                            @elseif($booking->pembayaran->status_pembayaran === 'ditolak')
                                <span class="badge bg-danger px-3 py-2 rounded-pill">Ditolak</span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                            @endif

                            @if($booking->jenis_pembayaran == 'dp' && $booking->sisa_pembayaran > 0)
                                <div class="mt-3 alert alert-warning py-2 text-start small">
                                    <strong>Status Pembayaran: DP 50%</strong><br>
                                    Terdapat sisa pembayaran yang harus dilunasi pengunjung (dibayar saat Check-in).
                                </div>
                                <form action="{{ route('admin.booking.pelunasan', $booking->id) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini sebagai Lunas (Pengunjung telah membayar sisa tagihan)?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-success w-100 rounded-pill fw-bold mt-2">
                                        <i class="bi bi-check2-circle"></i> Tandai Sudah Lunas
                                    </button>
                                </form>
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