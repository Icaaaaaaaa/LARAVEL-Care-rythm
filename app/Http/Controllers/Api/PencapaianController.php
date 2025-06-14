<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pencapaian;
use App\Models\Akun;

class PencapaianController extends Controller
{
    // GET /api/pencapaian
    // Menampilkan semua pencapaian milik user yang sedang login (berdasarkan token)
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
        $user = Akun::where('api_token', $token)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid'], 401);
        }

        // Ambil pencapaian berdasarkan user_id
        $data = Pencapaian::where('user_id', $user->id)->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    // GET /api/pencapaian/{akun}
    public function show($akun)
    {
        $data = Pencapaian::where('user_id', $akun)->get();
        if ($data->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Data pencapaian tidak ditemukan'], 404);
        }
        return response()->json(['success' => true, 'data' => $data]);
    }

    // POST /api/pencapaian/tambah
    public function store(Request $request)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token tidak ditemukan'], 401);
        }

        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        $user = Akun::where('api_token', $token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid',
            ], 401);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'waktu_pencapaian' => 'required|date_format:Y-m-d',
            'target' => 'required|integer|min:1',
            'jumlah' => 'required|integer|min:0|max:100000',
            'kategori' => 'nullable|string|max:50',
        ]);

        if ($validated['jumlah'] > $validated['target']) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah tidak boleh melebihi target. Data tidak bisa ditambah.'
            ], 422);
        }

        $validated['user_id'] = $user->id;

        $pencapaian = Pencapaian::create($validated);


        return response()->json(['success' => true, 'data' => $pencapaian]);
    }

    // PUT /api/pencapaian/{id}
    public function update(Request $request, $id)
    {
        $pencapaian = Pencapaian::find($id);
        if (!$pencapaian) {
            return response()->json(['success' => false, 'message' => 'Pencapaian tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'waktu_pencapaian' => 'required|date_format:Y-m-d',
            'target' => 'required|integer|min:1',
            'jumlah' => 'required|integer|min:0|max:100000',
            'kategori' => 'nullable|string|max:50',
        ]);

        if ($validated['jumlah'] > $validated['target']) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah tidak boleh melebihi target. Data tidak bisa diupdate.'
            ], 422);
        }

        $pencapaian->update($validated);

        if ($validated['jumlah'] == $validated['target']) {
            return response()->json([
                'success' => true,
                'message' => 'Pencapaian selesai!',
                'data' => $pencapaian
            ]);
        }

        return response()->json(['success' => true, 'data' => $pencapaian]);
    }

    // DELETE /api/pencapaian/{id}
    public function destroy($id)
    {
        $pencapaian = Pencapaian::find($id);
        if (!$pencapaian) {
            return response()->json(['success' => false, 'message' => 'Pencapaian tidak ditemukan'], 404);
        }
        $pencapaian->delete();
        return response()->json(['success' => true, 'message' => 'Pencapaian berhasil dihapus']);
    }
}
