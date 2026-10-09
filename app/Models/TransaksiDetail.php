<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $fillable = ['transaksi_id', 'barang_id', 'jumlah', 'harga'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
