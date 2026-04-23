<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingManual;
use App\Models\Booking;
use App\Models\Cabin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingManualController extends Controller
{
    public function index()
    {
        $book_manuals = BookingManual::with('cabin', 'admin')->latest()->get();
        return view('admin.booking_manual.index', compact('book_manuals'));
    }

    public function create()
    {
        $cabins = Cabin::where('status', 'tersedia')->get();

        // Ambil semua tanggal yang sudah dipesan (Online)
        $onlineBookings = Booking::where('status_booking', '!=', 'ditolak')
            ->where('tanggal_checkout', '>=', now()->toDateString())
            ->get(['cabin_id', 'tanggal_checkin', 'tanggal_checkout']);

        // Ambil semua tanggal yang sudah dipesan (Manual)
        $manualBookings = BookingManual::where('tanggal_checkout', '>=', now()->toDateString())
            ->get(['cabin_id', 'tanggal_checkin', 'tanggal_checkout']);

        $bookedDates = [];

        foreach ($onlineBookings as $b) {
            $bookedDates[$b->cabin_id][] = [
                'from' => $b->tanggal_checkin,
                'to' => $b->tanggal_checkout
            ];
        }

        foreach ($manualBookings as $b) {
            $bookedDates[$b->cabin_id][] = [
                'from' => $b->tanggal_checkin,
                'to' => $b->tanggal_checkout
            ];
        }

        return view('admin.booking_manual.create', compact('cabins', 'bookedDates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cabin_id' => 'required|exists:cabins,id',
            'nama_pengunjung' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'tanggal_checkin' => 'required|date',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
        ]);

        $cabin = Cabin::findOrFail($request->cabin_id);
        $checkin = Carbon::parse($request->tanggal_checkin);
        $checkout = Carbon::parse($request->tanggal_checkout);

        // Overlap Check with Online Booking
        $isBookedOnline = Booking::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'ditolak')
            ->where('tanggal_checkin', '<', $checkout)
            ->where('tanggal_checkout', '>', $checkin)
            ->exists();

        // Overlap Check with Manual Booking
        $isBookedManual = BookingManual::where('cabin_id', $cabin->id)
            ->where('tanggal_checkin', '<', $checkout)
            ->where('tanggal_checkout', '>', $checkin)
            ->exists();

        if ($isBookedOnline || $isBookedManual) {
            return back()->withInput()->with('error', 'Pemesanan gagal! Tanggal ini sudah dipesan (bentrok jadwal).');
        }

        $diffDays = $checkin->diffInDays($checkout);
        $totalHarga = $diffDays * $cabin->harga_per_malam;

        BookingManual::create([
            'admin_id' => auth()->id(),
            'cabin_id' => $cabin->id,
            'nama_pengunjung' => $request->nama_pengunjung,
            'no_hp' => $request->no_hp,
            'tanggal_checkin' => $checkin->format('Y-m-d'),
            'tanggal_checkout' => $checkout->format('Y-m-d'),
            'total_harga' => $totalHarga,
        ]);

        return redirect()->route('admin.booking_manual.index')->with('success', 'Reservasi manual berhasil ditambahkan!');
    }

    public function edit(BookingManual $booking_manual)
    {
        $cabins = Cabin::all();
        return view('admin.booking_manual.edit', compact('booking_manual', 'cabins'));
    }

    public function update(Request $request, BookingManual $booking_manual)
    {
        $request->validate([
            'nama_pengunjung' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
        ]);

        // Only allowing to change name and phone. To change date they must delete and recreate
        $booking_manual->update([
            'nama_pengunjung' => $request->nama_pengunjung,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.booking_manual.index')->with('success', 'Data reservasi berhasil diperbarui!');
    }

    public function destroy(BookingManual $booking_manual)
    {
        $booking_manual->delete();
        return back()->with('success', 'Pemesanan manual berhasil dihapus!');
    }
}
