<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BdgsZoomClinicRegistration;
use App\Support\ZoomClinicCalendar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyZoomClinicController extends Controller
{
    public function index(): View
    {
        $user = $this->authUser();

        $registrations = BdgsZoomClinicRegistration::query()
            ->confirmed()
            ->whereRaw('LOWER(email) = ?', [strtolower($user->email)])
            ->with(['clinic'])
            ->latest('registered_at')
            ->paginate(20);

        return view('dashboard.my-zoom-clinics.index', [
            'registrations' => $registrations,
            'user' => $user,
        ]);
    }

    public function markCalendarAdded(Request $request, BdgsZoomClinicRegistration $registration): JsonResponse
    {
        $user = $this->authUser();

        if (strcasecmp((string) $registration->email, (string) $user->email) !== 0) {
            return response()->json([
                'ok' => false,
                'message' => 'You can only update calendar status for your own registrations.',
            ], 403);
        }

        if ($registration->status !== 'confirmed') {
            return response()->json([
                'ok' => false,
                'message' => 'This registration is no longer active.',
            ], 422);
        }

        if (! $registration->calendar_added_at) {
            $registration->forceFill(['calendar_added_at' => now()])->save();
        }

        $registration->loadMissing('clinic');
        $clinic = $registration->clinic;

        return response()->json([
            'ok' => true,
            'calendar_added_at' => $registration->calendar_added_at?->toIso8601String(),
            'google_calendar_url' => $clinic
                ? ZoomClinicCalendar::googleCalendarUrl($clinic, $registration)
                : null,
        ]);
    }
}
