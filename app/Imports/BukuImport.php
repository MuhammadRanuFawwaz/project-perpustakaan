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

            $kolom = $this->ambilKolomHeader($sheet, $headerRow);

            for ($row = $headerRow + 1; $row <= $sheet->getHighestRow(); $row++) {

                $kodeBukuExcel = $this->ambilNilai($sheet, $kolom, 'kode_buku', $row);
                $judul = $this->ambilNilai($sheet, $kolom, 'judul_buku', $row);
                $kategoriExcel = $this->ambilNilai($sheet, $kolom, 'kategori', $row);
                $jenjang = $this->ambilNilai($sheet, $kolom, 'jenjang', $row);
                $kodeDdcExcel = $this->ambilNilai($sheet, $kolom, 'ddc', $row);
                $stok = $this->ambilNilai($sheet, $kolom, 'stok', $row);
                $tanggal = $this->ambilNilai($sheet, $kolom, 'tanggal_kirim', $row);

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

                $namaKategori = $kategoriExcel
                    ? $this->normalisasiKategori($kategoriExcel)
                    : $this->tebakKategori($judul, $jenjang);

                $kategori = Kategori::firstOrCreate([
                    'nama_kategori' => $namaKategori,
                ]);

                $kodeDdc = $kodeDdcExcel
                    ? trim((string) $kodeDdcExcel)
                    : $this->tebakKodeDdc($judul, $namaKategori);

                $existing = null;

                if ($kodeBukuExcel) {
                    $existing = Buku::where('kode_buku', trim((string) $kodeBukuExcel))->first();
                }

                if (!$existing) {
                    $existing = Buku::where('judul_buku', strtoupper($judul))
                        ->where('jenjang_kelas', $jenjang)
                        ->whereDate('tanggal_kirim', $tanggalKirim)
                        ->first();
                }

                if ($existing) {
                    $existing->update([
                        'judul_buku' => strtoupper($judul),
                        'id_kategori' => $kategori->id,
                        'jenjang_kelas' => $jenjang,
                        'kode_ddc' => $kodeDdc,
                        'stok' => $stok,
                        'tanggal_kirim' => $tanggalKirim,
                    ]);

                    $update++;
                    continue;
                }

                $kodeBuku = $kodeBukuExcel ?: $this->generateKodeBuku(
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
            for ($col = 1; $col <= 10; $col++) {
                $value = strtoupper(trim((string) $sheet->getCellByColumnAndRow($col, $row)->getValue()));

                if (
                    str_contains($value, 'NAMA BUKU') ||
                    str_contains($value, 'JUDUL BUKU')
                ) {
                    return $row;
                }
            }
        }

        return null;
    }

    private function ambilKolomHeader($sheet, $headerRow)
    {
        $kolom = [];

        for ($col = 1; $col <= 10; $col++) {
            $value = strtoupper(trim((string) $sheet->getCellByColumnAndRow($col, $headerRow)->getValue()));

            if (!$value) {
                continue;
            }

            if (str_contains($value, 'KODE BUKU')) {
                $kolom['kode_buku'] = $col;
            }

            if (str_contains($value, 'NAMA BUKU') || str_contains($value, 'JUDUL BUKU')) {
                $kolom['judul_buku'] = $col;
            }

            if (str_contains($value, 'KATEGORI')) {
                $kolom['kategori'] = $col;
            }

            if (str_contains($value, 'JENJANG') || str_contains($value, 'KELAS')) {
                $kolom['jenjang'] = $col;
            }

            if (str_contains($value, 'DDC')) {
                $kolom['ddc'] = $col;
            }

            if (str_contains($value, 'STOK')) {
                $kolom['stok'] = $col;
            }

            if (str_contains($value, 'TANGGAL')) {
                $kolom['tanggal_kirim'] = $col;
            }
        }

        return $kolom;
    }

    private function ambilNilai($sheet, $kolom, $key, $row)
    {
        if (!isset($kolom[$key])) {
            return null;
        }

        return trim((string) $sheet->getCellByColumnAndRow($kolom[$key], $row)->getValue());
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

        if (str_contains($judul, 'JUDUL BUKU')) {
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

    private function normalisasiKategori($kategori)
    {
        $kategori = trim((string) $kategori);
        $kategoriUpper = strtoupper($kategori);

        if (str_contains($kategoriUpper, 'KENDARAAN') || str_contains($kategoriUpper, 'TKR')) {
            return 'Teknik Kendaraan Ringan';
        }

        if (str_contains($kategoriUpper, 'KIMIA')) {
            return 'Teknik Kimia Industri';
        }

        if (str_contains($kategoriUpper, 'KOMPUTER') || str_contains($kategoriUpper, 'JARINGAN') || str_contains($kategoriUpper, 'TKJ')) {
            return 'Teknik Komputer dan Jaringan';
        }

        if (str_contains($kategoriUpper, 'MESIN') || str_contains($kategoriUpper, 'MEKANIK')) {
            return 'Teknik Pemeliharaan Mesin Industri';
        }

        if (str_contains($kategoriUpper, 'UMUM')) {
            return 'Umum';
        }

        return $kategori;
    }

    private function tebakKategori($judul, $jenjang)
    {
        $judul = strtoupper($judul);

        if (
            str_contains($judul, 'MATEMATIKA') ||
            str_contains($judul, 'BAHASA INDONESIA') ||
            str_contains($judul, 'BAHASA INGGRIS') ||
            str_contains($judul, 'ENGLISH') ||
            str_contains($judul, 'PENDIDIKAN AGAMA') ||
            str_contains($judul, 'AGAMA') ||
            str_contains($judul, 'ISLAM') ||
            str_contains($judul, 'PANCASILA') ||
            str_contains($judul, 'PPKN') ||
            str_contains($judul, 'SEJARAH') ||
            str_contains($judul, 'PJOK') ||
            str_contains($judul, 'SENI BUDAYA') ||
            str_contains($judul, 'PRODUK KREATIF') ||
            str_contains($judul, 'KEWIRAUSAHAAN')
        ) {
            return 'Umum';
        }

        if (
            str_contains($judul, 'KIMIA') ||
            str_contains($judul, 'LABORATORIUM KIMIA') ||
            str_contains($judul, 'PROSES INDUSTRI KIMIA')
        ) {
            return 'Teknik Kimia Industri';
        }

        if (
            str_contains($judul, 'KOMPUTER') ||
            str_contains($judul, 'JARINGAN') ||
            str_contains($judul, 'TELEKOMUNIKASI') ||
            str_contains($judul, 'INFORMATIKA') ||
            str_contains($judul, 'DESAIN GRAFIS')
        ) {
            return 'Teknik Komputer dan Jaringan';
        }

        if (
            str_contains($judul, 'KENDARAAN') ||
            str_contains($judul, 'OTOMOTIF') ||
            str_contains($judul, 'ENGINE') ||
            str_contains($judul, 'CHASIS') ||
            str_contains($judul, 'PEMINDAH TENAGA')
        ) {
            return 'Teknik Kendaraan Ringan';
        }

        if (
            str_contains($judul, 'MESIN') ||
            str_contains($judul, 'MEKANIK') ||
            str_contains($judul, 'SISTEM KONTROL') ||
            str_contains($judul, 'PEMELIHARAAN')
        ) {
            return 'Teknik Pemeliharaan Mesin Industri';
        }

        return 'Umum';
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

        if (str_contains($nama, 'KENDARAAN') || str_contains($nama, 'TKR')) {
            return 'TKR';
        }

        if (str_contains($nama, 'KIMIA')) {
            return 'TKI';
        }

        if (str_contains($nama, 'KOMPUTER') || str_contains($nama, 'JARINGAN') || str_contains($nama, 'TKJ')) {
            return 'TKJ';
        }

        if (str_contains($nama, 'MESIN') || str_contains($nama, 'MEKANIK')) {
            return 'TMI';
        }

        if (str_contains($nama, 'UMUM')) {
            return 'UMM';
        }

        return strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $namaKategori), 0, 3));
    }
}
