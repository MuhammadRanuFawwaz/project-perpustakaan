<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\HargaBuku;
use Illuminate\Http\Request;

class HargaBukuController extends Controller
{
    public function index(Request $request)
    {
        $query = HargaBuku::with('buku');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_buku', 'like', '%' . $request->search . '%')
                    ->orWhereHas('buku', function ($buku) use ($request) {
                        $buku->where('judul_buku', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $hargaBuku = $query
            ->orderBy('kode_buku')
            ->paginate($request->get('per_page', 10))
            ->withQueryString();

        $buku = Buku::whereDoesntHave('hargaBuku')
            ->orderBy('judul_buku')
            ->get();

        $semuaBuku = Buku::orderBy('judul_buku')->get();

        return view('master.harga-buku.index', compact(
            'hargaBuku',
            'buku',
            'semuaBuku'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_buku' => 'required|exists:buku,kode_buku|unique:harga_buku,kode_buku',
            'harga' => 'required|numeric|min:0',
        ]);

        HargaBuku::create([
            'kode_buku' => $request->kode_buku,
            'harga' => $request->harga,
        ]);

        return redirect()
            ->route('master.harga-buku.index')
            ->with('success', 'Harga buku berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $hargaBuku = HargaBuku::findOrFail($id);

        $request->validate([
            'kode_buku' => 'required|exists:buku,kode_buku|unique:harga_buku,kode_buku,' . $hargaBuku->id,
            'harga' => 'required|numeric|min:0',
        ]);

        $hargaBuku->update([
            'kode_buku' => $request->kode_buku,
            'harga' => $request->harga,
        ]);

        return redirect()
            ->route('master.harga-buku.index')
            ->with('success', 'Harga buku berhasil diupdate.');
    }

    public function destroy($id)
    {
        $hargaBuku = HargaBuku::findOrFail($id);
        $hargaBuku->delete();

        return redirect()
            ->route('master.harga-buku.index')
            ->with('success', 'Harga buku berhasil dihapus.');
    }
}
