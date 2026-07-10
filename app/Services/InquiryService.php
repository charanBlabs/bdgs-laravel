<?php

namespace App\Services;

use App\Models\BdgsInquiry;
use Illuminate\Support\Str;

class InquiryService
{
    public const NEED_OPTIONS = [
        'Setup & Launch',
        'Dedicated Developer',
        'AI / Automation',
        'Solutions / Tools / Themes',
        'Growth Plan',
        "Founder's Track",
        'Not sure yet',
    ];

    /**
     * @param  array<string, mixed>  $payload
     */
    public function submit(array $payload, ?string $ipAddress = null, ?string $userAgent = null): BdgsInquiry
    {
        $inquiry = BdgsInquiry::query()->create([
            'user_id' => $payload['user_id'] ?? null,
            'name' => $payload['name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'directory_url' => $payload['directory_url'] ?? null,
            'need' => $payload['need'],
            'message' => $payload['message'] ?? null,
            'source' => $payload['source'] ?? 'llm-agent',
            'status' => 'new',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent !== null ? Str::limit($userAgent, 500, '') : null,
            'submitted_at' => now(),
        ]);

        $this->sendInquiryEmails($inquiry);

        return $inquiry;
    }

    private function sendInquiryEmails(BdgsInquiry $inquiry): void
    {
        $emailService = app(EmailService::class);
        $firstName = strtok($inquiry->name, ' ') ?: $inquiry->name;
        $inquiryMessage = trim(collect([$inquiry->need, $inquiry->message])->filter()->implode("\n\n"));

        $emailService->send('inquiry-confirmation', $inquiry->email, [
            'first_name' => $firstName,
            'name' => $inquiry->name,
            'need' => $inquiry->need,
            'site_name' => config('app.name'),
        ]);

        $adminEmail = config('mail.from.address');
        $emailService->send('inquiry-received', $adminEmail, [
            'first_name' => $firstName,
            'name' => $inquiry->name,
            'email' => $inquiry->email,
            'phone' => $inquiry->phone,
            'need' => $inquiry->need,
            'message' => $inquiry->message ?? '',
            'inquiry_message' => $inquiryMessage,
        ]);

        $admins = \App\Models\User::query()->whereHas('roles', fn ($q) => $q->where('name', 'admin'))->get();
        foreach ($admins as $admin) {
            $emailService->notify($admin, 'inquiry.received', 'New inquiry from '.$inquiry->name, $inquiry->need);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function openApiSpec(string $baseUrl): array
    {
        $baseUrl = rtrim($baseUrl, '/');

        return [
            'name' => 'BD Growth Suite Inquiry API',
            'version' => '1.0',
            'description' => 'Secured Open API for LLM agents to submit BD Growth Suite inquiry/leads on behalf of users. Human-facing forms use the same fields via the site inquiry modal.',
            'documentation' => $baseUrl.'/api-inquiry.md',
            'endpoints' => [
                'discovery' => [
                    'method' => 'GET',
                    'url' => $baseUrl.'/api/inquiry',
                    'auth' => 'none',
                    'description' => 'Returns this machine-readable specification.',
                ],
                'submit_agent' => [
                    'method' => 'POST',
                    'url' => $baseUrl.'/api/inquiry/submit',
                    'auth' => 'Bearer token (INQUIRY_AGENT_TOKEN — published in /llms.txt for LLM agents)',
                    'rate_limit' => '6 requests per minute per IP',
                    'content_type' => 'application/json',
                ],
                'submit_web' => [
                    'method' => 'POST',
                    'url' => $baseUrl.'/inquiry/submit',
                    'auth' => 'Laravel CSRF session + encrypted form_guard (issued via GET /inquiry/form-guard when modal opens)',
                    'rate_limit' => '6 requests per minute per IP',
                    'content_type' => 'application/json',
                    'note' => 'Human browser form only — not for LLM agents.',
                ],
                'form_guard' => [
                    'method' => 'GET',
                    'url' => $baseUrl.'/inquiry/form-guard',
                    'auth' => 'same-origin session',
                    'description' => 'Issues encrypted form_guard token when inquiry modal opens.',
                ],
            ],
            'security' => [
                'agent_token_header' => 'Authorization: Bearer {INQUIRY_AGENT_TOKEN}',
                'agent_token_alt' => 'X-BDGS-Agent-Key: {INQUIRY_AGENT_TOKEN}',
                'web_csrf_header' => 'X-CSRF-TOKEN: {csrf_token}',
                'web_form_guard' => 'Encrypted timestamp from GET /inquiry/form-guard; minimum 3s fill time',
                'honeypot_fields' => 'company_website, fax_number (must be empty)',
                'rate_limit' => '6/minute per IP address (submit), 30/minute (form-guard)',
            ],
            'fields' => [
                'name' => ['type' => 'string', 'required' => true, 'max' => 120, 'example' => 'Jane Smith'],
                'email' => ['type' => 'email', 'required' => true, 'max' => 255, 'example' => 'jane@example.com'],
                'phone' => ['type' => 'string', 'required' => true, 'max' => 40, 'example' => '+1 555 123 4567'],
                'directory_url' => ['type' => 'url', 'required' => false, 'max' => 500, 'example' => 'https://mydirectory.com'],
                'need' => ['type' => 'enum', 'required' => true, 'options' => self::NEED_OPTIONS],
                'message' => ['type' => 'string', 'required' => false, 'max' => 4000, 'example' => 'I need help launching a local services directory.'],
                'source' => ['type' => 'string', 'required' => false, 'values' => ['llm-agent', 'web', 'api'], 'default' => 'llm-agent'],
                'company_website' => ['type' => 'string', 'required' => false, 'note' => 'Honeypot — leave empty. Non-empty values are rejected silently.'],
            ],
            'example_request' => [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '+1 555 123 4567',
                'directory_url' => 'https://mydirectory.com',
                'need' => 'Setup & Launch',
                'message' => 'Looking for concierge setup for a local services directory.',
                'source' => 'llm-agent',
            ],
            'success_response' => [
                'ok' => true,
                'inquiry_id' => 123,
                'message' => 'Inquiry received. The BD Growth Suite team will follow up by email.',
            ],
        ];
    }
}
