<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nip', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_guru', 'like', '%' . $request->search . '%');
            });
        }

        $guru = $query
            ->latest()
            ->paginate($request->get('per_page', 10))
            ->withQueryString();

        $totalGuru = Guru::count();

        return view('master.guru.index', compact(
            'guru',
            'totalGuru'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:guru,nip',
            'nama_guru' => 'required',
        ]);

        Guru::create([
            'nip' => $request->nip,
            'nama_guru' => strtoupper($request->nama_guru),
        ]);

        return redirect()
            ->route('master.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $editGuru = Guru::findOrFail($id);

        $guru = Guru::latest()
            ->paginate(10);

        $totalGuru = Guru::count();

        return view('master.guru.index', compact(
            'guru',
            'editGuru',
            'totalGuru'
        ));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nip' => 'required|unique:guru,nip,' . $guru->id,
            'nama_guru' => 'required',
        ]);

        $guru->update([
            'nip' => $request->nip,
            'nama_guru' => strtoupper($request->nama_guru),
        ]);

        return redirect()
            ->route('master.guru.index')
            ->with('success', 'Data guru berhasil diupdate.');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);

        $guru->delete();

        return redirect()
            ->route('master.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
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
                ->route('master.guru.index')
                ->with('error', 'File Excel tidak bisa dibaca. Pastikan format file benar.');
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

        return redirect()
            ->route('master.guru.index')
            ->with('success', 'Import selesai. Data masuk/update: ' . $berhasil . '. Baris dilewati: ' . $dilewati . '.');
    }

    public function export(Request $request)
    {
        $query = Guru::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nip', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_guru', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->orderBy('nama_guru')->get();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Data Guru');

        $sheet->fromArray([
            ['NIP', 'Nama Guru'],
        ], null, 'A1');

        $row = 2;

        foreach ($data as $g) {
            $sheet->setCellValue('A' . $row, $g->nip);
            $sheet->setCellValue('B' . $row, $g->nama_guru);

            $row++;
        }

        foreach (range('A', 'B') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $fileName = 'data-guru.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName);
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
