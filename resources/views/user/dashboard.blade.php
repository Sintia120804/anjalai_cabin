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
                            if ($firstBooking->order_id) {
                                $pembayaran = \App\Models\Pembayaran::where('order_id', $firstBooking->order_id)->first();
                            } else {
                                $pembayaran = \App\Models\Pembayaran::where('booking_id', $firstBooking->id)->first();
                            }
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
                                                    {{ $booking->status_booking == 'pending' ? 'MENUNGGU RESPON ADMIN' : strtoupper($booking->status_booking) }}
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
                                            @if($firstBooking->status_booking === 'pending')
                                                <div class="text-info fw-bold small mt-1">
                                                    <i class="bi bi-hourglass-split"></i> MENUNGGU RESPON ADMIN
                                                </div>
                                            @endif
                                        @elseif($pembayaran && $pembayaran->status_pembayaran === 'menunggu_konfirmasi')
                                            <div class="text-info fw-bold d-flex align-items-center gap-1 fs-5">
                                                <i class="bi bi-hourglass-split"></i> MENUNGGU RESPON ADMIN
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
                                            @if($pembayaran && $pembayaran->status_pembayaran === 'diterima')
                                                <a href="{{ route('user.booking.pdf', $orderId) }}" target="_blank" class="btn btn-outline-success rounded-pill px-4 fw-bold shadow-sm">
                                                    <i class="bi bi-printer me-1"></i> Cetak Bukti PDF
                                                </a>
                                            @endif
                                            
                                            @if(!$orderBookings->contains('status_booking', 'ditolak') && (!$pembayaran || !in_array($pembayaran->status_pembayaran, ['diterima', 'menunggu_konfirmasi'])))
                                                <button type="button"
                                                    class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"
                                                    data-bs-toggle="modal" data-bs-target="#uploadModal{{ $orderId }}">
                                                    <i class="bi bi-wallet2 me-1"></i>
                                                    {{ ($pembayaran && $pembayaran->status_pembayaran === 'ditolak') ? 'Ulangi Pembayaran' : 'Bayar Sekarang' }}
                                                </button>
                                            @endif
                                            
                                            @if($firstBooking->status_booking === 'pending')
                                                <form action="{{ route('user.booking.destroy', $firstBooking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dikembalikan.')">
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

                                {{-- Bukti Pembayaran Section --}}
                                @if($pembayaran)
                                    <div class="mt-4 pt-3 border-top">
                                        <h6 class="fw-bold text-muted text-uppercase small mb-3">
                                            <i class="bi bi-receipt me-1"></i> Bukti Pembayaran
                                        </h6>

                                        @if($pembayaran->status_pembayaran === 'diterima' && !$pembayaran->bukti_pembayaran)
                                            {{-- Struk digital untuk pembayaran Midtrans online --}}
                                            <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded-4 p-4">
                                                <div class="d-flex align-items-center gap-3 mb-3">
                                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                                        <i class="bi bi-check-lg fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-success fs-6">Pembayaran Berhasil</div>
                                                        <div class="text-muted small">Transaksi telah dikonfirmasi secara otomatis</div>
                                                    </div>
                                                </div>
                                                <div class="row g-2 small">
                                                    <div class="col-6 col-md-3">
                                                        <div class="text-muted">No. Order</div>
                                                        <div class="fw-bold text-dark">{{ $pembayaran->order_id ?? ('BKG-'.$pembayaran->booking_id) }}</div>
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <div class="text-muted">Metode</div>
                                                        <div class="fw-bold text-dark">
                                                            @php
                                                                $metode = $pembayaran->metode_pembayaran ?? '-';
                                                                $metodeLabel = match(strtolower($metode)) {
                                                                    'bank_transfer' => '🏦 Transfer Bank',
                                                                    'gopay' => '💚 GoPay',
                                                                    'shopeepay' => '🧡 ShopeePay',
                                                                    'qris' => '📱 QRIS',
                                                                    'cstore' => '🏪 Minimarket',
                                                                    'credit_card' => '💳 Kartu Kredit',
                                                                    default => $metode,
                                                                };
                                                            @endphp
                                                            {{ $metodeLabel }}
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <div class="text-muted">Tanggal Bayar</div>
                                                        <div class="fw-bold text-dark">{{ $pembayaran->tanggal_pembayaran ? \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d M Y, H:i') : '-' }}</div>
                                                    </div>
                                                    <div class="col-6 col-md-3">
                                                        <div class="text-muted">Jumlah</div>
                                                        <div class="fw-bold text-success">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                        @elseif($pembayaran->bukti_pembayaran)
                                            {{-- Bukti foto transfer manual --}}
                                            <div class="d-flex flex-column flex-md-row gap-3 align-items-start">
                                                <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank" title="Klik untuk lihat penuh">
                                                    <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                                                        class="rounded-3 border shadow-sm"
                                                        style="max-height: 160px; max-width: 220px; object-fit: cover; cursor: zoom-in;"
                                                        alt="Bukti Transfer">
                                                </a>
                                                <div class="small text-muted mt-1">
                                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-image me-1"></i>Bukti Transfer Manual</div>
                                                    <div>Diunggah: {{ $pembayaran->tanggal_pembayaran ? \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d M Y, H:i') : '-' }}</div>
                                                    <div class="mt-2">
                                                        @if($pembayaran->status_pembayaran === 'pending')
                                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu Verifikasi Admin</span>
                                                        @elseif($pembayaran->status_pembayaran === 'ditolak')
                                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak — Silakan unggah ulang</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        @elseif($pembayaran->status_pembayaran === 'pending' && !$pembayaran->bukti_pembayaran)
                                            {{-- Pending Midtrans - belum dibayar --}}
                                            <div class="alert alert-warning border-0 rounded-4 small py-2 px-3 d-flex align-items-center gap-2 mb-0">
                                                <i class="bi bi-hourglass-split fs-5"></i>
                                                <span>Pembayaran online belum diselesaikan. Klik <strong>Bayar Sekarang</strong> untuk melanjutkan.</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if(!$orderBookings->contains('status_booking', 'ditolak') && (!$pembayaran || $pembayaran->status_pembayaran !== 'diterima'))
                            <div class="modal fade" id="uploadModal{{ $orderId }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="fw-bold">Selesaikan Pembayaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="card border border-primary border-opacity-25 rounded-4 p-4 text-center mb-4 shadow-sm bg-primary bg-opacity-10">
                                                <div class="display-5 text-primary mb-3"><i class="bi bi-shield-check"></i></div>
                                                <h6 class="fw-bold text-dark mb-1">Bayar Online Instan (Midtrans)</h6>
                                                <p class="small text-muted mb-3">Bayar otomatis menggunakan E-Wallet (GoPay/ShopeePay), Virtual Account Bank, atau QRIS. Verifikasi instan tanpa perlu unggah bukti transfer.</p>
                                                <button type="button" id="btnPayMidtrans-{{ $orderId }}" 
                                                    class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm"
                                                    onclick="startMidtransPay('{{ $orderId }}')">
                                                    <i class="bi bi-wallet2 me-1"></i> Bayar Online Sekarang
                                                </button>
                                            </div>

                                            <div class="text-center my-3 text-muted position-relative">
                                                <hr>
                                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small fw-bold text-uppercase">Atau</span>
                                            </div>

                                            <div class="d-grid">
                                                <button class="btn btn-outline-secondary rounded-pill py-2.5 fw-bold collapsed" 
                                                    type="button" data-bs-toggle="collapse" 
                                                    data-bs-target="#collapseManual{{ $orderId }}" 
                                                    aria-expanded="false" aria-controls="collapseManual{{ $orderId }}">
                                                    <i class="bi bi-bank me-1"></i> Transfer Bank Manual
                                                </button>
                                            </div>

                                            <div class="collapse mt-3" id="collapseManual{{ $orderId }}">
                                                <form action="{{ route('payment.upload', $firstBooking->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="alert alert-info border-0 rounded-4 small mb-3">
                                                        <h6 class="fw-bold mb-2"><i class="bi bi-info-circle-fill me-1"></i> Rekening Transfer:</h6>
                                                        <p class="mb-1 text-dark">Silakan transfer sebesar <strong class="fs-5 text-primary">Rp
                                                                {{ number_format($pembayaran->jumlah_bayar ?? $grandTotal, 0, ',', '.') }}</strong></p>
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
                                                        <div class="mt-3 mb-3">
                                                            <p class="small text-muted mb-2">Bukti yang sudah diunggah sebelumnya:</p>
                                                            <img src="{{ asset('storage/' .$pembayaran->bukti_pembayaran) }}"
                                                                class="rounded shadow-sm w-100"
                                                                style="max-height: 150px; object-fit: cover;">
                                                        </div>
                                                    @endif

                                                    <button type="submit" class="btn btn-secondary w-100 rounded-pill py-2.5 fw-bold">Unggah Bukti Sekarang</button>
                                                </form>
                                            </div>
                                        </div>
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
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script>
            function startMidtransPay(orderId) {
                const payBtn = document.getElementById('btnPayMidtrans-' + orderId);
                const originalText = payBtn.innerHTML;
                
                payBtn.disabled = true;
                payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
                
                fetch(`/payment/snap-token/${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        payBtn.disabled = false;
                        payBtn.innerHTML = originalText;
                        
                        if (data.snap_token) {
                            const modalEl = document.getElementById('uploadModal' + orderId);
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                            
                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    fetch("{{ route('midtrans.frontend_success') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ order_id: result.order_id })
                                    }).then(() => {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: 'Pembayaran sukses! Pesanan Anda telah terkonfirmasi.',
                                            confirmButtonColor: '#2563eb'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    });
                                },
                                onPending: function(result) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Tertunda',
                                        text: 'Pembayaran tertunda. Silakan selesaikan pembayaran Anda.',
                                        confirmButtonColor: '#2563eb'
                                    }).then(() => {
                                        location.reload();
                                    });
                                },
                                onError: function(result) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: 'Pembayaran gagal!',
                                        confirmButtonColor: '#2563eb'
                                    }).then(() => {
                                        location.reload();
                                    });
                                },
                                onClose: function() {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Dibatalkan',
                                        text: 'Anda menutup halaman pembayaran sebelum transaksi selesai.',
                                        confirmButtonColor: '#2563eb'
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.error || 'Gagal membuat token pembayaran.',
                                confirmButtonColor: '#2563eb'
                            });
                        }
                    })
                    .catch(error => {
                        payBtn.disabled = false;
                        payBtn.innerHTML = originalText;
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan Sistem',
                            text: 'Terjadi kesalahan saat memproses pembayaran.',
                            confirmButtonColor: '#2563eb'
                        });
                    });
            }
        </script>
    @endpush
@endsection