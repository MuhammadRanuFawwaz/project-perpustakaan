<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Murid;
use Illuminate\Http\Request;

use App\Exports\MuridExport;
use App\Imports\MuridImport;
use Maatwebsite\Excel\Facades\Excel;

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
        ]);

        Murid::create([
            'nis' => $request->nis,
            'nama_murid' => strtoupper($request->nama_murid),
            'id_kelas' => $request->id_kelas,
            'status' => $request->status ?? 'aktif',
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
        ]);

        $murid->update([
            'nis' => $request->nis,
            'nama_murid' => strtoupper($request->nama_murid),
            'id_kelas' => $request->id_kelas,
            'status' => $request->status,
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
            $hasil = (new MuridImport())->import($request->file('file_excel'));
        } catch (\Throwable $e) {
            return redirect()
                ->route('master.murid.index')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('master.murid.index')
            ->with(
                'success',
                'Import selesai. Data masuk/update: ' . $hasil['berhasil'] .
                    '. Baris dilewati: ' . $hasil['dilewati'] . '.'
            );
    }

    public function export(Request $request)
    {
        return Excel::download(
            new MuridExport($request),
            'data-murid.xlsx'
        );
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
            ->with('success', 'Semua murid kelas XII berhasil diluluskan.');
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
}
