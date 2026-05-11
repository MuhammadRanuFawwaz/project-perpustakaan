<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengunjung', function (Blueprint $table) {

            $table->id();

            $table->string('nama_pengunjung');

<<<<<<< HEAD
            $table->enum('jenis_pengunjung', ['Murid', 'Guru']);

            $table->foreignId('id_kelas')
                ->nullable()
=======
            $table->foreignId('id_kelas')
>>>>>>> 7fd2d379b2aab1588c9827f01616e7a7d0700a36
                ->constrained('kelas')
                ->onDelete('cascade');

            $table->date('tanggal_kunjung');

            $table->time('waktu_kunjung');

            $table->string('keperluan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengunjung');
    }
};
