<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Akun; // gunakan model Akun, bukan User

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('landing.buatakun');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:akun,email',
            'password' => 'required|confirmed|min:3',
        ]);

        Akun::create([
            'name' => $request->name,
            'username' => $request->name, // atau tambahkan input username jika ingin berbeda
            'email' => $request->email,
            'kataSandi' => Hash::make($request->password), // aman & sesuai best practice 
        ]);

        return redirect('/login')->with('success', 'Akun berhasil dibuat, silakan login.');
    }
}
