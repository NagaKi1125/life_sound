<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Music extends Model
{
    use HasFactory;
    protected $fillable = ['url', 'name', 'category', 'author', 'thumbnail'];
}
