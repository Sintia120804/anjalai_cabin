<?php

namespace App\Http\Controllers;

use App\Models\Cabin;
use App\Models\Wahana;

use App\Models\GaleriUmum;
use Illuminate\Http\Request;

class PublicCabinController extends Controller
{
    public function index(Request $request)
    {
        // Get all available cabins
        $cabins = Cabin::with(['galeris', 'units'])->where('status', 'tersedia')->latest()->get();

        if ($request->filled('checkin') && $request->filled('checkout')) {
            $checkin = \Carbon\Carbon::parse($request->checkin);
            $checkout = \Carbon\Carbon::parse($request->checkout);

            $cabins = $cabins->filter(function ($cabin) use ($checkin, $checkout) {
                $totalUnits = $cabin->units->where('status', 'available')->count();
                if ($totalUnits == 0) return false;

                $bookedOnlineCount = \App\Models\Booking::where('cabin_id', $cabin->id)
                    ->where('status_booking', '!=', 'ditolak')
                    ->where('tanggal_checkin', '<', $checkout)
                    ->where('tanggal_checkout', '>', $checkin)
                    ->whereNotNull('cabin_unit_id')
                    ->distinct('cabin_unit_id')
                    ->count('cabin_unit_id');

                $bookedManualCount = \App\Models\BookingManual::where('cabin_id', $cabin->id)
                    ->where('status_booking', '!=', 'cancelled')
                    ->where('tanggal_checkin', '<', $checkout)
                    ->where('tanggal_checkout', '>', $checkin)
                    ->whereNotNull('cabin_unit_id')
                    ->distinct('cabin_unit_id')
                    ->count('cabin_unit_id');

                $totalBooked = $bookedOnlineCount + $bookedManualCount;
                
                $kamarDiKeranjang = 0;
                $cart = session()->get('cart', []);
                foreach ($cart as $item) {
                    if ($item['cabin_id'] == $cabin->id) {
                        $itemCheckin = \Carbon\Carbon::parse($item['tanggal_checkin']);
                        $itemCheckout = \Carbon\Carbon::parse($item['tanggal_checkout']);
                        if ($checkin->lt($itemCheckout) && $checkout->gt($itemCheckin)) {
                            $kamarDiKeranjang += $item['jumlah_kamar'];
                        }
                    }
                }
                
                // Set atribut dinamis untuk ditampilkan di view (sisa kamar)
                $cabin->sisa_kamar = max(0, $totalUnits - $totalBooked - $kamarDiKeranjang);

                return $cabin->sisa_kamar > 0;
            });
        } else {
            // Tanpa filter tanggal: hitung sisa kamar untuk HARI INI
            $now = \Carbon\Carbon::now();
            $today_start = $now->copy()->startOfDay();
            $today_end = $now->copy()->endOfDay();
            
            $cabins->each(function ($cabin) use ($today_start, $today_end) {
                $totalUnits = $cabin->units->where('status', 'available')->count();

                // Unit yang sedang dipakai hari ini
                $bookedOnlineIds = \App\Models\Booking::where('cabin_id', $cabin->id)
                    ->where('status_booking', '!=', 'ditolak')
                    ->where('tanggal_checkin', '<', $today_end)
                    ->where('tanggal_checkout', '>', $today_start)
                    ->whereNotNull('cabin_unit_id')
                    ->distinct('cabin_unit_id')
                    ->pluck('cabin_unit_id')->toArray();

                $bookedManualIds = \App\Models\BookingManual::where('cabin_id', $cabin->id)
                    ->where('status_booking', '!=', 'cancelled')
                    ->where('tanggal_checkin', '<', $today_end)
                    ->where('tanggal_checkout', '>', $today_start)
                    ->whereNotNull('cabin_unit_id')
                    ->distinct('cabin_unit_id')
                    ->pluck('cabin_unit_id')->toArray();

                $totalBooked = count(array_unique(array_merge($bookedOnlineIds, $bookedManualIds)));
                
                $kamarDiKeranjang = 0;
                $cart = session()->get('cart', []);
                foreach ($cart as $item) {
                    if ($item['cabin_id'] == $cabin->id) {
                        $itemCheckin = \Carbon\Carbon::parse($item['tanggal_checkin']);
                        $itemCheckout = \Carbon\Carbon::parse($item['tanggal_checkout']);
                        if ($today_start->lt($itemCheckout) && $today_end->gt($itemCheckin)) {
                            $kamarDiKeranjang += $item['jumlah_kamar'];
                        }
                    }
                }
                
                $cabin->sisa_kamar = max(0, $totalUnits - $totalBooked - $kamarDiKeranjang);
            });
        }

        $wahanas = Wahana::latest()->get();

        $galeriUmum = GaleriUmum::latest()->get();

        return view('welcome', compact('cabins', 'wahanas', 'galeriUmum'));
    }

