<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('kelas')
            ->where('jurusan', 'Kimia Industri')
            ->update([
                'jurusan' => 'Teknik Kimia Industri',
            ]);

        $kelas = DB::table('kelas')->get();

        foreach ($kelas as $k) {
            $namaKelas = strtoupper(trim($k->nama_kelas));

            $namaKelas = str_replace('TKRO', 'TKR', $namaKelas);

            if (preg_match('/^(X|XI|XII)-(TKJ|TKR|TMI|KI)-([A-Z])$/', $namaKelas, $match)) {
                DB::table('kelas')
                    ->where('id', $k->id)
                    ->update([
                        'nama_kelas' => $match[1] . '-' . $match[3],
                    ]);
            }
        }
    }

    public function down(): void
    {
        //
    }
};
    