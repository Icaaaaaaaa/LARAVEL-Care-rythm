<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalController extends Controller
{
    // Menampilkan semua jadwal
    public function index(Request $request)
    {
        $jadwals = session()->get('jadwals', []);
        
        // Filter berdasarkan hari jika ada parameter
        if ($request->has('hari')) {
            $jadwals = array_filter($jadwals, function($jadwal) use ($request) {
                return in_array($request->hari, explode(', ', $jadwal['hari']));
            });
        }
        
        return view('jadwal', compact('jadwals'));
    }

    // Menampilkan form tambah jadwal
    public function create()
    {
        return view('tambahjadwal');
    }

    // Menyimpan jadwal baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'catatan' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:100',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'hari' => 'required|array',
        ]);

        $jadwals = session()->get('jadwals', []);

        $newJadwal = [
            'nama' => $request->nama,
            'catatan' => $request->catatan,
            'kategori' => $request->kategori,
            'waktu' => $request->waktu_mulai . ' - ' . $request->waktu_selesai,
            'hari' => implode(', ', $request->hari)
        ];

        array_push($jadwals, $newJadwal);
        session()->put('jadwals', $jadwals);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $jadwals = session()->get('jadwals', []);
        
        if (!isset($jadwals[$id])) {
            return redirect()->route('jadwal.index')->with('error', 'Jadwal tidak ditemukan.');
        }

        $jadwal = $jadwals[$id];
        return view('editjadwal', compact('jadwal', 'id'));
    }

    // Update jadwal
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'catatan' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:100',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'hari' => 'required|array',
        ]);

        $jadwals = session()->get('jadwals', []);
        
        $jadwals[$id] = [
            'nama' => $request->nama,
            'catatan' => $request->catatan,
            'kategori' => $request->kategori,
            'waktu' => $request->waktu_mulai . ' - ' . $request->waktu_selesai,
            'hari' => implode(', ', $request->hari)
        ];
        
        session()->put('jadwals', $jadwals);
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    // Hapus jadwal
    public function destroy($id)
    {
        $jadwals = session()->get('jadwals', []);
        
        if (!isset($jadwals[$id])) {
            return redirect()->route('jadwal.index')->with('error', 'Jadwal tidak ditemukan.');
        }

        unset($jadwals[$id]);
        session()->put('jadwals', array_values($jadwals)); // Re-index array

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
