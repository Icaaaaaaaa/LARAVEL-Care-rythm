<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pencapaian;

class PencapaianController extends Controller
{
    public function index()
    {
        $pencapaians = Pencapaian::where('user_id', auth()->id() ?? 1)->get();
        return view('pencapaian.index', compact('pencapaians'));
    }

    public function tambah(Request $request)
    {
        $id = $request->input('id');
        $pencapaian = Pencapaian::find($id);
        if ($pencapaian) {
            $pencapaian->jumlah += 1;
            $pencapaian->save();
        }
        return redirect()->route('pencapaian.index')->with('success', 'Jumlah berhasil ditambah.');
    }

    public function reset()
    {
        // Hapus semua pencapaian milik user (atau semua jika belum ada auth)
        Pencapaian::where('user_id', auth()->id() ?? 1)->delete();
        return redirect()->route('pencapaian.index')->with('success', 'Semua pencapaian berhasil direset.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'target' => 'required|integer',
            'jumlah' => 'required|integer',
            'kategori' => 'nullable|string|max:255',
        ]);

        $user_id = auth()->id() ?? 1;

        Pencapaian::create([
            'user_id' => $user_id,
            'nama' => $request->nama,
            'waktu_pencapaian' => now(),
            'target' => $request->target,
            'jumlah' => $request->jumlah,
            'kategori' => $request->kategori,
        ]);

        return redirect()->route('pencapaian.index')->with('success', 'Pencapaian berhasil ditambahkan!');
    }

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

    public function formTambahCounter($index){
        $data = $this->getData();

        if (!isset($data[$index])) {
            return redirect('/pencapaian')->with('error', 'Kegiatan tidak ditemukan.');
        }

        return view('pencapaian.tambah-counter', [
            'index' => $index,
            'kegiatan' => $data[$index],
        ]);
    }

    public function simpanTambahCounter(Request $request, $index){
        $request->validate([
            'keterangan' => 'required|string',
        ]);

        $data = $this->getData();
        $catatan = session('catatan', []);

        if (!isset($data[$index])) {
            return redirect('/pencapaian')->with('error', 'Kegiatan tidak ditemukan.');
        }

        $data[$index]['counter']++;

        $catatan[] = [
            'nama' => $data[$index]['nama'],
            'kategori' => $data[$index]['kategori'],
            'waktu' => now()->format('Y-m-d H:i'),
            'keterangan' => $request->input('keterangan'),
        ];

        session([
            'pencapaian' => $data,
            'catatan' => $catatan,
        ]);

        return redirect('/pencapaian')->with('success', 'Kegiatan diperbarui dan keterangan disimpan!');
    }

    public function hapus(Request $request){
        $index = $request->input('index');
        $data = $this->getData();

        if (isset($data[$index])) {
            unset($data[$index]);
            $data = array_values($data);
            $this->saveData($data);
        }

        return redirect('/pencapaian')->with('success', 'Kegiatan berhasil dihapus.');
    }
}

