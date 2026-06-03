<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaBuku extends Model
{
    protected $table = 'harga_buku';

    protected $fillable = [
        'kode_buku',
        'harga',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'kode_buku', 'kode_buku');
    }
}
