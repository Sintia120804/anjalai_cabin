<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabin extends Model
{
    protected $fillable = [
        'name_cabin', 
        'deskripsi', 
        'harga_weekday',
        'harga_weekend',
        'harga_couple',
        'kapasitas', 
        'status',
        'fasilitas'
    ];

    protected $casts = [
        'fasilitas' => 'array',
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

    public function units()
    {
        return $this->hasMany(CabinUnit::class);
    }
}
