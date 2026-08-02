<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_manual_wahanas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('wahana_id');
            $table->string('nama_pengunjung', 25);
            $table->string('no_hp', 20);
            $table->integer('jumlah_tiket')->default(1);
            $table->date('tanggal_kunjungan');
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->enum('status_booking', ['booked', 'selesai', 'cancelled'])->default('booked');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('wahana_id')->references('id')->on('wahanas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_manual_wahanas');
    }
};
