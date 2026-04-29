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
        Schema::table('wahanas', function (Blueprint $table) {
            $table->bigInteger('harga')->default(0)->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wahanas', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }
};
