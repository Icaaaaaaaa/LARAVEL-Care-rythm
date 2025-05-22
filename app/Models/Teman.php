<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teman extends Model
{
    use HasFactory;

    // Jika nama tabel berbeda dari konvensi Laravel (misal bukan "temans"), tambahkan:
    // protected $table = 'nama_tabel';

    // Tambahkan properti fillable untuk mass assignment
    protected $table = 'teman';

    protected $fillable = [
        'name',
        'last_active',
        'photo',
    ];
}