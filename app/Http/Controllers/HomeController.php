<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Pencapaian;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil user id dari auth, jika tidak ada fallback ke session
        $user_id = auth()->id();
        if (!$user_id) {
            $user = session('user');
            $user_id = is_array($user) ? ($user['id'] ?? null) : (is_object($user) ? ($user->id ?? null) : session('user_id'));
        }

        $jadwals = Jadwal::where('user_id', $user_id)->get();

        $pencapaians = Pencapaian::where('user_id', $user_id)
            ->select('kategori', \DB::raw('SUM(jumlah) as jumlah'))
            ->groupBy('kategori')
            ->get()
            ->map(function($item) {
                return [
                    'kategori' => $item->kategori,
                    'jumlah' => $item->jumlah
                ];
            });

        return view('welcome', [
            'jadwals' => $jadwals,
            'pencapaians' => $pencapaians,
        ]);
    }
}
