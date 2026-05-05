<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // Ambil ID booking pertama dari setiap order_id unik
        $latestIds = Booking::selectRaw('MIN(id) as id')
            ->groupBy('order_id')
            ->pluck('id');

        $query = Booking::whereIn('id', $latestIds)
            ->with(['user', 'cabin', 'pembayaran'])
            ->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(10)->withQueryString();

        // Hitung jumlah kamar per order untuk ditampilkan di view
        foreach($bookings as $booking) {
            $booking->total_rooms = Booking::where('order_id', $booking->order_id)->count();
            $booking->total_order_price = Booking::where('order_id', $booking->order_id)->sum('total_harga');
        }

        return view('admin.booking.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'cabin', 'pembayaran']);
        return view('admin.booking.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status_booking' => 'required|in:pending,diterima,ditolak'
        ]);

        // Update SEMUA booking dalam satu order yang sama
        Booking::where('order_id', $booking->order_id)->update([
            'status_booking' => $request->status_booking
        ]);

        // Sinkronisasi status pembayaran sesuai dengan status booking (berdasarkan order_id)
        $pembayaran = \App\Models\Pembayaran::where('order_id', $booking->order_id)->first();
        if ($pembayaran) {
            if ($request->status_booking === 'ditolak') {
                $pembayaran->update(['status_pembayaran' => 'ditolak']);
            } elseif ($request->status_booking === 'diterima') {
                $pembayaran->update([
                    'status_pembayaran' => 'diterima',
                    'tanggal_pembayaran' => $pembayaran->tanggal_pembayaran ?? now()
                ]);
            }
        }

        return back()->with('success', 'Seluruh pesanan dalam Order ID ' . $booking->order_id . ' berhasil diperbarui menjadi ' . ucfirst($request->status_booking));
    }
}
