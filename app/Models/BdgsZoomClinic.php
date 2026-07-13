<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BdgsZoomClinic extends Model
{
    protected $table = 'bdgs_zoom_clinics';

    protected $primaryKey = 'clinic_id';

    public $incrementing = true;

    protected $fillable = [
        'slug',
        'title',
        'agenda',
        'description',
        'registration_hooks',
        'session_starts_at',
        'session_ends_at',
        'buffer_ends_at',
        'source_timezone',
        'format_note',
        'zoom_meeting_url',
        'access_type',
        'max_capacity',
        'status',
        'is_published',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'session_starts_at' => 'datetime',
            'session_ends_at' => 'datetime',
            'buffer_ends_at' => 'datetime',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'max_capacity' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(BdgsZoomClinicRegistration::class, 'clinic_id', 'clinic_id');
    }

    public function confirmedRegistrations(): HasMany
    {
        return $this->registrations()->where('status', 'confirmed');
    }

    /** @param Builder<BdgsZoomClinic> $query */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', 1);
    }

    /** @param Builder<BdgsZoomClinic> $query */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query
            ->where('status', 'scheduled')
            ->where('session_starts_at', '>=', now());
    }

    /** @param Builder<BdgsZoomClinic> $query */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('session_starts_at')->orderBy('sort_order');
    }

    /** @param Builder<BdgsZoomClinic> $query */
    public function scopeCurrentlyLive(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where(function (Builder $query) use ($now) {
                $query->where('status', 'live')
                    ->orWhere(function (Builder $query) use ($now) {
                        $query->whereIn('status', ['scheduled', 'live'])
                            ->where('session_starts_at', '<=', $now)
                            ->whereRaw('COALESCE(buffer_ends_at, session_ends_at) >= ?', [$now]);
                    });
            });
    }

    public function isLiveNow(): bool
    {
        if ($this->status === 'live') {
            return true;
        }

        if (! in_array($this->status, ['scheduled', 'live'], true)) {
            return false;
        }

        $now = now();
        $endsAt = $this->buffer_ends_at ?? $this->session_ends_at;

        return $this->session_starts_at <= $now && $endsAt >= $now;
    }

    /**
     * Lifecycle for UI lists — uses wall-clock when DB status hasn't been flipped yet.
     */
    public function displayLifecycleStatus(): string
    {
        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        if ($this->status === 'completed') {
            return 'completed';
        }

        $endsAt = $this->buffer_ends_at ?? $this->session_ends_at;
        if ($endsAt && $endsAt->lt(now())) {
            return 'completed';
        }

        if ($this->isLiveNow()) {
            return 'live';
        }

        return 'upcoming';
    }
}
