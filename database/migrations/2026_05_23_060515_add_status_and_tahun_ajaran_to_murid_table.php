<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('murid', function (Blueprint $table) {
            if (!Schema::hasColumn('murid', 'status')) {
                $table->string('status')
                    ->default('aktif')
                    ->after('id_kelas');
            }

            if (!Schema::hasColumn('murid', 'tahun_ajaran')) {
                $table->string('tahun_ajaran')
                    ->nullable()
                    ->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('murid', function (Blueprint $table) {
            if (Schema::hasColumn('murid', 'tahun_ajaran')) {
                $table->dropColumn('tahun_ajaran');
            }

            if (Schema::hasColumn('murid', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
