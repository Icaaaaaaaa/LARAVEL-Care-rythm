<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'kegiatan',
        'catatan',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
    ];

    public function akun()
    {
        return $this->belongsTo(\App\Models\Akun::class, 'user_id', 'id');
    }
}