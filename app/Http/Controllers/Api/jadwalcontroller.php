<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;

class JadwalController extends Controller
{
    // GET /api/jadwal
    public function index()
    {
        $jadwals = Jadwal::all();
        return response()->json(['success' => true, 'data' => $jadwals]);
    }

    // GET /api/jadwal/{akun}
    public function show($akun)
    {
        $jadwal = Jadwal::where('user_id', $akun)->get();
        if ($jadwal->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $jadwal]);
    }

    // POST /api/jadwal/tambah
    public function store(Request $request)
    {
        // Ambil token dari header Authorization
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token tidak ditemukan'], 401);
        }

        // Hilangkan prefix "Bearer " jika ada
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        // Debug: tampilkan token yang diterima
        // \Log::info('Token dari header:', [$token]);

        // Cari user berdasarkan api_token
        $user = \App\Models\Akun::where('api_token', $token)->first();

        // Debug: tampilkan token di database
        // $userDebug = \App\Models\Akun::pluck('api_token', 'email');
        // \Log::info('Semua token di database:', $userDebug);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid',
                'token_dikirim' => $token,
                // 'token_db' => $userDebug, // aktifkan jika ingin debug semua token
            ], 401);
        }

        $validated = $request->validate([
            // user_id diambil dari user yang login, bukan dari request
            'nama_jadwal' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'catatan' => 'nullable|string',
            // 'jam' => 'nullable',
        ]);

        $validated['user_id'] = $user->id;

        $jadwal = Jadwal::create($validated);

        return response()->json(['success' => true, 'data' => $jadwal]);
    }

    // PUT /api/jadwal/{akun}
    public function update(Request $request, $akun)
    {
        $jadwal = Jadwal::where('user_id', $akun)->first();
        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama_jadwal' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'catatan' => 'nullable|string',
            // 'jam' => 'nullable', // tambahkan jika memang ada field jam
        ]);

        $jadwal->update($validated);

        return response()->json(['success' => true, 'data' => $jadwal]);
    }

    // DELETE /api/jadwal/{akun}
    public function destroy($akun)
    {
        $jadwal = Jadwal::where('user_id', $akun)->first();
        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }
        $jadwal->delete();
        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus']);
    }
}
