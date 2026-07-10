<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LicenseSdclController extends Controller
{
    public function index(): View
    {
        return view('pages.license.sdcl-v1.index');
    }
}
