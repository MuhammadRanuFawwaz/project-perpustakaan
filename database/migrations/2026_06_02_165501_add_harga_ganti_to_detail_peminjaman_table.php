<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE detail_peminjaman MODIFY status_buku ENUM('dipinjam','kembali','hilang','rusak') NOT NULL DEFAULT 'dipinjam'");

        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->decimal('harga_ganti', 12, 2)
                ->nullable()
                ->after('status_buku');
        });
    }

    public function down(): void
    {
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->dropColumn('harga_ganti');
        });

        DB::statement("ALTER TABLE detail_peminjaman MODIFY status_buku ENUM('dipinjam','kembali','rusak') NOT NULL DEFAULT 'dipinjam'");
    }
};
