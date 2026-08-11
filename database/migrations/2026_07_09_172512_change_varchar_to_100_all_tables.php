<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Merapikan ukuran varchar di berbagai tabel
     */
    public function up(): void
    {
        // Tabel galeris: kolom foto diubah ke varchar(255)
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('foto', 255)->change();
        });

        // Tabel galeri_umums: kolom foto diubah ke varchar(255)
        Schema::table('galeri_umums', function (Blueprint $table) {
            $table->string('foto', 255)->change();
        });

        // Tabel pembayarans: bukti_pembayaran dan snap_token dirapikan
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('bukti_pembayaran', 255)->nullable()->change();
            $table->string('snap_token', 191)->nullable()->change();
        });

        // Tabel wahanas: kolom foto diubah ke varchar(255)
        Schema::table('wahanas', function (Blueprint $table) {
            $table->string('foto', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('foto')->change();
        });

        Schema::table('galeri_umums', function (Blueprint $table) {
            $table->string('foto')->change();
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable()->change();
            $table->string('snap_token', 255)->nullable()->change();
        });

        Schema::table('wahanas', function (Blueprint $table) {
            $table->string('foto')->nullable()->change();
        });
    }
};
