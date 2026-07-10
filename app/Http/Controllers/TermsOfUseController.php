<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TermsOfUseController extends Controller
{
    public function index(): View
    {
        return view('pages.terms.index');
    }
}
