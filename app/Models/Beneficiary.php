<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    protected $fillable = [
        'full_name',
        'nida_number',
        'gender',
        'district',
        'ward',
        'village',
        'group_name',
        'support_type',
        'benefited_at',
        'notes',
    ];

    protected $casts = [
        'benefited_at' => 'date',
    ];
}
