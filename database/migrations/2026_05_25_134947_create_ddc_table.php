<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ddc', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ddc');
            $table->string('nama_ddc');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ddc');
    }
};
