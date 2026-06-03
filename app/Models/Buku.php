<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    protected $primaryKey = 'kode_buku';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'kode_buku',
        'judul_buku',
        'id_kategori',
        'id_ddc',
        'jenjang_kelas',
        'kode_ddc',
        'stok',
        'tanggal_kirim'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function ddc()
    {
        return $this->belongsTo(Ddc::class, 'id_ddc');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'kode_buku', 'kode_buku');
    }

    public function hargaBuku()
    {
        return $this->hasOne(HargaBuku::class, 'kode_buku', 'kode_buku');
    }
}
