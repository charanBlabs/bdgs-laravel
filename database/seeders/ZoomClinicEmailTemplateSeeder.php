<?php

namespace Database\Seeders;

use App\Models\BdgsEmailTemplate;
use Illuminate\Database\Seeder;

/**
 * Zoom Clinic email bodies are content-only.
 * Brand chrome (logo, gradient bar, footer) is applied by EmailTheme at send time.
 */
class ZoomClinicEmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->templates() as $template) {
            BdgsEmailTemplate::query()->updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }

    /** @return list<array<string, mixed>> */
    private function templates(): array
    {
        return [
            [
                'slug' => 'zoom-clinic-confirmation',
                'name' => 'Zoom Clinic — Registration Confirmation',
                'subject' => 'You\'re in: {{ clinic_title }} · {{ clinic_schedule }}',
                'body_html' => $this->content(
                    eyebrow: 'Registration confirmed',
                    headline: 'You\'re on the list, {{ first_name }}',
                    lead: 'Your seat is reserved for our free Zoom Clinic. Add it to your calendar so you don\'t miss the live session.',
                    ctaLabel: 'Add to Google Calendar',
                    ctaUrl: '{{ google_calendar_url }}',
                    secondaryCtaLabel: 'Open Zoom Clinics page',
                    secondaryCtaUrl: '{{ page_url }}',
                    note: 'A .ics calendar invite is attached — open it to add this session to Outlook, Apple Calendar, or Google Calendar.',
                    tip: 'Bring your directory URL and one specific question. We\'ll work through it live on the call.'
                ),
                'body_text' => "Hi {{ first_name }},\n\nYou're registered for {{ clinic_title }}.\nWhen: {{ clinic_schedule }}\nJoin: {{ join_url }}\nAdd to Google Calendar: {{ google_calendar_url }}\n\n— {{ site_name }}",
                'variables' => [
                    'first_name', 'name', 'clinic_title', 'clinic_agenda', 'clinic_format',
                    'clinic_schedule', 'join_url', 'page_url', 'google_calendar_url',
                    'directory_url', 'help_topic', 'site_name',
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'zoom-clinic-reminder-24h',
                'name' => 'Zoom Clinic — Reminder (24 hours before)',
                'subject' => 'Tomorrow: {{ clinic_title }} · {{ clinic_schedule }}',
                'body_html' => $this->content(
                    eyebrow: 'Gentle reminder · tomorrow',
                    headline: 'Your Zoom Clinic is tomorrow',
                    lead: 'Just a quick heads-up, {{ first_name }} — your free clinic session is coming up in about 24 hours. No action needed unless you want to add it to your calendar.',
                    ctaLabel: 'Add to Google Calendar',
                    ctaUrl: '{{ google_calendar_url }}',
                    secondaryCtaLabel: 'View session details',
                    secondaryCtaUrl: '{{ page_url }}',
                    note: 'This is reminder 1 of 2. You\'ll get one final note about an hour before we go live — then we\'re done.',
                    tip: 'Have your screen share ready and one stuck widget / CSS / search issue in mind.'
                ),
                'body_text' => "Hi {{ first_name }},\n\nReminder: {{ clinic_title }} is tomorrow.\nWhen: {{ clinic_schedule }}\nJoin: {{ join_url }}\n\n— {{ site_name }}",
                'variables' => [
                    'first_name', 'clinic_title', 'clinic_agenda', 'clinic_format',
                    'clinic_schedule', 'join_url', 'page_url', 'google_calendar_url', 'site_name',
                ],
                'is_active' => true,
            ],
            [
                'slug' => 'zoom-clinic-reminder-1h',
                'name' => 'Zoom Clinic — Reminder (1 hour before)',
                'subject' => 'Starting soon: {{ clinic_title }} · join in ~1 hour',
                'body_html' => $this->content(
                    eyebrow: 'Starting in about 1 hour',
                    headline: 'We go live shortly',
                    lead: '{{ first_name }}, your Zoom Clinic starts in about an hour. Click Join when you\'re ready — cameras optional, questions welcome.',
                    ctaLabel: 'Join Zoom Clinic',
                    ctaUrl: '{{ join_url }}',
                    secondaryCtaLabel: 'Open session page',
                    secondaryCtaUrl: '{{ page_url }}',
                    note: 'This is your final reminder for this session. We won\'t email you again about this clinic.',
                    tip: null
                ),
                'body_text' => "Hi {{ first_name }},\n\n{{ clinic_title }} starts in about 1 hour.\nWhen: {{ clinic_schedule }}\nJoin now: {{ join_url }}\n\n— {{ site_name }}",
                'variables' => [
                    'first_name', 'clinic_title', 'clinic_agenda', 'clinic_format',
                    'clinic_schedule', 'join_url', 'page_url', 'site_name',
                ],
                'is_active' => true,
            ],
        ];
    }

    private function content(
        string $eyebrow,
        string $headline,
        string $lead,
        string $ctaLabel,
        string $ctaUrl,
        string $secondaryCtaLabel,
        string $secondaryCtaUrl,
        string $note,
        ?string $tip
    ): string {
        $font = $this->font();
        $tipBlock = $tip ? <<<HTML
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0 0 22px;border-left:3px solid #E74D56;">
  <tr>
    <td style="padding:4px 0 4px 14px;font-family:{$font};font-size:13px;line-height:1.55;color:#6B6B80;">
      {$tip}
    </td>
  </tr>
</table>
HTML : '';

        return <<<HTML
<p style="margin:0 0 10px;font-family:{$font};font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#E74D56;">{$eyebrow}</p>
<h1 style="margin:0 0 12px;font-family:{$font};font-size:24px;line-height:1.3;color:#1A1A2E;font-weight:700;">{$headline}</h1>
<p style="margin:0 0 22px;font-family:{$font};font-size:15px;line-height:1.6;color:#6B6B80;">{$lead}</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background:#FDF0F0;border:1px solid #F5D0D3;border-radius:10px;margin:0 0 22px;font-family:{$font};">
  <tr>
    <td style="padding:16px 18px;font-family:{$font};">
      <p style="margin:0 0 10px;font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#95256E;font-family:{$font};">Session details</p>
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-family:{$font};font-size:15px;color:#2C2C3A;line-height:1.5;">
        <tr>
          <td style="padding:6px 0;width:88px;color:#6B6B80;vertical-align:top;font-family:{$font};">Session</td>
          <td style="padding:6px 0;font-weight:700;font-family:{$font};">{{ clinic_title }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#6B6B80;vertical-align:top;font-family:{$font};">When</td>
          <td style="padding:6px 0;font-family:{$font};">{{ clinic_schedule }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#6B6B80;vertical-align:top;font-family:{$font};">Format</td>
          <td style="padding:6px 0;font-family:{$font};">{{ clinic_format }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#6B6B80;vertical-align:top;font-family:{$font};">Focus</td>
          <td style="padding:6px 0;font-family:{$font};">{{ clinic_agenda }}</td>
        </tr>
      </table>
    </td>
  </tr>
</table>

{$tipBlock}

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0 0 12px;">
  <tr>
    <td align="center">
      <a href="{$ctaUrl}" style="display:inline-block;background:linear-gradient(135deg,#E74D56 0%,#95256E 100%);color:#ffffff;text-decoration:none;font-family:{$font};font-size:15px;font-weight:700;padding:13px 26px;border-radius:8px;">{$ctaLabel}</a>
    </td>
  </tr>
</table>
<p style="margin:0 0 20px;text-align:center;font-family:{$font};font-size:13px;">
  <a href="{$secondaryCtaUrl}" style="color:#E74D56;text-decoration:underline;font-family:{$font};">{$secondaryCtaLabel}</a>
</p>
<p style="margin:0 0 8px;font-family:{$font};font-size:13px;line-height:1.55;color:#6B6B80;">{$note}</p>
<p style="margin:0;font-family:{$font};font-size:13px;color:#9CA3AF;">Direct join link: <a href="{{ join_url }}" style="color:#E74D56;font-family:{$font};">{{ join_url }}</a></p>
HTML;
    }

    private function font(): string
    {
        return "'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif";
    }
}
