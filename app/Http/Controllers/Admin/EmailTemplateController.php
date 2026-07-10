<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BdgsEmailTemplate;
use App\Services\EmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    public function index(): View
    {
        return view('admin.email-templates.index', [
            'templates' => BdgsEmailTemplate::query()->orderBy('name')->get(),
        ]);
    }

    public function edit(BdgsEmailTemplate $template): View
    {
        return view('admin.email-templates.form', compact('template'));
    }

    public function update(Request $request, BdgsEmailTemplate $template): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'subject' => ['required', 'string', 'max:500'],
            'body_html' => ['required', 'string'],
            'body_text' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $template->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Template saved.');
    }

    public function testSend(Request $request, BdgsEmailTemplate $template, EmailService $emailService): RedirectResponse
    {
        $request->validate(['test_email' => ['required', 'email']]);

        $emailService->send($template->slug, $request->string('test_email'), [
            'first_name' => 'Test',
            'last_name' => 'User',
            'full_name' => 'Test User',
            'name' => 'Test User',
            'email' => $request->string('test_email'),
            'site_name' => config('app.name'),
            'dashboard_url' => url('/dashboard'),
            'login_url' => url('/login'),
            'verification_url' => url('/verify-email/test-token'),
            'reset_url' => url('/reset-password/test-token'),
            'need' => 'Setup & Launch',
            'phone' => '+1 555 123 4567',
            'message' => 'This is a test inquiry message.',
            'inquiry_message' => "Setup & Launch\n\nThis is a test inquiry message.",
            'reply' => 'This is a test reply.',
            'solution_name' => 'Sample Solution',
            'order_id' => '12345',
            'service_name' => 'BD Setup Service',
            'solution_url' => url('/solutions/sample-solution'),
            'solution_product_type' => 'subscription',
            'order_summary_html' => '<p>Sample order summary</p>',
            'balance' => '100.00',
        ], false);

        return back()->with('status', 'Test email sent.');
    }
}
