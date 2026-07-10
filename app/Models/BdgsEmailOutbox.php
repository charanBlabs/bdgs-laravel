<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BdgsEmailOutbox extends Model
{
    protected $table = 'bdgs_email_outbox';

    protected $fillable = [
        'to_email',
        'to_name',
        'from_email',
        'from_name',
        'subject',
        'body_html',
        'body_text',
        'template_slug',
        'variables',
        'status',
        'priority',
        'max_attempts',
        'attempts',
        'last_error',
        'schedule_id',
        'automation_rule_id',
        'send_after',
        'sent_at',
        'failed_at',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'send_after' => 'datetime',
            'sent_at' => 'datetime',
            'failed_at' => 'datetime',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(BdgsEmailSchedule::class, 'schedule_id');
    }

    public function automationRule(): BelongsTo
    {
        return $this->belongsTo(BdgsEmailAutomationRule::class, 'automation_rule_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending')
            ->where(function ($q) {
                $q->whereNull('send_after')
                    ->orWhere('send_after', '<=', now());
            });
    }

    public function scopeByPriority($query)
    {
        return $query->orderByRaw("FIELD(priority, 'high', 'normal', 'low')")
            ->orderBy('created_at');
    }

    public function markSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markFailed(string $error): void
    {
        $this->increment('attempts');
        $data = ['last_error' => $error];

        if ($this->attempts >= $this->max_attempts) {
            $data['status'] = 'failed';
            $data['failed_at'] = now();
        }

        $this->update($data);
    }
}
