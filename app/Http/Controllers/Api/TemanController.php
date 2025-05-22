<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemanController extends Controller
{
    // Ambil semua data teman
    public function index()
    {
        $temans = Teman::all();
        return response()->json($temans);
    }

    // Simpan data teman baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'last_active' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $teman = Teman::create($data);

        return response()->json([
            'message' => 'Data teman berhasil disimpan',
            'data' => $teman
        ], 201);
    }

    // Tampilkan satu data teman
    public function show(Teman $teman)
    {
        return response()->json($teman);
    }

    // Update data teman
    public function update(Request $request, Teman $teman)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'last_active' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Hapus foto lama jika ada
        if ($request->hasFile('photo')) {
            if ($teman->photo) {
                Storage::disk('public')->delete($teman->photo);
            }

            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $teman->update($data);

        return response()->json([
            'message' => 'Data teman berhasil diupdate',
            'data' => $teman
        ]);
    }

    // Hapus data teman
    public function destroy(Teman $teman)
    {
        if ($teman->photo) {
            Storage::disk('public')->delete($teman->photo);
        }

        $teman->delete();

        return response()->json([
            'message' => 'Data teman berhasil dihapus'
        ]);
    }
}