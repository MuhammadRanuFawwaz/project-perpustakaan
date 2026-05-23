<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengunjung;
use App\Models\Kelas;
use App\Models\Murid;
use App\Models\Guru;
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
            $query->where(function ($q) use ($request) {
                $q->where('nama_pengunjung', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_induk', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->get('per_page', 10);

        $pengunjung = $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $kelas = Kelas::orderByRaw("
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

        return view('pengunjung.index', compact(
            'pengunjung',
            'kelas'
        ));
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'nomor_induk' => 'required',
        ]);

        $dataIdentitas = $this->ambilDataIdentitas($request->nomor_induk);

        if (!$dataIdentitas) {
            return response()->json([
                'status' => false,
                'message' => 'NIS / NIP tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $dataIdentitas,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_induk' => 'required',
            'keperluan' => 'required',
        ]);

        $dataIdentitas = $this->ambilDataIdentitas($request->nomor_induk);

        if (!$dataIdentitas) {
            return back()
                ->withInput()
                ->with('error', 'NIS / NIP tidak ditemukan di data master.');
        }

        Pengunjung::create([
            'nomor_induk' => $dataIdentitas['nomor_induk'],
            'nama_pengunjung' => $dataIdentitas['nama_pengunjung'],
            'jenis_pengunjung' => $dataIdentitas['jenis_pengunjung'],
            'id_kelas' => $dataIdentitas['id_kelas'],
            'tanggal_kunjung' => now()->toDateString(),
            'waktu_kunjung' => now()->format('H:i:s'),
            'keperluan' => $request->keperluan,
        ]);

        return redirect()
            ->route('pengunjung.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_induk' => 'required',
            'keperluan' => 'required',
        ]);

        $dataIdentitas = $this->ambilDataIdentitas($request->nomor_induk);

        if (!$dataIdentitas) {
            return back()
                ->withInput()
                ->with('error', 'NIS / NIP tidak ditemukan di data master.');
        }

        $pengunjung = Pengunjung::findOrFail($id);

        $pengunjung->update([
            'nomor_induk' => $dataIdentitas['nomor_induk'],
            'nama_pengunjung' => $dataIdentitas['nama_pengunjung'],
            'jenis_pengunjung' => $dataIdentitas['jenis_pengunjung'],
            'id_kelas' => $dataIdentitas['id_kelas'],
            'keperluan' => $request->keperluan,
        ]);

        return redirect()
            ->route('pengunjung.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $pengunjung = Pengunjung::findOrFail($id);

        if ($pengunjung->peminjaman()->exists()) {
            return redirect()
                ->route('pengunjung.index')
                ->with('error', 'Data pengunjung tidak bisa dihapus karena masih memiliki data peminjaman.');
        }

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

    private function ambilDataIdentitas($nomorInduk)
    {
        $murid = Murid::with('kelas')
            ->where('nis', $nomorInduk)
            ->first();

        if ($murid) {
            return [
                'nomor_induk' => $murid->nis,
                'nama_pengunjung' => $murid->nama_murid,
                'jenis_pengunjung' => 'Murid',
                'id_kelas' => $murid->id_kelas,
                'nama_kelas' => $murid->kelas->nama_kelas ?? '-',
                'jurusan' => $murid->kelas->jurusan ?? '-',
            ];
        }

        $guru = Guru::where('nip', $nomorInduk)->first();

        if ($guru) {
            return [
                'nomor_induk' => $guru->nip,
                'nama_pengunjung' => $guru->nama_guru,
                'jenis_pengunjung' => 'Guru',
                'id_kelas' => null,
                'nama_kelas' => '-',
                'jurusan' => '-',
            ];
        }

        return null;
    }
}
