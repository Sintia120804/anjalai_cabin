<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingManualWahana extends Model
{
    protected $fillable = [
        'admin_id',
        'wahana_id',
        'nama_pengunjung',
        'no_hp',
        'jumlah_tiket',
        'tanggal_kunjungan',
        'total_harga',
        'status_booking',
        'catatan',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function wahana()
    {
        return $this->belongsTo(Wahana::class);
    }
}
