<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'reviews_sync' => [
        'token' => env('REVIEWS_SYNC_TOKEN'),
    ],

    'inquiry_agent' => [
        'token' => env('INQUIRY_AGENT_TOKEN'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cal.com — Discovery Call booking (30-min)
    |--------------------------------------------------------------------------
    |
    | Element-click embed. Confirmation + reminder emails are sent by Cal.com
    | (configure in the Cal.com event type settings).
    |
    */
    'cal' => [
        'origin' => env('CAL_ORIGIN', 'https://app.cal.com'),
        'link' => env('CAL_LINK', 'charan-tej-vattikuti-unbo7v/30min'),
        'namespace' => env('CAL_NAMESPACE', '30min'),
        'config' => [
            'layout' => 'month_view',
            'useSlotsViewOnSmallScreen' => 'true',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketing pixels (GA4 / Meta / LinkedIn)
    |--------------------------------------------------------------------------
    |
    | Loaded on public layouts when enabled and at least one ID is set.
    | Zoom Clinic registration fires conversion events in-browser on success
    | (no thank-you page). See docs/post-production-checklist.md.
    |
    */
    'tracking' => [
        // Default: on in production only. Set TRACKING_ENABLED=true to test on staging.
        'enabled' => env('TRACKING_ENABLED') !== null
            ? filter_var(env('TRACKING_ENABLED'), FILTER_VALIDATE_BOOLEAN)
            : env('APP_ENV') === 'production',
        'ga4_id' => env('GOOGLE_ANALYTICS_ID', env('GA4_MEASUREMENT_ID')),
        'meta_pixel_id' => env('FACEBOOK_PIXEL_ID', env('META_PIXEL_ID')),
        'linkedin_partner_id' => env('LINKEDIN_PARTNER_ID'),
        'linkedin_zoom_clinic_conversion_id' => env('LINKEDIN_ZOOM_CLINIC_CONVERSION_ID'),
    ],

];
