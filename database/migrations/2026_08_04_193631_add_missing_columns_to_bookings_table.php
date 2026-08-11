<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom yang hilang dari tabel bookings:
     * - fasilitas_tambahan (text)
     * - total_harga_fasilitas (decimal)
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Cek dan tambah kolom fasilitas_tambahan jika belum ada
            if (!Schema::hasColumn('bookings', 'fasilitas_tambahan')) {
                $table->text('fasilitas_tambahan')->nullable()->after('is_couple');
            }

            // Cek dan tambah kolom total_harga_fasilitas jika belum ada
            if (!Schema::hasColumn('bookings', 'total_harga_fasilitas')) {
                $table->decimal('total_harga_fasilitas', 10, 2)->default(0)->after('fasilitas_tambahan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'total_harga_fasilitas')) {
                $table->dropColumn('total_harga_fasilitas');
            }
            if (Schema::hasColumn('bookings', 'fasilitas_tambahan')) {
                $table->dropColumn('fasilitas_tambahan');
            }
        });
    }
};
