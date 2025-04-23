<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PencapaianController extends Controller
{
    private function getData()
    {
        return session('pencapaian', [
            ['nama' => 'Olahraga', 'counter' => 0, 'kategori' => 'Kesehatan'],
            ['nama' => 'Membaca', 'counter' => 0, 'kategori' => 'Pendidikan'],
        ]);
    }

    private function saveData($data)
    {
        session(['pencapaian' => $data]);
    }

    public function index()
    {
        $data = $this->getData();
        return view('pencapaian.index', compact('data'));
    }

    public function tambah(Request $request)
    {
        $data = $this->getData();
        $index = $request->input('index');
        $data[$index]['counter']++;
        $this->saveData($data);
        return redirect('/pencapaian');
    }

    public function kurang(Request $request)
    {
        $data = $this->getData();
        $index = $request->input('index');
        if ($data[$index]['counter'] > 0) {
            $data[$index]['counter']--;
        }
        $this->saveData($data);
        return redirect('/pencapaian');
    }

    public function reset()
    {
        session()->forget('pencapaian');
        return redirect('/pencapaian');
    }

    public function tambahKegiatan(Request $request){
        $request->validate([
            'nama' => 'required|string',
            'kategori' => 'required|string',
        ]);

        $data = $this->getData();

        $data[] = [
            'nama' => $request->input('nama'),
            'counter' => 0,
            'kategori' => $request->input('kategori'),
        ];

        $this->saveData($data);

        return redirect('/pencapaian')->with('success', 'Kegiatan baru berhasil ditambahkan!');
    }

    public function hapus(Request $request){
        $index = $request->input('index');
        $data = $this->getData();

        if (isset($data[$index])) {
            unset($data[$index]);
            $data = array_values($data); // Re-index array setelah unset
            $this->saveData($data);
        }

        return redirect('/pencapaian')->with('success', 'Kegiatan berhasil dihapus.');
    }
}

