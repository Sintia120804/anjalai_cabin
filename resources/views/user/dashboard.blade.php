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
                            <div class="fw-bold mb-0">{{ $orders->count() }}</div>
                            <div class="small text-muted">Total Pesanan (Order)</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h4 class="fw-bold mb-4">Riwayat Reservasi Anda</h4>

                    @forelse($orders as $orderId => $orderBookings)
                        @php 
                            $firstBooking = $orderBookings->first();
                            $pembayaran = $firstBooking->pembayaran;
                            $grandTotal = $orderBookings->sum('total_harga');
                        @endphp
                        <div class="card border border-primary border-opacity-25 rounded-4 shadow-sm mb-5 overflow-hidden">
                            <div class="card-header bg-primary bg-opacity-10 py-3 px-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-primary fw-bold"><i class="bi bi-bag-check-fill me-2"></i>Order {{ $orderId }}</span>
                                    <span class="text-muted small ms-3"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($firstBooking->created_at)->format('d M Y, H:i') }}</span>
                                </div>
                                <div>
                                    <span class="badge bg-primary rounded-pill px-3 py-2">{{ $orderBookings->count() }} Item Kamar</span>
                                </div>
                            </div>
                            
                            <div class="card-body p-0">
                                @foreach($orderBookings as $booking)
                                    <div class="row g-0 {{ !$loop->last ? 'border-bottom' : '' }} p-4">
                                        <div class="col-md-2 position-relative">
                                            @if($booking->cabin->galeris->count() > 0)
                                                <img src="{{ asset('storage/' .$booking->cabin->galeris->first()->foto) }}"
                                                    class="w-100 h-100 object-fit-cover rounded-3" style="max-height: 120px;" alt="{{ $booking->cabin->name_cabin }}">
                                            @else
                                                <div
                                                    class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white rounded-3" style="max-height: 120px;">
                                                    <i class="bi bi-image fs-1 opacity-50"></i>
                                                </div>
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <span class="badge {{ $booking->status_booking == 'diterima' ? 'bg-success' : ($booking->status_booking == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }} shadow-sm">
                                                    {{ strtoupper($booking->status_booking) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-10 ps-md-4 mt-3 mt-md-0">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="fw-bold mb-1">{{ $booking->cabin->name_cabin }}</h5>
                                                    <div class="text-muted small">#BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-bold text-dark">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-wrap gap-4 mt-3 small">
                                                <div>
                                                    <span class="text-muted d-block mb-1">Check-In</span>
                                                    <span class="fw-bold"><i class="bi bi-box-arrow-in-right text-success me-1"></i>{{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d M Y H:i') }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-muted d-block mb-1">Check-Out</span>
                                                    <span class="fw-bold"><i class="bi bi-box-arrow-left text-danger me-1"></i>{{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d M Y H:i') }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-muted d-block mb-1">Tamu</span>
                                                    <span class="fw-bold"><i class="bi bi-people text-primary me-1"></i>{{ $booking->jumlah_tamu }} Orang</span>
                                                </div>
                                                
                                                @php $fasilitas = json_decode($booking->fasilitas_tambahan, true); @endphp
                                                @if($fasilitas && count($fasilitas) > 0)
                                                    <div>
                                                        <span class="text-muted d-block mb-1">Fasilitas</span>
                                                        <span class="fw-bold"><i class="bi bi-stars text-warning me-1"></i>{{ count($fasilitas) }} Item</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="card-footer bg-white p-4 border-top">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                    <div>
                                        <p class="mb-1 text-muted small">Status Pembayaran Keseluruhan:</p>
                                        @if($pembayaran && $pembayaran->status_pembayaran === 'diterima')
                                            <div class="text-success fw-bold d-flex align-items-center gap-1 fs-5">
                                                <i class="bi bi-patch-check-fill"></i> LUNAS
                                            </div>
                                        @elseif($pembayaran && $pembayaran->status_pembayaran === 'ditolak')
                                            <div class="text-danger fw-bold d-flex align-items-center gap-1 fs-5">
                                                <i class="bi bi-x-circle-fill"></i> GAGAL / DITOLAK
                                            </div>
                                        @else
                                            <div class="text-warning fw-bold d-flex align-items-center gap-1 fs-5">
                                                <i class="bi bi-hourglass-split"></i> MENUNGGU PEMBAYARAN
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="text-md-end">
                                        <p class="mb-1 text-muted small">Total Tagihan:</p>
                                        <h4 class="fw-bold text-primary mb-3">Rp {{ number_format($pembayaran->jumlah_bayar ?? $grandTotal, 0, ',', '.') }}</h4>
                                        
                                        <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                            @if(!$orderBookings->contains('status_booking', 'ditolak') && (!$pembayaran || $pembayaran->status_pembayaran !== 'diterima'))
                                                <button type="button"
                                                    class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"
                                                    data-bs-toggle="modal" data-bs-target="#uploadModal{{ $orderId }}">
                                                    <i class="bi bi-wallet2 me-1"></i>
                                                    {{ ($pembayaran && $pembayaran->bukti_pembayaran) ? 'Ganti Bukti Transfer' : 'Bayar Sekarang' }}
                                                </button>
                                                
                                                <form action="{{ route('user.booking.destroy', $firstBooking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dikembalikan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <!-- Info: Currently logic in UserBookingController@destroy cancels 1 booking, so ideally it should cancel whole order, but for now we leave it -->
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Pembayaran per Order -->
                        @if(!$orderBookings->contains('status_booking', 'ditolak') && (!$pembayaran || $pembayaran->status_pembayaran !== 'diterima'))
                            <div class="modal fade" id="uploadModal{{ $orderId }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="fw-bold">Upload Bukti Transfer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('payment.upload', $firstBooking->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body p-4">
                                                <div class="alert alert-info border-0 rounded-4 small mb-4">
                                                    <h6 class="fw-bold mb-2"><i class="bi bi-info-circle-fill me-1"></i> Instruksi
                                                        Pembayaran:</h6>
                                                    <p class="mb-1 text-dark">Silakan transfer sebesar <strong class="fs-5">Rp
                                                            {{ number_format($pembayaran->jumlah_bayar ?? $grandTotal, 0, ',', '.') }}</strong> ke rekening
                                                        berikut:</p>
                                                    <ul class="mb-0 list-unstyled mt-2 bg-white bg-opacity-50 p-2 rounded">
                                                        <li><strong>Bank BRI</strong></li>
                                                        <li>No. Rek: <strong class="text-primary fs-6">123-456-7890-53-1</strong></li>
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

                                                @if($pembayaran && $pembayaran->bukti_pembayaran)
                                                    <div class="mt-3">
                                                        <p class="small text-muted mb-2">Bukti yang sudah diunggah sebelumnya:</p>
                                                        <img src="{{ asset('storage/' .$pembayaran->bukti_pembayaran) }}"
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
                        <h5 class="fw-bold mb-1">Belum Ada Reservasi</h5>
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