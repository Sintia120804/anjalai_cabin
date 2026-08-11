<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah semua sisa kolom bigint ke int.
     * Kolom cache, cache_locks, failed_jobs, jobs, sessions
     * adalah tabel bawaan Laravel dibiarkan (tidak perlu diubah untuk akademik).
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // bookings: user_id & cabin_id
        DB::statement('ALTER TABLE bookings MODIFY user_id INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE bookings MODIFY cabin_id INT UNSIGNED NOT NULL');

        // booking_manuals: admin_id & cabin_id
        DB::statement('ALTER TABLE booking_manuals MODIFY admin_id INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE booking_manuals MODIFY cabin_id INT UNSIGNED NOT NULL');

        // cabin_units: cabin_id
        DB::statement('ALTER TABLE cabin_units MODIFY cabin_id INT UNSIGNED NOT NULL');

        // galeris: cabin_id
        DB::statement('ALTER TABLE galeris MODIFY cabin_id INT UNSIGNED NOT NULL');

        // pembayarans: booking_id
        DB::statement('ALTER TABLE pembayarans MODIFY booking_id INT UNSIGNED');

        // ulasans: user_id, cabin_id, booking_id
        DB::statement('ALTER TABLE ulasans MODIFY user_id INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE ulasans MODIFY cabin_id INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE ulasans MODIFY booking_id INT UNSIGNED NOT NULL');

        // wahanas: kolom harga (jika ada) -> ubah ke decimal lebih tepat
        // harga di wahanas masih bigint, ubah ke int
        DB::statement('ALTER TABLE wahanas MODIFY harga INT UNSIGNED NOT NULL DEFAULT 0');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::statement('ALTER TABLE bookings MODIFY user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE bookings MODIFY cabin_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE booking_manuals MODIFY admin_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE booking_manuals MODIFY cabin_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE cabin_units MODIFY cabin_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE galeris MODIFY cabin_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE pembayarans MODIFY booking_id BIGINT UNSIGNED');
        DB::statement('ALTER TABLE ulasans MODIFY user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE ulasans MODIFY cabin_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE ulasans MODIFY booking_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE wahanas MODIFY harga BIGINT UNSIGNED NOT NULL DEFAULT 0');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
