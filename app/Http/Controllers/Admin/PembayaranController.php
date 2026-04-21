<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with(['booking.user', 'booking.cabin'])->latest()->get();
        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['booking.user', 'booking.cabin']);
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:pending,diterima,ditolak'
        ]);

        $pembayaran->update([
            'status_pembayaran' => $request->status_pembayaran
        ]);

        // If payment is accepted, also accept the booking
        if ($request->status_pembayaran === 'diterima') {
            $pembayaran->booking->update(['status_booking' => 'diterima']);
        } 
        // If payment is rejected, reject the booking
        elseif ($request->status_pembayaran === 'ditolak') {
            $pembayaran->booking->update(['status_booking' => 'ditolak']);
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui menjadi ' . ucfirst($request->status_pembayaran));
    }
}
