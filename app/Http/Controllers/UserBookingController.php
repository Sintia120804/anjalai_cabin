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

    public function index()
    {
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

        // Proses Fasilitas Tambahan
        $totalHargaFasilitas = 0;
        $fasilitasTambahanJson = null;

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

    public function destroy(Booking $booking)
    {
        // Ensure only the owner can delete, and only if it's pending
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status_booking === 'pending' || $booking->pembayaran?->status_pembayaran === 'pending') {
            $booking->delete();
            return redirect()->route('user.dashboard')->with('success', 'Reservasi berhasil dibatalkan.');
        }

        return redirect()->route('user.dashboard')->with('error', 'Reservasi tidak dapat dibatalkan karena sudah diproses.');
    }
}
