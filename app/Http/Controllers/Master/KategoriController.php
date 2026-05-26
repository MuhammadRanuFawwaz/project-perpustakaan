<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::query();

        if ($request->search) {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        $kategori = $query
            ->orderBy('nama_kategori')
            ->paginate($request->get('per_page', 10))
            ->withQueryString();

        return view('master.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()
            ->route('master.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori,' . $kategori->id,
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()
            ->route('master.kategori.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        if ($kategori->buku()->exists()) {
            return redirect()
                ->route('master.kategori.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan pada data buku.');
        }

        $kategori->delete();

        return redirect()
            ->route('master.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
