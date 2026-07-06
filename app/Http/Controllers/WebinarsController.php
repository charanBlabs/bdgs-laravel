<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class WebinarsController extends Controller
{
    public function index(): View
    {
        return view('pages.webinars.index');
    }
}
