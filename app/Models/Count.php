<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Count extends Model
{
    use HasFactory;
    protected $fillable = ['userId', 'musicId'];
}
