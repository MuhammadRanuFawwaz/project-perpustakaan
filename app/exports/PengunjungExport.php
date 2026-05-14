<?php

namespace App\Exports;

use App\Models\Pengunjung;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengunjungExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Pengunjung::with('kelas');

        if ($this->request->start_date) {
            $query->whereDate('tanggal_kunjung', '>=', $this->request->start_date);
        }

        if ($this->request->end_date) {
            $query->whereDate('tanggal_kunjung', '<=', $this->request->end_date);
        }

        if ($this->request->jenis_pengunjung) {
            $query->where('jenis_pengunjung', $this->request->jenis_pengunjung);
        }

        if ($this->request->jurusan) {
            $query->whereHas('kelas', function ($q) {
                $q->where('jurusan', $this->request->jurusan);
            });
        }

        if ($this->request->search) {
            $query->where('nama_pengunjung', 'like', '%' . $this->request->search . '%');
        }

        return $query->latest()->get()->map(function ($p) {
            return [
                'nama_pengunjung' => $p->nama_pengunjung,
                'jenis_pengunjung' => $p->jenis_pengunjung,
                'kelas' => $p->kelas->nama_kelas ?? '-',
                'jurusan' => $p->kelas->jurusan ?? '-',
                'tanggal_kunjung' => $p->tanggal_kunjung,
                'waktu_kunjung' => $p->waktu_kunjung,
                'keperluan' => $p->keperluan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pengunjung',
            'Jenis Pengunjung',
            'Kelas',
            'Jurusan',
            'Tanggal Kunjung',
            'Waktu Kunjung',
            'Keperluan',
        ];
    }
}
