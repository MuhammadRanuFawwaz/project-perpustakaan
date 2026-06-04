<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE peminjaman MODIFY status_peminjaman ENUM('dipinjam', 'kembali', 'terlambat', 'hilang') NOT NULL DEFAULT 'dipinjam'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE peminjaman MODIFY status_peminjaman ENUM('dipinjam', 'kembali', 'terlambat') NOT NULL DEFAULT 'dipinjam'");
    }
};
