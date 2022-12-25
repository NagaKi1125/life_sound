<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'thumbnail', 'popularity', 'spotify_id'];
}