@extends('layouts.app')

@section('content')
    <div class="py-5" style="background-color: #f8fafc; min-vh-100;">
        <div class="container py-lg-4">
            <div class="row g-4 align-items-end mb-5">
                <div class="col">
                    <h6 class="text-primary fw-bold text-uppercase tracking-wider">Selamat Datang</h6>
                    <h2 class="display-6 fw-bold mb-0">{{ Auth::user()->name }}</h2>
                </div>
                <div class="col-auto">
                    <div class="p-3 bg-white rounded-4 shadow-sm border border-light d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded">
                            <i class="bi bi-calendar-check fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold mb-0">{{ $bookings->count() }}</div>
                            <div class="small text-muted">Total Reservasi</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h4 class="fw-bold mb-4">Riwayat Pemesanan Anda</h4>

                    @forelse($bookings as $booking)
                            <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden booking-card">
                                <div class="row g-0">
                                    <div class="col-md-3 position-relative">
                                        @if($booking->cabin->galeris->count() > 0)
                                            <img src="{{ asset('storage/' .$booking->cabin->galeris->first()->foto) }}"
                                                class="w-100 h-100 object-fit-cover" alt="{{ $booking->cabin->name_cabin }}">
                                        @else
                                            <div
                                                class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                                                <i class="bi bi-image fs-1 opacity-50"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <span
                                                class="badge {{ $booking->status_booking == 'diterima' ? 'bg-success' : ($booking->status_booking == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }} shadow-sm px-3 py-2 rounded-pill">
                                                {{ strtoupper($booking->status_booking) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h5 class="fw-bold mb-1">{{ $booking->cabin->name_cabin }}</h5>
                                                    <div class="text-muted small">
                                                        #BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="h5 fw-bold text-primary mb-0">Rp
                                                        {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                                                    <div class="small text-muted">
                                                        {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->diffInDays(\Carbon\Carbon::parse($booking->tanggal_checkout)) }}
                                                        Malam</div>
                                                </div>
                                            </div>

                                            <div class="row g-3 p-3 bg-light rounded-4 mb-4">
                                                <div class="col-md-4">
                                                    <div class="small text-muted mb-1">Check-In</div>
                                                    <div class="fw-bold d-flex align-items-center gap-2">
                                                        <i class="bi bi-box-arrow-in-right text-success"></i>
                                                        {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M Y') }}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="small text-muted mb-1">Check-Out</div>
                                                    <div class="fw-bold d-flex align-items-center gap-2">
                                                        <i class="bi bi-box-arrow-left text-danger"></i>
                                                        {{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d M Y') }}
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="small text-muted mb-1">Tamu</div>
                                                    <div class="fw-bold d-flex align-items-center gap-2">
                                                        <i class="bi bi-people text-primary"></i>
                                                        {{ $booking->jumlah_tamu }} Orang
                                                    </div>
                                                </div>

                                                @php $fasilitas = json_decode($booking->fasilitas_tambahan, true); @endphp
                                                @if($fasilitas && count($fasilitas) > 0)
                                                    <div class="col-12 mt-3 pt-3 border-top">
                                                        <div class="small text-muted mb-2">Fasilitas Tambahan</div>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @foreach($fasilitas as $item)
                                                                <span class="badge bg-white text-dark border shadow-sm fw-normal px-3 py-2">
                                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                                    {{ $item['nama'] }}
                                                                    <span
                                                                        class="text-muted ms-1">Rp{{ number_format($item['harga'], 0, ',', '.') }}</span>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="mb-0 text-muted small">Status Pembayaran:</p>
                                                    @if($booking->pembayaran && $booking->pembayaran->status_pembayaran === 'diterima')
                                                        <div class="text-success fw-bold d-flex align-items-center gap-1">
                                                            <i class="bi bi-patch-check-fill"></i> LUNAS (Diterima)
                                                        </div>
                                                    @elseif($booking->pembayaran && $booking->pembayaran->status_pembayaran === 'ditolak')
                                                        <div class="text-danger fw-bold d-flex align-items-center gap-1">
                                                            <i class="bi bi-x-circle-fill"></i> GAGAL / DITOLAK
                                                        </div>
                                                    @else
                                                        <div class="text-warning fw-bold d-flex align-items-center gap-1">
                                                            <i class="bi bi-hourglass-split"></i> MENUNGGU PEMBAYARAN
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="d-flex gap-2">
                                                    @if($booking->status_booking !== 'ditolak' && (!$booking->pembayaran || $booking->pembayaran->status_pembayaran !== 'diterima'))
                                                        <button type="button"
                                                            class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"
                                                            data-bs-toggle="modal" data-bs-target="#uploadModal{{ $booking->id }}">
                                                            <i class="bi bi-wallet2 me-1"></i>
                                                            {{ ($booking->pembayaran && $booking->pembayaran->bukti_pembayaran) ? 'Ganti Bukti Transfer' : 'Bayar Sekarang' }}
                                                        </button>
                                                        <form action="{{ route('user.booking.destroy', $booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dikembalikan.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4 fw-bold shadow-sm">
                                                                <i class="bi bi-x-circle me-1"></i> Batalkan
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        @if($booking->status_booking !== 'ditolak' && $booking->pembayaran && $booking->pembayaran->status_pembayaran !== 'diterima')
                            <div class="modal fade" id="uploadModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="fw-bold">Upload Bukti Transfer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('payment.upload', $booking->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body p-4">
                                                <div class="alert alert-info border-0 rounded-4 small mb-4">
                                                    <h6 class="fw-bold mb-2"><i class="bi bi-info-circle-fill me-1"></i> Instruksi
                                                        Pembayaran:</h6>
                                                    <p class="mb-1 text-dark">Silakan transfer sebesar <strong>Rp
                                                            {{ number_format($booking->total_harga, 0, ',', '.') }}</strong> ke rekening
                                                        berikut:</p>
                                                    <ul class="mb-0 list-unstyled">
                                                        <li><strong>Bank BRI</strong></li>
                                                        <li>No. Rek: <strong>123-456-7890-53-1</strong></li>
                                                        <li>A/N: <strong>Anjalai Cabin Management</strong></li>
                                                    </ul>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-muted text-uppercase">Pilih Foto Bukti
                                                        Transfer</label>
                                                    <input type="file" name="bukti_pembayaran" class="form-control rounded-pill"
                                                        accept="image/*" required>
                                                    <div class="form-text small">Maksimal ukuran file 5MB (JPG, PNG, WEBP).</div>
                                                </div>

                                                @if($booking->pembayaran && $booking->pembayaran->bukti_pembayaran)
                                                    <div class="mt-3">
                                                        <p class="small text-muted mb-2">Bukti yang sudah diunggah sebelumnya:</p>
                                                        <img src="{{ asset('storage/' .$booking->pembayaran->bukti_pembayaran) }}"
                                                            class="rounded shadow-sm"
                                                            style="width: 100%; height: 150px; object-fit: cover;">
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer border-0 pt-0 p-4">
                                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">Unggah
                                                    Bukti Sekarang</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                    <div class="p-5 bg-white border border-light rounded-4 shadow-sm text-center">
                        <div class="mb-4 text-muted">
                            <i class="bi bi-file-earmark-text fs-1 opacity-25"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Belum Ada Pemesanan</h5>
                        <p class="text-muted mb-4 small">Mulai jelajahi kabin indah kami dan buat kenangan berharga.</p>
                        <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-5 fw-bold">Cari Cabin Sekarang</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
        </script>
    @endpush
@endsection