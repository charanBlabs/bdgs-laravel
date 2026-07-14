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

        $to = $request->string('test_email')->toString();

        $ok = $emailService->send($template->slug, $to, [
            'first_name' => 'Test',
            'last_name' => 'User',
            'full_name' => 'Test User',
            'name' => 'Test User',
            'email' => $to,
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
            'clinic_title' => 'BD Website Review Zoom Clinic',
            'clinic_agenda' => 'Open Q&A — widgets, CSS, search',
            'clinic_format' => '60-min live Zoom session',
            'clinic_schedule' => 'Tue, Jul 15 · 9:00 AM – 10:00 AM EDT - New York',
            'join_url' => 'https://zoom.us/j/example',
            'page_url' => url('/zoom-clinics'),
            'google_calendar_url' => 'https://calendar.google.com/calendar/render?action=TEMPLATE',
            'directory_url' => 'https://example-directory.com',
            'help_topic' => 'Homepage widget layout',
        ], false);

        if ($ok) {
            return back()->with('status', "Test email sent to {$to}.");
        }

        $error = \App\Models\BdgsEmailLog::query()
            ->where('to_email', $to)
            ->where('template_slug', $template->slug)
            ->where('status', 'failed')
            ->latest('id')
            ->value('error_message');

        return back()->with('error', 'Test email failed: '.($error ?: 'Unknown SMTP error. Check storage/logs/laravel.log.'));
    }
}
