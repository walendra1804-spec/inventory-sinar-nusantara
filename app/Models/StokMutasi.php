<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokMutasi extends Model
{
    protected $fillable = ['transaksi_id', 'barang_id', 'gudang_id', 'jumlah', 'keterangan'];

    // hitung stok barang di satu gudang = jumlah semua mutasinya
    public static function stok($barang_id, $gudang_id)
    {
        return self::where('barang_id', $barang_id)
            ->where('gudang_id', $gudang_id)
            ->sum('jumlah');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
