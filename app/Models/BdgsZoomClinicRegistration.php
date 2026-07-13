<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BdgsZoomClinicRegistration extends Model
{
    protected $table = 'bdgs_zoom_clinic_registrations';

    protected $primaryKey = 'registration_id';

    public $incrementing = true;

    protected $fillable = [
        'clinic_id',
        'name',
        'email',
        'directory_url',
        'help_topic',
        'registrant_timezone',
        'status',
        'registered_at',
        'confirmation_sent_at',
        'reminder_24h_sent_at',
        'reminder_1h_sent_at',
        'calendar_uid',
        'calendar_added_at',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'confirmation_sent_at' => 'datetime',
            'reminder_24h_sent_at' => 'datetime',
            'reminder_1h_sent_at' => 'datetime',
            'calendar_added_at' => 'datetime',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(BdgsZoomClinic::class, 'clinic_id', 'clinic_id');
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim($this->name)) ?: [];
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        return $initials ?: '?';
    }
}
