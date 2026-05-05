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

        $cabins = Cabin::all(); // Get all cabins to pass pricing data
 
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
            'status_booking' => 'required|in:booked,check-in,check-out,cancelled',
        ]);

        $cabin = Cabin::findOrFail($request->cabin_id);
        $checkin = Carbon::parse($request->tanggal_checkin);
        $checkout = Carbon::parse($request->tanggal_checkout);

        // Cari unit-unit dari kategori ini yang berstatus available
        $availableUnits = $cabin->units()->where('status', 'available')->get();

        if ($availableUnits->isEmpty()) {
            return back()->withInput()->with('error', 'Pemesanan gagal! Belum ada unit kamar yang tersedia untuk kategori ini.');
        }

        // Overlap Check with Online Booking
        $bookedOnlineUnitIds = Booking::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'ditolak')
            ->where('tanggal_checkin', '<', $checkout)
            ->where('tanggal_checkout', '>', $checkin)
            ->whereNotNull('cabin_unit_id')
            ->pluck('cabin_unit_id')
            ->toArray();

        // Overlap Check with Manual Booking
        $bookedManualUnitIds = BookingManual::where('cabin_id', $cabin->id)
            ->where('status_booking', '!=', 'cancelled')
            ->where('tanggal_checkin', '<', $checkout)
            ->where('tanggal_checkout', '>', $checkin)
            ->whereNotNull('cabin_unit_id')
            ->pluck('cabin_unit_id')
            ->toArray();

        $allBookedUnitIds = array_unique(array_merge($bookedOnlineUnitIds, $bookedManualUnitIds));

        // Cari 1 unit yang ID-nya TIDAK ADA di $allBookedUnitIds
        $availableUnit = $availableUnits->whereNotIn('id', $allBookedUnitIds)->first();

        if (!$availableUnit) {
            return back()->withInput()->with('error', 'Pemesanan gagal! Semua unit Cabin penuh pada tanggal tersebut.');
        }

        $isCouple = $request->has('is_couple');
        $totalHarga = 0;
        
        $currentDate = $checkin->copy()->startOfDay();
        $endDate = $checkout->copy()->startOfDay();

        if ($checkin->diffInHours($checkout) < 1) {
            return back()->withInput()->with('error', 'Minimal durasi reservasi adalah 1 jam.');
        }

        while ($currentDate->lt($endDate)) {
            if ($isCouple) {
                $totalHarga += $cabin->harga_couple;
            } else {
                $day = $currentDate->dayOfWeek;
                if ($day >= 0 && $day <= 4) { // Sunday to Thursday
                    $totalHarga += $cabin->harga_weekday;
                } else { // Friday to Saturday
                    $totalHarga += $cabin->harga_weekend;
                }
            }
            $currentDate->addDay();
        }

        BookingManual::create([
            'admin_id' => auth()->id(),
            'cabin_id' => $cabin->id,
            'cabin_unit_id' => $availableUnit->id,
            'nama_pengunjung' => $request->nama_pengunjung,
            'no_hp' => $request->no_hp,
            'is_couple' => $isCouple,
            'tanggal_checkin' => $checkin->format('Y-m-d H:i:s'),
            'tanggal_checkout' => $checkout->format('Y-m-d H:i:s'),
            'total_harga' => $totalHarga,
            'status_booking' => $request->status_booking,
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
            'status_booking' => 'required|in:booked,check-in,check-out,cancelled',
        ]);

        // Only allowing to change name and phone. To change date they must delete and recreate
        $booking_manual->update([
            'nama_pengunjung' => $request->nama_pengunjung,
            'no_hp' => $request->no_hp,
            'status_booking' => $request->status_booking,
        ]);

        return redirect()->route('admin.booking_manual.index')->with('success', 'Data reservasi berhasil diperbarui!');
    }

    public function destroy(BookingManual $booking_manual)
    {
        $booking_manual->delete();
        return back()->with('success', 'Pemesanan manual berhasil dihapus!');
    }
}
