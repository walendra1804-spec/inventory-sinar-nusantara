<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['no_transaksi', 'jenis', 'tanggal', 'gudang_id', 'gudang_tujuan_id', 'pelanggan_id', 'user_id', 'status', 'keterangan'];

    public function detail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function gudangTujuan()
    {
        return $this->belongsTo(Gudang::class, 'gudang_tujuan_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
