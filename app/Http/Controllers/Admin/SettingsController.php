<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(SettingsService $settings): View
    {
        return view('admin.settings.index', [
            'general' => $settings->group('general'),
            'seo' => $settings->group('seo'),
            'mail' => $settings->group('mail'),
            'social' => $settings->group('social'),
        ]);
    }

    public function update(Request $request, SettingsService $settings): RedirectResponse
    {
        foreach ($request->input('settings', []) as $group => $pairs) {
            foreach ($pairs as $key => $value) {
                $settings->set($group, $key, $value);
            }
        }

        return back()->with('status', 'Settings saved.');
    }
}
