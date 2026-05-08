<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'jurusan'
    ];

    public function pengunjung()
    {
        return $this->hasMany(Pengunjung::class, 'id_kelas');
    }
}
