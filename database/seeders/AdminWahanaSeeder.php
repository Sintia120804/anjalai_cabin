<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminWahanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'adminwahana@anjalai.com'],
            [
                'name'       => 'Admin Wahana',
                'email'      => 'adminwahana@anjalai.com',
                'password'   => Hash::make('adminwahana123'),
                'role'       => 'admin_wahana',
                'no_hp'      => '081234567891',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('✅ Admin Wahana berhasil dibuat!');
        $this->command->info('   Email   : adminwahana@anjalai.com');
        $this->command->info('   Password: adminwahana123');
    }
}
