<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BdgsInquiry;
use App\Services\EmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(): View
    {
        return view('admin.inquiries.index', [
            'inquiries' => BdgsInquiry::query()->latest('submitted_at')->paginate(25),
        ]);
    }

    public function show(BdgsInquiry $inquiry): View
    {
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function reply(Request $request, BdgsInquiry $inquiry, EmailService $emailService): RedirectResponse
    {
        $validated = $request->validate([
            'admin_reply' => ['required', 'string', 'max:4000'],
        ]);

        $inquiry->update([
            'admin_reply' => $validated['admin_reply'],
            'status' => 'replied',
        ]);

        $emailService->send('inquiry-reply', $inquiry->email, [
            'first_name' => strtok($inquiry->name, ' ') ?: $inquiry->name,
            'name' => $inquiry->name,
            'reply' => $validated['admin_reply'],
            'site_name' => config('app.name'),
        ]);

        if ($inquiry->user_id) {
            $user = \App\Models\User::query()->find($inquiry->user_id);
            if ($user) {
                $emailService->notify($user, 'inquiry.reply', 'Reply to your inquiry', $validated['admin_reply']);
            }
        }

        return back()->with('status', 'Reply sent.');
    }
}
