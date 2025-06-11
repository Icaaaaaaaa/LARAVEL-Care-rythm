<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Tambahkan ini

class LoginController extends Controller
{
    public function landing() {
        return view('landing.login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        // Ambil user berdasarkan email saja
        $user = DB::table('akun')
            ->where('email', $email)
            ->first();

        // Jika user ditemukan dan password cocok
        if ($user && Hash::check($password, $user->kataSandi)) {
            session(['user' => $user]);
            return redirect('/home');
        }

        // Jika gagal login
        return back()->with('error', 'Email atau password salah.');
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/');
    }
}
