<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CabinUnit extends Model
{
    protected $fillable = [
        'cabin_id',
        'unit_name',
        'status',
    ];

    public function cabin()
    {
        return $this->belongsTo(Cabin::class);
    }
}
