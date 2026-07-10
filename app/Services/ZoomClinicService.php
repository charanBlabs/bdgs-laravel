<?php

namespace App\Services;

use App\Models\BdgsZoomClinic;
use App\Models\BdgsZoomClinicRegistration;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ZoomClinicService
{
    /**
     * @return Collection<int, BdgsZoomClinic>
     */
    public function upcomingPublished(): Collection
    {
        return BdgsZoomClinic::query()
            ->published()
            ->upcoming()
            ->ordered()
            ->with(['confirmedRegistrations' => fn ($q) => $q->orderBy('registered_at')])
            ->get();
    }

    public function totalUpcomingCount(): int
    {
        return BdgsZoomClinic::query()->published()->upcoming()->count();
    }

    public function currentlyLive(): ?BdgsZoomClinic
    {
        return BdgsZoomClinic::query()
            ->published()
            ->currentlyLive()
            ->ordered()
            ->with(['confirmedRegistrations' => fn ($q) => $q->orderBy('registered_at')])
            ->first();
    }

    public function findUpcomingClinic(?int $clinicId): ?BdgsZoomClinic
    {
        $query = BdgsZoomClinic::query()->published()->upcoming()->ordered();

        if ($clinicId) {
            return $query->where('clinic_id', $clinicId)->first();
        }

        return $query->first();
    }

    /**
     * @param  array{name: string, email: string, registrant_timezone?: string, directory_url?: string, help_topic?: string}  $data
     */
    public function register(BdgsZoomClinic $clinic, array $data): BdgsZoomClinicRegistration
    {
        $existing = BdgsZoomClinicRegistration::query()
            ->where('clinic_id', $clinic->clinic_id)
            ->where('email', $data['email'])
            ->where('status', 'confirmed')
            ->exists();

        if ($existing) {
            throw ValidationException::withMessages([
                'email' => ['You are already registered for this clinic session.'],
            ]);
        }

        return BdgsZoomClinicRegistration::create([
            'clinic_id' => $clinic->clinic_id,
            'name' => $data['name'],
            'email' => $data['email'],
            'directory_url' => $data['directory_url'] ?? '',
            'help_topic' => $data['help_topic'] ?? null,
            'registrant_timezone' => $data['registrant_timezone'] ?? '',
            'status' => 'confirmed',
            'registered_at' => now(),
        ]);
    }
}
