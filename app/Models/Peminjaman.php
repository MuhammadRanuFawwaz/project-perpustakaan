<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'id_pengunjung',
        'tanggal_peminjaman',
        'batas_pengembalian',
        'tanggal_pengembalian',
        'status_peminjaman',
    ];

    public function pengunjung()
    {
        return $this->belongsTo(Pengunjung::class, 'id_pengunjung');
    }

    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman');
    }
}
