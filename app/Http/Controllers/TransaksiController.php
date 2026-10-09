<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Pelanggan;
use App\Models\StokMutasi;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::orderBy('id', 'desc')->get();
        return view('home.transaksi.index', compact('transaksi'));
    }

    public function create(string $jenis)
    {
        $gudang = Gudang::all();
        $pelanggan = Pelanggan::all();
        $barang = Barang::where('status_aktif', 1)->get();
        return view('home.transaksi.create', compact('jenis', 'gudang', 'pelanggan', 'barang'));
    }

    public function store(Request $request, string $jenis)
    {
        //1. VALIDASI
        $aturan = [
            'tanggal' => 'required|date',
            'gudang_id' => 'required',
            'barang_id' => 'required',
            'barang_id.*' => 'required|distinct',
            'jumlah.*' => 'required|integer|min:1',
        ];
        if ($jenis == 'transfer') {
            $aturan['gudang_tujuan_id'] = 'required|different:gudang_id';
        }
        if ($jenis == 'penjualan') {
            $aturan['pelanggan_id'] = 'required';
        }
        $request->validate($aturan);

        //2. MULAI TRANSAKSI DATABASE
        DB::beginTransaction();

        //kunci barang yang dipilih, supaya kalau ada 2 orang input bersamaan, yang kedua menunggu
        Barang::whereIn('id', $request->barang_id)->lockForUpdate()->get();

        //3. CEK STOK (khusus penjualan & transfer, karena barangnya keluar)
        if ($jenis != 'masuk') {
            foreach ($request->barang_id as $i => $barang_id) {
                $stok = StokMutasi::stok($barang_id, $request->gudang_id);
                if ($request->jumlah[$i] > $stok) {
                    DB::rollBack();
                    $nama = Barang::find($barang_id)->nama_barang;
                    return back()->withInput()->with('error', 'Stok ' . $nama . ' tidak cukup. Sisa stok: ' . $stok);
                }
            }
        }

        //4. SIMPAN HEADER TRANSAKSI
        //nomor urut: TRX-00001, TRX-00002, dst (transaksi tidak pernah dihapus, jadi urutannya aman)
        $nomor = 'TRX-' . str_pad(Transaksi::count() + 1, 5, '0', STR_PAD_LEFT);

        $transaksi = Transaksi::create([
            'no_transaksi' => $nomor,
            'jenis' => $jenis,
            'tanggal' => $request->tanggal,
            'gudang_id' => $request->gudang_id,
            'gudang_tujuan_id' => $request->gudang_tujuan_id,
            'pelanggan_id' => $request->pelanggan_id,
            'user_id' => Auth::id(),
            'keterangan' => $request->keterangan,
        ]);

        //5. SIMPAN SETIAP BARANG
        foreach ($request->barang_id as $i => $barang_id) {
            $barang = Barang::find($barang_id);
            $jumlah = $request->jumlah[$i];

            //harga disalin dari master, supaya tidak ikut berubah kalau master diubah
            if ($jenis == 'penjualan') {
                $harga = $barang->harga_jual;
            } else {
                $harga = $barang->harga_pokok;
            }

            TransaksiDetail::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $barang_id,
                'jumlah' => $jumlah,
                'harga' => $harga,
            ]);

            //kartu stok: barang masuk = tambah (+), penjualan & transfer = kurang (-)
            if ($jenis == 'masuk') {
                $jumlah_mutasi = $jumlah;
            } else {
                $jumlah_mutasi = -$jumlah;
            }

            StokMutasi::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $barang_id,
                'gudang_id' => $request->gudang_id,
                'jumlah' => $jumlah_mutasi,
                'keterangan' => ucfirst($jenis) . ' ' . $transaksi->no_transaksi,
            ]);

            //khusus transfer: gudang tujuan bertambah (+)
            if ($jenis == 'transfer') {
                StokMutasi::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $barang_id,
                    'gudang_id' => $request->gudang_tujuan_id,
                    'jumlah' => $jumlah,
                    'keterangan' => 'Transfer masuk ' . $transaksi->no_transaksi,
                ]);
            }
        }

        //6. SELESAI, SIMPAN PERMANEN
        DB::commit();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan');
    }

    public function show(string $id)
    {
        $transaksi = Transaksi::find($id);
        $mutasi = StokMutasi::where('transaksi_id', $id)->get();
        return view('home.transaksi.show', compact('transaksi', 'mutasi'));
    }

    public function batal(string $id)
    {
        //1. MULAI TRANSAKSI DATABASE
        DB::beginTransaction();

        //ambil kartu stok dari transaksi ini, lalu kunci barangnya (sama seperti saat simpan)
        $mutasi = StokMutasi::where('transaksi_id', $id)->lockForUpdate()->get();
        Barang::whereIn('id', $mutasi->pluck('barang_id'))->lockForUpdate()->get();

        //2. CEK: transaksi yang sudah batal tidak bisa dibatalkan lagi
        $transaksi = Transaksi::find($id);
        if ($transaksi->status == 'batal') {
            DB::rollBack();
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya');
        }

        //3. CEK STOK: kalau dibalik, stok tidak boleh jadi minus
        //contoh: barang masuk 10 mau dibatalkan, tapi 8 sudah terjual -> sisa 2, tidak bisa dikurangi 10
        foreach ($mutasi as $m) {
            if ($m->jumlah > 0) {
                $stok = StokMutasi::stok($m->barang_id, $m->gudang_id);
                if ($stok < $m->jumlah) {
                    DB::rollBack();
                    return back()->with('error', 'Tidak bisa dibatalkan. Stok ' . $m->barang->nama_barang . ' di ' . $m->gudang->nama_gudang . ' sudah terpakai. Sisa stok: ' . $stok);
                }
            }
        }

        //4. BUAT KARTU STOK KEBALIKANNYA (+ jadi -, - jadi +). data lama TIDAK dihapus
        foreach ($mutasi as $m) {
            StokMutasi::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $m->barang_id,
                'gudang_id' => $m->gudang_id,
                'jumlah' => -$m->jumlah,
                'keterangan' => 'Batal ' . $transaksi->no_transaksi . ' oleh ' . Auth::user()->name,
            ]);
        }

        //5. UBAH STATUS JADI BATAL
        $transaksi->update(['status' => 'batal']);

        //6. SELESAI, SIMPAN PERMANEN
        DB::commit();

        return redirect()->route('transaksi.show', $id)->with('success', 'Transaksi berhasil dibatalkan');
    }
}