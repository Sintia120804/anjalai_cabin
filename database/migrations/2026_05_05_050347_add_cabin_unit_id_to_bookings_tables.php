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
            $table->foreignId('cabin_unit_id')->nullable()->after('cabin_id')->constrained('cabin_units')->onDelete('cascade');
        });

        Schema::table('booking_manuals', function (Blueprint $table) {
            $table->foreignId('cabin_unit_id')->nullable()->after('cabin_id')->constrained('cabin_units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['cabin_unit_id']);
            $table->dropColumn('cabin_unit_id');
        });

        Schema::table('booking_manuals', function (Blueprint $table) {
            $table->dropForeign(['cabin_unit_id']);
            $table->dropColumn('cabin_unit_id');
        });
    }
};
