<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Murid;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MuridController extends Controller
{
    public function index(Request $request)
    {
        $query = Murid::with('kelas');

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'aktif');
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nis', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_murid', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->tingkat) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('nama_kelas', 'like', $request->tingkat . '-%');
            });
        }

        if ($request->jurusan) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('jurusan', $request->jurusan);
            });
        }

        $murid = $query
            ->latest()
            ->paginate($request->get('per_page', 10))
            ->withQueryString();

        $kelas = $this->ambilKelas();

        $jurusan = Kelas::select('jurusan')
            ->distinct()
            ->orderBy('jurusan')
            ->pluck('jurusan');

        $totalAktif = Murid::where('status', 'aktif')->count();
        $totalLulus = Murid::where('status', 'lulus')->count();
        $totalNonaktif = Murid::where('status', 'nonaktif')->count();

        return view('master.murid.index', compact(
            'murid',
            'kelas',
            'jurusan',
            'totalAktif',
            'totalLulus',
            'totalNonaktif'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:murid,nis',
            'nama_murid' => 'required',
            'id_kelas' => 'required|exists:kelas,id',
            'status' => 'nullable|in:aktif,lulus,nonaktif',
            'tahun_ajaran' => 'nullable',
        ]);

        Murid::create([
            'nis' => $request->nis,
            'nama_murid' => strtoupper($request->nama_murid),
            'id_kelas' => $request->id_kelas,
            'status' => $request->status ?? 'aktif',
            'tahun_ajaran' => $request->tahun_ajaran ?? $this->tahunAjaranSekarang(),
        ]);

        return redirect()
            ->route('master.murid.index')
            ->with('success', 'Data murid berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $editMurid = Murid::findOrFail($id);

        $murid = Murid::with('kelas')
            ->where('status', 'aktif')
            ->latest()
            ->paginate(10);

        $kelas = $this->ambilKelas();

        $jurusan = Kelas::select('jurusan')
            ->distinct()
            ->orderBy('jurusan')
            ->pluck('jurusan');

        $totalAktif = Murid::where('status', 'aktif')->count();
        $totalLulus = Murid::where('status', 'lulus')->count();
        $totalNonaktif = Murid::where('status', 'nonaktif')->count();

        return view('master.murid.index', compact(
            'murid',
            'kelas',
            'jurusan',
            'editMurid',
            'totalAktif',
            'totalLulus',
            'totalNonaktif'
        ));
    }

    public function update(Request $request, $id)
    {
        $murid = Murid::findOrFail($id);

        $request->validate([
            'nis' => 'required|unique:murid,nis,' . $murid->id,
            'nama_murid' => 'required',
            'id_kelas' => 'required|exists:kelas,id',
            'status' => 'required|in:aktif,lulus,nonaktif',
            'tahun_ajaran' => 'nullable',
        ]);

        $murid->update([
            'nis' => $request->nis,
            'nama_murid' => strtoupper($request->nama_murid),
            'id_kelas' => $request->id_kelas,
            'status' => $request->status,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()
            ->route('master.murid.index')
            ->with('success', 'Data murid berhasil diupdate.');
    }

    public function destroy($id)
    {
        $murid = Murid::findOrFail($id);

        $murid->update([
            'status' => 'nonaktif',
        ]);

        return redirect()
            ->route('master.murid.index')
            ->with('success', 'Data murid berhasil dinonaktifkan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file_excel')->getRealPath());
        } catch (\Throwable $e) {
            return redirect()
                ->route('master.murid.index')
                ->with('error', 'File Excel tidak bisa dibaca. Pastikan format file benar.');
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
                        'tahun_ajaran' => $this->tahunAjaranSekarang(),
                    ]
                );

                $berhasil++;
            }
        }

        return redirect()
            ->route('master.murid.index')
            ->with('success', 'Import selesai. Data masuk/update: ' . $berhasil . '. Baris dilewati: ' . $dilewati . '.');
    }

    public function export(Request $request)
    {
        $query = Murid::with('kelas');

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'aktif');
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nis', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_murid', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->tingkat) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('nama_kelas', 'like', $request->tingkat . '-%');
            });
        }

        if ($request->jurusan) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('jurusan', $request->jurusan);
            });
        }

        $data = $query->orderBy('nama_murid')->get();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Data Murid');

        $sheet->fromArray([
            ['NIS', 'Nama Murid', 'Kelas', 'Jurusan', 'Status'],
        ], null, 'A1');

        $row = 2;

        foreach ($data as $m) {
            $sheet->setCellValue('A' . $row, $m->nis);
            $sheet->setCellValue('B' . $row, $m->nama_murid);
            $sheet->setCellValue('C' . $row, $m->kelas->nama_kelas ?? '-');
            $sheet->setCellValue('D' . $row, $m->kelas->jurusan ?? '-');
            $sheet->setCellValue('E' . $row, ucfirst($m->status));

            $row++;
        }

        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $fileName = 'data-murid.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName);
    }

    public function luluskan()
    {
        Murid::where('status', 'aktif')
            ->whereHas('kelas', function ($q) {
                $q->where('nama_kelas', 'like', 'XII-%');
            })
            ->update([
                'status' => 'lulus',
            ]);

        return redirect()
            ->route('master.murid.index')
            ->with('success', 'Semua murid kelas XII aktif berhasil diluluskan.');
    }

    private function ambilKelas()
    {
        return Kelas::orderByRaw("
            CASE
                WHEN nama_kelas LIKE 'X-%' THEN 1
                WHEN nama_kelas LIKE 'XI-%' THEN 2
                WHEN nama_kelas LIKE 'XII-%' THEN 3
                ELSE 4
            END
        ")
            ->orderBy('nama_kelas')
            ->orderBy('jurusan')
            ->get();
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
                $value = strtoupper(trim((string) $sheet->getCellByColumnAndRow($col, $row)->getValue()));

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

        return Kelas::firstOrCreate(
            [
                'nama_kelas' => $namaKelas,
                'jurusan' => $jurusan,
            ],
            [
                'nama_kelas' => $namaKelas,
                'jurusan' => $jurusan,
            ]
        );
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

    private function tahunAjaranSekarang()
    {
        $tahun = (int) date('Y');
        $bulan = (int) date('m');

        if ($bulan >= 7) {
            return $tahun . '/' . ($tahun + 1);
        }

        return ($tahun - 1) . '/' . $tahun;
    }
}
