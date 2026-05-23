<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    protected $table = 'murid';

    protected $fillable = [
        'nis',
        'nama_murid',
        'id_kelas',
        'status',
        'tahun_ajaran',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
