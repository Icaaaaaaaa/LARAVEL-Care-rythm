<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    // Menentukan nama tabel yang digunakan
    protected $table = 'jadwal';

    // Kolom-kolom yang boleh diisi secara mass assignment
    protected $fillable = [
        'user_id',       
        'nama_jadwal',   
        'kategori',     
        'waktu_mulai',  
        'waktu_selesai', 
        'hari',         
        'catatan',       
    ];

    // Menonaktifkan timestamps (created_at dan updated_at)
    public $timestamps = false;
}
