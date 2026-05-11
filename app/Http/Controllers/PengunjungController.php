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

        return view('pengunjung.index', compact(
            'pengunjung'
        ));
    }

    public function create()
    {
        $kelas = Kelas::all();

        return view('pengunjung.create', compact(
            'kelas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pengunjung' => 'required',
<<<<<<< HEAD
            'jenis_pengunjung' => 'required',
            'id_kelas' => 'nullable',
=======
            'id_kelas' => 'required',
>>>>>>> 7fd2d379b2aab1588c9827f01616e7a7d0700a36
            'tanggal_kunjung' => 'required',
            'waktu_kunjung' => 'required',
            'keperluan' => 'required',
        ]);

        Pengunjung::create($request->all());

        return redirect()
            ->route('pengunjung.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pengunjung = Pengunjung::findOrFail($id);

        $kelas = Kelas::all();

        return view('pengunjung.edit', compact(
            'pengunjung',
            'kelas'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pengunjung' => 'required',
<<<<<<< HEAD
            'jenis_pengunjung' => 'required',
            'id_kelas' => 'nullable',
=======
            'id_kelas' => 'required',
>>>>>>> 7fd2d379b2aab1588c9827f01616e7a7d0700a36
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
<<<<<<< HEAD
}
=======
}
>>>>>>> 7fd2d379b2aab1588c9827f01616e7a7d0700a36
