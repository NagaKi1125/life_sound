<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;
    protected $fillable = ['userId', 'content'];
}