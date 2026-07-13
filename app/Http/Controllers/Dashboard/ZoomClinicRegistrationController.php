<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BdgsZoomClinic;
use App\Models\BdgsZoomClinicRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ZoomClinicRegistrationController extends Controller
{
    public function __construct()
    {
        Gate::authorize('manage-content');
    }

    public function index(Request $request): View
    {
        $clinicId = $request->integer('clinic_id') ?: null;

        $registrations = BdgsZoomClinicRegistration::query()
            ->with('clinic:clinic_id,title,session_starts_at')
            ->when($clinicId, fn ($query) => $query->where('clinic_id', $clinicId))
            ->latest('registered_at')
            ->paginate(25)
            ->withQueryString();

        $clinics = BdgsZoomClinic::query()
            ->orderByDesc('session_starts_at')
            ->get(['clinic_id', 'title', 'session_starts_at']);

        $filteredClinic = $clinicId
            ? $clinics->firstWhere('clinic_id', $clinicId)
            : null;

        return view('dashboard.zoom-clinics.registrations', [
            'registrations' => $registrations,
            'clinics' => $clinics,
            'clinicId' => $clinicId,
            'filteredClinic' => $filteredClinic,
        ]);
    }

    public function show(BdgsZoomClinicRegistration $registration): View
    {
        $registration->load('clinic:clinic_id,title,session_starts_at,slug');

        return view('dashboard.zoom-clinics.registration-show', compact('registration'));
    }
}
