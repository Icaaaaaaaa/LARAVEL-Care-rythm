<?php
namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::all();
        return view('kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('kegiatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kegiatan' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'tempat' => 'nullable|string|max:255',
        ]);

        // Pastikan user_id selalu diisi
        $validated['user_id'] = auth()->id() ?? session('user_id') ?? 1;

        // Cek tabrakan waktu pada tanggal yang sama
        $bentrok = \App\Models\Kegiatan::where('user_id', $validated['user_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where(function($q) use ($validated) {
                $q->where(function($q2) use ($validated) {
                    $q2->where('waktu_mulai', '<', $validated['waktu_selesai'])
                       ->where('waktu_selesai', '>', $validated['waktu_mulai']);
                });
            })
            ->exists();

        if ($bentrok) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['waktu_mulai' => 'Waktu kegiatan bertabrakan dengan jadwal lain pada tanggal yang sama.']);
        }

        Kegiatan::create($validated);
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'kegiatan' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'tempat' => 'nullable|string|max:255',
        ]);

        $kegiatan->update($validated);
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}

