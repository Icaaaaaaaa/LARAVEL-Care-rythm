<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teman extends Model
{
    use HasFactory;

    protected $table = 'teman';

    protected $fillable = [
        'user_id',
        'teman_id',
    ];
}
