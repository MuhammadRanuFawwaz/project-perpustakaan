<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

use App\Exports\GuruExport;
use App\Imports\GuruImport;
use Maatwebsite\Excel\Facades\Excel;

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
            $hasil = (new GuruImport())->import($request->file('file_excel'));
        } catch (\Throwable $e) {
            return redirect()
                ->route('master.guru.index')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('master.guru.index')
            ->with(
                'success',
                'Import selesai. Data masuk/update: ' . $hasil['berhasil'] .
                    '. Baris dilewati: ' . $hasil['dilewati'] . '.'
            );
    }

    public function export(Request $request)
    {
        return Excel::download(
            new GuruExport($request),
            'data-guru.xlsx'
        );
    }
}
