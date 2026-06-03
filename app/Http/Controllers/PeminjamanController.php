<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Buku;
use App\Models\Pengunjung;
use App\Models\Kelas;
use App\Exports\PeminjamanExport;
use Maatwebsite\Excel\Facades\Excel;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['pengunjung.kelas', 'details.buku.hargaBuku']);

        if ($request->start_date) {
            $query->whereDate('tanggal_peminjaman', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('tanggal_peminjaman', '<=', $request->end_date);
        }

        if ($request->status_peminjaman) {
            $query->where('status_peminjaman', $request->status_peminjaman);
        }

        if ($request->nama_kelas) {
            $query->whereHas('pengunjung.kelas', function ($q) use ($request) {
                [$tingkat, $rombel] = explode('-', $request->nama_kelas);

                $q->where('nama_kelas', 'like', $tingkat . '-%' . $rombel);
            });
        }

        if ($request->jurusan) {
            $query->whereHas('pengunjung.kelas', function ($q) use ($request) {
                $q->where('jurusan', $request->jurusan);
            });
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('pengunjung', function ($pengunjung) use ($request) {
                    $pengunjung->where('nama_pengunjung', 'like', '%' . $request->search . '%');
                })
                    ->orWhereHas('details.buku', function ($buku) use ($request) {
                        $buku->where('kode_buku', 'like', '%' . $request->search . '%')
                            ->orWhere('judul_buku', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $peminjaman = $query
            ->latest()
            ->paginate($request->get('per_page', 10))
            ->withQueryString();

        $pengunjung = Pengunjung::with('kelas')
            ->orderBy('nama_pengunjung')
            ->get();

        $buku = Buku::with('kategori')
            ->orderByRaw("
        CASE
            WHEN jenjang_kelas = 'X' THEN 1
            WHEN jenjang_kelas = 'XI' THEN 2
            WHEN jenjang_kelas = 'XII' THEN 3
            ELSE 4
        END
    ")
            ->orderBy('judul_buku')
            ->get();

        $kelas = Kelas::orderByRaw("
        CASE
            WHEN nama_kelas LIKE 'XII-%' THEN 3
            WHEN nama_kelas LIKE 'XI-%' THEN 2
            WHEN nama_kelas LIKE 'X-%' THEN 1
            ELSE 4
        END
    ")
            ->orderBy('nama_kelas')
            ->orderBy('jurusan')
            ->get();

        return view('peminjaman.index', compact(
            'peminjaman',
            'pengunjung',
            'buku',
            'kelas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pengunjung' => 'required|exists:pengunjung,id',
            'kode_buku' => 'required|array|min:1',
            'kode_buku.*' => 'required|exists:buku,kode_buku',
            'tanggal_peminjaman' => 'required|date',
            'batas_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
        ]);

        $kodeBuku = array_values(array_unique(array_filter($request->kode_buku)));

        if (count($kodeBuku) < 1) {
            return redirect()
                ->route('peminjaman.index')
                ->withErrors('Minimal pilih 1 buku.')
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $kodeBuku) {

                $peminjaman = Peminjaman::create([
                    'id_pengunjung' => $request->id_pengunjung,
                    'tanggal_peminjaman' => $request->tanggal_peminjaman,
                    'batas_pengembalian' => $request->batas_pengembalian,
                    'tanggal_pengembalian' => null,
                    'status_peminjaman' => 'dipinjam',
                ]);

                foreach ($kodeBuku as $kode) {

                    $buku = Buku::where('kode_buku', $kode)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($buku->stok <= 0) {
                        throw new \Exception('Stok buku habis: ' . $buku->judul_buku);
                    }

                    $buku->decrement('stok');

                    DetailPeminjaman::create([
                        'id_peminjaman' => $peminjaman->id,
                        'kode_buku' => $kode,
                        'status_buku' => 'dipinjam',
                        'tanggal_dikembalikan' => null,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()
                ->route('peminjaman.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pengunjung' => 'required|exists:pengunjung,id',
            'tanggal_peminjaman' => 'required|date',
            'batas_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
        ]);

        Peminjaman::findOrFail($id)->update([
            'id_pengunjung' => $request->id_pengunjung,
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'batas_pengembalian' => $request->batas_pengembalian,
        ]);

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil diupdate');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $peminjaman = Peminjaman::with('details')->findOrFail($id);

            foreach ($peminjaman->details as $detail) {
                if ($detail->status_buku === 'dipinjam') {
                    Buku::where('kode_buku', $detail->kode_buku)->increment('stok');
                }
            }

            $peminjaman->details()->delete();
            $peminjaman->delete();
        });

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus');
    }

    public function kembali($id)
    {
        DB::transaction(function () use ($id) {

            $detail = DetailPeminjaman::lockForUpdate()->findOrFail($id);

            if ($detail->status_buku === 'kembali') {
                return;
            }

            $detail->update([
                'status_buku' => 'kembali',
                'tanggal_dikembalikan' => now()->toDateString(),
            ]);

            Buku::where('kode_buku', $detail->kode_buku)->increment('stok');

            $peminjaman = $detail->peminjaman;

            $masihDipinjam = $peminjaman->details()
                ->where('status_buku', 'dipinjam')
                ->exists();

            if (! $masihDipinjam) {
                $peminjaman->update([
                    'status_peminjaman' => 'kembali',
                    'tanggal_pengembalian' => now()->toDateString(),
                ]);
            }
        });

        return redirect()
            ->route('peminjaman.index', [
                'open_detail' => request('open_detail')
            ])
            ->with('success', 'Buku berhasil dikembalikan');
    }

    public function hilang($id)
    {
        DB::transaction(function () use ($id) {

            $detail = DetailPeminjaman::with('buku.hargaBuku')
                ->lockForUpdate()
                ->findOrFail($id);

            if ($detail->status_buku !== 'dipinjam') {
                return;
            }

            $hargaGanti = $detail->buku->hargaBuku->harga ?? 0;

            $detail->update([
                'status_buku' => 'hilang',
                'harga_ganti' => $hargaGanti,
                'tanggal_dikembalikan' => now()->toDateString(),
            ]);

            $peminjaman = $detail->peminjaman;

            $masihDipinjam = $peminjaman->details()
                ->where('status_buku', 'dipinjam')
                ->exists();

            if (! $masihDipinjam) {
                $peminjaman->update([
                    'status_peminjaman' => 'kembali',
                    'tanggal_pengembalian' => now()->toDateString(),
                ]);
            }
        });

        return redirect()
            ->route('peminjaman.index', [
                'open_detail' => request('open_detail')
            ])
            ->with('success', 'Buku berhasil ditandai hilang');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new PeminjamanExport($request),
            'data-peminjaman.xlsx'
        );
    }
}
