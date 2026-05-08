<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_peminjaman')->constrained('peminjaman');
            $table->string('kode_buku');

            $table->enum('status_buku', ['dipinjam', 'kembali', 'rusak']);
            $table->date('tanggal_dikembalikan')->nullable();

            $table->foreign('kode_buku')
                ->references('kode_buku')
                ->on('buku');

            $table->unique(['id_peminjaman', 'kode_buku']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
