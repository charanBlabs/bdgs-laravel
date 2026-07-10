<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ZoomClinicService;
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

        $clinic = $this->clinics->findUpcomingClinic(
            isset($validated['clinic_id']) ? (int) $validated['clinic_id'] : null
        );

        if (! $clinic) {
            throw ValidationException::withMessages([
                'clinic_id' => ['This clinic session is no longer available for registration.'],
            ]);
        }

        $registration = $this->clinics->register($clinic, $validated);

        return response()->json([
            'ok' => true,
            'registration_id' => $registration->registration_id,
            'clinic_id' => $clinic->clinic_id,
            'participant_count' => $clinic->confirmedRegistrations()->count(),
        ], 201);
    }
}
