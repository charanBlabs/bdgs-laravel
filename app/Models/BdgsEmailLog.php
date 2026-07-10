<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BdgsEmailLog extends Model
{
    public $timestamps = false;

    protected $table = 'bdgs_email_logs';

    protected $fillable = [
        'user_id',
        'template_slug',
        'to_email',
        'to_name',
        'from_email',
        'from_name',
        'subject',
        'body_html',
        'body_text',
        'status',
        'channel',
        'message_id',
        'error_message',
        'attempts',
        'sent_at',
        'opened_at',
        'clicked_at',
        'bounced_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'opened_at' => 'datetime',
            'clicked_at' => 'datetime',
            'bounced_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(BdgsEmailTemplate::class, 'template_slug', 'slug');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
