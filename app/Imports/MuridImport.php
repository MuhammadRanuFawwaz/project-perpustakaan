<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Murid;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MuridImport
{
    public function import($file)
    {
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
        } catch (\Throwable $e) {
            throw new \Exception('File Excel tidak bisa dibaca.');
        }

        $berhasil = 0;
        $dilewati = 0;

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $sheetName = trim($sheet->getTitle());

            if (str_contains(strtoupper($sheetName), 'DAFTAR NILAI')) {
                continue;
            }

            $infoKelas = $this->ambilInfoKelasDariSheet($sheetName);

            if (!$infoKelas) {
                $infoKelas = $this->ambilInfoKelasDariHeader($sheet);
            }

            if (!$infoKelas) {
                continue;
            }

            $kelas = $this->cariAtauBuatKelas(
                $infoKelas['tingkat'],
                $infoKelas['rombel'],
                $infoKelas['jurusan']
            );

            for ($row = 1; $row <= $sheet->getHighestRow(); $row++) {
                $nis = trim((string) $sheet->getCell('B' . $row)->getValue());
                $nama = trim((string) $sheet->getCell('C' . $row)->getValue());

                if (!$this->validNis($nis) || !$this->validNama($nama)) {
                    $dilewati++;
                    continue;
                }

                Murid::updateOrCreate(
                    [
                        'nis' => $nis,
                    ],
                    [
                        'nama_murid' => strtoupper($nama),
                        'id_kelas' => $kelas->id,
                        'status' => 'aktif',
                    ]
                );

                $berhasil++;
            }
        }

        return [
            'berhasil' => $berhasil,
            'dilewati' => $dilewati,
        ];
    }

    private function ambilInfoKelasDariSheet($sheetName)
    {
        $nama = strtoupper(trim($sheetName));

        $nama = str_replace('TKRO', 'TKR', $nama);
        $nama = preg_replace('/\s+/', ' ', $nama);

        if (!preg_match('/^(X|XI|XII)\s+(TMI|KI|TKJ|TKR)\s+([A-Z])$/', $nama, $match)) {
            return null;
        }

        return [
            'tingkat' => $match[1],
            'jurusan' => $this->namaJurusan($match[2]),
            'rombel' => $match[3],
        ];
    }

    private function ambilInfoKelasDariHeader($sheet)
    {
        for ($row = 1; $row <= 10; $row++) {
            for ($col = 1; $col <= 6; $col++) {
                $value = strtoupper(trim(
                    (string) $sheet->getCellByColumnAndRow($col, $row)->getValue()
                ));

                $value = str_replace('TKRO', 'TKR', $value);

                if (preg_match('/(X|XI|XII)\s*\/\s*(TMI|KI|TKJ|TKR)\s*([A-Z])/', $value, $match)) {
                    return [
                        'tingkat' => $match[1],
                        'jurusan' => $this->namaJurusan($match[2]),
                        'rombel' => $match[3],
                    ];
                }
            }
        }

        return null;
    }

    private function cariAtauBuatKelas($tingkat, $rombel, $jurusan)
    {
        $namaKelas = $tingkat . '-' . $rombel;

        return Kelas::firstOrCreate([
            'nama_kelas' => $namaKelas,
            'jurusan' => $jurusan,
        ]);
    }

    private function namaJurusan($kode)
    {
        return match ($kode) {
            'TMI' => 'Teknik Pemeliharaan Mesin Industri',
            'KI' => 'Teknik Kimia Industri',
            'TKJ' => 'Teknik Komputer dan Jaringan',
            'TKR' => 'Teknik Kendaraan Ringan',
            default => $kode,
        };
    }

    private function validNis($nis)
    {
        return preg_match('/^[0-9]{5,}$/', $nis);
    }

    private function validNama($nama)
    {
        if (!$nama) {
            return false;
        }

        if (strtoupper($nama) === 'NAMA SISWA') {
            return false;
        }

        return strlen($nama) >= 3;
    }
}