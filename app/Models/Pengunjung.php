<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    protected $table = 'pengunjung';

    protected $fillable = [
        'nama_pengunjung',
<<<<<<< HEAD
        'jenis_pengunjung',
=======
>>>>>>> 7fd2d379b2aab1588c9827f01616e7a7d0700a36
        'id_kelas',
        'tanggal_kunjung',
        'waktu_kunjung',
        'keperluan'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_pengunjung');
    }
}
