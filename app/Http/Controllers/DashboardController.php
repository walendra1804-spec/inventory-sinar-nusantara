<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Pelanggan;
use App\Models\StokMutasi;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        //1. total data master
        $total_barang = Barang::count();
        $total_gudang = Gudang::count();
        $total_pelanggan = Pelanggan::count();

        //2. total penjualan hari ini (yang dibatalkan tidak dihitung)
        $penjualan = Transaksi::where('jenis', 'penjualan')
            ->where('status', 'selesai')
            ->whereDate('tanggal', date('Y-m-d'))
            ->get();
        $total_penjualan = 0;
        foreach ($penjualan as $p) {
            $total_penjualan += $p->total();
        }

        //3. barang dengan stok terendah (stok = jumlah semua kartu stok barang itu di semua gudang)
        $barang = Barang::where('status_aktif', 1)->get();
        foreach ($barang as $b) {
            $b->stok = StokMutasi::where('barang_id', $b->id)->sum('jumlah');
        }
        $stok_terendah = $barang->sortBy('stok')->take(5);

        return view('home.dashboard', compact('total_barang', 'total_gudang', 'total_pelanggan', 'total_penjualan', 'stok_terendah'));
    }
}
