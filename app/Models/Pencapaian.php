<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pencapaian extends Model
{
    use HasFactory;

    protected $table = 'pencapaian';

    protected $fillable = [
        'user_id',
        'nama',
        'waktu_pencapaian',
        'target',
        'jumlah',
        'kategori', // jika ada kolom kategori
    ];

    // Relasi ke akun
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'user_id');
    }
}
