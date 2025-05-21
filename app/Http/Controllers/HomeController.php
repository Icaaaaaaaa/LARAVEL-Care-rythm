<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;

class HomeController extends Controller
{
    public function index()
    {
        // Jika ingin semua jadwal:
        // $jadwals = Jadwal::all();

        // Jika ingin jadwal user yang login:
        // $jadwals = Jadwal::where('user_id', auth()->id())->get();

        $jadwals = Jadwal::all(); // atau filter sesuai kebutuhan
        return view('welcome', compact('jadwals'));
    }
}
