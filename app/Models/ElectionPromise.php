<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionPromise extends Model
{
    protected $fillable = [
        'title',
        'description',
        'district',
        'ward',
        'village',
        'status',
        'implementation_notes',
        'start_date',
        'end_date',
        'challenges',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
