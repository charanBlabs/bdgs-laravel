<?php

namespace App\Services;

use App\Models\BdgsEmailLog;
use App\Models\BdgsEmailOutbox;
use App\Models\BdgsEmailTemplate;
use App\Models\BdgsNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function render(string $slug, array $variables = [], bool $requireActive = true): ?array
    {
        $query = BdgsEmailTemplate::query()->where('slug', $slug);

        if ($requireActive) {
            $query->where('is_active', true);
        }

        $template = $query->first();

        if (! $template) {
            return null;
        }

        $variables = $this->prepareVariables($variables);

        $subject = $this->replaceTags($template->subject, $variables);
        $bodyHtml = $this->replaceTags($template->body_html, $variables);
        $bodyText = $template->body_text
            ? $this->replaceTags($template->body_text, $variables)
            : strip_tags($bodyHtml);

        return [
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'body_text' => $bodyText,
        ];
    }

    public function send(string $slug, string $to, array $variables = [], bool $requireActive = true): bool
    {
        $rendered = $this->render($slug, $variables, $requireActive);

        if (! $rendered) {
            return false;
        }

        $fromEmail = config('mail.from.address');
        $fromName = config('mail.from.name');

        $log = BdgsEmailLog::query()->create([
            'user_id' => $variables['_user_id'] ?? null,
            'template_slug' => $slug,
            'to_email' => $to,
            'to_name' => $variables['full_name'] ?? $variables['name'] ?? $variables['first_name'] ?? null,
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'subject' => $rendered['subject'],
            'body_html' => $rendered['body_html'],
            'body_text' => $rendered['body_text'],
            'status' => 'sending',
            'channel' => config('mail.default', 'smtp'),
        ]);

        try {
            Mail::html($rendered['body_html'], function ($message) use ($to, $rendered) {
                $message->to($to)->subject($rendered['subject']);
            });

            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
                'attempts' => 1,
            ]);

            return true;
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => mb_substr($e->getMessage(), 0, 2000),
                'attempts' => 1,
            ]);

            Log::error('EmailService send failed', [
                'slug' => $slug,
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function sendToUser(User $user, string $slug, array $variables = []): bool
    {
        $variables = array_merge([
            '_user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->fullName(),
            'name' => $user->fullName(),
            'email' => $user->email,
        ], $variables);

        return $this->send($slug, $user->email, $variables);
    }

    /**
     * Queue an email into the outbox for deferred sending (batch campaigns, scheduled, etc.)
     */
    public function queue(string $slug, string $to, array $variables = [], ?string $priority = 'normal', ?\DateTimeInterface $sendAfter = null, ?int $scheduleId = null, ?int $automationRuleId = null): BdgsEmailOutbox
    {
        $rendered = $this->render($slug, $variables);

        $fromEmail = config('mail.from.address');
        $fromName = config('mail.from.name');

        return BdgsEmailOutbox::query()->create([
            'to_email' => $to,
            'to_name' => $variables['full_name'] ?? $variables['name'] ?? $variables['first_name'] ?? null,
            'from_email' => $fromEmail,
            'from_name' => $fromName,
            'subject' => $rendered ? $rendered['subject'] : $slug,
            'body_html' => $rendered ? $rendered['body_html'] : '',
            'body_text' => $rendered ? $rendered['body_text'] : null,
            'template_slug' => $slug,
            'variables' => $variables,
            'status' => 'pending',
            'priority' => $priority,
            'send_after' => $sendAfter,
            'schedule_id' => $scheduleId,
            'automation_rule_id' => $automationRuleId,
        ]);
    }

    /**
     * Process pending outbox items (called by scheduler/artisan command).
     */
    public function processOutbox(int $batchSize = 50): array
    {
        $items = BdgsEmailOutbox::query()
            ->pending()
            ->byPriority()
            ->limit($batchSize)
            ->get();

        $results = ['sent' => 0, 'failed' => 0];

        foreach ($items as $item) {
            try {
                Mail::html($item->body_html, function ($message) use ($item) {
                    $message->to($item->to_email)
                        ->subject($item->subject);

                    if ($item->from_email) {
                        $message->from($item->from_email, $item->from_name);
                    }
                });

                $item->markSent();

                BdgsEmailLog::query()->create([
                    'template_slug' => $item->template_slug,
                    'to_email' => $item->to_email,
                    'to_name' => $item->to_name,
                    'from_email' => $item->from_email,
                    'from_name' => $item->from_name,
                    'subject' => $item->subject,
                    'body_html' => $item->body_html,
                    'body_text' => $item->body_text,
                    'status' => 'sent',
                    'channel' => config('mail.default', 'smtp'),
                    'sent_at' => now(),
                    'attempts' => $item->attempts + 1,
                ]);

                $results['sent']++;
            } catch (\Throwable $e) {
                $item->markFailed($e->getMessage());
                $results['failed']++;

                Log::warning('Outbox send failed', [
                    'outbox_id' => $item->id,
                    'to' => $item->to_email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }

    public function notify(User $user, string $type, string $title, ?string $body = null, ?array $data = null): BdgsNotification
    {
        return BdgsNotification::query()->create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);
    }

    /** @return array<string, mixed> */
    private function prepareVariables(array $variables): array
    {
        $variables = array_merge([
            'site_name' => config('app.name'),
            'dashboard_url' => url('/dashboard'),
            'login_url' => url('/login'),
        ], $variables);

        if (isset($variables['name']) && ! isset($variables['first_name'])) {
            $variables['first_name'] = strtok((string) $variables['name'], ' ') ?: $variables['name'];
        }

        if (isset($variables['message']) && ! isset($variables['inquiry_message'])) {
            $variables['inquiry_message'] = $variables['message'];
        }

        if (isset($variables['need']) && ! isset($variables['inquiry_message'])) {
            $variables['inquiry_message'] = $variables['need'];
        }

        return $variables;
    }

    private function replaceTags(string $content, array $variables): string
    {
        foreach ($variables as $key => $value) {
            if (str_starts_with($key, '_')) {
                continue;
            }

            $replacement = (string) $value;

            $content = str_replace(
                ['{{'.$key.'}}', '{{ '.$key.' }}'],
                $replacement,
                $content
            );

            $content = str_replace('%'.$key.'%', $replacement, $content);
            $content = str_replace('%%'.$key.'%%', $replacement, $content);
            $content = str_replace('%%%'.$key.'%%%', $replacement, $content);
        }

        return $content;
    }
}
