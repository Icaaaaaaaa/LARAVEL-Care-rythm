<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Jadwal;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        // Ambil user id dari Auth, jika tidak ada fallback ke session
        $userId = auth()->id();
        if (!$userId) {
            $user = session('user');
            $userId = is_array($user) ? ($user['id'] ?? null) : (is_object($user) ? ($user->id ?? null) : session('user_id'));
        }

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $query = Jadwal::query()->where('user_id', $userId);

        // Perbaiki filter hari agar sesuai dengan format penyimpanan (jika multi hari, gunakan FIND_IN_SET)
        if ($request->has('hari') && $request->hari) {
            $query->where(function($q) use ($request) {
                $q->whereRaw('FIND_IN_SET(?, hari)', [$request->hari]);
            });
        }

        $jadwals = $query->get();

        // Tidak perlu map jika field sudah sesuai, langsung kirim ke view
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
        // Coba dapatkan user id dari Auth, jika tidak ada fallback ke session
        $userId = auth()->id();
        if (!$userId) {
            $user = session('user');
            $userId = is_array($user) ? ($user['id'] ?? null) : (is_object($user) ? ($user->id ?? null) : session('user_id'));
        }

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

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

        // Coba dapatkan user id dari Auth, jika tidak ada fallback ke session
        $user_id = auth()->id();
        if (!$user_id) {
            $user = session('user');
            $user_id = is_array($user) ? ($user['id'] ?? null) : (is_object($user) ? ($user->id ?? null) : session('user_id'));
        }

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
            'hari' => 'required|array|min:1',
            'hari.*' => 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'catatan' => 'nullable|string',
        ]);

        $user_id = auth()->id();

        if (is_null($user_id)) {
            return redirect()->back()->with('error', 'User belum login');
        }

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update([
            'nama_jadwal' => $request->nama_jadwal,
            'kategori' => $request->kategori,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'hari' => implode(',', $request->hari), // simpan sebagai string dipisah koma
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
