<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\StokMutasi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    //LAPORAN STOK: stok setiap barang di setiap gudang
    public function stok(Request $request)
    {
        $query = StokMutasi::query();

        //filter (hanya dipakai jika diisi)
        if ($request->gudang_id) {
            $query->where('gudang_id', $request->gudang_id);
        }
        if ($request->barang_id) {
            $query->where('barang_id', $request->barang_id);
        }
        if ($request->sampai) {
            //stok per tanggal tertentu = jumlah semua kartu stok sampai tanggal itu
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        //jumlahkan kartu stok per barang per gudang
        $data = $query->select('barang_id', 'gudang_id', DB::raw('SUM(jumlah) as stok'))
            ->groupBy('barang_id', 'gudang_id')
            ->get();

        //export CSV
        if ($request->export == 'csv') {
            $baris = [];
            foreach ($data as $d) {
                $baris[] = [$d->barang->sku, $d->barang->nama_barang, $d->gudang->nama_gudang, $d->stok, $d->barang->satuan];
            }
            return $this->csv('laporan-stok.csv', ['SKU', 'Nama Barang', 'Gudang', 'Stok', 'Satuan'], $baris);
        }

        $gudang = Gudang::all();
        $barang = Barang::all();
        return view('home.laporan.stok', compact('data', 'gudang', 'barang'));
    }

    //LAPORAN BARANG MASUK & PENJUALAN: daftar barang dari transaksi yang tidak dibatalkan
    public function transaksi(Request $request, string $jenis)
    {
        $query = TransaksiDetail::whereHas('transaksi', function ($q) use ($request, $jenis) {
            $q->where('jenis', $jenis)->where('status', 'selesai');

            if ($request->dari) {
                $q->where('tanggal', '>=', $request->dari);
            }
            if ($request->sampai) {
                $q->where('tanggal', '<=', $request->sampai);
            }
            if ($request->gudang_id) {
                $q->where('gudang_id', $request->gudang_id);
            }
        });

        if ($request->barang_id) {
            $query->where('barang_id', $request->barang_id);
        }

        $data = $query->get();

        //export CSV
        if ($request->export == 'csv') {
            $baris = [];
            foreach ($data as $d) {
                $baris[] = [
                    $d->transaksi->no_transaksi,
                    $d->transaksi->tanggal,
                    $d->transaksi->gudang->nama_gudang,
                    $d->transaksi->pelanggan->nama_pelanggan ?? '-',
                    $d->barang->sku,
                    $d->barang->nama_barang,
                    $d->jumlah,
                    $d->harga,
                    $d->jumlah * $d->harga,
                ];
            }
            return $this->csv('laporan-' . $jenis . '.csv', ['No Transaksi', 'Tanggal', 'Gudang', 'Pelanggan', 'SKU', 'Nama Barang', 'Jumlah', 'Harga', 'Subtotal'], $baris);
        }

        $gudang = Gudang::all();
        $barang = Barang::all();
        return view('home.laporan.transaksi', compact('data', 'jenis', 'gudang', 'barang'));
    }

    //membuat file CSV untuk didownload
    private function csv($nama_file, $judul, $baris)
    {
        return response()->streamDownload(function () use ($judul, $baris) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $judul);
            foreach ($baris as $b) {
                fputcsv($file, $b);
            }
            fclose($file);
        }, $nama_file, ['Content-Type' => 'text/csv']);
    }
}
