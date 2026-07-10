<?php

namespace App\Http\Controllers;

use App\Services\ReviewsService;
use App\Support\ReviewPresenter;
use Illuminate\View\View;

class BlabsReviewController extends Controller
{
    public function __construct(private readonly ReviewsService $reviews)
    {
    }

    public function index(): View
    {
        $reviewCount = $this->reviews->publishedCount();
        $dbCount = $this->reviews->publishedDbCount();
        $offset = 0;
        $limit = ReviewsService::PER_PAGE;
        $reviews = $this->reviews->publishedPage($offset, $limit);
        $schemaReviews = $this->reviews->allPublishedForSchema();
        $schemaGraph = ReviewPresenter::professionalServiceGraph($schemaReviews, $reviewCount);

        return view('pages.blabs-review.index', [
            'reviews' => $reviews,
            'reviewCount' => $reviewCount,
            'reviewsPerPage' => $limit,
            'hasMoreReviews' => $dbCount > $reviews->count(),
            'schemaGraph' => $schemaGraph,
        ]);
    }
}
