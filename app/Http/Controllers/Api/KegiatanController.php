<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Kegiatan::all()
        ]);
    }

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

        $validated = $request->validate([
            'kegiatan' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'tempat' => 'nullable|string|max:255',
        ], [
            'waktu_mulai.date_format' => 'Format waktu_mulai harus HH:mm (contoh: 08:00)',
            'waktu_selesai.date_format' => 'Format waktu_selesai harus HH:mm (contoh: 09:00)',
        ]);

        $validated['user_id'] = $user->id;

        $kegiatan = Kegiatan::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Kegiatan berhasil ditambahkan.',
            'data' => $kegiatan
        ], 201);
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::find($id);
        if (!$kegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Kegiatan tidak ditemukan.'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $kegiatan
        ]);
    }

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
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid',
                'token_dikirim' => $token,
            ], 401);
        }

        $kegiatan = Kegiatan::find($id);
        if (!$kegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Kegiatan tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'kegiatan' => 'sometimes|required|string|max:255',
            'catatan' => 'nullable|string',
            'tanggal' => 'sometimes|required|date',
            'waktu_mulai' => 'sometimes|required|date_format:H:i',
            'waktu_selesai' => 'sometimes|required|date_format:H:i',
            'tempat' => 'nullable|string|max:255',
            // 'user_id' tidak perlu divalidasi/diupdate manual
        ]);

        $kegiatan->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Kegiatan berhasil diupdate.',
            'data' => $kegiatan
        ]);
    }

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
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid',
                'token_dikirim' => $token,
            ], 401);
        }

        $kegiatan = Kegiatan::find($id);
        if (!$kegiatan) {
            return response()->json([
                'status' => false,
                'message' => 'Kegiatan tidak ditemukan.'
            ], 404);
        }
        $kegiatan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kegiatan berhasil dihapus.'
        ]);
    }
}