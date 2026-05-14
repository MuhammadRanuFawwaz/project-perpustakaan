<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengunjung;
use App\Models\Kelas;

class PengunjungController extends Controller
{
    public function index()
    {
        $pengunjung = Pengunjung::with('kelas')
            ->latest()
            ->get();

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
}



