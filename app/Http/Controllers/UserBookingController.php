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
        // View user's bookings (Dashboard Pengunjung)
        $bookings = Booking::with(['cabin', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.dashboard', compact('bookings'));
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

        // Cek bentrok (Overlap Check): Booking overlap jika (Mulai1 < Selesai2) DAN (Selesai1 > Mulai2)
        // Cari di tabel Booking Online
        $isBookedOnline = Booking::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'ditolak')
            ->where('tanggal_checkin', '<', $tanggal_checkout)
            ->where('tanggal_checkout', '>', $tanggal_checkin)
            ->exists();

        // Cari di tabel Booking Manual
        $isBookedManual = \App\Models\BookingManual::where('cabin_id', $cabin->id)
            ->where('tanggal_checkin', '<', $tanggal_checkout)
            ->where('tanggal_checkout', '>', $tanggal_checkin)
            ->exists();

        if ($isBookedOnline || $isBookedManual) {
            return back()->with('error', 'Maaf, Cabin ini sudah dipesan pada tanggal tersebut (baik secara online maupun offline). Silakan pilih tanggal lain.');
        }

        // Kalkulasi Total Harga Basic
        $diffDays = $tanggal_checkin->diffInDays($tanggal_checkout);
        if ($diffDays < 1) {
            return back()->with('error', 'Minimal menginap adalah 1 malam.');
        }
        $totalHargaBase = $diffDays * $cabin->harga_per_malam;

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
            'tanggal_checkin' => $tanggal_checkin->format('Y-m-d'),
            'tanggal_checkout' => $tanggal_checkout->format('Y-m-d'),
            'jumlah_tamu' => $request->jumlah_tamu,
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
