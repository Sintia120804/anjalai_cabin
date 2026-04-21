<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingManual extends Model
{
    protected $fillable = [
        'admin_id',
        'cabin_id',
        'nama_pengunjung',
        'no_hp',
        'tanggal_checkin',
        'tanggal_checkout',
        'total_harga'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function cabin()
    {
        return $this->belongsTo(Cabin::class);
    }
}
