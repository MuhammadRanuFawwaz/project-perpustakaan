<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ddc extends Model
{
    protected $table = 'ddc';

    protected $fillable = [
        'kode_ddc',
        'nama_ddc',
    ];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_ddc');
    }
}
