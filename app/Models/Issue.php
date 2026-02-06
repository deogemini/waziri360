<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'deputy_id',
        'due_date',
        'remarks',
        'escalated',
        'escalated_at',
        'escalation_reason',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'escalated' => 'boolean',
        'escalated_at' => 'datetime',
    ];

    public function deputy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deputy_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(IssueAttachment::class);
    }
}
