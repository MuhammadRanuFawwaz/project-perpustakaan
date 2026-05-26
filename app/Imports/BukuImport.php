<?php

namespace App\Imports;

use App\Models\Buku;
use App\Models\Kategori;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BukuImport
{
    public function import($file)
    {
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
        } catch (\Throwable $e) {
            throw new \Exception('File Excel tidak bisa dibaca. Pastikan format file benar.');
        }

        $berhasil = 0;
        $update = 0;
        $dilewati = 0;

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {

            $headerRow = $this->cariBarisHeader($sheet);

            if (!$headerRow) {
                continue;
            }

            for ($row = $headerRow + 1; $row <= $sheet->getHighestRow(); $row++) {

                $judul = trim((string) $sheet->getCell('B' . $row)->getValue());
                $jenjang = trim((string) $sheet->getCell('C' . $row)->getValue());
                $stok = trim((string) $sheet->getCell('D' . $row)->getValue());
                $tanggal = trim((string) $sheet->getCell('E' . $row)->getValue());

                if (!$this->validJudul($judul)) {
                    $dilewati++;
                    continue;
                }

                $stok = $this->bersihkanStok($stok);

                if ($stok === null) {
                    $dilewati++;
                    continue;
                }

                $tanggalKirim = $this->formatTanggal($tanggal);

                if (!$tanggalKirim) {
                    $dilewati++;
                    continue;
                }

                $jenjang = $this->normalisasiJenjang($jenjang);

                $namaKategori = $this->tebakKategori($judul, $jenjang);

                $kategori = Kategori::firstOrCreate([
                    'nama_kategori' => $namaKategori,
                ]);

                $kodeDdc = $this->tebakKodeDdc($judul, $namaKategori);

                $existing = Buku::where('judul_buku', strtoupper($judul))
                    ->where('jenjang_kelas', $jenjang)
                    ->whereDate('tanggal_kirim', $tanggalKirim)
                    ->first();

                if ($existing) {
                    $existing->update([
                        'id_kategori' => $kategori->id,
                        'kode_ddc' => $kodeDdc,
                        'stok' => $stok,
                    ]);

                    $update++;
                    continue;
                }

                $kodeBuku = $this->generateKodeBuku(
                    $kodeDdc,
                    $kategori->nama_kategori,
                    $jenjang
                );

                Buku::create([
                    'kode_buku' => $kodeBuku,
                    'judul_buku' => strtoupper($judul),
                    'id_kategori' => $kategori->id,
                    'jenjang_kelas' => $jenjang,
                    'kode_ddc' => $kodeDdc,
                    'stok' => $stok,
                    'tanggal_kirim' => $tanggalKirim,
                ]);

                $berhasil++;
            }
        }

        return [
            'berhasil' => $berhasil,
            'update' => $update,
            'dilewati' => $dilewati,
        ];
    }

    private function cariBarisHeader($sheet)
    {
        for ($row = 1; $row <= 20; $row++) {
            for ($col = 1; $col <= 8; $col++) {
                $value = strtoupper(trim((string) $sheet->getCellByColumnAndRow($col, $row)->getValue()));

                if (str_contains($value, 'NAMA BUKU')) {
                    return $row;
                }
            }
        }

        return null;
    }

    private function validJudul($judul)
    {
        if (!$judul) {
            return false;
        }

        $judul = strtoupper($judul);

        if (str_contains($judul, 'NAMA BUKU')) {
            return false;
        }

        if ($judul === 'TOTAL') {
            return false;
        }

        return strlen($judul) >= 3;
    }

    private function bersihkanStok($stok)
    {
        $stok = strtolower((string) $stok);

        $stok = str_replace('buku', '', $stok);

        preg_match('/[0-9]+/', $stok, $match);

        if (!$match) {
            return null;
        }

        return (int) $match[0];
    }

    private function formatTanggal($tanggal)
    {
        if (!$tanggal) {
            return null;
        }

        if (is_numeric($tanggal)) {
            try {
                return Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal)
                )->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        }

        $tanggal = trim((string) $tanggal);

        $tanggal = str_replace(',', '', $tanggal);
        $tanggal = preg_replace('/\([^)]*\)/', '', $tanggal);
        $tanggal = preg_replace('/^(Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu)\s+/i', '', $tanggal);

        $bulan = [
            'januari' => '01',
            'februari' => '02',
            'maret' => '03',
            'april' => '04',
            'mei' => '05',
            'juni' => '06',
            'juli' => '07',
            'agustus' => '08',
            'september' => '09',
            'oktober' => '10',
            'november' => '11',
            'desember' => '12',
        ];

        if (preg_match('/([0-9]{1,2})[-\/]([0-9]{1,2})[-\/]([0-9]{4})/', $tanggal, $match)) {
            return $match[3] . '-' . str_pad($match[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($match[1], 2, '0', STR_PAD_LEFT);
        }

        if (preg_match('/([0-9]{1,2})\s+([A-Za-z]+)\s+([0-9]{4})/', $tanggal, $match)) {
            $namaBulan = strtolower($match[2]);

            if (isset($bulan[$namaBulan])) {
                return $match[3] . '-' . $bulan[$namaBulan] . '-' . str_pad($match[1], 2, '0', STR_PAD_LEFT);
            }
        }

        try {
            return Carbon::parse($tanggal)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalisasiJenjang($jenjang)
    {
        $jenjang = strtoupper(trim((string) $jenjang));

        if (!$jenjang || $jenjang === 'NAN') {
            return 'Umum';
        }

        if (str_contains($jenjang, 'XII')) {
            return 'XII';
        }

        if (str_contains($jenjang, 'XI')) {
            return 'XI';
        }

        if (preg_match('/\bX\b/', $jenjang)) {
            return 'X';
        }

        if (str_contains($jenjang, 'NOVEL')) {
            return 'Umum';
        }

        return 'Umum';
    }

    private function tebakKategori($judul, $jenjang)
    {
        $judul = strtoupper($judul);

        if (str_contains($judul, 'NOVEL') || str_contains($jenjang, 'NOVEL')) {
            return 'Novel';
        }

        return 'Pelajaran';
    }

    private function tebakKodeDdc($judul, $kategori)
    {
        $judul = strtoupper($judul);

        if (str_contains($judul, 'AGAMA') || str_contains($judul, 'ISLAM') || str_contains($judul, 'BUDI PEKERTI') || str_contains($judul, 'ULAMA')) {
            return '297';
        }

        if (str_contains($judul, 'BAHASA INGGRIS') || str_contains($judul, 'ENGLISH')) {
            return '420';
        }

        if (str_contains($judul, 'BAHASA INDONESIA')) {
            return '410';
        }

        if (str_contains($judul, 'MATEMATIKA')) {
            return '510';
        }

        if (str_contains($judul, 'FISIKA')) {
            return '530';
        }

        if (str_contains($judul, 'KIMIA')) {
            return '540';
        }

        if (str_contains($judul, 'BIOLOGI')) {
            return '570';
        }

        if (str_contains($judul, 'IPA') || str_contains($judul, 'IPAS') || str_contains($judul, 'ALAM')) {
            return '500';
        }

        if (str_contains($judul, 'PANCASILA') || str_contains($judul, 'PPKN') || str_contains($judul, 'KEWARGANEGARAAN')) {
            return '320';
        }

        if (str_contains($judul, 'IPS') || str_contains($judul, 'SOSIAL')) {
            return '300';
        }

        if (str_contains($judul, 'SEJARAH')) {
            return '900';
        }

        if (str_contains($judul, 'KOMPUTER') || str_contains($judul, 'INFORMATIKA') || str_contains($judul, 'JARINGAN') || str_contains($judul, 'DESAIN GRAFIS')) {
            return '004';
        }

        if (str_contains($judul, 'OTOMOTIF') || str_contains($judul, 'KENDARAAN') || str_contains($judul, 'TKR')) {
            return '629';
        }

        if (str_contains($judul, 'MESIN')) {
            return '621';
        }

        if (str_contains($judul, 'TEKNIK')) {
            return '620';
        }

        if (str_contains($judul, 'SASTRA') || str_contains($judul, 'CERITA')) {
            return '800';
        }

        return '000';
    }

    private function generateKodeBuku($kodeDdc, $namaKategori, $jenjangKelas)
    {
        $singkatan = $this->buatSingkatanKategori($namaKategori);

        $jenjang = $jenjangKelas ?: 'UMUM';

        $prefix = $kodeDdc . '-' . $singkatan . '-' . $jenjang;

        $lastBuku = Buku::where('kode_buku', 'like', $prefix . '-%')
            ->orderBy('kode_buku', 'desc')
            ->first();

        $nomor = 1;

        if ($lastBuku) {
            $lastNumber = (int) substr($lastBuku->kode_buku, -4);
            $nomor = $lastNumber + 1;
        }

        return $prefix . '-' . str_pad($nomor, 4, '0', STR_PAD_LEFT);
    }

    private function buatSingkatanKategori($namaKategori)
    {
        $nama = strtoupper($namaKategori);

        if (str_contains($nama, 'AGAMA')) {
            return 'PAI';
        }

        if (str_contains($nama, 'BAHASA')) {
            return 'BHS';
        }

        if (str_contains($nama, 'MATEMATIKA')) {
            return 'MTK';
        }

        if (str_contains($nama, 'IPA') || str_contains($nama, 'FISIKA') || str_contains($nama, 'KIMIA') || str_contains($nama, 'BIOLOGI')) {
            return 'IPA';
        }

        if (str_contains($nama, 'IPS') || str_contains($nama, 'SOSIAL') || str_contains($nama, 'PANCASILA') || str_contains($nama, 'PPKN')) {
            return 'SOS';
        }

        if (str_contains($nama, 'SEJARAH')) {
            return 'SEJ';
        }

        if (str_contains($nama, 'NOVEL') || str_contains($nama, 'SASTRA')) {
            return 'SAS';
        }

        if (str_contains($nama, 'TEKNIK') || str_contains($nama, 'TKR') || str_contains($nama, 'JARINGAN') || str_contains($nama, 'KOMPUTER')) {
            return 'TEK';
        }

        return strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $namaKategori), 0, 3));
    }
}