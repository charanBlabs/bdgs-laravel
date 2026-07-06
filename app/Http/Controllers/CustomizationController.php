<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CustomizationController extends Controller
{
    public function index(): View
    {
        return view('pages.customization.index');
    }
}
