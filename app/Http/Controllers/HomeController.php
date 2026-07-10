<?php

namespace App\Http\Controllers;

use App\Services\ReviewsService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly ReviewsService $reviews)
    {
    }

    public function index(): View
    {
        return view('pages.home.index', [
            'reviewCount' => $this->reviews->publishedCount(),
            'carouselReviews' => $this->reviews->carouselReviewArrays(),
        ]);
    }
}
