<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pencapaian;
use App\Models\Akun;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
        try {
            // Log request data untuk debugging
            Log::info('Request data:', $request->all());
            Log::info('Headers:', $request->headers->all());

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

            // Validasi dengan handling error yang lebih baik
            try {
                $validated = $request->validate([
                    'nama' => 'required|string|max:255',
                    'waktu_pencapaian' => 'required|date', // Ubah dari date_format ke date
                    'target' => 'required|integer|min:1|max:1000000', // Tambah max limit
                    'jumlah' => 'required|integer|min:0|max:1000000',
                    'kategori' => 'nullable|string|max:255',
                ]);
            } catch (ValidationException $e) {
                Log::error('Validation failed:', $e->errors());
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }

            if ($validated['jumlah'] > $validated['target']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah tidak boleh melebihi target. Data tidak bisa ditambah.'
                ], 422);
            }

            $validated['user_id'] = $user->id;
            
            // Convert date format untuk database datetime
            $validated['waktu_pencapaian'] = date('Y-m-d H:i:s', strtotime($validated['waktu_pencapaian']));

            Log::info('Data yang akan disimpan:', $validated);

            $pencapaian = Pencapaian::create($validated);

            Log::info('Pencapaian berhasil dibuat:', $pencapaian->toArray());

            return response()->json([
                'success' => true, 
                'message' => 'Pencapaian berhasil ditambahkan',
                'data' => $pencapaian
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error saat menyimpan pencapaian:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT /api/pencapaian/{id}
    public function update(Request $request, $id)
    {
        $pencapaian = Pencapaian::find($id);
        if (!$pencapaian) {
            return response()->json(['success' => false, 'message' => 'Pencapaian tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'waktu_pencapaian' => 'required|date',
            'target' => 'required|integer|min:1|max:1000000',
            'jumlah' => 'required|integer|min:0|max:1000000',
            'kategori' => 'nullable|string|max:255',
        ]);

        if ($validated['jumlah'] > $validated['target']) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah tidak boleh melebihi target. Data tidak bisa diupdate.'
            ], 422);
        }

        // Convert date format untuk database datetime
        $validated['waktu_pencapaian'] = date('Y-m-d H:i:s', strtotime($validated['waktu_pencapaian']));

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