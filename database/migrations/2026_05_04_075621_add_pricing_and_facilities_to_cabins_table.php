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
        Schema::table('cabins', function (Blueprint $table) {
            $table->decimal('harga_weekday', 15, 2)->after('harga_per_malam')->nullable();
            $table->decimal('harga_weekend', 15, 2)->after('harga_weekday')->nullable();
            $table->decimal('harga_couple', 15, 2)->after('harga_weekend')->nullable();
            $table->text('fasilitas')->after('harga_couple')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cabins', function (Blueprint $table) {
            $table->dropColumn(['harga_weekday', 'harga_weekend', 'harga_couple', 'fasilitas']);
        });
    }
};
