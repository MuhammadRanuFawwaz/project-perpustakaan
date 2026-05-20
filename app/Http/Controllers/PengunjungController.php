<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengunjung;
use App\Models\Kelas;
use App\Exports\PengunjungExport;
use Maatwebsite\Excel\Facades\Excel;

class PengunjungController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengunjung::with('kelas');

        if ($request->start_date) {
            $query->whereDate('tanggal_kunjung', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('tanggal_kunjung', '<=', $request->end_date);
        }

        if ($request->nama_kelas) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $kelas = $request->nama_kelas;

                if (preg_match('/^(X|XI|XII)-([A-Z])$/', $kelas, $match)) {
                    $tingkat = $match[1];
                    $rombel = $match[2];

                    $q->where('nama_kelas', 'like', $tingkat . '-%' . $rombel);
                } else {
                    $q->where('nama_kelas', $kelas);
                }
            });
        }

        if ($request->jurusan) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('jurusan', $request->jurusan);
            });
        }

        if ($request->search) {
            $query->where('nama_pengunjung', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 10);

        $pengunjung = $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $kelas = Kelas::all();

        return view('pengunjung.index', compact(
            'pengunjung',
            'kelas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pengunjung' => 'required',
            'jenis_pengunjung' => 'required',
            'id_kelas' => 'nullable',
            'tanggal_kunjung' => 'required',
            'waktu_kunjung' => 'required',
            'keperluan' => 'required',
        ]);

        Pengunjung::create($request->all());

        return redirect()
            ->route('pengunjung.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pengunjung' => 'required',
            'jenis_pengunjung' => 'required',
            'id_kelas' => 'nullable',
            'tanggal_kunjung' => 'required',
            'waktu_kunjung' => 'required',
            'keperluan' => 'required',
        ]);

        $pengunjung = Pengunjung::findOrFail($id);

        $pengunjung->update($request->all());

        return redirect()
            ->route('pengunjung.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $pengunjung = Pengunjung::findOrFail($id);

        $pengunjung->delete();

        return redirect()
            ->route('pengunjung.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new PengunjungExport($request),
            'data-pengunjung.xlsx'
        );
    }
}
