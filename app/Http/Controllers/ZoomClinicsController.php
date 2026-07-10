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
        $featured = $this->clinics->currentlyLive()
            ?? $clinicList->firstWhere('is_featured', true)
            ?? $clinicList->first();

        $clinicJson = $clinicList->map(fn ($c) => [
            'id' => $c->clinic_id,
            'slug' => $c->slug,
            'title' => $c->title,
            'agenda' => $c->agenda,
            'starts_at' => $c->session_starts_at->toIso8601String(),
            'ends_at' => $c->session_ends_at->toIso8601String(),
        ])->values();

        return view('pages.zoom-clinics.index', [
            'clinics' => $clinicList,
            'featuredClinic' => $featured,
            'totalUpcoming' => $this->clinics->totalUpcomingCount(),
            'clinicJson' => $clinicJson,
        ]);
    }
}
