<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('murid', function (Blueprint $table) {
            $table->id();

            $table->string('nis')->unique();

            $table->string('nama_murid');

            $table->foreignId('id_kelas')
                ->constrained('kelas')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('murid');
    }
};
