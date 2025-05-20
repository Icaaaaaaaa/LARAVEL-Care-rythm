<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    public $timestamps = false;
    protected $fillable = [
        'kegiatan',
        'catatan',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'tempat'
    ];
}