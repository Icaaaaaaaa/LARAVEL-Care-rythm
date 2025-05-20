<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function landing() {
        return view('landing.login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = DB::table('akun')
            ->where('email', $email)
            ->where('kataSandi', $password)
            ->first();

        if ($user) {
            session(['user' => $user]);
            return redirect('/home');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/');
    }
}
