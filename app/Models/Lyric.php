<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Lyric extends Model
{
    use HasFactory;
    protected $fillable = ['musicId', 'name', 'lyric'];
}