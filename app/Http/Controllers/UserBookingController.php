<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cabin;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingNotification;

class UserBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Handle Midtrans redirect parameters for localhost/sandbox testing
        if ($request->has('order_id') && ($request->get('transaction_status') === 'settlement' || $request->get('status_code') === '200')) {
            $orderId = $request->get('order_id');
            
            // Strip timestamp suffix if present
            $baseOrderId = $orderId;
            if (str_contains($orderId, '-')) {
                $parts = explode('-', $orderId);
                if (is_numeric(end($parts)) && strlen(end($parts)) >= 9) {
                    array_pop($parts);
                    $baseOrderId = implode('-', $parts);
                }
            }

            $pembayaran = Pembayaran::where('order_id', $baseOrderId)->first();
            if ($pembayaran && $pembayaran->status_pembayaran === 'pending') {
                $pembayaran->status_pembayaran = 'diterima';
                $pembayaran->metode_pembayaran = 'Midtrans Gateway';
                $pembayaran->tanggal_pembayaran = now();
                $pembayaran->save();
            }
        }

        // View user's bookings (Dashboard Pengunjung) dikelompokkan berdasarkan order_id
        $orders = Booking::with(['cabin', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->groupBy(function($item) {
                return $item->order_id ?? 'BKG-' . $item->id;
            });

        return view('user.dashboard', compact('orders'));
    }

    public function store(Request $request, Cabin $cabin)
    {
        $request->validate([
            'dates' => 'required|string',
            'jumlah_tamu' => 'required|integer|min:1|max:' . $cabin->kapasitas
        ]);

        // Parse dates from Flatpickr (Format: "YYYY-MM-DD to YYYY-MM-DD")
        $dates = explode(' to ', $request->dates);

        if (count($dates) !== 2) {
            return back()->with('error', 'Silakan pilih rentang tanggal (Check-In dan Check-Out) di kalender.');
        }

        $tanggal_checkin = Carbon::parse($dates[0]);
        $tanggal_checkout = Carbon::parse($dates[1]);

        // Cari unit-unit dari kategori ini yang berstatus available
        $availableUnits = $cabin->units()->where('status', 'available')->get();

        if ($availableUnits->isEmpty()) {
            return back()->with('error', 'Maaf, belum ada unit kamar yang tersedia untuk kategori ini.');
        }

        // Cari ID unit yang sudah dipesan (Overlap Check)
        // Booking overlap jika (Mulai1 < Selesai2) DAN (Selesai1 > Mulai2)
        $bookedOnlineUnitIds = Booking::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'ditolak')
            ->where('tanggal_checkin', '<', $tanggal_checkout)
            ->where('tanggal_checkout', '>', $tanggal_checkin)
            ->whereNotNull('cabin_unit_id')
            ->pluck('cabin_unit_id')
            ->toArray();

        $bookedManualUnitIds = \App\Models\BookingManual::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'cancelled')
            ->where('tanggal_checkin', '<', $tanggal_checkout)
            ->where('tanggal_checkout', '>', $tanggal_checkin)
            ->whereNotNull('cabin_unit_id')
            ->pluck('cabin_unit_id')
            ->toArray();

        $allBookedUnitIds = array_unique(array_merge($bookedOnlineUnitIds, $bookedManualUnitIds));

        // Cari 1 unit yang ID-nya TIDAK ADA di $allBookedUnitIds
        $availableUnit = $availableUnits->whereNotIn('id', $allBookedUnitIds)->first();

        if (!$availableUnit) {
            return back()->with('error', 'Maaf, semua unit Cabin penuh pada rentang tanggal tersebut. Silakan pilih tanggal lain.');
        }

        // Kalkulasi Total Harga Basic (Berdasarkan blok 24 jam)
        $isCouple = $request->has('is_couple');
        $totalHargaBase = 0;
        
        $currentDate = $tanggal_checkin->copy()->startOfDay();
        $endDate = $tanggal_checkout->copy()->startOfDay();

        if ($tanggal_checkin->diffInHours($tanggal_checkout) < 1) {
            return back()->with('error', 'Minimal menginap adalah 1 jam.');
        }

        while ($currentDate->lt($endDate)) {
            if ($isCouple) {
                $totalHargaBase += $cabin->harga_couple;
            } else {
                // Day of week: 0 (Sun) to 6 (Sat)
                // Carbon's dayOfWeek: 0 (Sunday) to 6 (Saturday)
                $day = $currentDate->dayOfWeek;
                if ($day >= 0 && $day <= 4) { // Sunday to Thursday
                    $totalHargaBase += $cabin->harga_weekday;
                } else { // Friday to Saturday
                    $totalHargaBase += $cabin->harga_weekend;
                }
            }
            $currentDate->addDay();
        }

        // Proses Fasilitas Tambahan (Dinonaktifkan)
        $totalHargaFasilitas = 0;
        $fasilitasTambahanJson = null;

        /*
        if ($request->has('fasilitas') && is_array($request->fasilitas)) {
            $fasilitasData = [];
            $fasilitasList = \App\Models\FasilitasTambahan::whereIn('id', $request->fasilitas)->get();

            foreach ($fasilitasList as $fasilitas) {
                $totalHargaFasilitas += $fasilitas->harga;
                $fasilitasData[] = [
                    'nama' => $fasilitas->nama,
                    'harga' => $fasilitas->harga
                ];
            }
            $fasilitasTambahanJson = json_encode($fasilitasData);
        }
        */

        $totalHargaAkhir = $totalHargaBase + $totalHargaFasilitas;

        // Create Booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'cabin_id' => $cabin->id,
            'cabin_unit_id' => $availableUnit->id,
            'tanggal_checkin' => $tanggal_checkin->format('Y-m-d H:i:s'),
            'tanggal_checkout' => $tanggal_checkout->format('Y-m-d H:i:s'),
            'jumlah_tamu' => $request->jumlah_tamu,
            'is_couple' => $isCouple,
            'fasilitas_tambahan' => $fasilitasTambahanJson,
            'total_harga_fasilitas' => $totalHargaFasilitas,
            'total_harga' => $totalHargaAkhir,
            'status_booking' => 'pending'
        ]);

        // Create Pembayaran (Pending)
        Pembayaran::create([
            'booking_id' => $booking->id,
            'metode_pembayaran' => null,
            'tanggal_pembayaran' => null,
            'jumlah_bayar' => $totalHargaAkhir,
            'bukti_pembayaran' => null,
            'status_pembayaran' => 'pending'
        ]);

        // Kirim Email Notifikasi
        try {
            Mail::to(Auth::user()->email)->send(new BookingNotification($booking));
        } catch (\Exception $e) {
            // Log error if mail fails, but don't stop the process
            \Illuminate\Support\Facades\Log::error('Mail Error: ' . $e->getMessage());
        }

        return redirect()->route('user.dashboard')->with('success', 'Reservasi berhasil dibuat! Silakan lakukan pembayaran agar pesanan tidak dibatalkan.');
    }

    public function reschedule(Request $request, $orderId)
    {
        $request->validate([
            'new_checkin' => 'required|date|after_or_equal:today',
        ]);

        $orderBookings = Booking::where('user_id', Auth::id())
            ->where(function($query) use ($orderId) {
                $query->where('order_id', $orderId)
                      ->orWhere('id', str_replace('BKG-', '', $orderId));
            })->get();

        if ($orderBookings->isEmpty()) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $firstBooking = $orderBookings->first();
        
        // Cek syarat H-14
        if (Carbon::now()->addDays(14)->gt(Carbon::parse($firstBooking->tanggal_checkin))) {
            return back()->with('error', 'Reschedule maksimal dilakukan H-14 sebelum tanggal Check-In.');
        }
        
        if ($firstBooking->reschedule_count > 0) {
            return back()->with('error', 'Pesanan ini sudah pernah di-reschedule.');
        }

        $oldCheckin = Carbon::parse($firstBooking->tanggal_checkin);
        $oldCheckout = Carbon::parse($firstBooking->tanggal_checkout);
        $durationHours = $oldCheckin->diffInHours($oldCheckout);

        $newCheckin = Carbon::parse($request->new_checkin)->setTime($oldCheckin->hour, $oldCheckin->minute, $oldCheckin->second);
        $newCheckout = clone $newCheckin;
        $newCheckout->addHours($durationHours);

        $bookingsGrouped = $orderBookings->groupBy('cabin_id');
        
        foreach ($bookingsGrouped as $cabinId => $bGroup) {
            $cabin = Cabin::find($cabinId);
            $neededUnitsCount = $bGroup->count();

            $bookedOnlineUnitIds = Booking::where('cabin_id', $cabin->id)
                ->where('status_booking', '!=', 'ditolak')
                ->whereNotIn('id', $bGroup->pluck('id'))
                ->where('tanggal_checkin', '<', $newCheckout)
                ->where('tanggal_checkout', '>', $newCheckin)
                ->whereNotNull('cabin_unit_id')
                ->pluck('cabin_unit_id')
                ->toArray();

            $bookedManualUnitIds = \App\Models\BookingManual::where('cabin_id', $cabin->id)
                ->where('status_booking', '!=', 'cancelled')
                ->where('tanggal_checkin', '<', $newCheckout)
                ->where('tanggal_checkout', '>', $newCheckin)
                ->whereNotNull('cabin_unit_id')
                ->pluck('cabin_unit_id')
                ->toArray();

            $allBookedUnitIds = array_unique(array_merge($bookedOnlineUnitIds, $bookedManualUnitIds));
            
            $availableUnits = $cabin->units()->where('status', 'available')->whereNotIn('id', $allBookedUnitIds)->get();
            
            if ($availableUnits->count() < $neededUnitsCount) {
                return back()->with('error', 'Kamar ' . $cabin->name_cabin . ' sudah penuh pada tanggal yang baru. Silakan pilih tanggal lain.');
            }

            $unitIndex = 0;
            foreach ($bGroup as $booking) {
                $booking->tanggal_checkin = $newCheckin->format('Y-m-d H:i:s');
                $booking->tanggal_checkout = $newCheckout->format('Y-m-d H:i:s');
                $booking->cabin_unit_id = $availableUnits[$unitIndex]->id;
                $booking->reschedule_count = 1;
                $booking->save();
                $unitIndex++;
            }
        }

        return back()->with('success', 'Berhasil! Jadwal pemesanan Anda telah diubah ke ' . $newCheckin->format('d M Y') . '.');
    }

    public function destroy(Booking $booking)
    {
        // Ensure only the owner can delete, and only if it's pending
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $pembayaran = $booking->order_id ? \App\Models\Pembayaran::where('order_id', $booking->order_id)->first() : \App\Models\Pembayaran::where('booking_id', $booking->id)->first();

        if ($booking->status_booking === 'pending' || in_array($pembayaran?->status_pembayaran, ['pending', 'menunggu_konfirmasi'])) {
            if ($booking->order_id) {
                $bookings = Booking::where('order_id', $booking->order_id)->get();
                foreach ($bookings as $b) {
                    $b->delete();
                }
            } else {
                $booking->delete();
            }
            return redirect()->route('user.dashboard')->with('success', 'Reservasi berhasil dibatalkan.');
        }

        return redirect()->route('user.dashboard')->with('error', 'Reservasi tidak dapat dibatalkan karena sudah diproses.');
    }

    public function exportPdf($orderId)
    {
        // Cari semua booking dengan orderId tersebut milik user ini
        $orderBookings = Booking::with(['cabin', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->where(function($query) use ($orderId) {
                $query->where('order_id', $orderId)
                      ->orWhere('id', str_replace('BKG-', '', $orderId));
            })
            ->get();

        if ($orderBookings->isEmpty()) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $firstBooking = $orderBookings->first();
        $pembayaran = $firstBooking->order_id 
            ? Pembayaran::where('order_id', $firstBooking->order_id)->first() 
            : Pembayaran::where('booking_id', $firstBooking->id)->first();
            
        $grandTotal = $orderBookings->sum('total_harga');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.booking_pdf', compact('orderBookings', 'firstBooking', 'pembayaran', 'grandTotal', 'orderId'));
        
        return $pdf->download('Bukti-Booking-' . $orderId . '.pdf');
    }
    public function storeUlasan(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if user already reviewed this booking
        $existing = \App\Models\Ulasan::where('booking_id', $booking->id)->first();
        if ($existing) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        \App\Models\Ulasan::create([
            'user_id' => Auth::id(),
            'cabin_id' => $booking->cabin_id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'is_tampil' => 1
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil disimpan.');
    }
}
