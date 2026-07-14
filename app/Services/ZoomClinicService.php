<?php

namespace App\Services;

use App\Models\BdgsZoomClinic;
use App\Models\BdgsZoomClinicRegistration;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ZoomClinicService
{
    public function __construct(private ZoomClinicMailService $mail) {}

    /**
     * Flip DB status from wall-clock: past sessions → completed, in-window → live.
     * Keeps admin lists and edit forms honest without manual status changes.
     */
    public function syncLifecycleStatuses(): int
    {
        $now = now();

        $completed = BdgsZoomClinic::query()
            ->whereIn('status', ['scheduled', 'live'])
            ->whereRaw('COALESCE(buffer_ends_at, session_ends_at) < ?', [$now])
            ->update(['status' => 'completed']);

        $live = BdgsZoomClinic::query()
            ->where('status', 'scheduled')
            ->where('session_starts_at', '<=', $now)
            ->whereRaw('COALESCE(buffer_ends_at, session_ends_at) >= ?', [$now])
            ->update(['status' => 'live']);

        return $completed + $live;
    }

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

    /**
     * Upcoming clinics plus any currently-live session (for modal/register payloads).
     *
     * @return Collection<int, BdgsZoomClinic>
     */
    public function registerablePublished(): Collection
    {
        $upcoming = $this->upcomingPublished();
        $live = $this->currentlyLive();

        if ($live && ! $upcoming->contains(fn (BdgsZoomClinic $c) => $c->clinic_id === $live->clinic_id)) {
            return $upcoming->prepend($live)->values();
        }

        return $upcoming->values();
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
        return $this->findRegisterableClinic($clinicId);
    }

    /**
     * Find a clinic that can still be registered for (upcoming or live-in-buffer).
     */
    public function findRegisterableClinic(?int $clinicId): ?BdgsZoomClinic
    {
        if ($clinicId) {
            $clinic = BdgsZoomClinic::query()
                ->published()
                ->where('clinic_id', $clinicId)
                ->first();

            return $this->isRegisterable($clinic) ? $clinic : null;
        }

        $live = $this->currentlyLive();
        if ($live && $this->isRegisterable($live)) {
            return $live;
        }

        $next = BdgsZoomClinic::query()->published()->upcoming()->ordered()->first();

        return $this->isRegisterable($next) ? $next : null;
    }

    public function hasRegisterableClinics(): bool
    {
        return $this->findRegisterableClinic(null) !== null;
    }

    public function isRegisterable(?BdgsZoomClinic $clinic): bool
    {
        if (! $clinic || ! $clinic->is_published) {
            return false;
        }

        if (in_array($clinic->status, ['cancelled', 'completed'], true)) {
            return false;
        }

        if ($clinic->isLiveNow()) {
            return true;
        }

        return $clinic->status === 'scheduled' && $clinic->session_starts_at >= now();
    }

    /**
     * @return array{id:int,slug:?string,title:string,agenda:?string,starts_at:string,ends_at:string,join_url:?string,is_live:bool}
     */
    public function toPublicClinicArray(BdgsZoomClinic $clinic): array
    {
        return [
            'id' => $clinic->clinic_id,
            'slug' => $clinic->slug,
            'title' => $clinic->title,
            'agenda' => $clinic->agenda,
            'starts_at' => $clinic->session_starts_at->toIso8601String(),
            'ends_at' => $clinic->session_ends_at->toIso8601String(),
            'join_url' => $clinic->zoom_meeting_url,
            'is_live' => $clinic->isLiveNow(),
        ];
    }

    /**
     * @param  array{name: string, email: string, registrant_timezone?: string, directory_url?: string, help_topic?: string}  $data
     */
    public function register(BdgsZoomClinic $clinic, array $data): BdgsZoomClinicRegistration
    {
        if (! $this->isRegisterable($clinic)) {
            throw ValidationException::withMessages([
                'clinic_id' => ['This clinic session is no longer available for registration.'],
            ]);
        }

        if ($clinic->status === 'cancelled') {
            throw ValidationException::withMessages([
                'clinic_id' => ['This clinic has been cancelled. Please pick another upcoming clinic.'],
            ]);
        }

        $email = strtolower(trim($data['email']));
        $name = trim($data['name']);

        if ($clinic->max_capacity !== null && $clinic->max_capacity > 0) {
            $confirmed = $clinic->confirmedRegistrations()->count();
            if ($confirmed >= $clinic->max_capacity) {
                throw ValidationException::withMessages([
                    'clinic_id' => ['This clinic session is full. Please pick another date.'],
                ]);
            }
        }

        $existing = BdgsZoomClinicRegistration::query()
            ->where('clinic_id', $clinic->clinic_id)
            ->where('email', $email)
            ->first();

        if ($existing && $existing->status === 'confirmed') {
            throw ValidationException::withMessages([
                'email' => ['You are already registered for this clinic session.'],
            ]);
        }

        $payload = [
            'name' => $name,
            'email' => $email,
            'directory_url' => $data['directory_url'] ?? '',
            'help_topic' => $data['help_topic'] ?? null,
            'registrant_timezone' => $data['registrant_timezone'] ?? '',
            'status' => 'confirmed',
            'registered_at' => now(),
        ];

        try {
            if ($existing && $existing->status === 'cancelled') {
                $existing->fill($payload)->save();
                $registration = $existing->fresh();
            } else {
                $registration = BdgsZoomClinicRegistration::create(array_merge($payload, [
                    'clinic_id' => $clinic->clinic_id,
                ]));
            }
        } catch (QueryException $e) {
            if ($this->isUniqueConstraintViolation($e)) {
                throw ValidationException::withMessages([
                    'email' => ['You are already registered for this clinic session.'],
                ]);
            }

            throw $e;
        }

        try {
            $this->mail->sendConfirmation($registration);
        } catch (\Throwable $e) {
            Log::error('Zoom clinic confirmation email failed', [
                'registration_id' => $registration->registration_id,
                'error' => $e->getMessage(),
            ]);
        }

        return $registration;
    }

    private function isUniqueConstraintViolation(QueryException $e): bool
    {
        $sqlState = (string) ($e->errorInfo[0] ?? '');
        $driverCode = (int) ($e->errorInfo[1] ?? 0);
        $message = strtolower($e->getMessage());

        return $sqlState === '23000'
            || $driverCode === 1062
            || str_contains($message, 'unique')
            || str_contains($message, 'duplicate');
    }
}
