<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Handle manual payment proof upload
     */
    public function uploadProof(Request $request, Booking $booking)
    {
        // Pastikan hanya owner yang bisa upload
        if ($booking->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:5048',
        ]);

        try {
            // Hapus bukti lama jika ada
            if ($booking->pembayaran && $booking->pembayaran->bukti_pembayaran) {
                Storage::disk('public')->delete($booking->pembayaran->bukti_pembayaran);
            }

            // Upload file baru
            $file = $request->file('bukti_pembayaran');
            $filename = 'bukti_' . time() . '_' . $booking->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('bukti_pembayaran', $filename, 'public');

            // Tentukan key pencarian: gunakan order_id jika ada, jika tidak gunakan booking_id (untuk data lama)
            $searchKey = $booking->order_id ? ['order_id' => $booking->order_id] : ['booking_id' => $booking->id];

            // Update data pembayaran menggunakan updateOrCreate berdasarkan order_id / booking_id
            $pembayaran = Pembayaran::updateOrCreate(
                $searchKey,
                [
                    'bukti_pembayaran' => $path,
                    'tanggal_pembayaran' => now(),
                    'metode_pembayaran' => 'Transfer Bank',
                    'status_pembayaran' => 'pending', // Kembali ke pending untuk dicek admin
                ]
            );

            return back()->with('success', 'Bukti transfer berhasil diunggah! Silakan tunggu verifikasi dari admin.');
        } catch (\Exception $e) {
            \Log::error('Gagal upload bukti: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunggah bukti: ' . $e->getMessage());
        }
    }
}
