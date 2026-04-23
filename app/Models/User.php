<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'google_id',
        'password',
        'no_hp',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    public function testimonis()
    {
        return $this->hasMany(Testimoni::class);
    }

}
