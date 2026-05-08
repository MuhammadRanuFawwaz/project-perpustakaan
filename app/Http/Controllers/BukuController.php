<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        return Buku::with('kategori')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_buku' => 'required|unique:buku',
            'judul_buku' => 'required',
            'id_kategori' => 'required',
            'stok' => 'required|integer'
        ]);

        return Buku::create($request->all());
    }
}
