<?php

namespace App\Http\Controllers\AdminWahana;

use App\Http\Controllers\Controller;
use App\Models\BookingManualWahana;
use App\Models\Wahana;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingManualWahanaController extends Controller
{
    public function index()
    {
        $bookings = BookingManualWahana::with('wahana', 'admin')->latest()->get();
        return view('admin_wahana.booking_manual.index', compact('bookings'));
    }

    public function create()
    {
        $wahanas = Wahana::orderBy('nama')->get();
        return view('admin_wahana.booking_manual.create', compact('wahanas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'wahana_id'        => 'required|exists:wahanas,id',
            'nama_pengunjung'  => 'required|string|max:255',
            'no_hp'            => 'required|string|max:20',
            'jumlah_tiket'     => 'required|integer|min:1|max:999',
            'tanggal_kunjungan'=> 'required|date|after_or_equal:today',
            'status_booking'   => 'required|in:booked,selesai,cancelled',
            'catatan'          => 'nullable|string|max:500',
        ]);

        $wahana     = Wahana::findOrFail($request->wahana_id);
        $totalHarga = $wahana->harga * $request->jumlah_tiket;

        BookingManualWahana::create([
            'admin_id'         => auth()->user()->id,
            'wahana_id'        => $wahana->id,
            'nama_pengunjung'  => $request->nama_pengunjung,
            'no_hp'            => $request->no_hp,
            'jumlah_tiket'     => $request->jumlah_tiket,
            'tanggal_kunjungan'=> $request->tanggal_kunjungan,
            'total_harga'      => $totalHarga,
            'status_booking'   => $request->status_booking,
            'catatan'          => $request->catatan,
        ]);

        return redirect()->route('admin_wahana.booking_manual.index')
            ->with('success', 'Data booking wahana manual berhasil ditambahkan!');
    }

    public function edit(BookingManualWahana $booking_manual)
    {
        $wahanas = Wahana::orderBy('nama')->get();
        return view('admin_wahana.booking_manual.edit', compact('booking_manual', 'wahanas'));
    }

    public function update(Request $request, BookingManualWahana $booking_manual)
    {
        $request->validate([
            'nama_pengunjung'  => 'required|string|max:255',
            'no_hp'            => 'required|string|max:20',
            'status_booking'   => 'required|in:booked,selesai,cancelled',
            'catatan'          => 'nullable|string|max:500',
        ]);

        $booking_manual->update([
            'nama_pengunjung' => $request->nama_pengunjung,
            'no_hp'           => $request->no_hp,
            'status_booking'  => $request->status_booking,
            'catatan'         => $request->catatan,
        ]);

        return redirect()->route('admin_wahana.booking_manual.index')
            ->with('success', 'Data booking wahana berhasil diperbarui!');
    }

    public function destroy(BookingManualWahana $booking_manual)
    {
        $booking_manual->delete();
        return back()->with('success', 'Data booking wahana berhasil dihapus!');
    }
}
