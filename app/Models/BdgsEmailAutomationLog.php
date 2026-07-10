<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BdgsEmailAutomationLog extends Model
{
    public $timestamps = false;

    protected $table = 'bdgs_email_automation_log';

    protected $fillable = [
        'rule_id',
        'user_id',
        'email',
        'trigger_event',
        'status',
        'response',
        'email_log_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(BdgsEmailAutomationRule::class, 'rule_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function emailLog(): BelongsTo
    {
        return $this->belongsTo(BdgsEmailLog::class, 'email_log_id');
    }
}
