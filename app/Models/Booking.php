<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'cabin_id',
        'cabin_unit_id',
        'tanggal_checkin',
        'tanggal_checkout',
        'jumlah_tamu',
        'is_couple',
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

    public function cabinUnit()
    {
        return $this->belongsTo(CabinUnit::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'order_id', 'order_id');
    }

}
