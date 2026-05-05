<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingManual extends Model
{
    protected $fillable = [
        'admin_id',
        'cabin_id',
        'cabin_unit_id',
        'nama_pengunjung',
        'no_hp',
        'is_couple',
        'tanggal_checkin',
        'tanggal_checkout',
        'total_harga',
        'status_booking'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function cabin()
    {
        return $this->belongsTo(Cabin::class);
    }

    public function cabinUnit()
    {
        return $this->belongsTo(CabinUnit::class);
    }
}
