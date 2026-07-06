<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BlabsReviewController extends Controller
{
    public function index(): View
    {
        return view('pages.blabs-review.index');
    }
}
