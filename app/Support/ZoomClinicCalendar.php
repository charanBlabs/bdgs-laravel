<?php

namespace App\Support;

use App\Models\BdgsZoomClinic;
use App\Models\BdgsZoomClinicRegistration;
use Illuminate\Support\Str;

/**
 * Builds ICS + Google Calendar links so clients detect the Zoom Clinic as a calendar event.
 */
class ZoomClinicCalendar
{
    public static function uid(BdgsZoomClinicRegistration $registration): string
    {
        if ($registration->calendar_uid) {
            return $registration->calendar_uid;
        }

        return 'zoom-clinic-'.$registration->registration_id.'@'.parse_url((string) config('app.url'), PHP_URL_HOST);
    }

    public static function googleCalendarUrl(BdgsZoomClinic $clinic, BdgsZoomClinicRegistration $registration): string
    {
        if (! $clinic->session_starts_at || ! $clinic->session_ends_at) {
            return url('/zoom-clinics');
        }

        $start = $clinic->session_starts_at->copy()->utc();
        $end = $clinic->session_ends_at->copy()->utc();

        // www.google.com/calendar/render opens the event template directly.
        // calendar.google.com often sends signed-out users to Workspace marketing.
        $query = http_build_query([
            'action' => 'TEMPLATE',
            'text' => $clinic->title.' — BD Growth Suite Zoom Clinic',
            'dates' => $start->format('Ymd\THis\Z').'/'.$end->format('Ymd\THis\Z'),
            'details' => self::description($clinic, $registration),
            'location' => $clinic->zoom_meeting_url ?: 'Online via Zoom',
        ], '', '&', PHP_QUERY_RFC3986);

        return 'https://www.google.com/calendar/render?'.$query;
    }

    public static function ics(BdgsZoomClinic $clinic, BdgsZoomClinicRegistration $registration): string
    {
        $uid = self::uid($registration);
        $start = $clinic->session_starts_at->copy()->utc();
        $end = $clinic->session_ends_at->copy()->utc();
        $stamp = now()->utc();
        $from = config('mail.from.address', 'noreply@bdgrowthsuite.com');
        $organizerName = config('mail.from.name', 'BD Growth Suite');
        $description = self::escapeIcs(self::description($clinic, $registration));
        $summary = self::escapeIcs($clinic->title.' — BD Growth Suite Zoom Clinic');
        $location = self::escapeIcs($clinic->zoom_meeting_url ?: 'Online via Zoom');
        $url = $clinic->zoom_meeting_url ?: url('/zoom-clinics');

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//BD Growth Suite//Zoom Clinics//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:REQUEST',
            'BEGIN:VEVENT',
            'UID:'.$uid,
            'DTSTAMP:'.$stamp->format('Ymd\THis\Z'),
            'DTSTART:'.$start->format('Ymd\THis\Z'),
            'DTEND:'.$end->format('Ymd\THis\Z'),
            'SUMMARY:'.$summary,
            'DESCRIPTION:'.$description,
            'LOCATION:'.$location,
            'URL:'.$url,
            'ORGANIZER;CN='.self::escapeIcs($organizerName).':MAILTO:'.$from,
            'ATTENDEE;CN='.self::escapeIcs($registration->name).';RSVP=TRUE;PARTSTAT=NEEDS-ACTION;ROLE=REQ-PARTICIPANT:MAILTO:'.$registration->email,
            'STATUS:CONFIRMED',
            'SEQUENCE:0',
            'BEGIN:VALARM',
            'TRIGGER:-PT60M',
            'ACTION:DISPLAY',
            'DESCRIPTION:Zoom Clinic starts in 1 hour',
            'END:VALARM',
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return implode("\r\n", $lines)."\r\n";
    }

    public static function ensureUid(BdgsZoomClinicRegistration $registration): string
    {
        if ($registration->calendar_uid) {
            return $registration->calendar_uid;
        }

        $uid = 'zoom-clinic-'.$registration->registration_id.'-'.Str::lower(Str::random(8)).'@bdgrowthsuite.com';
        $registration->forceFill(['calendar_uid' => $uid])->save();

        return $uid;
    }

    private static function description(BdgsZoomClinic $clinic, BdgsZoomClinicRegistration $registration): string
    {
        $parts = [
            'You are registered for this free Zoom Clinic with BD Growth Suite.',
            'When: '.ZoomClinicTimes::clinicRange($clinic),
            'Format: '.($clinic->format_note ?: '60-min live Q&A'),
        ];

        if ($clinic->agenda) {
            $parts[] = 'Focus: '.$clinic->agenda;
        }

        if ($clinic->zoom_meeting_url) {
            $parts[] = 'Join Zoom: '.$clinic->zoom_meeting_url;
        }

        if ($registration->directory_url) {
            $parts[] = 'Directory: '.$registration->directory_url;
        }

        if ($registration->help_topic) {
            $parts[] = 'Help needed: '.$registration->help_topic;
        }

        $parts[] = 'Page: '.url('/zoom-clinics');
        $parts[] = 'Registered as: '.$registration->name.' ('.$registration->email.')';

        return implode("\n", $parts);
    }

    private static function escapeIcs(string $value): string
    {
        return str_replace(
            ["\\", ';', ',', "\n", "\r"],
            ['\\\\', '\\;', '\\,', '\\n', ''],
            $value
        );
    }
}
