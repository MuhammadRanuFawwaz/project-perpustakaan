<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Ddc;
use Illuminate\Http\Request;

class DdcController extends Controller
{
    public function index(Request $request)
    {
        $query = Ddc::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_ddc', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_ddc', 'like', '%' . $request->search . '%');
            });
        }

        $ddc = $query
            ->orderBy('kode_ddc')
            ->paginate($request->get('per_page', 10))
            ->withQueryString();

        return view('master.ddc.index', compact('ddc'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_ddc' => 'required',
            'nama_ddc' => 'required',
        ]);

        Ddc::create([
            'kode_ddc' => $request->kode_ddc,
            'nama_ddc' => $request->nama_ddc,
        ]);

        return redirect()
            ->route('master.ddc.index')
            ->with('success', 'DDC berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $ddc = Ddc::findOrFail($id);

        $request->validate([
            'kode_ddc' => 'required',
            'nama_ddc' => 'required',
        ]);

        $ddc->update([
            'kode_ddc' => $request->kode_ddc,
            'nama_ddc' => $request->nama_ddc,
        ]);

        return redirect()
            ->route('master.ddc.index')
            ->with('success', 'DDC berhasil diupdate.');
    }

    public function destroy($id)
    {
        $ddc = Ddc::findOrFail($id);

        if ($ddc->buku()->exists()) {
            return redirect()
                ->route('master.ddc.index')
                ->with('error', 'DDC tidak bisa dihapus karena masih digunakan pada data buku.');
        }

        $ddc->delete();

        return redirect()
            ->route('master.ddc.index')
            ->with('success', 'DDC berhasil dihapus.');
    }
}
