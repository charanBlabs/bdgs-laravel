<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BdgsZoomClinicRegistration;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ZoomClinicRegistrationController extends Controller
{
    public function __construct()
    {
        Gate::authorize('manage-content');
    }

    public function index(): View
    {
        return view('dashboard.zoom-clinics.registrations', [
            'registrationCount' => BdgsZoomClinicRegistration::query()->count(),
        ]);
    }

    public function show(BdgsZoomClinicRegistration $registration): View
    {
        $registration->load('clinic:clinic_id,title,session_starts_at,slug');

        return view('dashboard.zoom-clinics.registration-show', compact('registration'));
    }
}
