<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Exports\BukuExport;
use Maatwebsite\Excel\Facades\Excel;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->start_date) {
            $query->whereDate('tanggal_kirim', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('tanggal_kirim', '<=', $request->end_date);
        }

        if ($request->id_kategori) {
            $query->where('id_kategori', $request->id_kategori);
        }

        if ($request->jenjang_kelas) {
            $query->where('jenjang_kelas', $request->jenjang_kelas);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_buku', 'like', '%' . $request->search . '%')
                    ->orWhere('judul_buku', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_ddc', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->get('per_page', 10);

        $buku = $query
            ->orderBy('tanggal_kirim', 'asc')
            ->paginate($perPage)
            ->withQueryString();
        $kategori = Kategori::all();

        return view('buku.index', compact('buku', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_buku' => 'required',
            'id_kategori' => 'required',
            'jenjang_kelas' => 'nullable',
            'kode_ddc' => 'required',
            'stok' => 'required|integer|min:0',
            'tanggal_kirim' => 'required|date',
        ]);

        $kategori = Kategori::findOrFail($request->id_kategori);

        $kodeBuku = $this->generateKodeBuku(
            $request->kode_ddc,
            $kategori->nama_kategori,
            $request->jenjang_kelas
        );

        Buku::create([
            'kode_buku' => $kodeBuku,
            'judul_buku' => $request->judul_buku,
            'id_kategori' => $request->id_kategori,
            'jenjang_kelas' => $request->jenjang_kelas ?? 'Umum',
            'kode_ddc' => $request->kode_ddc,
            'stok' => $request->stok,
            'tanggal_kirim' => $request->tanggal_kirim,
        ]);

        return redirect()
            ->route('buku.index')
            ->with('success', 'Data buku berhasil ditambahkan');
    }

    public function update(Request $request, $kode_buku)
    {
        $buku = Buku::findOrFail($kode_buku);

        $request->validate([
            'judul_buku' => 'required',
            'id_kategori' => 'required',
            'jenjang_kelas' => 'nullable',
            'kode_ddc' => 'required',
            'stok' => 'required|integer|min:0',
            'tanggal_kirim' => 'required|date',
        ]);

        $buku->update([
            'judul_buku' => $request->judul_buku,
            'id_kategori' => $request->id_kategori,
            'jenjang_kelas' => $request->jenjang_kelas ?? 'Umum',
            'kode_ddc' => $request->kode_ddc,
            'stok' => $request->stok,
            'tanggal_kirim' => $request->tanggal_kirim,
        ]);

        return redirect()
            ->route('buku.index')
            ->with('success', 'Data buku berhasil diupdate');
    }

    public function destroy($kode_buku)
    {
        $buku = Buku::findOrFail($kode_buku);
        $buku->delete();

        return redirect()
            ->route('buku.index')
            ->with('success', 'Data buku berhasil dihapus');
    }

    private function generateKodeBuku($kodeDdc, $namaKategori, $jenjangKelas)
    {
        $singkatan = $this->buatSingkatanKategori($namaKategori);

        $jenjang = $jenjangKelas ?: 'UMUM';

        $prefix = $kodeDdc . '-' . $singkatan . '-' . $jenjang;

        $lastBuku = Buku::where('kode_buku', 'like', $prefix . '-%')
            ->orderBy('kode_buku', 'desc')
            ->first();

        $nomor = 1;

        if ($lastBuku) {
            $lastNumber = (int) substr($lastBuku->kode_buku, -4);
            $nomor = $lastNumber + 1;
        }

        return $prefix . '-' . str_pad($nomor, 4, '0', STR_PAD_LEFT);
    }

    private function buatSingkatanKategori($namaKategori)
    {
        $nama = strtoupper($namaKategori);

        if (str_contains($nama, 'AGAMA')) {
            return 'PAI';
        }

        if (str_contains($nama, 'BAHASA')) {
            return 'BHS';
        }

        if (str_contains($nama, 'MATEMATIKA')) {
            return 'MTK';
        }

        if (str_contains($nama, 'IPA') || str_contains($nama, 'FISIKA') || str_contains($nama, 'KIMIA') || str_contains($nama, 'BIOLOGI')) {
            return 'IPA';
        }

        if (str_contains($nama, 'IPS') || str_contains($nama, 'SOSIAL') || str_contains($nama, 'PANCASILA') || str_contains($nama, 'PPKN')) {
            return 'SOS';
        }

        if (str_contains($nama, 'SEJARAH')) {
            return 'SEJ';
        }

        if (str_contains($nama, 'NOVEL') || str_contains($nama, 'SASTRA')) {
            return 'SAS';
        }

        if (str_contains($nama, 'TEKNIK') || str_contains($nama, 'TKR') || str_contains($nama, 'JARINGAN') || str_contains($nama, 'KOMPUTER')) {
            return 'TEK';
        }

        return strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $namaKategori), 0, 3));
    }

    public function export(Request $request)
    {
        return Excel::download(new BukuExport($request), 'data-buku.xlsx');
    }
}
