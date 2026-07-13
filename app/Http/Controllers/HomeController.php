<?php

namespace App\Http\Controllers;

use App\Services\ReviewsService;
use App\Services\ZoomClinicService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly ReviewsService $reviews,
        private readonly ZoomClinicService $clinics,
    ) {
    }

    public function index(): View
    {
        $clinicList = $this->clinics->registerablePublished();

        return view('pages.home.index', [
            'reviewCount' => $this->reviews->publishedCount(),
            'carouselReviews' => $this->reviews->carouselReviewArrays(),
            'clinicJson' => $clinicList->map(fn ($c) => $this->clinics->toPublicClinicArray($c))->values(),
        ]);
    }
}
