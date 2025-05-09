<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    private $users = [
        ['email' => 'mufnah.ridz@gmail.com', 'password' => '123456', 'name' => 'Hafidz'],
        ['email' => 'disa123@gmail.com', 'password' => '123456', 'name' => 'Disa'],
    ];

    public function landing() {
        return view('landing.login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        foreach ($this->users as $user) {
            if ($user['email'] === $email && $user['password'] === $password) {
                session(['user' => $user]);
                return redirect('/home');
            }
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/');
    }
}
