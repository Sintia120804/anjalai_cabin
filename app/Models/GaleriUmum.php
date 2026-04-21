<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriUmum extends Model
{
    use HasFactory;

    protected $table = 'galeri_umums';
    protected $fillable = ['foto', 'caption'];
}
