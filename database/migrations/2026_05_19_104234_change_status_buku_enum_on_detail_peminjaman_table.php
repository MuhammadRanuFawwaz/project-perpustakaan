<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE detail_peminjaman MODIFY status_buku ENUM('dipinjam', 'kembali', 'rusak', 'hilang') DEFAULT 'dipinjam'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE detail_peminjaman MODIFY status_buku ENUM('dipinjam', 'kembali', 'rusak') DEFAULT 'dipinjam'");
    }
};