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
        'status_pembayaran'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
