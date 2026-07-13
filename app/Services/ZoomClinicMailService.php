<?php

namespace App\Services;

use App\Models\BdgsZoomClinic;
use App\Models\BdgsZoomClinicRegistration;
use App\Support\ZoomClinicCalendar;
use App\Support\ZoomClinicTimes;
use Illuminate\Support\Facades\Log;

class ZoomClinicMailService
{
    public function __construct(private EmailService $emailService) {}

    public function sendConfirmation(BdgsZoomClinicRegistration $registration): bool
    {
        $registration->loadMissing('clinic');
        $clinic = $registration->clinic;

        if (! $clinic instanceof BdgsZoomClinic) {
            return false;
        }

        ZoomClinicCalendar::ensureUid($registration);

        $sent = $this->emailService->send(
            'zoom-clinic-confirmation',
            $registration->email,
            $this->variables($clinic, $registration),
            true,
            [$this->icsAttachment($clinic, $registration)]
        );

        if ($sent) {
            $registration->forceFill(['confirmation_sent_at' => now()])->save();
        }

        return $sent;
    }

    public function sendReminder24h(BdgsZoomClinicRegistration $registration): bool
    {
        return $this->sendReminder($registration, 'zoom-clinic-reminder-24h', 'reminder_24h_sent_at');
    }

    public function sendReminder1h(BdgsZoomClinicRegistration $registration): bool
    {
        return $this->sendReminder($registration, 'zoom-clinic-reminder-1h', 'reminder_1h_sent_at');
    }

    /**
     * Process due reminders for confirmed registrations.
     * Fixed cadence: confirmation (immediate) + 24h + 1h = max 3 emails.
     *
     * @return array{confirmations: int, reminder_24h: int, reminder_1h: int}
     */
    public function processDueReminders(): array
    {
        $counts = [
            'confirmations' => $this->processPendingConfirmations(),
            'reminder_24h' => 0,
            'reminder_1h' => 0,
        ];

        $window24hStart = now()->addHours(23);
        $window24hEnd = now()->addHours(25);
        $window1hStart = now()->addMinutes(50);
        $window1hEnd = now()->addMinutes(70);

        $due24h = BdgsZoomClinicRegistration::query()
            ->confirmed()
            ->whereNull('reminder_24h_sent_at')
            ->whereNotNull('confirmation_sent_at')
            ->whereHas('clinic', function ($q) use ($window24hStart, $window24hEnd) {
                $q->published()
                    ->where('status', 'scheduled')
                    ->whereBetween('session_starts_at', [$window24hStart, $window24hEnd]);
            })
            ->with('clinic')
            ->limit(100)
            ->get();

        foreach ($due24h as $registration) {
            if ($this->sendReminder24h($registration)) {
                $counts['reminder_24h']++;
            }
        }

        $due1h = BdgsZoomClinicRegistration::query()
            ->confirmed()
            ->whereNull('reminder_1h_sent_at')
            ->whereNotNull('confirmation_sent_at')
            ->whereHas('clinic', function ($q) use ($window1hStart, $window1hEnd) {
                $q->published()
                    ->whereIn('status', ['scheduled', 'live'])
                    ->whereBetween('session_starts_at', [$window1hStart, $window1hEnd]);
            })
            ->with('clinic')
            ->limit(100)
            ->get();

        foreach ($due1h as $registration) {
            if ($this->sendReminder1h($registration)) {
                $counts['reminder_1h']++;
            }
        }

        return $counts;
    }

    /**
     * Retry confirmation emails that failed at registration time.
     */
    public function processPendingConfirmations(int $limit = 50): int
    {
        $pending = BdgsZoomClinicRegistration::query()
            ->confirmed()
            ->whereNull('confirmation_sent_at')
            ->where('registered_at', '>=', now()->subDays(14))
            ->whereHas('clinic', function ($q) {
                $q->published()->whereIn('status', ['scheduled', 'live']);
            })
            ->with('clinic')
            ->orderBy('registration_id')
            ->limit($limit)
            ->get();

        $sent = 0;
        foreach ($pending as $registration) {
            try {
                if ($this->sendConfirmation($registration)) {
                    $sent++;
                }
            } catch (\Throwable $e) {
                Log::error('Zoom clinic confirmation retry failed', [
                    'registration_id' => $registration->registration_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($pending->isNotEmpty() && $sent < $pending->count()) {
            Log::warning('Zoom clinic confirmation retries still pending', [
                'attempted' => $pending->count(),
                'sent' => $sent,
                'remaining' => $pending->count() - $sent,
            ]);
        }

        return $sent;
    }

    private function sendReminder(BdgsZoomClinicRegistration $registration, string $slug, string $sentAtColumn): bool
    {
        $registration->loadMissing('clinic');
        $clinic = $registration->clinic;

        if (! $clinic instanceof BdgsZoomClinic) {
            return false;
        }

        if ($registration->{$sentAtColumn}) {
            return false;
        }

        ZoomClinicCalendar::ensureUid($registration);

        try {
            $sent = $this->emailService->send(
                $slug,
                $registration->email,
                $this->variables($clinic, $registration),
                true,
                [$this->icsAttachment($clinic, $registration)]
            );

            if ($sent) {
                $registration->forceFill([$sentAtColumn => now()])->save();
            }

            return $sent;
        } catch (\Throwable $e) {
            Log::error('Zoom clinic reminder failed', [
                'slug' => $slug,
                'registration_id' => $registration->registration_id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /** @return array<string, mixed> */
    private function variables(BdgsZoomClinic $clinic, BdgsZoomClinicRegistration $registration): array
    {
        $firstName = strtok(trim($registration->name), ' ') ?: $registration->name;
        $scheduleLine = ZoomClinicTimes::clinicRange($clinic);
        $joinUrl = $clinic->zoom_meeting_url ?: url('/zoom-clinics');

        return [
            'first_name' => $firstName,
            'name' => $registration->name,
            'email' => $registration->email,
            'clinic_title' => $clinic->title,
            'clinic_agenda' => $clinic->agenda ?: 'Open Q&A — bring your BD site questions',
            'clinic_format' => $clinic->format_note ?: '60-min live Zoom session',
            'clinic_schedule' => $scheduleLine,
            'clinic_timezone' => ZoomClinicTimes::PAGE_TZ,
            'join_url' => $joinUrl,
            'page_url' => url('/zoom-clinics'),
            'google_calendar_url' => ZoomClinicCalendar::googleCalendarUrl($clinic, $registration),
            'help_topic' => $registration->help_topic ?: 'Anything on your Brilliant Directories site',
            'site_name' => config('app.name'),
        ];
    }

    /** @return array{data: string, name: string, options: array<string, mixed>} */
    private function icsAttachment(BdgsZoomClinic $clinic, BdgsZoomClinicRegistration $registration): array
    {
        return [
            'data' => ZoomClinicCalendar::ics($clinic, $registration),
            'name' => 'bdgs-zoom-clinic.ics',
            'options' => [
                'mime' => 'text/calendar; charset=UTF-8; method=REQUEST',
                'as' => 'bdgs-zoom-clinic.ics',
            ],
        ];
    }
}
