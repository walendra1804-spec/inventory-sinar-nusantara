<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\TransaksiDetail;

use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::all();
        return view('home.barang.index', compact('barang'));
    }

    public function create()
    {
        return view('home.barang.create');
    }

    public function store(Request $request)
    {
        //melakukan pengecekan inputan
        $request->validate([
            'sku' => 'required|unique:barangs,sku',
            'nama_barang' => 'required',
            'kategori' => 'required',
            'satuan' => 'required',
            'harga_pokok' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'status_aktif' => 'required|boolean',
        ]);

        //tambah data barang baru
        Barang::create([
            'sku' => $request->sku,
            'nama_barang' => $request->nama_barang,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'harga_pokok' => $request->harga_pokok,
            'harga_jual' => $request->harga_jual,
            'status_aktif' => $request->status_aktif,
        ]);

        return redirect()->route('barang.index')->with('success', 'Data Berhasil ditambah');
    }

    public function edit(string $id)
    {
        $barang = Barang::find($id);
        return view('home.barang.edit', compact('barang'));
    }

    public function update(Request $request, string $id)
    {
        //melakukan pengecekan inputan
        $request->validate([
            'sku' => 'required|unique:barangs,sku,' . $id,
            'nama_barang' => 'required',
            'kategori' => 'required',
            'satuan' => 'required',
            'harga_pokok' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'status_aktif' => 'required|boolean',
        ]);

        //update data barang
        $barang = Barang::find($id);
        $barang->update([
            'sku' => $request->sku,
            'nama_barang' => $request->nama_barang,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'harga_pokok' => $request->harga_pokok,
            'harga_jual' => $request->harga_jual,
            'status_aktif' => $request->status_aktif,
        ]);

        return redirect()->route('barang.index')->with('success', 'Data Berhasil di edit');
    }

    public function destroy(string $id)
    {
        //barang yang sudah pernah ditransaksikan tidak boleh dihapus, agar riwayat tetap ada
        if (TransaksiDetail::where('barang_id', $id)->exists()) {
            return redirect()->route('barang.index')->with('error', 'Barang sudah dipakai di transaksi, tidak bisa dihapus. Nonaktifkan saja.');
        }

        $barang = Barang::find($id);
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Data Berhasil di hapus');
    }
}
