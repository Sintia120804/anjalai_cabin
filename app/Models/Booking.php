<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'cabin_id',
        'tanggal_checkin',
        'tanggal_checkout',
        'jumlah_tamu',
        'fasilitas_tambahan',
        'total_harga_fasilitas',
        'total_harga',
        'status_booking'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cabin()
    {
        return $this->belongsTo(Cabin::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

}
