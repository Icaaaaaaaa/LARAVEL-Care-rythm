<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;

class JadwalController extends Controller
{
    // Menampilkan semua jadwal milik user yang sedang login (berdasarkan token)
    // Endpoint: GET /api/jadwal
    public function index(Request $request)
    {
        // Ambil token dari header Authorization
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token tidak ditemukan'], 401);
        }
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }
        $user = \App\Models\Akun::where('api_token', $token)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid',
                'token_dikirim' => $token,
            ], 401);
        }

        // Ambil jadwal berdasarkan user_id
        $jadwals = Jadwal::where('user_id', $user->id)->get();
        return response()->json(['success' => true, 'data' => $jadwals]);
    }

    // Menampilkan semua jadwal berdasarkan user_id (akun)
    // Endpoint: GET /api/jadwal/{akun}
    public function show($akun)
    {
        $jadwal = Jadwal::where('user_id', $akun)->get();
        if ($jadwal->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $jadwal]);
    }

    // Menyimpan jadwal baru berdasarkan token pengguna
    // Endpoint: POST /api/jadwal/tambah
    // Header Authorization: Bearer <token> akan otomatis dikirim dari Flutter.
    // Di controller, ambil dengan $request->header('Authorization') seperti contoh di atas.
    public function store(Request $request)
    {
        // Ambil token dari header Authorization
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token tidak ditemukan'], 401);
        }

        // Jika format token adalah Bearer <token>, ambil hanya token-nya
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        // Cari user berdasarkan api_token
        $user = \App\Models\Akun::where('api_token', $token)->first();

        // Jika token tidak valid atau user tidak ditemukan
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid',
                'token_dikirim' => $token,
            ], 401);
        }

        // Validasi input dari request
        $validated = $request->validate([
            'nama_jadwal'   => 'required|string|max:100',
            'kategori'      => 'required|string|in:Pelajaran,Olahraga,Hiburan,Lainnya',
            'waktu_mulai'   => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            // hari bisa array (multi select checkbox)
            'hari'          => 'required|array',
            'hari.*'        => 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'catatan'       => 'nullable|string|max:255',
        ]);

        // Jika hari dikirim array, ubah ke string
        if (is_array($validated['hari'])) {
            $validated['hari'] = implode(',', $validated['hari']);
        }

        // Tambahkan user_id dari user yang terautentikasi
        $validated['user_id'] = $user->id;

        // Simpan data jadwal ke database
        $jadwal = Jadwal::create($validated);

        return response()->json(['success' => true, 'data' => $jadwal]);
    }

    // Memperbarui jadwal berdasarkan id jadwal
    // Endpoint: PUT /api/jadwal/{id}
    public function update(Request $request, $id)
    {
        // Ambil token dari header Authorization
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token tidak ditemukan'], 401);
        }
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }
        $user = \App\Models\Akun::where('api_token', $token)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid'], 401);
        }

        // Cari jadwal berdasarkan id
        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        // Pastikan hanya pemilik jadwal yang bisa update
        if ($jadwal->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak berhak mengedit jadwal ini'], 403);
        }

        // Validasi data yang akan diupdate
        $validated = $request->validate([
            'nama_jadwal' => 'required|string|max:100',
            'kategori' => 'required|string|in:Pelajaran,Olahraga,Hiburan,Lainnya',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'hari' => 'required|array',
            'hari.*' => 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'catatan' => 'nullable|string',
        ]);

        // Jika hari dikirim array, ubah ke string
        if (is_array($validated['hari'])) {
            $validated['hari'] = implode(',', $validated['hari']);
        }

        // Update data jadwal
        $jadwal->update($validated);

        return response()->json(['success' => true, 'data' => $jadwal]);
    }

    // Menghapus jadwal berdasarkan id jadwal
    // Endpoint: DELETE /api/jadwal/{id}
    public function destroy(Request $request, $id)
    {
        // Ambil token dari header Authorization
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token tidak ditemukan'], 401);
        }
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }
        $user = \App\Models\Akun::where('api_token', $token)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid'], 401);
        }

        // Cari jadwal berdasarkan id
        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        // Pastikan hanya pemilik jadwal yang bisa hapus
        if ($jadwal->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak berhak menghapus jadwal ini'], 403);
        }

        // Hapus jadwal
        $jadwal->delete();

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus']);
    }

    // Kernel jadwal: daftar hari dan kategori yang diizinkan
    // Endpoint: GET /api/jadwal/kernel
    public function kernel()
    {
        $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        $kategori = ['Pelajaran','Olahraga','Hiburan','Lainnya'];
        return response()->json([
            'success' => true,
            'hari' => $hari,
            'kategori' => $kategori
        ]);
    }
}