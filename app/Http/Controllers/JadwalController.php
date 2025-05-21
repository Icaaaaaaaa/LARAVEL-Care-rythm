<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Jadwal;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = Jadwal::all()->map(function($item) {
            return [
                'id' => $item->id,
                'nama_jadwal' => $item->nama_jadwal,
                'kategori' => $item->kategori,
                'waktu_mulai' => $item->waktu_mulai,
                'waktu_selesai' => $item->waktu_selesai,
                'hari' => $item->hari,
                'catatan' => $item->catatan,
                'jam' => $item->jam,
                'user_id' => $item->user_id,
            ];
        });
        return view('Jadwal', compact('jadwals'));
    }

    public function show($id)
    {
        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }
        return response()->json($jadwal);
    }

    public function create()
    {
        return view('tambahjadwal');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jadwal' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'hari' => 'required|array|min:1',
            'hari.*' => 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'catatan' => 'nullable|string',
        ]);

        $user = session('user');
        $user_id = is_array($user) ? ($user['id'] ?? null) : (is_object($user) ? ($user->id ?? null) : null);

        if (is_null($user_id)) {
            return redirect()->back()->with('error', 'User belum login');
        }

        Jadwal::create([
            'nama_jadwal' => $request->nama_jadwal,
            'kategori' => $request->kategori,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'hari' => implode(',', $request->hari), // simpan sebagai string dipisah koma
            'catatan' => $request->catatan,
            'user_id' => $user_id,
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        return view('editjadwal', [
            'jadwal' => $jadwal,
            'id' => $id
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jadwal' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'catatan' => 'nullable|string',
        ]);

        $user = session('user');
        $user_id = is_array($user) ? ($user['id'] ?? null) : (is_object($user) ? ($user->id ?? null) : null);

        if (is_null($user_id)) {
            return redirect()->back()->with('error', 'User belum login');
        }

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update([
            'nama_jadwal' => $request->nama_jadwal,
            'kategori' => $request->kategori,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'hari' => $request->hari,
            'catatan' => $request->catatan,
            'user_id' => $user_id,
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    public function destroy($id)
    {
        $deleted = Jadwal::destroy($id);
        if (!$deleted) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
