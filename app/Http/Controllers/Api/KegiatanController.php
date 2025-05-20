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
    $validated = $request->validate([
        'kegiatan' => 'required|string|max:255',
        'catatan' => 'nullable|string',
        'tanggal' => 'required|date',
        'waktu_mulai' => 'required|date_format:H:i',
        'waktu_selesai' => 'required|date_format:H:i',
        'tempat' => 'nullable|string|max:255',
    ]);

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
        ]);

        $kegiatan->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Kegiatan berhasil diupdate.',
            'data' => $kegiatan
        ]);
    }

    public function destroy($id)
    {
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