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
            $table->dateTime('tanggal_checkin')->change();
            $table->dateTime('tanggal_checkout')->change();
        });

        Schema::table('booking_manuals', function (Blueprint $table) {
            $table->dateTime('tanggal_checkin')->change();
            $table->dateTime('tanggal_checkout')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('tanggal_checkin')->change();
            $table->date('tanggal_checkout')->change();
        });

        Schema::table('booking_manuals', function (Blueprint $table) {
            $table->date('tanggal_checkin')->change();
            $table->date('tanggal_checkout')->change();
        });
    }
};
