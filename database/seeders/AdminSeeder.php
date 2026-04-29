<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cabin;
use App\Models\Wahana;
use App\Models\GaleriUmum;
use App\Models\Booking;
use App\Models\BookingManual;
use App\Models\Pembayaran;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::create([
            'name' => 'Admin Anjalai',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'no_hp' => '08123456789'
        ]);
    }
}

