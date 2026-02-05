<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'purpose',
        'requested_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'requested_date' => 'datetime',
    ];
}
