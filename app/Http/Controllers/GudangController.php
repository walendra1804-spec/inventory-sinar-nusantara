<?php

namespace App\Http\Controllers;
use App\Models\Gudang;
use App\Models\Transaksi;

use Illuminate\Http\Request;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gudang = Gudang::all();
        return view('home.gudang.index', compact('gudang'));
    }

    public function create()
    {
        return view('home.gudang.create');
    }

    public function store(Request $request)
    {
        //melakukan pengecekan inputan
        $request->validate([
            'nama_gudang' => 'required',
        ]);

        //tambah data gudang baru
        Gudang::create([
            'nama_gudang' => $request->nama_gudang,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('gudang.index')->with('success', 'Data Berhasil ditambah');
    }

    public function edit(string $id)
    {
        $gudang = Gudang::find($id);
        return view('home.gudang.edit', compact('gudang'));
    }

    public function update(Request $request, string $id)
    {
        //melakukan pengecekan inputan
        $request->validate([
            'nama_gudang' => 'required',
        ]);

        //update data gudang
        $gudang = Gudang::find($id);
        $gudang->update([
            'nama_gudang' => $request->nama_gudang,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('gudang.index')->with('success', 'Data Berhasil di edit');
    }

    public function destroy(string $id)
    {
        //gudang yang sudah dipakai transaksi tidak boleh dihapus
        if (Transaksi::where('gudang_id', $id)->orWhere('gudang_tujuan_id', $id)->exists()) {
            return redirect()->route('gudang.index')->with('error', 'Gudang sudah dipakai di transaksi, tidak bisa dihapus.');
        }

        $gudang = Gudang::find($id);
        $gudang->delete();
        return redirect()->route('gudang.index')->with('success', 'Data Berhasil di hapus');
    }
}
