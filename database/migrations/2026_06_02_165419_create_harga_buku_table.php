<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_buku', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku')->unique();
            $table->decimal('harga', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('kode_buku')
                ->references('kode_buku')
                ->on('buku')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_buku');
    }
};
