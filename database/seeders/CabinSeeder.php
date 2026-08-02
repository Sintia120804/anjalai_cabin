<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabin;
use App\Models\CabinUnit;

class CabinSeeder extends Seeder
{
    public function run(): void
    {
        $cabin1 = Cabin::create([
            'name_cabin' => 'Double Cabin 1',
            'deskripsi' => 'sangat nyaman',
            'harga_weekday' => 900000.00,
            'harga_weekend' => 1000000.00,
            'fasilitas' => [
                "Sarapan",
                "Balcone",
                "Water Heater",
                "Water Dispenser",
                "Teh dan Kopi",
                "Peralatan Mandi",
                "Tempat BBQ (belum termasuk gas dan arang)",
                "Tempat api unggun"
            ],
            'kapasitas' => 5,
            'status' => 'tersedia'
        ]);

        CabinUnit::create([
            'cabin_id' => $cabin1->id,
            'unit_name' => 'Unit A',
            'status' => 'available'
        ]);

        CabinUnit::create([
            'cabin_id' => $cabin1->id,
            'unit_name' => 'Unit B',
            'status' => 'available'
        ]);

        $this->command->info('✅ Data Cabin Dummy berhasil dibuat!');
    }
}
