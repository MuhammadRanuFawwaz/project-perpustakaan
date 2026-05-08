<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            # Teknik Komputer dan Jaringan #
            ['X-A', 'Teknik Komputer dan Jaringan'],
            ['X-B', 'Teknik Komputer dan Jaringan'],
            ['X-C', 'Teknik Komputer dan Jaringan'],

            ['XI-A', 'Teknik Komputer dan Jaringan'],
            ['XI-B', 'Teknik Komputer dan Jaringan'],
            ['XI-C', 'Teknik Komputer dan Jaringan'],

            ['XII-A', 'Teknik Komputer dan Jaringan'],
            ['XII-B', 'Teknik Komputer dan Jaringan'],
            ['XII-C', 'Teknik Komputer dan Jaringan'],
            
            # Teknik Pemeliharaan Mesin Industri #
            ['X-A', 'Teknik Pemeliharaan Mesin Industri'],
            ['X-B', 'Teknik Pemeliharaan Mesin Industri'],
            ['X-C', 'Teknik Pemeliharaan Mesin Industri'],

            ['XI-A', 'Teknik Pemeliharaan Mesin Industri'],
            ['XI-B', 'Teknik Pemeliharaan Mesin Industri'],
            ['XI-C', 'Teknik Pemeliharaan Mesin Industri'],

            ['XII-A', 'Teknik Pemeliharaan Mesin Industri'],
            ['XII-B', 'Teknik Pemeliharaan Mesin Industri'],
            ['XII-C', 'Teknik Pemeliharaan Mesin Industri'],
            
            # Kimia Industri #
            ['X-A', 'Kimia Industri'],
            ['X-B', 'Kimia Industri'],
            ['X-C', 'Kimia Industri'],

            ['XI-A', 'Kimia Industri'],
            ['XI-B', 'Kimia Industri'],
            ['XI-C', 'Kimia Industri'],

            ['XII-A', 'Kimia Industri'],
            ['XII-B', 'Kimia Industri'],
            ['XII-C', 'Kimia Industri'],
            
            # Teknik Kendaraan Ringan #
            ['X-TKR-A', 'Teknik Kendaraan Ringan'],
            ['X-TKR-B', 'Teknik Kendaraan Ringan'],
            ['X-TKR-C', 'Teknik Kendaraan Ringan'],

            ['XI-TKR-A', 'Teknik Kendaraan Ringan'],
            ['XI-TKR-B', 'Teknik Kendaraan Ringan'],
            ['XI-TKR-C', 'Teknik Kendaraan Ringan'],

            ['XII-TKR-A', 'Teknik Kendaraan Ringan'],
            ['XII-TKR-B', 'Teknik Kendaraan Ringan'],
            ['XII-TKR-C', 'Teknik Kendaraan Ringan'],
        ];

        foreach ($kelas as $k) {

            Kelas::create([
                'nama_kelas' => $k[0],
                'jurusan' => $k[1],
            ]);
        }
    }
}
