<?php

namespace App\Http\Controllers;

use App\Models\Pengunjung;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            ->map(function ($p) {
                return [
                    'judul' => 'Pengunjung ditambahkan',
                    'deskripsi' => $p->nama_pengunjung,
                    'waktu' => $p->created_at,
                ];
            });

        $aktivitasBuku = Buku::latest()
            ->take(5)
            ->get()
            ->map(function ($b) {
                return [
                    'judul' => 'Buku ditambahkan',
                    'deskripsi' => $b->judul_buku,
                    'waktu' => $b->created_at,
                ];
            });

        $aktivitasPeminjaman = DetailPeminjaman::with('peminjaman.pengunjung', 'buku')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($d) {
                return [
                    'judul' => $d->status_buku === 'kembali' ? 'Buku dikembalikan' : 'Buku dipinjam',
                    'deskripsi' => ($d->peminjaman->pengunjung->nama_pengunjung ?? '-') . ' - ' . ($d->buku->judul_buku ?? '-'),
                    'waktu' => $d->updated_at,
                ];
            });

        $aktivitas = $aktivitasPengunjung
            ->merge($aktivitasBuku)
            ->merge($aktivitasPeminjaman)
            ->sortByDesc('waktu')
            ->take(8);

        $jatuhTempo = Peminjaman::with(['pengunjung', 'details.buku'])
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
