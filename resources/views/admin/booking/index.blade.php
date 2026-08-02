@extends('admin.layouts.app')

@section('title', 'Kelola Reservasi Online')

@section('content')

<style>
    .table td,
    .table th {
        vertical-align: middle;
    }

    .table td {
        padding-top: 14px;
        padding-bottom: 14px;
    }

    .badge {
        font-size: 12px;
        font-weight: 500;
    }

    .booking-img {
        width: 55px;
        height: 40px;
        object-fit: cover;
        border-radius: 8px;
    }

    .table thead th {
        white-space: nowrap;
    }
</style>

<div class="card border-0 shadow-sm rounded-4">
    
    {{-- HEADER --}}
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0 fw-bold">
            Daftar Reservasi Online
        </h5>

        <form action="{{ route('admin.booking.index') }}" method="GET" class="d-flex gap-2">
            <input 
                type="text"
                name="search"
                class="form-control rounded-pill"
                placeholder="Cari nama tamu / email..."
                value="{{ request('search') }}"
            >

            <button type="submit" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle text-nowrap">

                {{-- HEAD --}}
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Kode</th>
                        <th>Pemesan</th>
                        <th>Cabin</th>
                        <th>Check-in / Check-out</th>
                        <th>Total Harga</th>
                        <th>Status Booking</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody>

                    @forelse($bookings as $index => $booking)

                        <tr>

                            {{-- NOMOR --}}
                            <td>
                                {{ $index + 1 }}
                            </td>

                            {{-- KODE --}}
                            <td>
                                <span class="fw-bold text-dark">
                                    {{ str_pad($booking->id, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            {{-- PEMESAN --}}
                            <td>
                                <div class="fw-bold">
                                    {{ $booking->user->name }}
                                </div>

                                <small class="text-muted">
                                    {{ $booking->user->email }}
                                </small>
                            </td>

                            {{-- CABIN --}}
                            <td>
                                <div class="fw-bold">
                                    {{ $booking->cabin->name_cabin }}
                                </div>

                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $booking->total_rooms }} Kamar
                                </span>
                            </td>

                            {{-- TANGGAL --}}
                            <td>
                                <div class="fw-semibold small">
                                    {{ \Carbon\Carbon::parse($booking->tanggal_checkin)->format('d/m/Y') }}
                                </div>

                                <div class="small text-muted">
                                    {{ \Carbon\Carbon::parse($booking->tanggal_checkout)->format('d/m/Y') }}
                                </div>
                            </td>

                            {{-- TOTAL HARGA --}}
                            <td class="fw-bold text-primary">
                                Rp {{ number_format($booking->total_order_price, 0, ',', '.') }}
                                @if($booking->jenis_pembayaran == 'dp')
                                    <div class="mt-1">
                                        <span class="badge bg-warning text-dark" style="font-size: 10px;">Status: DP (Sisa: Rp {{ number_format($booking->sisa_pembayaran * $booking->total_rooms, 0, ',', '.') }})</span>
                                    </div>
                                @else
                                    <div class="mt-1">
                                        <span class="badge bg-success" style="font-size: 10px;">Status: Lunas</span>
                                    </div>
                                @endif
                            </td>


                            {{-- STATUS BOOKING --}}
                            <td>

                                @if($booking->status_booking == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                        Menunggu
                                    </span>
                                @elseif($booking->status_booking == 'diterima')
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $checkin = \Carbon\Carbon::parse($booking->tanggal_checkin);
                                        $checkout = \Carbon\Carbon::parse($booking->tanggal_checkout);
                                    @endphp
                                    
                                    @if($now->greaterThanOrEqualTo($checkout))
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3 py-2">
                                            Selesai
                                        </span>
                                    @elseif($now->greaterThanOrEqualTo($checkin) && $now->lessThan($checkout))
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2">
                                            Check-in
                                        </span>
                                    @else
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3 py-2">
                                            Booking
                                        </span>
                                    @endif
                                @else

                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2">
                                        Ditolak
                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td>

                                <a 
                                    href="{{ route('admin.booking.show', $booking->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                >
                                    <i class="bi bi-eye"></i> Detail
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">

                                <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-25"></i>

                                Belum ada riwayat reservasi online.

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="mt-4 px-3">
            {{ $bookings->links('pagination::bootstrap-5') }}
        </div>

    </div>

</div>

@endsection