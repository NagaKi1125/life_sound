<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Music extends Model
{
    use HasFactory;
    protected $fillable = ['url', 'name', 'category', 'authors', 'thumbnail', 'preview_url', 'spotify_id', 'duration', 'year'];
}