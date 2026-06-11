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
        $periode = request('periode', 'bulan_ini');

        $dari = request('dari');
        $sampai = request('sampai');

        $queryPengunjung = Pengunjung::query();

        if ($dari && $sampai) {
            $queryPengunjung->whereBetween('tanggal_kunjung', [
                Carbon::parse($dari)->startOfDay()->toDateString(),
                Carbon::parse($sampai)->endOfDay()->toDateString(),
            ]);
        } else {
            if ($periode === 'hari_ini') {
                $queryPengunjung->whereDate('tanggal_kunjung', today());
            }

            if ($periode === 'minggu_ini') {
                $queryPengunjung->whereBetween('tanggal_kunjung', [
                    now()->startOfWeek()->toDateString(),
                    now()->endOfWeek()->toDateString(),
                ]);
            }

            if ($periode === 'bulan_ini') {
                $queryPengunjung->whereMonth('tanggal_kunjung', now()->month)
                    ->whereYear('tanggal_kunjung', now()->year);
            }

            if ($periode === 'bulan_lalu') {
                $bulanLalu = now()->subMonth();

                $queryPengunjung->whereMonth('tanggal_kunjung', $bulanLalu->month)
                    ->whereYear('tanggal_kunjung', $bulanLalu->year);
            }

            if ($periode === 'tahun_ini') {
                $queryPengunjung->whereYear('tanggal_kunjung', now()->year);
            }
        }

        $totalPengunjung = (clone $queryPengunjung)->count();

        $statistikJurusan = (clone $queryPengunjung)
            ->leftJoin('kelas', 'pengunjung.id_kelas', '=', 'kelas.id')
            ->select(
                DB::raw("COALESCE(kelas.nama_kelas, 'Guru') as nama_kelas"),
                DB::raw("COALESCE(kelas.jurusan, '-') as jurusan"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('kelas.nama_kelas', 'kelas.jurusan')
            ->orderByDesc('total')
            ->get();

        $statistikBukuKategori = Buku::leftJoin('kategori', 'buku.id_kategori', '=', 'kategori.id')
            ->select(
                DB::raw("COALESCE(kategori.nama_kategori, 'Tanpa Kategori') as nama_kategori"),
                DB::raw('COUNT(buku.kode_buku) as total_judul'),
                DB::raw('COALESCE(SUM(buku.stok), 0) as total_buku')
            )
            ->groupBy('kategori.nama_kategori')
            ->orderByDesc('total_buku')
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

        $jatuhTempo = Peminjaman::with([
            'pengunjung',
            'details.buku',
        ])
            ->where('status_peminjaman', 'dipinjam')
            ->whereDate('batas_pengembalian', '<=', Carbon::now())
            ->orderBy('batas_pengembalian', 'asc')
            ->get();

        $akanJatuhTempo = Peminjaman::with([
            'pengunjung',
            'details.buku',
        ])
            ->where('status_peminjaman', 'dipinjam')
            ->whereBetween('batas_pengembalian', [
                Carbon::now(),
                Carbon::now()->addDays(3),
            ])
            ->orderBy('batas_pengembalian', 'asc')
            ->get();

        return view('dashboard', [
            'periode' => $periode,
            'total_pengunjung' => $totalPengunjung,
            'total_judul_buku' => Buku::count(),
            'total_jumlah_buku' => Buku::sum('stok'),
            'total_dipinjam' => DetailPeminjaman::where('status_buku', 'dipinjam')->count(),
            'statistikJurusan' => $statistikJurusan,
            'statistikBukuKategori' => $statistikBukuKategori,
            'aktivitasPengunjung' => $aktivitasPengunjung,
            'aktivitasBuku' => $aktivitasBuku,
            'aktivitasPeminjaman' => $aktivitasPeminjaman,
            'jatuhTempo' => $jatuhTempo,
            'akanJatuhTempo' => $akanJatuhTempo,
        ]);
    }
}
