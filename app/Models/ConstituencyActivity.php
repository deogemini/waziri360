<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstituencyActivity extends Model
{
    protected $fillable = [
        'name',
        'activity_type',
        'date',
        'district',
        'ward',
        'village',
        'description',
        'key_participants',
        'outcomes',
        'attachment_path',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
