<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $fillable = ['cabin_id', 'foto'];

    public function cabin()
    {
        return $this->belongsTo(Cabin::class);
    }
}
