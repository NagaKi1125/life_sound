<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Album extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'authorId'];
}