<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BdgsEmailSchedule extends Model
{
    protected $table = 'bdgs_email_schedules';

    protected $fillable = [
        'name',
        'template_slug',
        'subject',
        'body_html',
        'from_email',
        'from_name',
        'list_ids',
        'exclude_list_ids',
        'status',
        'total_recipients',
        'total_sent',
        'total_failed',
        'total_opened',
        'total_clicked',
        'repeat_interval',
        'scheduled_at',
        'started_at',
        'completed_at',
        'next_run_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'list_ids' => 'array',
            'exclude_list_ids' => 'array',
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'next_run_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function outboxItems(): HasMany
    {
        return $this->hasMany(BdgsEmailOutbox::class, 'schedule_id');
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isRunning(): bool
    {
        return $this->status === 'sending';
    }
}
