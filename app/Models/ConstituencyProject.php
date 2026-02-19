<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConstituencyProject extends Model
{
    protected $fillable = [
        'name',
        'project_type',
        'district',
        'ward',
        'village',
        'funding_source',
        'budget',
        'amount_spent',
        'status',
        'start_date',
        'end_date',
        'contractor',
        'notes',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'amount_spent' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
