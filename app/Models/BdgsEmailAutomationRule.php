<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BdgsEmailAutomationRule extends Model
{
    protected $table = 'bdgs_email_automation_rules';

    protected $fillable = [
        'name',
        'trigger_event',
        'template_slug',
        'delay_days',
        'delay_hours',
        'send_to_user',
        'send_to_admin',
        'copy_admin',
        'conditions',
        'is_active',
        'sort_order',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'send_to_user' => 'boolean',
            'send_to_admin' => 'boolean',
            'copy_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BdgsEmailAutomationLog::class, 'rule_id');
    }

    public function template()
    {
        return $this->belongsTo(BdgsEmailTemplate::class, 'template_slug', 'slug');
    }

    public function hasDelay(): bool
    {
        return $this->delay_days > 0 || $this->delay_hours > 0;
    }

    public function delayInMinutes(): int
    {
        return ($this->delay_days * 1440) + ($this->delay_hours * 60);
    }
}
