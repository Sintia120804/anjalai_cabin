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

            <div class="row g-4">
                <!-- Left Column: Gallery & Description -->
                <div class="col-lg-8">
                    <!-- Gallery Carousel -->
                    <div id="cabinGallery" class="carousel slide rounded-4 overflow-hidden shadow-sm mb-4"
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

                    <!-- Description Card -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5">
                        <!-- Info Header moved to Right Column -->

                        <!-- Pricing Card moved to Right Column -->

                        <hr class="my-4 opacity-10">

                        <h5 class="fw-bold mb-3">Deskripsi Cabin</h5>
                        <p class="text-muted lh-lg fs-6">
                            {{ $cabin->deskripsi ?? 'Nikmati pengalaman menginap mewah di tengah alam. Cabin ini menawarkan pemandangan asri dengan fasilitas premium yang menjamin kenyamanan Anda. Setiap sudut ruangan didesain untuk memberikan ketenangan maksimal bagi Anda dan keluarga.' }}
                        </p>

                        <div class="row g-3 mt-2">
                            @if($cabin->fasilitas && count($cabin->fasilitas) > 0)
                                @foreach($cabin->fasilitas as $item)
                                    <div class="col-md-4 col-6">
                                        <div class="p-2 bg-light rounded-3 border d-flex align-items-center gap-2 h-100">
                                            <i class="bi bi-check2-circle text-primary"></i>
                                            <span class="fw-medium small text-dark">{{ $item }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <p class="text-muted small italic">Informasi fasilitas belum tersedia.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Info & Booking Form -->
                <div class="col-lg-4">
                    <!-- Cabin Title & Pricing Table -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="fw-bold mb-1">{{ $cabin->name_cabin }}</h4>
                                <div class="small">
                                    <span class="text-primary fw-bold"><i class="bi bi-people-fill"></i> {{ $cabin->kapasitas }} Orang</span>
                                    <span id="availabilityBadge" class="text-warning text-dark fw-bold ms-2"><i class="bi bi-door-open-fill"></i> Tersedia {{ $sisaKamar }} Kamar</span>
                                    <span class="text-success fw-bold ms-2"><i class="bi bi-check-circle-fill"></i> Bisa Dipesan</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary mb-1">Mulai Dari</span>
                                <div class="h5 fw-bold text-primary mb-0">Rp {{ number_format($cabin->harga_weekday, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <!-- Pricing Card Aesthetic -->
                        <div class="card border-0 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                            <div class="card-body p-3">
                                <div class="row text-center g-2">
                                    <div class="col-4 border-end border-white border-opacity-25">
                                        <div class="text-uppercase fw-bold" style="font-size: 0.7rem; opacity: 0.8;">Weekday</div>
                                        <div class="fw-bold small">Rp{{ number_format($cabin->harga_weekday / 1000, 0) }}k</div>
                                    </div>
                                    <div class="col-4 border-end border-white border-opacity-25">
                                        <div class="text-uppercase fw-bold" style="font-size: 0.7rem; opacity: 0.8;">Weekend</div>
                                        <div class="fw-bold small">Rp{{ number_format($cabin->harga_weekend / 1000, 0) }}k</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-uppercase fw-bold" style="font-size: 0.7rem; opacity: 0.8;">Couple</div>
                                        <div class="fw-bold small">{{ $cabin->harga_couple ? 'Rp'.number_format($cabin->harga_couple / 1000, 0).'k' : '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                     {{-- Booking Form --}}
                    <div class="sticky-top" style="top: 20px; z-index: 10;">
                        <div class="card border-0 rounded-4 shadow-lg overflow-hidden">
                            <div class="bg-primary p-3 text-white text-center">
                                <h5 class="mb-0 fw-bold">Pesan Sekarang</h5>
                                <p class="mb-0 opacity-75 small" style="font-size: 0.75rem;">Pilih tanggal lalu tambah ke keranjang</p>
                            </div>
                            <div class="card-body p-4">

                                @if($errors->any())
                                    <div class="alert alert-danger rounded-4 small p-2 mb-3">
                                        <ul class="mb-0 ps-3">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- STEP 1: Pilih Tanggal --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase tracking-wider text-muted">Pilih Tanggal Check-In & Check-Out</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 rounded-start-4"><i class="bi bi-calendar3"></i></span>
                                        <input type="text" id="datepicker" name="dates"
                                            class="form-control bg-light border-start-0 rounded-end-4 py-2"
                                            placeholder="Pilih Check-In & Check-Out"
                                            value="{{ old('dates') }}" readonly>
                                    </div>
                                    <div class="form-text text-muted small mt-1">Pilih rentang tanggal pada kalender.</div>
                                </div>

                                {{-- STEP 2: Jumlah Tamu --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase tracking-wider text-muted">Jumlah Tamu</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 rounded-start-4"><i class="bi bi-people"></i></span>
                                        <input type="number" id="jumlahTamuMain" name="jumlah_tamu_main"
                                            class="form-control bg-light border-start-0 rounded-end-4 py-2"
                                            value="1" min="1" max="{{ $cabin->kapasitas }}">
                                    </div>
                                </div>

                                @if($cabin->harga_couple)
                                <div class="mb-4">
                                    <div class="form-check form-switch p-3 bg-light rounded-4 border d-flex align-items-center justify-content-between">
                                        <div>
                                            <label class="form-check-label fw-bold mb-0" for="isCoupleMain">Paket Couple (2 Pax)</label>
                                            <small class="text-muted d-block mt-1">Harga spesial: Rp {{ number_format($cabin->harga_couple, 0, ',', '.') }}/mlm</small>
                                        </div>
                                        <input class="form-check-input me-0" type="checkbox" id="isCoupleMain" style="width: 3rem; height: 1.5rem;">
                                    </div>
                                </div>
                                @endif

                                {{-- Preview Harga --}}
                                <div id="pricePreview" class="p-3 bg-light rounded-4 mb-4 d-none">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted small">Estimasi per kamar</span>
                                        <span id="previewNights" class="fw-bold text-dark small">0 Malam</span>
                                    </div>
                                    <div class="d-flex justify-content-between pt-2 border-top">
                                        <span class="fw-bold">Harga/Kamar</span>
                                        <span id="previewPrice" class="fw-bold text-primary">Rp 0</span>
                                    </div>
                                </div>

                                {{-- Tombol --}}
                                @php $totalAvailableUnits = $cabin->units()->where('status', 'available')->count(); @endphp
                                @auth
                                    @if($totalAvailableUnits > 0)
                                        <button type="button" id="btnOpenCartModal"
                                            class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm mt-2" disabled>
                                            <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                                        </button>
                                        <p class="text-muted small text-center mt-2 mb-0">
                                            <i class="bi bi-info-circle me-1"></i>Pilih tanggal untuk melanjutkan
                                        </p>
                                    @else
                                        <div class="alert alert-warning rounded-4 text-center small mt-2">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                            Kabin ini belum memiliki unit kamar terdaftar.<br>Silakan hubungi pengelola.
                                        </div>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                        class="btn btn-outline-primary w-100 rounded-pill py-3 fw-bold mt-2">
                                        Login Untuk Memesan
                                    </a>
                                @endauth
                            </div>
                        </div>

                        <div class="mt-4 p-4 rounded-4 bg-info bg-opacity-10 border border-info border-opacity-10">
                            <h6 class="fw-bold text-info mb-2"><i class="bi bi-info-circle-fill me-2"></i>Kebijakan Pembatalan</h6>
                            <p class="small text-muted mb-0">Pembatalan gratis dilakukan maksimal 24 jam sebelum waktu check-in.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======== MODAL: Tambah ke Keranjang ======== --}}
    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cartForm" action="{{ route('cart.add', $cabin->id) }}" method="POST">
                    @csrf
                    {{-- Field hidden yang diisi via JS --}}
                    <input type="hidden" name="dates" id="modalDates">
                    <input type="hidden" name="jumlah_tamu" id="modalJumlahTamu">
                    <input type="hidden" name="is_couple" id="modalIsCouple" value="">
                    <div class="modal-body p-4">
                        {{-- Info Pesanan --}}
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Tipe Kamar</span>
                                <span class="fw-bold small">{{ $cabin->name_cabin }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Check-In</span>
                                <span class="fw-bold small" id="modalCheckIn">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Check-Out</span>
                                <span class="fw-bold small" id="modalCheckOut">-</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Kamar Tersisa</span>
                                <span class="fw-bold small text-success" id="modalAvailable">-</span>
                            </div>
                        </div>

                        {{-- Jumlah Kamar --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Total Kamar</label>
                            <div class="input-group input-group-lg">
                                <button type="button" class="btn btn-outline-secondary rounded-start-3" id="btnMinus">
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                                <input type="number" name="jumlah_kamar" id="modalJumlahKamar"
                                    class="form-control text-center fw-bold fs-4"
                                    value="1" min="1" max="1">
                                <button type="button" class="btn btn-outline-secondary rounded-end-3" id="btnPlus">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                            <div class="form-text text-muted small">Maksimal sesuai kamar yang tersedia pada tanggal pilihan.</div>
                        </div>

                        {{-- Ringkasan Harga --}}
                        <div class="bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 p-3">
                            <div class="d-flex justify-content-between mb-1 small">
                                <span class="text-muted">Harga Kamar (<span id="summaryKamar">1</span> kamar)</span>
                                <span class="fw-bold" id="summaryBase">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 small d-none" id="summaryFasilitasRow">
                                <span class="text-muted">Fasilitas Tambahan</span>
                                <span class="fw-bold" id="summaryFasilitas">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between pt-2 border-top border-primary border-opacity-25">
                                <span class="fw-bold">Estimasi Total</span>
                                <span class="fw-bold text-primary fs-5" id="summaryTotal">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold flex-grow-1">
                            <i class="bi bi-cart-check me-1"></i> Masukkan ke Keranjang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <style>
            .shadow-hover:hover { box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; transition: box-shadow 0.3s ease; }
            .carousel-control-prev-icon, .carousel-control-next-icon { width: 1.5rem; height: 1.5rem; }
            .grayscale { filter: grayscale(100%); }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hargaWeekday = {{ $cabin->harga_weekday ?? 0 }};
            const hargaWeekend  = {{ $cabin->harga_weekend ?? 0 }};
            const hargaCouple   = {{ $cabin->harga_couple ?? 0 }};
            const totalUnits    = {{ $cabin->units()->where('status','available')->count() }};
            const disableDates  = @json($fullyBookedDates ?? []);
            const cabinId       = {{ $cabin->id }};

            let basePricePerKamar = 0;  // harga total (semua malam) untuk 1 kamar
            let selectedDates = [];

            // ───── Flatpickr ─────
            const fp = flatpickr("#datepicker", {
                mode: "range",
                minDate: "today",
                dateFormat: "Y-m-d",
                disable: disableDates,
                defaultDate: "{{ old('dates') }}" ? "{{ old('dates') }}".split(' to ') : null,
                onReady: function(d) { if(d.length===2){ selectedDates=d; handleDateSelected(d); } },
                onChange: function(d) { selectedDates=d; handleDateSelected(d); }
            });

            const coupleSwitch  = document.getElementById('isCoupleMain');
            const tamu          = document.getElementById('jumlahTamuMain');
            const btnOpen       = document.getElementById('btnOpenCartModal');
            const pricePreview  = document.getElementById('pricePreview');

            if(coupleSwitch){
                coupleSwitch.addEventListener('change', function(){
                    if(selectedDates.length===2) handleDateSelected(selectedDates);
                    if(tamu){
                        if(this.checked){ if(parseInt(tamu.value)>2) tamu.value=2; tamu.setAttribute('max',2); }
                        else { tamu.setAttribute('max', {{ $cabin->kapasitas }}); }
                    }
                });
            }

            function calcNightsAndPrice(dates){
                const isCouple = coupleSwitch ? coupleSwitch.checked : false;
                let total = 0, nights = 0;
                let cur = new Date(dates[0]); cur.setHours(0,0,0,0);
                let end = new Date(dates[1]); end.setHours(0,0,0,0);
                while(cur < end){
                    nights++;
                    const day = cur.getDay();
                    if(isCouple && hargaCouple > 0){ total += hargaCouple; }
                    else if(day>=0 && day<=4){ total += hargaWeekday; }
                    else { total += hargaWeekend; }
                    cur.setDate(cur.getDate()+1);
                }
                return { nights, total };
            }

            function handleDateSelected(dates){
                if(!dates || dates.length < 2) { 
                    basePricePerKamar = 0;
                    pricePreview.classList.add('d-none');
                    if(btnOpen) btnOpen.disabled = true;
                    return;
                }
                const { nights, total } = calcNightsAndPrice(dates);
                if(nights > 0){
                    basePricePerKamar = total;
                    document.getElementById('previewNights').innerText = nights + ' Malam';
                    document.getElementById('previewPrice').innerText  = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                    pricePreview.classList.remove('d-none');
                    if(btnOpen) btnOpen.disabled = false;

                    // Update Badge Ketersediaan secara real-time
                    const checkin  = dates[0].toISOString().split('T')[0];
                    const checkout = dates[1].toISOString().split('T')[0];
                    fetch(`/cabin/${cabinId}/available-units?checkin=${encodeURIComponent(checkin)}&checkout=${encodeURIComponent(checkout)}`)
                        .then(r => r.json())
                        .then(data => {
                            const badge = document.getElementById('availabilityBadge');
                            if(badge) badge.innerHTML = `<i class="bi bi-door-open-fill"></i> Tersedia ${data.available} Kamar`;
                        });
                } else {
                    basePricePerKamar = 0;
                    pricePreview.classList.add('d-none');
                    if(btnOpen) btnOpen.disabled = true;
                }
            }

            // ───── Buka Modal ─────
            if(btnOpen){
                btnOpen.addEventListener('click', function(){
                    if(selectedDates.length < 2){
                        alert('Silakan pilih tanggal Check-In dan Check-Out terlebih dahulu!');
                        return;
                    }

                    // Ambil ketersediaan kamar dari server via fetch
                    const checkin  = document.getElementById('datepicker').value.split(' to ')[0].trim();
                    const checkout = document.getElementById('datepicker').value.split(' to ')[1].trim();

                    fetch(`/cabin/${cabinId}/available-units?checkin=${encodeURIComponent(checkin)}&checkout=${encodeURIComponent(checkout)}`)
                        .then(r => r.json())
                        .then(data => {
                            const available = data.available ?? totalUnits;

                            if(available < 1){
                                alert('Maaf, tidak ada kamar tersedia pada tanggal ini.');
                                return;
                            }

                            // Isi field modal
                            const rawDates = document.getElementById('datepicker').value;
                            document.getElementById('modalDates').value = rawDates;
                            document.getElementById('modalJumlahTamu').value = tamu ? tamu.value : 1;
                            document.getElementById('modalIsCouple').value  = (coupleSwitch && coupleSwitch.checked) ? '1' : '';
                            
                            // Format tanggal untuk display
                            const parts = rawDates.split(' to ');
                            document.getElementById('modalCheckIn').innerText  = parts[0] ? parts[0].trim() : '-';
                            document.getElementById('modalCheckOut').innerText = parts[1] ? parts[1].trim() : '-';
                            document.getElementById('modalAvailable').innerText = available + ' Kamar';

                            // Set max jumlah kamar
                            const kamarInput = document.getElementById('modalJumlahKamar');
                            kamarInput.max = available;
                            kamarInput.value = 1;

                            updateModalSummary();
                            
                            const modal = new bootstrap.Modal(document.getElementById('addToCartModal'));
                            modal.show();
                        })
                        .catch(() => {
                            // Fallback: buka modal tanpa fetch, tapi pastikan ada unit
                            if(totalUnits < 1){
                                alert('Maaf, kabin ini belum memiliki unit kamar terdaftar.');
                                return;
                            }
                            const rawDates = document.getElementById('datepicker').value;
                            document.getElementById('modalDates').value = rawDates;
                            document.getElementById('modalJumlahTamu').value = tamu ? tamu.value : 1;
                            document.getElementById('modalIsCouple').value  = (coupleSwitch && coupleSwitch.checked) ? '1' : '';
                            document.getElementById('modalAvailable').innerText = totalUnits + ' Kamar';
                            const parts = rawDates.split(' to ');
                            document.getElementById('modalCheckIn').innerText  = parts[0] ? parts[0].trim() : '-';
                            document.getElementById('modalCheckOut').innerText = parts[1] ? parts[1].trim() : '-';
                            document.getElementById('modalJumlahKamar').max = totalUnits;
                            document.getElementById('modalJumlahKamar').value = 1;
                            updateModalSummary();
                            new bootstrap.Modal(document.getElementById('addToCartModal')).show();
                        });
                });
            }

            // ───── Kontrol +/- ─────
            document.getElementById('btnPlus')?.addEventListener('click', function(){
                const inp = document.getElementById('modalJumlahKamar');
                if(parseInt(inp.value) < parseInt(inp.max)) { inp.value = parseInt(inp.value)+1; updateModalSummary(); }
            });
            document.getElementById('btnMinus')?.addEventListener('click', function(){
                const inp = document.getElementById('modalJumlahKamar');
                if(parseInt(inp.value) > 1) { inp.value = parseInt(inp.value)-1; updateModalSummary(); }
            });
            document.getElementById('modalJumlahKamar')?.addEventListener('input', updateModalSummary);
            document.querySelectorAll('.fasilitas-checkbox-modal').forEach(cb => cb.addEventListener('change', updateModalSummary));

            function updateModalSummary(){
                const kamar = parseInt(document.getElementById('modalJumlahKamar').value) || 1;
                let fasilitasPrice = 0;
                const fasIds = [];
                document.querySelectorAll('.fasilitas-checkbox-modal:checked').forEach(cb => {
                    fasilitasPrice += parseInt(cb.getAttribute('data-harga') || 0);
                    fasIds.push(cb.value);
                });
                document.getElementById('modalFasilitas').value = fasIds.join(',');

                const baseTotal = basePricePerKamar * kamar;
                const fasTotal  = fasilitasPrice * kamar;
                const grandTotal = baseTotal + fasTotal;

                document.getElementById('summaryKamar').innerText = kamar;
                document.getElementById('summaryBase').innerText   = 'Rp ' + new Intl.NumberFormat('id-ID').format(baseTotal);
                document.getElementById('summaryTotal').innerText  = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);

                const fasRow = document.getElementById('summaryFasilitasRow');
                if(fasTotal > 0){
                    fasRow.classList.remove('d-none');
                    document.getElementById('summaryFasilitas').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(fasTotal);
                } else {
                    fasRow.classList.add('d-none');
                }
            }

            // ───── Submit: ubah fasilitas dari checkbox ke hidden field ─────
            document.getElementById('cartForm')?.addEventListener('submit', function(){
                // Kumpulkan fasilitas yang dicentang lalu masukkan ke field hidden
                const checked = [];
                document.querySelectorAll('.fasilitas-checkbox-modal:checked').forEach(cb => checked.push(cb.value));
                // Hapus semua input fasilitas[] lama
                document.querySelectorAll('input[name="fasilitas[]"]').forEach(el => el.remove());
                // Tambahkan baru
                checked.forEach(id => {
                    const inp = document.createElement('input');
                    inp.type = 'hidden'; inp.name = 'fasilitas[]'; inp.value = id;
                    this.appendChild(inp);
                });
            });
        });
        </script>
    @endpush
@endsection