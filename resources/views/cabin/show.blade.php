@extends('layouts.app')

@section('content')
    <div class="py-5" style="background-color: #f8fafc;">
        <div class="container py-lg-4">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $cabin->name_cabin }}</li>
                </ol>
            </nav>

            <div class="row g-5">
                <!-- Left: Gallery & Description -->
                <div class="col-lg-8">
                    <!-- Gallery Carousel -->
                    <div id="cabinGallery" class="carousel slide rounded-4 overflow-hidden shadow-sm shadow-hover mb-4"
                        data-bs-ride="carousel">
                        <div class="carousel-inner" style="height: 500px;">
                            @forelse($cabin->galeris as $index => $galeri)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }} h-100">
                                    <img src="{{ asset('storage/' . $galeri->foto) }}" class="d-block w-100 h-100 object-fit-cover"
                                        alt="Foto {{ $cabin->name_cabin }}">
                                </div>
                            @empty
                                <div
                                    class="carousel-item active h-100 bg-secondary d-flex align-items-center justify-content-center text-white text-center">
                                    <div>
                                        <i class="bi bi-image fs-1 d-block mb-3"></i>
                                        <h4>Belum Ada Foto untuk Cabin Ini</h4>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        @if($cabin->galeris->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#cabinGallery"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon p-3 bg-dark rounded-circle" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#cabinGallery"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon p-3 bg-dark rounded-circle" aria-hidden="true"></span>
                            </button>
                        @endif
                    </div>

                    <!-- Cabin Info Card -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h2 class="fw-bold mb-2">{{ $cabin->name_cabin }}</h2>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="text-primary fw-bold"><i class="bi bi-people-fill me-1"></i> Kapasitas:
                                        {{ $cabin->kapasitas }} Orang</span>
                                    <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Status:
                                        Tersedia</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fs-2 fw-bold text-primary">Rp
                                    {{ number_format($cabin->harga_per_malam, 0, ',', '.') }}</span>
                                <span class="text-muted d-block mt-n1">/ malam</span>
                            </div>
                        </div>

                        <hr class="my-4 opacity-10">

                        <h5 class="fw-bold mb-3">Deskripsi Cabin</h5>
                        <p class="text-muted lh-lg fs-5">
                            {{ $cabin->deskripsi ?? 'Nikmati pengalaman menginap mewah di tengah alam. Cabin ini menawarkan pemandangan asri dengan fasilitas premium yang menjamin kenyamanan Anda. Setiap sudut ruangan didesain untuk memberikan ketenangan maksimal bagi Anda dan keluarga.' }}
                        </p>

                        <div class="row g-4 mt-2">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded-4 border d-flex align-items-center gap-3">
                                    <div class="bg-white p-2 rounded shadow-sm">
                                        <i class="bi bi-snow text-primary fs-3"></i>
                                    </div>
                                    <span class="fw-medium">Full AC</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded-4 border d-flex align-items-center gap-3">
                                    <div class="bg-white p-2 rounded shadow-sm">
                                        <i class="bi bi-wifi text-primary fs-3"></i>
                                    </div>
                                    <span class="fw-medium">Wifi Gratis</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded-4 border d-flex align-items-center gap-3">
                                    <div class="bg-white p-2 rounded shadow-sm">
                                        <i class="bi bi-cup-hot text-primary fs-3"></i>
                                    </div>
                                    <span class="fw-medium">Sarapan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Booking Form -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 100px; z-index: 10;">
                        <div class="card border-0 rounded-4 shadow-lg overflow-hidden">
                            <div class="bg-primary p-4 text-white text-center">
                                <h4 class="mb-0 fw-bold">Pesan Sekarang</h4>
                                <p class="mb-0 opacity-75 small">Cepat & Aman via Midtrans</p>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('user.booking.store', $cabin->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label
                                            class="form-label fw-bold small text-uppercase tracking-wider text-muted">Pilih
                                            Tanggal</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 rounded-start-4"><i
                                                    class="bi bi-calendar3"></i></span>
                                            <input type="text" id="datepicker" name="dates"
                                                class="form-control bg-light border-start-0 rounded-end-4 py-2"
                                                placeholder="Pilih Check-In & Check-Out" readonly required>
                                        </div>
                                        <div class="form-text text-muted small mt-2">Pilih rentang tanggal pada kalender.
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label
                                            class="form-label fw-bold small text-uppercase tracking-wider text-muted">Jumlah
                                            Tamu</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 rounded-start-4"><i
                                                    class="bi bi-people"></i></span>
                                            <input type="number" name="jumlah_tamu"
                                                class="form-control bg-light border-start-0 rounded-end-4 py-2" value="1"
                                                min="1" max="{{ $cabin->kapasitas }}" required>
                                        </div>
                                    </div>

                                    @if(count($fasilitasTambahan) > 0)
                                        <div class="mb-4">
                                            <label
                                                class="form-label fw-bold small text-uppercase tracking-wider text-muted">Fasilitas
                                                Tambahan (Opsional)</label>
                                            <div class="d-flex flex-column gap-2 mt-2">
                                                @foreach($fasilitasTambahan as $fasilitas)
                                                    <div class="form-check custom-checkbox bg-light p-3 rounded-4 border">
                                                        <input class="form-check-input ms-1 fasilitas-checkbox" type="checkbox"
                                                            name="fasilitas[]" value="{{ $fasilitas->id }}"
                                                            id="fasilitas_{{ $fasilitas->id }}"
                                                            data-harga="{{ $fasilitas->harga }}">
                                                        <label class="form-check-label ms-2 d-flex flex-column w-100 cursor-pointer"
                                                            for="fasilitas_{{ $fasilitas->id }}" style="cursor: pointer;">
                                                            <span class="fw-bold d-flex justify-content-between">
                                                                {{ $fasilitas->nama }}
                                                                <span
                                                                    class="text-primary">+Rp{{ number_format($fasilitas->harga, 0, ',', '.') }}</span>
                                                            </span>
                                                            @if($fasilitas->deskripsi)
                                                                <small class="text-muted mt-1">{{ $fasilitas->deskripsi }}</small>
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div id="priceDetail" class="p-3 bg-light rounded-4 mb-4 d-none">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted small">Malam</span>
                                            <span id="nightCount" class="fw-bold text-dark">0</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 d-none" id="fasilitasRow">
                                            <span class="text-muted small">Fasilitas Tambahan</span>
                                            <span id="fasilitasTotal" class="fw-bold text-dark">Rp 0</span>
                                        </div>
                                        <div class="d-flex justify-content-between pt-2 border-top">
                                            <span class="fw-bold">Total Harga</span>
                                            <span id="totalPriceDisplay" class="fw-bold text-primary">Rp 0</span>
                                        </div>
                                    </div>

                                    @auth
                                        <button type="submit"
                                            class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm mt-2">
                                            Konfirmasi Reservasi
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="btn btn-outline-primary w-100 rounded-pill py-3 fw-bold mt-2">
                                            Login Untuk Memesan
                                        </a>
                                    @endauth
                                </form>
                            </div>

                        </div>

                        <div class="mt-4 p-4 rounded-4 bg-info bg-opacity-10 border border-info border-opacity-10">
                            <h6 class="fw-bold text-info mb-2"><i class="bi bi-info-circle-fill me-2"></i>Kebijakan
                                Pembatalan</h6>
                            <p class="small text-muted mb-0">Pembatalan gratis dilakukan maksimal 24 jam sebelum waktu
                                check-in.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <style>
            .shadow-hover:hover {
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
                transition: box-shadow 0.3s ease;
            }

            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                width: 1.5rem;
                height: 1.5rem;
            }

            .grayscale {
                filter: grayscale(100%);
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            const hargaPerMalam = {{ $cabin->harga_per_malam }};
            const bookedDates = @json($bookedDates);
            const manualBookedDates = @json($manualBookedDates);

            // Combine all blocked dates
            const disableDates = [];
            [...bookedDates, ...manualBookedDates].forEach(range => {
                disableDates.push({
                    from: range.tanggal_checkin,
                    to: range.tanggal_checkout
                });
            });

            let basePriceTotal = 0;

            function calculateTotal() {
                let fasilitasPrice = 0;
                document.querySelectorAll('.fasilitas-checkbox:checked').forEach(function (checkbox) {
                    fasilitasPrice += parseInt(checkbox.getAttribute('data-harga'));
                });

                let finalTotal = basePriceTotal + fasilitasPrice;

                if (fasilitasPrice > 0) {
                    document.getElementById('fasilitasRow').classList.remove('d-none');
                    document.getElementById('fasilitasTotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(fasilitasPrice);
                } else {
                    document.getElementById('fasilitasRow').classList.add('d-none');
                }

                document.getElementById('totalPriceDisplay').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(finalTotal);
            }

            document.querySelectorAll('.fasilitas-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', calculateTotal);
            });

            function updatePriceDisplay(selectedDates) {
                if (selectedDates.length === 2) {
                    const checkIn = selectedDates[0];
                    const checkOut = selectedDates[1];
                    const diffTime = Math.abs(checkOut - checkIn);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (diffDays > 0) {
                        basePriceTotal = diffDays * hargaPerMalam;

                        document.getElementById('priceDetail').classList.remove('d-none');
                        document.getElementById('nightCount').innerText = diffDays + ' Malam';
                        calculateTotal();
                    }
                } else {
                    document.getElementById('priceDetail').classList.add('d-none');
                    basePriceTotal = 0;
                }
            }

            const defaultIn = "{{ request('checkin') }}";
            const defaultOut = "{{ request('checkout') }}";

            flatpickr("#datepicker", {
                mode: "range",
                minDate: "today",
                dateFormat: "Y-m-d",
                disable: disableDates,
                defaultDate: (defaultIn && defaultOut) ? [defaultIn, defaultOut] : null,
                onReady: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        updatePriceDisplay(selectedDates);
                    }
                },
                onChange: function(selectedDates, dateStr, instance) {
                    updatePriceDisplay(selectedDates);
                }
            });
        </script>
    @endpush
@endsection