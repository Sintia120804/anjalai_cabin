<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->text('fasilitas_tambahan')->nullable()->after('jumlah_tamu');
            $table->decimal('total_harga_fasilitas', 10, 2)->default(0)->after('fasilitas_tambahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['fasilitas_tambahan', 'total_harga_fasilitas']);
        });
    }
};
