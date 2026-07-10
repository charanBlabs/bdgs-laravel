<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BdgsDataPost;
use App\Models\BdgsInquiry;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.overview', [
            'stats' => [
                'users' => User::query()->count(),
                'posts' => BdgsDataPost::query()->count(),
                'published' => BdgsDataPost::query()->where('status', 'published')->count(),
                'inquiries' => BdgsInquiry::query()->count(),
                'new_inquiries' => BdgsInquiry::query()->where('status', 'new')->count(),
            ],
        ]);
    }
}
