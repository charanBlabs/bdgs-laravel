<?php

declare(strict_types=1);

namespace Database\Seeders\Support;

final class EmailTemplatePlaceholderNormalizer
{
    /** @var array<string, string> */
    private const VARIABLE_ALIASES = [
        'first_name' => 'first_name',
        'firstname' => 'first_name',
        'last_name' => 'last_name',
        'lastname' => 'last_name',
        'full_name' => 'full_name',
        'name' => 'name',
        'email' => 'email',
        'user_email' => 'email',
        'phone' => 'phone',
        'phone_number' => 'phone',
        'website_name' => 'site_name',
        'site_name' => 'site_name',
        'confirm_link' => 'verification_url',
        'verification_url' => 'verification_url',
        'reset_url' => 'reset_url',
        'dashboard_url' => 'dashboard_url',
        'login_url' => 'login_url',
        'password' => 'password',
        'need' => 'need',
        'message' => 'message',
        'inquiry_message' => 'inquiry_message',
        'reply' => 'reply',
        'balance' => 'balance',
        'order_id' => 'order_id',
        'tool_short_name' => 'solution_name',
        'tool_name' => 'solution_name',
        'tool_url' => 'solution_url',
        'tool_product_type' => 'solution_product_type',
        'service_name' => 'service_name',
        'referral_code' => 'referral_code',
        'refferal_code' => 'referral_code',
        'friend_name' => 'friend_name',
    ];

    /**
     * @return array{subject: string, body_html: string, variables: list<string>}
     */
    public static function normalize(string $subject, string $bodyHtml): array
    {
        $subject = self::normalizeContent($subject);
        $bodyHtml = self::normalizeContent($bodyHtml);

        return [
            'subject' => $subject,
            'body_html' => $bodyHtml,
            'variables' => self::extractVariables($subject, $bodyHtml),
        ];
    }

    public static function normalizeContent(string $content): string
    {
        $content = preg_replace_callback(
            '/%%%([a-zA-Z][a-zA-Z0-9_]*)%%+/',
            static fn (array $matches): string => '{{ '.self::canonicalVariable($matches[1]).' }}',
            $content
        ) ?? $content;

        return preg_replace_callback(
            '/%([a-zA-Z][a-zA-Z0-9_]*)%/',
            static fn (array $matches): string => '{{ '.self::canonicalVariable($matches[1]).' }}',
            $content
        ) ?? $content;
    }

    /** @return list<string> */
    public static function extractVariables(string ...$parts): array
    {
        $variables = [];

        foreach ($parts as $part) {
            if (preg_match_all('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', $part, $matches)) {
                foreach ($matches[1] as $variable) {
                    $variables[] = self::canonicalVariable($variable);
                }
            }
        }

        return array_values(array_unique($variables));
    }

    private static function canonicalVariable(string $raw): string
    {
        $key = strtolower($raw);

        return self::VARIABLE_ALIASES[$key] ?? $key;
    }
}
