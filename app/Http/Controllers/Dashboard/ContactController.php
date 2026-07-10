<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\InquiryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('dashboard.contact', [
            'needOptions' => InquiryService::NEED_OPTIONS,
        ]);
    }

    public function store(Request $request, InquiryService $inquiryService): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'need' => ['required', 'string', 'max:80'],
            'message' => ['nullable', 'string', 'max:4000'],
        ]);

        $inquiryService->submit(array_merge($validated, [
            'source' => 'dashboard',
            'user_id' => Auth::id(),
        ]), $request->ip(), $request->userAgent());

        return redirect()->route('dashboard.contact')->with('status', 'Your message was sent. We will follow up by email.');
    }
}
