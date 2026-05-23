<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Pengunjung;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $statistikJurusan = Pengunjung::join('kelas', 'pengunjung.id_kelas', '=', 'kelas.id')
            ->select('kelas.jurusan', DB::raw('COUNT(*) as total'))
            ->whereNotNull('pengunjung.id_kelas')
            ->groupBy('kelas.jurusan')
            ->orderByDesc('total')
            ->get();

        $aktivitasPengunjung = Pengunjung::latest()
            ->take(5)
            ->get()
            ->map(function ($pengunjung) {
                return [
                    'judul' => 'Pengunjung ditambahkan',
                    'deskripsi' => $pengunjung->nama_pengunjung,
                    'waktu' => $pengunjung->created_at,
                ];
            });

        $aktivitasBuku = Buku::latest()
            ->take(5)
            ->get()
            ->map(function ($buku) {
                return [
                    'judul' => 'Buku ditambahkan',
                    'deskripsi' => $buku->judul_buku,
                    'waktu' => $buku->created_at,
                ];
            });

        $aktivitasPeminjaman = DetailPeminjaman::with([
            'peminjaman.pengunjung',
            'buku',
        ])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($detail) {
                return [
                    'judul' => $detail->status_buku === 'kembali'
                        ? 'Buku dikembalikan'
                        : 'Buku dipinjam',

                    'deskripsi' => ($detail->peminjaman->pengunjung->nama_pengunjung ?? '-')
                        . ' - '
                        . ($detail->buku->judul_buku ?? '-'),

                    'waktu' => $detail->updated_at,
                ];
            });

        $aktivitas = collect()
            ->merge($aktivitasPengunjung)
            ->merge($aktivitasBuku)
            ->merge($aktivitasPeminjaman)
            ->sortByDesc('waktu')
            ->take(8)
            ->values();

        $jatuhTempo = Peminjaman::with([
            'pengunjung',
            'details.buku',
        ])
            ->where('status_peminjaman', 'dipinjam')
            ->whereDate('batas_pengembalian', '<=', Carbon::now()->addDays(3))
            ->orderBy('batas_pengembalian', 'asc')
            ->get();

        return view('dashboard', [
            'total_pengunjung' => Pengunjung::count(),
            'total_buku' => Buku::count(),
            'total_dipinjam' => DetailPeminjaman::where('status_buku', 'dipinjam')->count(),
            'statistikJurusan' => $statistikJurusan,
            'aktivitas' => $aktivitas,
            'jatuhTempo' => $jatuhTempo,
        ]);
    }
}
