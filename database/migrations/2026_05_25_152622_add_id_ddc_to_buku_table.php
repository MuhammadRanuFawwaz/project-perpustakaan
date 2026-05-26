<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            if (!Schema::hasColumn('buku', 'id_ddc')) {
                $table->foreignId('id_ddc')
                    ->nullable()
                    ->after('id_kategori')
                    ->constrained('ddc')
                    ->nullOnDelete();
            }
        });

        if (Schema::hasColumn('buku', 'kode_ddc')) {
            $kodeDdcList = DB::table('buku')
                ->whereNotNull('kode_ddc')
                ->select('kode_ddc')
                ->distinct()
                ->pluck('kode_ddc');

            foreach ($kodeDdcList as $kodeDdc) {
                if (!$kodeDdc) {
                    continue;
                }

                $ddc = DB::table('ddc')
                    ->where('kode_ddc', $kodeDdc)
                    ->first();

                if ($ddc) {
                    $ddcId = $ddc->id;
                } else {
                    $ddcId = DB::table('ddc')->insertGetId([
                        'kode_ddc' => $kodeDdc,
                        'nama_ddc' => 'DDC ' . $kodeDdc,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('buku')
                    ->where('kode_ddc', $kodeDdc)
                    ->update([
                        'id_ddc' => $ddcId,
                    ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            if (Schema::hasColumn('buku', 'id_ddc')) {
                $table->dropConstrainedForeignId('id_ddc');
            }
        });
    }
};
