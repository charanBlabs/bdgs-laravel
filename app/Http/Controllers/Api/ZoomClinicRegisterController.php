<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ZoomClinicService;
use App\Support\ZoomClinicCalendar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ZoomClinicRegisterController extends Controller
{
    public function __construct(private readonly ZoomClinicService $clinics)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'clinic_id' => ['nullable', 'integer', 'min:1'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'directory_url' => ['nullable', 'string', 'max:500'],
            'help_topic' => ['nullable', 'string', 'max:2000'],
            'registrant_timezone' => ['nullable', 'string', 'max:64'],
        ]);

        $validated['directory_url'] = trim((string) ($validated['directory_url'] ?? ''));
        $helpTopic = trim((string) ($validated['help_topic'] ?? ''));
        $validated['help_topic'] = $helpTopic !== '' ? $helpTopic : null;

        $clinic = $this->clinics->findUpcomingClinic(
            isset($validated['clinic_id']) ? (int) $validated['clinic_id'] : null
        );

        if (! $clinic) {
            $hasAnyOpen = $this->clinics->hasRegisterableClinics();
            $requestedId = isset($validated['clinic_id']) ? (int) $validated['clinic_id'] : null;

            if (! $hasAnyOpen) {
                throw ValidationException::withMessages([
                    'clinic_id' => ['No upcoming Zoom Clinics are open for registration right now. Please check back soon.'],
                ]);
            }

            throw ValidationException::withMessages([
                'clinic_id' => [
                    $requestedId
                        ? 'This clinic session is no longer available for registration. Please pick another upcoming clinic.'
                        : 'We could not find an open Zoom Clinic right now. Please refresh and try again.',
                ],
            ]);
        }

        try {
            $registration = $this->clinics->register($clinic, $validated);
            ZoomClinicCalendar::ensureUid($registration);
            $registration->refresh();
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            throw ValidationException::withMessages([
                'email' => ['Registration failed due to a temporary server issue. Please try again in a moment.'],
            ]);
        }

        return response()->json([
            'ok' => true,
            'registration_id' => $registration->registration_id,
            'clinic_id' => $clinic->clinic_id,
            'clinic_title' => $clinic->title,
            'starts_at' => $clinic->session_starts_at->toIso8601String(),
            'ends_at' => $clinic->session_ends_at->toIso8601String(),
            'join_url' => $clinic->zoom_meeting_url,
            'google_calendar_url' => ZoomClinicCalendar::googleCalendarUrl($clinic, $registration),
            'confirmation_sent' => (bool) $registration->confirmation_sent_at,
            'participant_count' => $clinic->confirmedRegistrations()->count(),
        ], 201);
    }
}
