<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;

    protected $table = 'akun';

    protected $fillable = [
        'username',
        'kataSandi',
        'email',
        'role',
        'api_token',
    ];

    public $timestamps = false;

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'user_id', 'id');
    }
}
