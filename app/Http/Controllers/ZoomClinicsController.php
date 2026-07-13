<?php

namespace App\Http\Controllers;

use App\Services\ZoomClinicService;
use Illuminate\View\View;

class ZoomClinicsController extends Controller
{
    public function __construct(private readonly ZoomClinicService $clinics)
    {
    }

    public function index(): View
    {
        $clinicList = $this->clinics->upcomingPublished();
        $registerable = $this->clinics->registerablePublished();
        $featured = $this->clinics->currentlyLive()
            ?? $clinicList->firstWhere('is_featured', true)
            ?? $clinicList->first();

        $clinicJson = $registerable->map(fn ($c) => $this->clinics->toPublicClinicArray($c))->values();

        return view('pages.zoom-clinics.index', [
            'clinics' => $clinicList,
            'featuredClinic' => $featured,
            'totalUpcoming' => $this->clinics->totalUpcomingCount(),
            'clinicJson' => $clinicJson,
        ]);
    }
}
