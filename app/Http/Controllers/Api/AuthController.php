<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Akun;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $credentials = $request->only('email', 'password');
            $user = Akun::where('email', $credentials['email'])->first();

            if (!$user) {
                // Debug: email tidak ditemukan
                return response()->json([
                    'status' => false,
                    'message' => 'Login gagal: email tidak ditemukan',
                    'input_email' => $credentials['email']
                ], 401);
            }

            // Debug: tampilkan password dari database dan input
            if ($credentials['password'] === $user->kataSandi) {
                // Generate token (panjang 60 karakter)
                $token = bin2hex(random_bytes(30));
                $user->api_token = $token;
                $user->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Login berhasil',
                    'user' => $user,
                    'token' => $token
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Login gagal: password tidak cocok',
                    'input_password' => $credentials['password'],
                    'db_password' => $user->kataSandi
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}