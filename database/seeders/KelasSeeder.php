<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            ['X-A', 'Teknik Komputer dan Jaringan'],
            ['X-B', 'Teknik Komputer dan Jaringan'],
            ['X-C', 'Teknik Komputer dan Jaringan'],

            ['XI-A', 'Teknik Komputer dan Jaringan'],
            ['XI-B', 'Teknik Komputer dan Jaringan'],
            ['XI-C', 'Teknik Komputer dan Jaringan'],

            ['XII-A', 'Teknik Komputer dan Jaringan'],
            ['XII-B', 'Teknik Komputer dan Jaringan'],
            ['XII-C', 'Teknik Komputer dan Jaringan'],

            ['X-A', 'Teknik Pemeliharaan Mesin Industri'],
            ['X-B', 'Teknik Pemeliharaan Mesin Industri'],
            ['X-C', 'Teknik Pemeliharaan Mesin Industri'],

            ['XI-A', 'Teknik Pemeliharaan Mesin Industri'],
            ['XI-B', 'Teknik Pemeliharaan Mesin Industri'],
            ['XI-C', 'Teknik Pemeliharaan Mesin Industri'],

            ['XII-A', 'Teknik Pemeliharaan Mesin Industri'],
            ['XII-B', 'Teknik Pemeliharaan Mesin Industri'],
            ['XII-C', 'Teknik Pemeliharaan Mesin Industri'],

            ['X-A', 'Teknik Kimia Industri'],
            ['X-B', 'Teknik Kimia Industri'],
            ['X-C', 'Teknik Kimia Industri'],

            ['XI-A', 'Teknik Kimia Industri'],
            ['XI-B', 'Teknik Kimia Industri'],
            ['XI-C', 'Teknik Kimia Industri'],

            ['XII-A', 'Teknik Kimia Industri'],
            ['XII-B', 'Teknik Kimia Industri'],
            ['XII-C', 'Teknik Kimia Industri'],

            ['X-A', 'Teknik Kendaraan Ringan'],
            ['X-B', 'Teknik Kendaraan Ringan'],
            ['X-C', 'Teknik Kendaraan Ringan'],

            ['XI-A', 'Teknik Kendaraan Ringan'],
            ['XI-B', 'Teknik Kendaraan Ringan'],
            ['XI-C', 'Teknik Kendaraan Ringan'],

            ['XII-A', 'Teknik Kendaraan Ringan'],
            ['XII-B', 'Teknik Kendaraan Ringan'],
            ['XII-C', 'Teknik Kendaraan Ringan'],
        ];

        foreach ($kelas as $k) {
            Kelas::firstOrCreate([
                'nama_kelas' => $k[0],
                'jurusan' => $k[1],
            ]);
        }
    }
}
