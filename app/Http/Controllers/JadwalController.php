<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = session()->get('jadwals', []);

        if ($request->has('hari')) {
            $jadwals = array_filter($jadwals, function ($jadwal) use ($request) {
                return in_array($request->hari, explode(', ', $jadwal['hari']));
            });
        }

        return view('jadwal', compact('jadwals'));
    }

    public function create()
    {
        return view('tambahjadwal');
    }

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
        $hariBaru = $request->hari;
        $mulaiBaru = $request->waktu_mulai;
        $selesaiBaru = $request->waktu_selesai;

        foreach ($jadwals as $jadwal) {
            $hariLama = explode(', ', $jadwal['hari']);
            $waktu = explode(' - ', $jadwal['waktu']);
            $mulaiLama = $waktu[0];
            $selesaiLama = $waktu[1];

            foreach ($hariBaru as $hari) {
                if (in_array($hari, $hariLama)) {
                    if (
                        ($mulaiBaru < $selesaiLama) &&
                        ($selesaiBaru > $mulaiLama)
                    ) {
                        return back()->withInput()->with('error', 'Jadwal tabrakan dengan jadwal yang sudah ada.');
                    }
                }
            }
        }

        $newJadwal = [
            'nama' => $request->nama,
            'catatan' => $request->catatan,
            'kategori' => $request->kategori,
            'waktu' => $request->waktu_mulai . ' - ' . $request->waktu_selesai,
            'hari' => implode(', ', $request->hari)
        ];

        $jadwals[] = $newJadwal;
        session()->put('jadwals', $jadwals);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jadwals = session()->get('jadwals', []);
        if (!isset($jadwals[$id])) {
            return redirect()->route('jadwal.index')->with('error', 'Jadwal tidak ditemukan.');
        }

        $jadwal = $jadwals[$id];
        return view('editjadwal', compact('jadwal', 'id'));
    }

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
        $hariBaru = $request->hari;
        $mulaiBaru = $request->waktu_mulai;
        $selesaiBaru = $request->waktu_selesai;

        foreach ($jadwals as $key => $jadwal) {
            if ($key == $id) continue;

            $hariLama = explode(', ', $jadwal['hari']);
            $waktu = explode(' - ', $jadwal['waktu']);
            $mulaiLama = $waktu[0];
            $selesaiLama = $waktu[1];

            foreach ($hariBaru as $hari) {
                if (in_array($hari, $hariLama)) {
                    if (
                        ($mulaiBaru < $selesaiLama) &&
                        ($selesaiBaru > $mulaiLama)
                    ) {
                        return back()->withInput()->with('error', 'Jadwal tabrakan dengan jadwal yang sudah ada.');
                    }
                }
            }
        }

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

    public function destroy($id)
    {
        $jadwals = session()->get('jadwals', []);
        if (!isset($jadwals[$id])) {
            return redirect()->route('jadwal.index')->with('error', 'Jadwal tidak ditemukan.');
        }

        unset($jadwals[$id]);
        session()->put('jadwals', array_values($jadwals)); // Reindex array
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
