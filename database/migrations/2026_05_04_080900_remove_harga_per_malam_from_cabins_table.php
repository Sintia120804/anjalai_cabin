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
            $table->dropColumn('harga_per_malam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cabins', function (Blueprint $table) {
            $table->decimal('harga_per_malam', 10, 2)->after('deskripsi');
        });
    }
};
