<?php

namespace App\Http\Controllers;
use App\Models\Pelanggan;
use App\Models\Transaksi;

use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggan = Pelanggan::all();
        return view('home.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('home.pelanggan.create');
    }

    public function store(Request $request)
    {
        //melakukan pengecekan inputan
        $request->validate([
            'nama_pelanggan' => 'required',
            'no_hp' => 'nullable|max:20',
            'alamat' => 'required',
        ]);

        //tambah data pelanggan baru
        Pelanggan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Data Berhasil ditambah');
    }

    public function edit(string $id)
    {
        $pelanggan = Pelanggan::find($id);
        return view('home.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, string $id)
    {
        //melakukan pengecekan inputan
        $request->validate([
            'nama_pelanggan' => 'required',
            'no_hp' => 'nullable|max:20',
            'alamat' => 'required',
        ]);

        //update data pelanggan
        $pelanggan = Pelanggan::find($id);
        $pelanggan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Data Berhasil di edit');
    }

    public function destroy(string $id)
    {
        // pelanggan yang sudah ada di transaksi tidak boleh dihapus
        if (Transaksi::where('pelanggan_id', $id)->exists()) {
            return redirect()->route('pelanggan.index')->with('error', 'Pelanggan sudah punya transaksi, tidak bisa dihapus.');
        }

        $pelanggan = Pelanggan::find($id);
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Data Berhasil di hapus');
    }
}
