<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = $this->authUser()->load('profile', 'roles');

        return view('dashboard.overview', compact('user'));
    }
}
