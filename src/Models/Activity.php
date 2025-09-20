<?php

namespace Heccubernny\ActivityTracker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $table = 'activities';

    protected $fillable = [
        'user_id',
        'user_type',
        'subject_id',
        'subject_type',
        'name',
        'description',
        'properties',
        'ip_address',
        'agent',
        'url',
        'method'
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo('subject');
    }
}
