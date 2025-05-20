<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    protected $fillable = [
        'user_id',
        'nama_jadwal',
        'kategori',
        'waktu_mulai',
        'waktu_selesai',
        'hari',
        'catatan',
    ];

    public $timestamps = false;
}
