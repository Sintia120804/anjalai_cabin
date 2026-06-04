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
                    'status_pembayaran' => 'menunggu_konfirmasi', // Kembali ke menunggu konfirmasi untuk dicek admin
                ]
            );

            return back()->with('success', 'Bukti transfer berhasil diunggah! Silakan tunggu verifikasi dari admin.');
        } catch (\Exception $e) {
            \Log::error('Gagal upload bukti: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunggah bukti: ' . $e->getMessage());
        }
    }

    /**
     * Generate or fetch cached snap token for an order/booking
     */
    public function getSnapToken(Request $request, $orderId)
    {
        // Cari data pembayaran berdasarkan order_id atau booking_id
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            // Coba cari berdasarkan booking_id sebagai fallback (kasus booking lama/single cabin)
            $id = $orderId;
            if (is_string($orderId) && str_starts_with($orderId, 'BKG-')) {
                $id = (int) str_replace('BKG-', '', $orderId);
            }
            $pembayaran = Pembayaran::where('booking_id', $id)->first();
        }

        if (!$pembayaran) {
            return response()->json(['error' => 'Data pembayaran tidak ditemukan.'], 404);
        }

        // Pastikan order/booking milik user yang terautentikasi
        $booking = Booking::where('order_id', $pembayaran->order_id)
            ->orWhere('id', $pembayaran->booking_id)
            ->first();

        if (!$booking || $booking->user_id !== auth()->id()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $snapToken = $pembayaran->getOrGenerateSnapToken();

        if (!$snapToken) {
            return response()->json(['error' => 'Gagal membuat token pembayaran dari Midtrans.'], 500);
        }

        return response()->json(['snap_token' => $snapToken]);
    }

    /**
     * Handle webhook/callback from Midtrans
     */
    public function handleCallback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $paymentType = $request->payment_type;
        $orderId = $request->order_id;

        // Strip timestamp suffix if present
        $baseOrderId = $orderId;
        if (str_contains($orderId, '-')) {
            $parts = explode('-', $orderId);
            if (is_numeric(end($parts)) && strlen(end($parts)) >= 9) {
                array_pop($parts);
                $baseOrderId = implode('-', $parts);
            }
        }

        // Cari data pembayaran berdasarkan order_id
        $pembayaran = Pembayaran::where('order_id', $baseOrderId)->first();

        if (!$pembayaran) {
            // Fallback ke check booking_id (jika prefix 'BKG-')
            if (str_starts_with($baseOrderId, 'BKG-')) {
                $bookingId = (int) str_replace('BKG-', '', $baseOrderId);
                $pembayaran = Pembayaran::where('booking_id', $bookingId)->first();
            }
        }

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran not found'], 444);
        }

        if ($transactionStatus == 'capture') {
            if ($paymentType == 'credit_card') {
                if ($request->fraction_status == 'challenge') {
                    $pembayaran->status_pembayaran = 'pending';
                } else {
                    $pembayaran->status_pembayaran = 'diterima';
                }
            }
        } elseif ($transactionStatus == 'settlement') {
            $pembayaran->status_pembayaran = 'diterima';
        } elseif ($transactionStatus == 'pending') {
            $pembayaran->status_pembayaran = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $pembayaran->status_pembayaran = 'ditolak';
        }

        $pembayaran->metode_pembayaran = $paymentType;
        $pembayaran->tanggal_pembayaran = now();
        $pembayaran->save();

        // Update status booking yang berelasi
        if ($pembayaran->status_pembayaran === 'ditolak') {
            if ($pembayaran->order_id) {
                Booking::where('order_id', $pembayaran->order_id)->update(['status_booking' => 'ditolak']);
            } else {
                Booking::where('id', $pembayaran->booking_id)->update(['status_booking' => 'ditolak']);
            }
        } elseif ($pembayaran->status_pembayaran === 'diterima') {
            if ($pembayaran->order_id) {
                Booking::where('order_id', $pembayaran->order_id)->update(['status_booking' => 'diterima']);
            } else {
                Booking::where('id', $pembayaran->booking_id)->update(['status_booking' => 'diterima']);
            }
        }

        return response()->json(['message' => 'Callback handled successfully']);
    }

    /**
     * Handle frontend success callback (useful for localhost testing without ngrok)
     */
    public function frontendSuccess(Request $request)
    {
        $orderId = $request->order_id;
        if (!$orderId) {
            return response()->json(['error' => 'Order ID required'], 400);
        }

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
        
        if (!$pembayaran && str_starts_with($baseOrderId, 'BKG-')) {
            $bookingId = (int) str_replace('BKG-', '', $baseOrderId);
            $pembayaran = Pembayaran::where('booking_id', $bookingId)->first();
        }

        if ($pembayaran && $pembayaran->status_pembayaran === 'pending') {
            $pembayaran->status_pembayaran = 'diterima';
            $pembayaran->metode_pembayaran = 'Midtrans Gateway';
            $pembayaran->tanggal_pembayaran = now();
            $pembayaran->save();

            if ($pembayaran->order_id) {
                Booking::where('order_id', $pembayaran->order_id)->update(['status_booking' => 'diterima']);
            } else {
                Booking::where('id', $pembayaran->booking_id)->update(['status_booking' => 'diterima']);
            }
        }

        return response()->json(['success' => true]);
    }
}
