<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah semua bigint ke int dan merapikan varchar.
     * Data lama TIDAK akan hilang.
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // =============================================
        // LANGKAH 1: Drop semua foreign key yang ada
        // =============================================
        DB::statement('ALTER TABLE bookings DROP FOREIGN KEY bookings_cabin_unit_id_foreign');
        DB::statement('ALTER TABLE booking_manuals DROP FOREIGN KEY booking_manuals_cabin_unit_id_foreign');

        // =============================================
        // LANGKAH 2: Ubah kolom id semua tabel: bigint -> int
        // =============================================
        DB::statement('ALTER TABLE users MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE cabins MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE wahanas MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE bookings MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE booking_manuals MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE pembayarans MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE galeris MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE galeri_umums MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE cabin_units MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE ulasans MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE booking_manual_wahanas MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT');

        // =============================================
        // LANGKAH 3: Ubah kolom foreign key: bigint -> int
        // =============================================
        DB::statement('ALTER TABLE bookings MODIFY cabin_unit_id INT UNSIGNED');
        DB::statement('ALTER TABLE booking_manuals MODIFY cabin_unit_id INT UNSIGNED');

        // =============================================
        // LANGKAH 4: Tambah kembali foreign key
        // =============================================
        DB::statement('ALTER TABLE bookings ADD CONSTRAINT bookings_cabin_unit_id_foreign FOREIGN KEY (cabin_unit_id) REFERENCES cabin_units(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE booking_manuals ADD CONSTRAINT booking_manuals_cabin_unit_id_foreign FOREIGN KEY (cabin_unit_id) REFERENCES cabin_units(id) ON DELETE CASCADE');

        // =============================================
        // LANGKAH 5: Rapikan ukuran varchar kolom foto & token
        // =============================================
        DB::statement('ALTER TABLE galeris MODIFY foto VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE galeri_umums MODIFY foto VARCHAR(255)');
        DB::statement('ALTER TABLE wahanas MODIFY foto VARCHAR(255)');
        DB::statement('ALTER TABLE pembayarans MODIFY bukti_pembayaran VARCHAR(255)');
        DB::statement('ALTER TABLE pembayarans MODIFY snap_token VARCHAR(191)');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop foreign keys
        DB::statement('ALTER TABLE bookings DROP FOREIGN KEY bookings_cabin_unit_id_foreign');
        DB::statement('ALTER TABLE booking_manuals DROP FOREIGN KEY booking_manuals_cabin_unit_id_foreign');

        // Kembalikan ke bigint
        DB::statement('ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE cabins MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE wahanas MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE bookings MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE booking_manuals MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE pembayarans MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE galeris MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE galeri_umums MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE cabin_units MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE ulasans MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE booking_manual_wahanas MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');

        DB::statement('ALTER TABLE bookings MODIFY cabin_unit_id BIGINT UNSIGNED');
        DB::statement('ALTER TABLE booking_manuals MODIFY cabin_unit_id BIGINT UNSIGNED');

        // Tambah kembali foreign key
        DB::statement('ALTER TABLE bookings ADD CONSTRAINT bookings_cabin_unit_id_foreign FOREIGN KEY (cabin_unit_id) REFERENCES cabin_units(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE booking_manuals ADD CONSTRAINT booking_manuals_cabin_unit_id_foreign FOREIGN KEY (cabin_unit_id) REFERENCES cabin_units(id) ON DELETE CASCADE');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