    public function show(Cabin $cabin)
    {
        // Ensure cabin is available
        if ($cabin->status !== 'tersedia') {
            abort(404);
        }

        $cabin->load('galeris');

        // Dapatkan total unit yang tersedia untuk kategori kabin ini
        $totalUnits = $cabin->units()->where('status', 'available')->count();
        $fullyBookedDates = [];

        if ($totalUnits > 0) {
            // Ambil semua booking online yang aktif ke depan
            $onlineBookings = \App\Models\Booking::where('cabin_id', $cabin->id)
                ->where('status_booking', '!=', 'ditolak')
                ->where('tanggal_checkout', '>=', now()->toDateString())
                ->get(['tanggal_checkin', 'tanggal_checkout']);

            // Ambil semua booking manual yang aktif ke depan
            $manualBookings = \App\Models\BookingManual::where('cabin_id', $cabin->id)
                ->where('status_booking', '!=', 'cancelled')
                ->where('tanggal_checkout', '>=', now()->toDateString())
                ->get(['tanggal_checkin', 'tanggal_checkout']);

            // Hitung jumlah booking yang tumpang tindih untuk setiap hari (selama 6 bulan ke depan)
            $dateCounts = [];
            $allBookings = $onlineBookings->concat($manualBookings);

            foreach ($allBookings as $booking) {
                $start = \Carbon\Carbon::parse($booking->tanggal_checkin)->startOfDay();
                $end = \Carbon\Carbon::parse($booking->tanggal_checkout)->startOfDay();
                
                // Iterasi setiap hari dalam rentang booking
                while ($start->lt($end)) {
                    $dateStr = $start->format('Y-m-d');
                    if (!isset($dateCounts[$dateStr])) {
                        $dateCounts[$dateStr] = 0;
                    }
                    $dateCounts[$dateStr]++;
                    $start->addDay();
                }
            }

            // Jika jumlah booking di suatu tanggal >= total unit, maka tanggal tersebut full
            foreach ($dateCounts as $date => $count) {
                if ($count >= $totalUnits) {
                    $fullyBookedDates[] = $date;
                }
            }
        } else {
            // Jika tidak ada unit yang tersedia sama sekali, block semua dari hari ini sampai 10 tahun ke depan
            $fullyBookedDates[] = [
                'from' => now()->format('Y-m-d'),
                'to' => now()->addYears(10)->format('Y-m-d')
            ];
        }

        // Hitung sisa kamar real-time (unit tersedia dikurangi booking aktif ke depan)
        $now = \Carbon\Carbon::now();
        $allUnits = $cabin->units()->where('status', 'available')->get();

        $bookedOnlineNowIds = \App\Models\Booking::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'ditolak')
            ->where('tanggal_checkout', '>', $now)
            ->whereNotNull('cabin_unit_id')
            ->distinct('cabin_unit_id')
            ->pluck('cabin_unit_id')->toArray();

        $bookedManualNowIds = \App\Models\BookingManual::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'cancelled')
            ->where('tanggal_checkout', '>', $now)
            ->whereNotNull('cabin_unit_id')
            ->distinct('cabin_unit_id')
            ->pluck('cabin_unit_id')->toArray();

        $allBookedNowIds = array_unique(array_merge($bookedOnlineNowIds, $bookedManualNowIds));
        $sisaKamar = max(0, $allUnits->whereNotIn('id', $allBookedNowIds)->count());

        $kamarDiKeranjang = 0;
        $cart = session()->get('cart', []);
        foreach ($cart as $item) {
            if ($item['cabin_id'] == $cabin->id) {
                $itemCheckout = \Carbon\Carbon::parse($item['tanggal_checkout']);
                if ($itemCheckout->gt($now)) {
                    $kamarDiKeranjang += $item['jumlah_kamar'];
                }
            }
        }
        $sisaKamar = max(0, $sisaKamar - $kamarDiKeranjang);

        return view('cabin.show', compact('cabin', 'fullyBookedDates', 'sisaKamar'));
    }

    /**
     * API: Kembalikan jumlah kamar yang tersedia untuk tanggal tertentu (digunakan oleh modal JS)
     */
    public function availableUnits(Request $request, Cabin $cabin)
    {
        $checkin  = \Carbon\Carbon::parse($request->checkin);
        $checkout = \Carbon\Carbon::parse($request->checkout);

        $allUnits = $cabin->units()->where('status', 'available')->get();
        $totalUnits = $allUnits->count();

        $bookedOnlineIds = \App\Models\Booking::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'ditolak')
            ->where('tanggal_checkin', '<', $checkout)
            ->where('tanggal_checkout', '>', $checkin)
            ->whereNotNull('cabin_unit_id')
            ->pluck('cabin_unit_id')
            ->toArray();

        $bookedManualIds = \App\Models\BookingManual::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'cancelled')
            ->where('tanggal_checkin', '<', $checkout)
            ->where('tanggal_checkout', '>', $checkin)
            ->whereNotNull('cabin_unit_id')
            ->pluck('cabin_unit_id')
            ->toArray();

        $allBookedIds = array_unique(array_merge($bookedOnlineIds, $bookedManualIds));
        $available = $allUnits->whereNotIn('id', $allBookedIds)->count();

        // Kurangi dengan yang ada di keranjang
        $cart = session()->get('cart', []);
        $kamarDiKeranjang = 0;
        foreach ($cart as $item) {
            if ($item['cabin_id'] == $cabin->id) {
                $itemCheckin = \Carbon\Carbon::parse($item['tanggal_checkin']);
                $itemCheckout = \Carbon\Carbon::parse($item['tanggal_checkout']);
                if ($checkin->lt($itemCheckout) && $checkout->gt($itemCheckin)) {
                    $kamarDiKeranjang += $item['jumlah_kamar'];
                }
            }
        }
        $available = $available - $kamarDiKeranjang;

        return response()->json([
            'available' => max(0, $available),
            'total'     => $totalUnits,
        ]);
    }

    /**
     * Tampilkan halaman detail Wahana/Aktivitas
     */
    public function showWahana(\App\Models\Wahana $wahana)
    {
        $otherWahanas = \App\Models\Wahana::where('id', '!=', $wahana->id)->latest()->take(3)->get();
        return view('wahana.show', compact('wahana', 'otherWahanas'));
    }
}

