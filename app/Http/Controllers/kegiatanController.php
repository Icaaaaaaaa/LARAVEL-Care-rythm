<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data kegiatan dari session
        $kegiatan = session()->get('kegiatan', []);

        // Filter berdasarkan hari jika parameter 'hari' ada dalam request
        if ($request->has('hari')) {
            $kegiatan = array_filter($kegiatan, function ($item) use ($request) {
                // Memeriksa apakah hari yang dipilih ada di dalam array hari kegiatan
                return in_array($request->hari, explode(', ', $item['hari']));
            });
        }

        return view('kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('kegiatan.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:100',
            'waktu' => 'required|date_format:H:i',
            'hari' => 'required|array',
        ]);

        // Ambil data kegiatan dari session
        $kegiatan = session()->get('kegiatan', []);

        // Menambahkan kegiatan baru ke dalam sesi
        $newKegiatan = [
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'waktu' => $request->waktu,
            'hari' => implode(', ', $request->hari),  // Mengubah array menjadi string
        ];

        $kegiatan[] = $newKegiatan;

        // Simpan kembali ke session
        session()->put('kegiatan', $kegiatan);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Ambil data kegiatan dari session
        $kegiatan = session()->get('kegiatan', []);

        // Pastikan kegiatan dengan id yang diberikan ada
        if (!isset($kegiatan[$id])) {
            return redirect()->route('kegiatan.index')->with('error', 'Kegiatan tidak ditemukan.');
        }

        return view('kegiatan.edit', compact('kegiatan', 'id'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
            'kategori' => 'required|string|max:100',
            'waktu' => 'required|date_format:H:i',
            'hari' => 'required|array',
        ]);

        // Ambil data kegiatan dari session
        $kegiatan = session()->get('kegiatan', []);

        // Pastikan kegiatan dengan id yang diberikan ada
        if (!isset($kegiatan[$id])) {
            return redirect()->route('kegiatan.index')->with('error', 'Kegiatan tidak ditemukan.');
        }

        // Update data kegiatan yang ada
        $kegiatan[$id] = [
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'waktu' => $request->waktu,
            'hari' => implode(', ', $request->hari),  // Mengubah array menjadi string
        ];

        // Simpan kembali ke session
        session()->put('kegiatan', $kegiatan);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diupdate!');
    }

    public function destroy($id)
    {
        // Ambil data kegiatan dari session
        $kegiatan = session()->get('kegiatan', []);

        // Pastikan kegiatan dengan id yang diberikan ada
        if (!isset($kegiatan[$id])) {
            return redirect()->route('kegiatan.index')->with('error', 'Kegiatan tidak ditemukan.');
        }

        // Hapus kegiatan berdasarkan id
        unset($kegiatan[$id]);

        // Reindex array setelah penghapusan
        session()->put('kegiatan', array_values($kegiatan));

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus!');
    }
}
