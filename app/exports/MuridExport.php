<?php

namespace App\Exports;

use App\Models\Murid;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;

class MuridExport implements FromCollection
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Murid::with('kelas');

        if ($this->request->status) {
            $query->where('status', $this->request->status);
        } else {
            $query->where('status', 'aktif');
        }

        if ($this->request->search) {
            $query->where(function ($q) {
                $q->where('nis', 'like', '%' . $this->request->search . '%')
                    ->orWhere('nama_murid', 'like', '%' . $this->request->search . '%');
            });
        }

        if ($this->request->tingkat) {
            $query->whereHas('kelas', function ($q) {
                $q->where('nama_kelas', 'like', $this->request->tingkat . '-%');
            });
        }

        if ($this->request->jurusan) {
            $query->whereHas('kelas', function ($q) {
                $q->where('jurusan', $this->request->jurusan);
            });
        }

        return $query
            ->get()
            ->map(function ($m) {
                return [
                    'NIS' => $m->nis,
                    'Nama Murid' => $m->nama_murid,
                    'Kelas' => $m->kelas->nama_kelas ?? '-',
                    'Jurusan' => $m->kelas->jurusan ?? '-',
                    'Status' => ucfirst($m->status),
                ];
            });
    }
}
