<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // menampilkan halaman login
    public function index()
    {
        return view('auth.login');
    }

    // memproses login
    public function login(Request $request)
    {
        //melakukan pengecekan inputan tidak boleh kosong
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //cek email & password ke tabel users
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect('/');
        }

        //jika salah, kembali ke halaman login
        return back()->with('error', 'Email atau password salah')->withInput();
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
