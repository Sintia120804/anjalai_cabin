<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabin extends Model
{
    protected $fillable = [
        'name_cabin', 
        'deskripsi', 
        'harga_per_malam', 
        'kapasitas', 
        'status'
    ];

    public function galeris()
    {
        return $this->hasMany(Galeri::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function booking_manuals()
    {
        return $this->hasMany(BookingManual::class);
    }
}
