<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pencapaian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PencapaianController extends Controller
{
    // Tampilkan halaman index pencapaian
    public function index()
    {
        // Ambil user id dari Auth, jika tidak ada fallback ke session
        $user_id = Auth::check() ? Auth::user()->id : null;
        if (!$user_id) {
            $user = session('user');
            $user_id = is_array($user) ? ($user['id'] ?? null) : (is_object($user) ? ($user->id ?? null) : session('user_id'));
        }
        if (!$user_id) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $pencapaians = Pencapaian::where('user_id', $user_id)->get();
        return view('pencapaian.index', compact('pencapaians'));
    }

    // Tambah jumlah pencapaian (tombol +)
    public function tambah(Request $request)
    {
        $user_id = Auth::check() ? Auth::user()->id : null;
        if (!$user_id) {
            $user = session('user');
            $user_id = is_array($user) ? ($user['id'] ?? null) : (is_object($user) ? ($user->id ?? null) : session('user_id'));
        }
        if (!$user_id) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        // Cek apakah user_id ada di tabel akun
        $userExists = DB::table('akun')->where('id', $user_id)->exists();
        if (!$userExists) {
            // Simpan pesan error ke session dan redirect kembali
            return redirect()->back()->with('error', 'User tidak ditemukan di tabel akun.');
        }
        $id = $request->input('id');
        if ($id) {
            $pencapaian = Pencapaian::where('id', $id)
                ->where('user_id', $user_id)
                ->first();
            if ($pencapaian) {
                $pencapaian->jumlah += 1;
                $pencapaian->waktu_pencapaian = now();
                $pencapaian->save();
                return redirect()->back()->with('success', 'Jumlah pencapaian berhasil ditambah!');
            }
            return redirect()->back()->with('error', 'Pencapaian tidak ditemukan.');
        }
        return redirect()->back()->with('error', 'ID pencapaian tidak ditemukan.');
    }

    // Reset semua pencapaian user
    public function reset()
    {
        $user_id = Auth::check() ? Auth::user()->id : null;
        if (!$user_id) {
            $user = session('user');
            $user_id = is_array($user) ? ($user['id'] ?? null) : (is_object($user) ? ($user->id ?? null) : session('user_id'));
        }
        if (!$user_id) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $userExists = DB::table('akun')->where('id', $user_id)->exists();
        if (!$userExists) {
            return redirect()->back()->with('error', 'User tidak ditemukan di tabel akun.');
        }
        Pencapaian::where('user_id', $user_id)->delete();
        // Simpan pesan sukses ke session
        return redirect()->route('pencapaian.index')->with('success', 'Semua pencapaian berhasil direset.');
    }

    // Tambah pencapaian baru dari form
    public function store(Request $request)
    {
        $user_id = Auth::check() ? Auth::user()->id : null;
        if (!$user_id) {
            $user = session('user');
            $user_id = is_array($user) ? ($user['id'] ?? null) : (is_object($user) ? ($user->id ?? null) : session('user_id'));
        }
        if (!$user_id) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $request->validate([
            'nama' => 'required|string|max:255',
            'target' => 'required|integer',
            'kategori' => 'required|in:pelajaran,istirahat,olahraga,hiburan',
        ]);
        $userExists = DB::table('akun')->where('id', $user_id)->exists();
        if (!$userExists) {
            return redirect()->back()->with('error', 'User tidak ditemukan di tabel akun.');
        }

        // Cek jika sudah ada pencapaian dengan nama & kategori yang sama
        $existing = Pencapaian::where('user_id', $user_id)
            ->where('nama', $request->nama)
            ->where('kategori', $request->kategori)
            ->first();

        if ($existing) {
            // Simpan pesan error ke session
            return redirect()->back()->with('error', 'Pencapaian dengan nama dan kategori ini sudah ada.');
        }

        Pencapaian::create([
            'user_id' => $user_id,
            'nama' => $request->nama,
            'waktu_pencapaian' => now(),
            'target' => $request->target,
            'jumlah' => 0,
            'kategori' => $request->kategori,
        ]);

        // Simpan pesan sukses ke session
        return redirect()->route('pencapaian.index')->with('success', 'Pencapaian berhasil ditambahkan!');
    }
}
