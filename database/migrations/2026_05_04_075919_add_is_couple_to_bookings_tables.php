<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('is_couple')->default(false)->after('jumlah_tamu');
        });

        Schema::table('booking_manuals', function (Blueprint $table) {
            $table->boolean('is_couple')->default(false)->after('no_hp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('is_couple');
        });

        Schema::table('booking_manuals', function (Blueprint $table) {
            $table->dropColumn('is_couple');
        });
    }
};
