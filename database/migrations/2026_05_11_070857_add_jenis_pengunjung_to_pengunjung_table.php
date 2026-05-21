<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pengunjung', 'jenis_pengunjung')) {
            Schema::table('pengunjung', function (Blueprint $table) {
                $table->enum('jenis_pengunjung', ['Murid', 'Guru'])
                    ->after('nama_pengunjung');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pengunjung', 'jenis_pengunjung')) {
            Schema::table('pengunjung', function (Blueprint $table) {
                $table->dropColumn('jenis_pengunjung');
            });
        }
    }
};
