<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HireDeveloperController extends Controller
{
    public function index(): View
    {
        return view('pages.hire-developer.index');
    }
}
