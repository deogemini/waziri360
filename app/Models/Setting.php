<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'audit_enabled',
        'retention_days',
    ];

    protected $casts = [
        'audit_enabled' => 'boolean',
        'retention_days' => 'integer',
    ];
}
