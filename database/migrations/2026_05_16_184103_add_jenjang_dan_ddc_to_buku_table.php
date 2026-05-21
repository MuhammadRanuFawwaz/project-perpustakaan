<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {

            if (!Schema::hasColumn('buku', 'jenjang_kelas')) {
                $table->string('jenjang_kelas')
                    ->nullable()
                    ->after('id_kategori');
            }

            if (!Schema::hasColumn('buku', 'kode_ddc')) {
                $table->string('kode_ddc')
                    ->nullable()
                    ->after('jenjang_kelas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {

            if (Schema::hasColumn('buku', 'jenjang_kelas')) {
                $table->dropColumn('jenjang_kelas');
            }

            if (Schema::hasColumn('buku', 'kode_ddc')) {
                $table->dropColumn('kode_ddc');
            }
        });
    }
};
