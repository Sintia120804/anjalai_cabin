<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'cabin', 'pembayaran'])->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(10)->withQueryString();

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

        $booking->update([
            'status_booking' => $request->status_booking
        ]);

        // Sinkronisasi status pembayaran sesuai dengan status booking
        if ($booking->pembayaran) {
            if ($request->status_booking === 'ditolak') {
                $booking->pembayaran->update(['status_pembayaran' => 'ditolak']);
            } elseif ($request->status_booking === 'diterima') {
                $booking->pembayaran->update([
                    'status_pembayaran' => 'diterima',
                    'tanggal_pembayaran' => $booking->pembayaran->tanggal_pembayaran ?? now()
                ]);
            }
        }

        return back()->with('success', 'Status Pemesanan berhasil diperbarui menjadi ' . ucfirst($request->status_booking));
    }
}
