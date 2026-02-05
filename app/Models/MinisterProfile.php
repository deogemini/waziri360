<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinisterProfile extends Model
{
    protected $fillable = [
        'name',
        'title',
        'photo_path',
        'bio',
    ];
}
