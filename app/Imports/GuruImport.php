<?php

namespace App\Imports;

use App\Models\Guru;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GuruImport
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
            for ($row = 1; $row <= $sheet->getHighestRow(); $row++) {
                $nama = trim((string) $sheet->getCell('B' . $row)->getValue());
                $nip = trim((string) $sheet->getCell('C' . $row)->getValue());

                if (!$this->validNip($nip) || !$this->validNama($nama)) {
                    $dilewati++;
                    continue;
                }

                Guru::updateOrCreate(
                    [
                        'nip' => $nip,
                    ],
                    [
                        'nama_guru' => strtoupper($nama),
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

    private function validNip($nip)
    {
        return preg_match('/^[0-9]{10,}$/', $nip);
    }

    private function validNama($nama)
    {
        if (!$nama) {
            return false;
        }

        if (strtoupper($nama) === 'NAMA') {
            return false;
        }

        return strlen($nama) >= 3;
    }
}