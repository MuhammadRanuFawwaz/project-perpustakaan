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
        $periode = request('periode', 'harian');

        $dari = request('dari');
        $sampai = request('sampai');


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
        $queryPengunjung = Pengunjung::query();
        if ($dari && $sampai) {

            $queryPengunjung->whereBetween(
                'pengunjung.created_at',
                [
                    Carbon::parse($dari)->startOfDay(),
                    Carbon::parse($sampai)->endOfDay()
                ]
            );
        } else {

            if ($periode == 'harian') {

                $queryPengunjung->whereDate(
                    'pengunjung.created_at',
                    today()
                );
            } elseif ($periode == 'mingguan') {

                $queryPengunjung->whereBetween(
                    'pengunjung.created_at',
                    [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]
                );
            } elseif ($periode == 'bulanan') {

                $queryPengunjung->whereMonth(
                    'pengunjung.created_at',
                    now()->month
                );
            } elseif ($periode == 'tahunan') {

                $queryPengunjung->whereYear(
                    'pengunjung.created_at',
                    now()->year
                );
            }
        }

        $totalPengunjung = $queryPengunjung->count();
        $statistikJurusan = (clone $queryPengunjung)
            ->join('kelas', 'pengunjung.id_kelas', '=', 'kelas.id')
            ->select(
                'kelas.nama_kelas',
                'kelas.jurusan',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(
                'kelas.nama_kelas',
                'kelas.jurusan'
            )
            ->orderByDesc('total')
            ->get();

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
            ->whereBetween(
                'batas_pengembalian',
                [
                    Carbon::now(),
                    Carbon::now()->addDays(3)
                ]
            )
            ->orderBy('batas_pengembalian', 'asc')
            ->get();

        return view('dashboard', [
            'total_pengunjung' => $totalPengunjung,
            'total_buku' => Buku::count(),
            'total_dipinjam' => DetailPeminjaman::where('status_buku', 'dipinjam')->count(),
            'statistikJurusan' => $statistikJurusan,
            'aktivitasPengunjung' => $aktivitasPengunjung,
            'aktivitasBuku' => $aktivitasBuku,
            'aktivitasPeminjaman' => $aktivitasPeminjaman,
            'jatuhTempo' => $jatuhTempo,
            'akanJatuhTempo' => $akanJatuhTempo,
        ]);
    }
}
