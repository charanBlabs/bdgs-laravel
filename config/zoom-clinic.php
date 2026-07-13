<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Participant count threshold
    |--------------------------------------------------------------------------
    | Avatars and registration counts show only at or above this number.
    | Below it, purpose quotes are shown instead.
    */
    'min_registrations_to_show' => 30,

    /*
    |--------------------------------------------------------------------------
    | Hero quote rotation (ms)
    |--------------------------------------------------------------------------
    | The hero visual card crossfades through quotes when count is below threshold.
    | Card footers stay on one static matched quote (less noise in a list).
    */
    'hero_quote_interval_ms' => 5500,

    /*
    |--------------------------------------------------------------------------
    | Hero visual card quotes (always Zoom Clinics — not topic-specific)
    |--------------------------------------------------------------------------
    | Short single-line hooks for the hero card rotator. Keep each under ~42
    | characters so they stay on one line in the visual card.
    */
    'hero_quotes' => [
        'Free Zoom Clinics — Tue & Thu.',
        'Drop in live. Fix your BD site.',
        '500+ owners join our clinics.',
        'Widgets, CSS, search — live help.',
        '$0 forever. No support ticket.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Topic quotes — automatic fallback when admin hooks are empty
    |--------------------------------------------------------------------------
    |
    | PRODUCTION: You do NOT need to edit this file for each new clinic.
    | Set hooks in Admin → Zoom Clinics → "Registration hooks" (one line per hook).
    |
    | This config is only used when that field is blank — matched against title,
    | agenda, and description. Developers can add new topic groups here for
    | clinics that share a keyword pattern (e.g. all "search" clinics).
    */
    'topic_quotes' => [
        [
            'keywords' => ['search', 'filter', 'map search', 'findability', 'discovery'],
            'quote' => 'Filters not behaving? We debug them live.',
        ],
        [
            'keywords' => ['widget', 'css', 'homepage', 'custom css'],
            'quote' => 'Widget off? CSS fighting you? Show your screen — we fix it live.',
        ],
        [
            'keywords' => ['email', 'template', 'automation', 'notification', 'drip'],
            'quote' => 'Email templates acting up? We trace and fix them on the call.',
        ],
        [
            'keywords' => ['member', 'dashboard', 'profile', 'onboarding', 'checklist'],
            'quote' => 'Member dashboard stuck? Walk us through it — we unblock you live.',
        ],
        [
            'keywords' => ['website review', 'open q&a', 'live review'],
            'quote' => 'Bring your site URL. Leave with answers, not a support ticket.',
        ],
        [
            'keywords' => ['basics', 'getting started', 'fundamentals'],
            'quote' => 'New to BD? Ask anything — our devs answer in plain English.',
        ],
        [
            'keywords' => ['payment', 'gateway', 'stripe', 'billing', 'checkout'],
            'quote' => 'Payments misbehaving? We walk through gateway setup live.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Generic fallback quotes
    |--------------------------------------------------------------------------
    | Used only when admin hooks are empty AND no topic_quotes keyword matches.
    */
    'fallback_quotes' => [
        'Bring your screen. Leave with the fix.',
        'No ticket queue — drop in and ask.',
        '60 minutes of free dev help on your BD site.',
        'Small fixes solved live — $0, every clinic.',
        'Share your URL. Get answers in real time.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Email reminder cadence (marketing best practice)
    |--------------------------------------------------------------------------
    |
    | Fixed max: 3 emails per registration (never more).
    | 1) Confirmation — immediately on register (with .ics + Google Calendar)
    | 2) Reminder 24h — ~24 hours before session
    | 3) Reminder 1h  — ~1 hour before session (final)
    |
    | Why not more: webinar research (HubSpot / Zoom Webinar / ConvertKit)
    | shows diminishing returns after 2 reminders; extra emails raise
    | unsubscribe / frustration without lifting attendance much.
    |
    | Late registrations skip overdue reminders automatically.
    */
    'email' => [
        'max_emails_per_registration' => 3,
        'reminder_24h_window_hours' => [23, 25],
        'reminder_1h_window_minutes' => [50, 70],
    ],

];
