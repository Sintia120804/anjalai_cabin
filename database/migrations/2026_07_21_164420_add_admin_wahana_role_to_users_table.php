<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah ENUM role untuk menambahkan nilai 'admin_wahana'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pengunjung', 'admin_wahana') NOT NULL DEFAULT 'pengunjung'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM sebelumnya (tanpa admin_wahana)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pengunjung') NOT NULL DEFAULT 'pengunjung'");
    }
};
