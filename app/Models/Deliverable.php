<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deliverable extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'description',
        'theme',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
