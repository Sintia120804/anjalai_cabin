<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah unsignedBigInteger ke unsignedInteger pada tabel booking_manual_wahanas
     */
    public function up(): void
    {
        // Hapus foreign key dulu sebelum ubah tipe kolom
        Schema::table('booking_manual_wahanas', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['wahana_id']);
        });

        Schema::table('booking_manual_wahanas', function (Blueprint $table) {
            $table->unsignedInteger('admin_id')->change();
            $table->unsignedInteger('wahana_id')->change();
        });

        // Tambah kembali foreign key
        Schema::table('booking_manual_wahanas', function (Blueprint $table) {
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('wahana_id')->references('id')->on('wahanas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_manual_wahanas', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['wahana_id']);
        });

        Schema::table('booking_manual_wahanas', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->change();
            $table->unsignedBigInteger('wahana_id')->change();
        });

        Schema::table('booking_manual_wahanas', function (Blueprint $table) {
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('wahana_id')->references('id')->on('wahanas')->onDelete('cascade');
        });
    }
};
