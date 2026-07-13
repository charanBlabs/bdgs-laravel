<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'failed_login_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function initials(): string
    {
        $first = strtoupper(substr($this->first_name ?? 'U', 0, 1));
        $last = strtoupper(substr($this->last_name ?? '', 0, 1));

        return $first.($last !== '' ? $last : '');
    }

    public function profilePhotoUrl(?string $variant = 'thumb'): ?string
    {
        $this->loadMissing('profile.avatar');

        return $this->profile?->avatar?->url($variant);
    }

    public function companyLogoUrl(?string $variant = 'thumb'): ?string
    {
        $this->loadMissing('profile.logo');

        return $this->profile?->logo?->url($variant);
    }

    /**
     * Profile photo for people-facing UI (header, sidebar). Falls back to site placeholder.
     * Company logo is never used here — see companyLogoUrl() for business branding.
     */
    public function avatarUrl(?string $variant = 'thumb'): string
    {
        return $this->profilePhotoUrl($variant) ?? asset('images/default-profile.svg');
    }

    public function primaryRoleLabel(): string
    {
        $this->loadMissing('roles');

        return $this->roles->first()?->display_name ?? 'Member';
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(BdgsRole::class, 'bdgs_role_user', 'user_id', 'role_id');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(BdgsUserData::class, 'user_id');
    }

    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BdgsNotification::class, 'user_id');
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;

        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Fresh DB check — never cached, never trust session alone.
     * Used by Gate('manage-content') and Blade guards.
     */
    public function canManageContent(): bool
    {
        return $this->roles()
            ->where('name', 'admin')
            ->exists();
    }

    public function isLocked(): bool
    {
        return $this->locked_until !== null && $this->locked_until->isFuture();
    }

    public function recordFailedLogin(): void
    {
        $attempts = $this->failed_login_attempts + 1;
        $lockedUntil = $attempts >= 5 ? now()->addMinutes(15) : null;

        $this->forceFill([
            'failed_login_attempts' => $attempts,
            'locked_until' => $lockedUntil,
        ])->save();
    }

    public function recordSuccessfulLogin(?string $ip): void
    {
        $this->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->save();
    }
}
