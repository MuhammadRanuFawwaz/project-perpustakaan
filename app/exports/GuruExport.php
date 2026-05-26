<?php

namespace App\Exports;

use App\Models\Guru;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;

class GuruExport implements FromCollection
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Guru::query();

        if ($this->request->search) {
            $query->where(function ($q) {
                $q->where('nip', 'like', '%' . $this->request->search . '%')
                    ->orWhere('nama_guru', 'like', '%' . $this->request->search . '%');
            });
        }

        return $query
            ->get()
            ->map(function ($g) {
                return [
                    'NIP' => $g->nip,
                    'Nama Guru' => $g->nama_guru,
                ];
            });
    }
}
