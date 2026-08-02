<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $fillable = [
        'user_id',
        'cabin_id',
        'booking_id',
        'rating',
        'komentar',
        'is_tampil'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cabin()
    {
        return $this->belongsTo(Cabin::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
