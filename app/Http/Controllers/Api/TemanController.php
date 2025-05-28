<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teman;
use App\Models\Akun;  // Model untuk tabel akun
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TemanController extends Controller
{
    // Tampilkan semua relasi pertemanan (optional)
    public function index()
    {
        return Teman::all();
    }

    // Tampilkan daftar teman untuk user tertentu dengan detail username dan email
    public function show($userId)
    {
        $temans = DB::table('teman')
            ->join('akun', 'teman.teman_id', '=', 'akun.id')
            ->where('teman.user_id', $userId)
            ->select('akun.id', 'akun.username', 'akun.email', 'teman.created_at')
            ->get();

        return response()->json([
            'user_id' => $userId,
            'temans' => $temans,
        ]);
    }

    // Tambah relasi teman baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:akun,id',
            'teman_id' => 'required|exists:akun,id|different:user_id',
        ]);

        $userId = $request->input('user_id');
        $temanId = $request->input('teman_id');

        $exists = Teman::where('user_id', $userId)
            ->where('teman_id', $temanId)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Teman sudah ada'], 409);
        }

        $teman = Teman::create([
            'user_id' => $userId,
            'teman_id' => $temanId,
        ]);

        return response()->json([
            'message' => 'Teman berhasil ditambahkan',
            'data' => $teman,
        ], 201);
    }

    // Hapus relasi teman berdasarkan id teman (primary key tabel teman)
    public function destroy($id)
    {
        $teman = Teman::find($id);

        if (!$teman) {
            return response()->json(['message' => 'Data teman tidak ditemukan'], 404);
        }

        $teman->delete();

        return response()->json(['message' => 'Teman berhasil dihapus']);
    }

    // Tambah user baru (akun)
    public function tambahUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'kataSandi' => 'required|string|min:6',
            'email' => 'required|email|unique:akun,email',
            'role' => 'required|in:user,admin',
        ]);

        $user = Akun::create([
            'username' => $request->username,
            'kataSandi' => Hash::make($request->kataSandi),
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'User berhasil ditambahkan',
            'data' => $user,
        ], 201);
    }

    // Method baru: Tampilkan semua user (untuk cek user lewat API)
    public function listUsers()
    {
        $users = Akun::select('id', 'username', 'email', 'role')->get();

        return response()->json($users);
    }
}
