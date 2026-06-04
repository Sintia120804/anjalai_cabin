<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'order_id',
        'booking_id',
        'metode_pembayaran',
        'tanggal_pembayaran',
        'jumlah_bayar',
        'bukti_pembayaran',
        'status_pembayaran',
        'snap_token'
    ];

    /**
     * Generate or get cached Midtrans Snap Token
     */
    public function getOrGenerateSnapToken()
    {
        if ($this->snap_token) {
            return $this->snap_token;
        }

        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            // Disable SSL verification for sandbox / local development to avoid Windows cURL certificate issues
            if (!config('midtrans.is_production')) {
                \Midtrans\Config::$curlOptions = [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_HTTPHEADER => [],
                ];
            }

            $user = auth()->user();
            
            $params = [
                'transaction_details' => [
                    // Tambahkan suffix timestamp agar order_id selalu unik di Midtrans
                    // (Midtrans menolak order_id yang sudah pernah dipakai sebelumnya)
                    'order_id'     => ($this->order_id ?? ('BKG-' . $this->booking_id)) . '-' . time(),
                    'gross_amount' => (int) $this->jumlah_bayar,
                ],
                'customer_details' => [
                    'first_name' => $user->name ?? 'Guest',
                    'email'      => $user->email ?? '',
                    'phone'      => $user->no_hp ?? '',
                ],
                // Menampilkan QRIS, GoPay, dan metode lainnya
                'enabled_payments' => [
                    'qris',
                    'gopay',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'permata_va',
                    'other_va',
                    'credit_card',
                    'alfamart',
                    'indomaret',
                ],
                'callbacks' => [
                    'finish' => route('user.dashboard'),
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $this->snap_token = $snapToken;
            $this->save();

            return $snapToken;
        } catch (\Exception $e) {
            \Log::error('Midtrans Snap Token Generation Failed: ' . $e->getMessage());
            return null;
        }
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
