<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PrivacyPolicyController extends Controller
{
    public function index(): View
    {
        return view('pages.privacy.index');
    }
}
