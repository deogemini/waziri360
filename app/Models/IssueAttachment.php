<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueAttachment extends Model
{
    protected $fillable = [
        'issue_id',
        'type',
        'path',
    ];

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }
}
