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
        'successes',
        'challenges',
        'next_steps',
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

    public function documents()
    {
        return $this->hasMany(EventDocument::class);
    }

    public function deliverables()
    {
        return $this->hasMany(Deliverable::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'event_tag')->withTimestamps();
    }
}
