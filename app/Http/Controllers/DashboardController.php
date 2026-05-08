<?php

namespace App\Http\Controllers;

use App\Models\Pengunjung;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [

            'total_pengunjung' =>
            Pengunjung::count(),

            'total_buku' =>
            Buku::count(),

            'total_dipinjam' =>
            DetailPeminjaman::where('status_buku', 'dipinjam')->count(),

            'aktivitas' =>
            DetailPeminjaman::with('peminjaman.pengunjung')
                ->latest()
                ->take(5)
                ->get(),

            'jatuhTempo' =>
            Peminjaman::with('pengunjung')
                ->where('status_peminjaman', 'dipinjam')
                ->get()
        ]);
    }
}
