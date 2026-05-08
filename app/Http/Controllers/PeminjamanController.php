<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Buku;

class PeminjamanController extends Controller
{
    public function pinjam(Request $request)
    {
        $request->validate([
            'id_pengunjung' => 'required|exists:pengunjung,id',
            'kode_buku' => 'required|array',
            'kode_buku.*' => 'exists:buku,kode_buku'
        ]);

        DB::transaction(function () use ($request) {

            $peminjaman = Peminjaman::create([
                'id_pengunjung' => $request->id_pengunjung,
                'tanggal_peminjaman' => now(),
                'status_peminjaman' => 'dipinjam'
            ]);

            foreach ($request->kode_buku as $kode) {

                $buku = Buku::lockForUpdate()->findOrFail($kode);

                if ($buku->stok <= 0) {
                    throw new \Exception("Stok habis: " . $buku->judul_buku);
                }

                $buku->decrement('stok');

                DetailPeminjaman::create([
                    'id_peminjaman' => $peminjaman->id,
                    'kode_buku' => $kode,
                    'status_buku' => 'dipinjam'
                ]);
            }
        });

        return redirect()->back()->with('success', 'Peminjaman berhasil');
    }

    public function kembali($id)
    {
        DB::transaction(function () use ($id) {

            $detail = DetailPeminjaman::lockForUpdate()->findOrFail($id);

            if ($detail->status_buku === 'kembali') {
                throw new \Exception("Buku sudah dikembalikan");
            }

            $detail->update([
                'status_buku' => 'kembali',
                'tanggal_dikembalikan' => now()
            ]);

            Buku::where('kode_buku', $detail->kode_buku)
                ->increment('stok');

            $peminjaman = $detail->peminjaman;

            $masihDipinjam = $peminjaman->details()
                ->where('status_buku', 'dipinjam')
                ->exists();

            if (!$masihDipinjam) {
                $peminjaman->update([
                    'status_peminjaman' => 'kembali',
                    'tanggal_pengembalian' => now()
                ]);
            }
        });

        return redirect()->back()->with('success', 'Buku dikembalikan');
    }
}
