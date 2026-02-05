<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'category_id',
        'location',
        'is_recurring',
        'recurrence_pattern',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_recurring' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->withPivot('status')
            ->withTimestamps();
    }
}
