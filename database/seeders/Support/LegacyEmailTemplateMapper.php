<?php

declare(strict_types=1);

namespace Database\Seeders\Support;

final class LegacyEmailTemplateMapper
{
    /**
     * Maps Laravel template slugs to legacy BD email_name keys.
     *
     * @return array<string, array{name: string, source: string|null, is_active: bool, category: string}>
     */
    public static function definitions(): array
    {
        return [
            // Auth & account — active now
            'welcome' => [
                'name' => 'Welcome Email (Registration)',
                'source' => 'registration',
                'is_active' => true,
                'category' => 'auth',
            ],
            'email-verification' => [
                'name' => 'Email Verification',
                'source' => 'welcome-basic',
                'is_active' => true,
                'category' => 'auth',
            ],
            'password-reset' => [
                'name' => 'Password Reset',
                'source' => null,
                'is_active' => true,
                'category' => 'auth',
            ],
            'registration-admin' => [
                'name' => 'Registration (Admin Copy)',
                'source' => 'registration-admin',
                'is_active' => true,
                'category' => 'auth',
            ],

            // Inquiry — active now
            'inquiry-confirmation' => [
                'name' => 'Inquiry Confirmation',
                'source' => 'contact-us',
                'is_active' => true,
                'category' => 'inquiry',
            ],
            'inquiry-received' => [
                'name' => 'New Inquiry (Admin)',
                'source' => 'contact-us-admin',
                'is_active' => true,
                'category' => 'inquiry',
            ],
            'inquiry-reply' => [
                'name' => 'Inquiry Reply',
                'source' => null,
                'is_active' => true,
                'category' => 'inquiry',
            ],

            // Solutions (legacy tools) — seeded for future checkout flows
            'solution-subscription-order' => [
                'name' => 'Solution Subscription Order',
                'source' => 'subscription_tools',
                'is_active' => false,
                'category' => 'solutions',
            ],
            'solution-fixed-price-order' => [
                'name' => 'Solution Fixed Price Order',
                'source' => 'fixed_price_tools',
                'is_active' => false,
                'category' => 'solutions',
            ],
            'solution-free-order' => [
                'name' => 'Solution Free Order',
                'source' => 'free_tools',
                'is_active' => false,
                'category' => 'solutions',
            ],
            'solution-quote-request' => [
                'name' => 'Solution Quote Request',
                'source' => 'ask_for_quote_tools',
                'is_active' => false,
                'category' => 'solutions',
            ],
            'solution-starts-from-full' => [
                'name' => 'Solution Meeting Request (Full)',
                'source' => 'starts_from_full_tools',
                'is_active' => false,
                'category' => 'solutions',
            ],
            'solution-starts-from-deposit' => [
                'name' => 'Solution Meeting Request (Deposit)',
                'source' => 'starts_from_deposit_tools',
                'is_active' => false,
                'category' => 'solutions',
            ],
            'solution-quick-order' => [
                'name' => 'Solution Quick Order',
                'source' => 'quick_services_tools',
                'is_active' => false,
                'category' => 'solutions',
            ],

            // Themes — seeded for future theme checkout
            'theme-order-received' => [
                'name' => 'Theme Order Received (Client)',
                'source' => 'theme_order_received_client',
                'is_active' => false,
                'category' => 'themes',
            ],
            'theme-order-admin' => [
                'name' => 'Theme Order Manual Invoice (Admin)',
                'source' => 'theme_order_manual_admin',
                'is_active' => false,
                'category' => 'themes',
            ],

            // Services — seeded for future service checkout
            'service-setup' => [
                'name' => 'BD Setup Service Order',
                'source' => 'setup',
                'is_active' => false,
                'category' => 'services',
            ],
            'service-setup-admin' => [
                'name' => 'BD Setup Service Order (Admin)',
                'source' => 'setup-admin',
                'is_active' => false,
                'category' => 'services',
            ],
            'service-maintenance' => [
                'name' => 'BD Maintenance Service Order',
                'source' => 'maintenance',
                'is_active' => false,
                'category' => 'services',
            ],
            'service-maintenance-admin' => [
                'name' => 'BD Maintenance Service Order (Admin)',
                'source' => 'maintenance-admin',
                'is_active' => false,
                'category' => 'services',
            ],
            'service-customization' => [
                'name' => 'BD Customization Service Order',
                'source' => 'customization',
                'is_active' => false,
                'category' => 'services',
            ],
            'service-customization-admin' => [
                'name' => 'BD Customization Service Order (Admin)',
                'source' => 'customization-admin',
                'is_active' => false,
                'category' => 'services',
            ],
            'service-consultation' => [
                'name' => 'BD Consultation Service Order',
                'source' => 'consultation',
                'is_active' => false,
                'category' => 'services',
            ],
            'service-consultation-admin' => [
                'name' => 'BD Consultation Service Order (Admin)',
                'source' => 'consultation-admin',
                'is_active' => false,
                'category' => 'services',
            ],
            'service-request' => [
                'name' => 'Service Request Processed',
                'source' => 'service-request',
                'is_active' => false,
                'category' => 'services',
            ],
            'service-request-admin' => [
                'name' => 'Service Request Processed (Admin)',
                'source' => 'service-request-admin',
                'is_active' => false,
                'category' => 'services',
            ],

            // Wallet & payments
            'wallet-credits-added' => [
                'name' => 'Wallet Credits Added',
                'source' => 'add-credits',
                'is_active' => false,
                'category' => 'billing',
            ],
            'payment-updated' => [
                'name' => 'Payment Updated (Client)',
                'source' => 'payment-email',
                'is_active' => false,
                'category' => 'billing',
            ],
            'payment-updated-admin' => [
                'name' => 'Payment Updated (Admin)',
                'source' => 'payment-email-admin',
                'is_active' => false,
                'category' => 'billing',
            ],

            // Hire developers inquiry
            'hire-developers-inquiry' => [
                'name' => 'Hire Developers Inquiry',
                'source' => 'hire-developers',
                'is_active' => false,
                'category' => 'inquiry',
            ],
            'hire-developers-inquiry-admin' => [
                'name' => 'Hire Developers Inquiry (Admin)',
                'source' => 'hire-developers-admin',
                'is_active' => false,
                'category' => 'inquiry',
            ],
        ];
    }

    /** @return list<string> */
    public static function legacySourceNames(): array
    {
        return array_values(array_filter(array_map(
            static fn (array $definition): ?string => $definition['source'],
            self::definitions()
        )));
    }

    public static function builtInTemplate(string $slug): ?array
    {
        return match ($slug) {
            'password-reset' => [
                'subject' => 'Reset your password — {{ site_name }}',
                'body_html' => <<<'HTML'
<p style="margin:0 0 10px;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#E74D56;">Password reset</p>
<h1 style="margin:0 0 14px;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:24px;line-height:1.3;color:#1A1A2E;">Reset your password</h1>
<p style="margin:0 0 14px;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:15px;line-height:1.6;color:#6B6B80;">Hello <strong style="color:#2C2C3A;">{{ first_name }}</strong>,</p>
<p style="margin:0 0 18px;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:15px;line-height:1.6;color:#6B6B80;">We received a request to reset the password for your {{ site_name }} account. Click the button below to choose a new password.</p>
<p style="margin:0 0 18px;text-align:center;">
  <a href="{{ reset_url }}" style="display:inline-block;background:linear-gradient(135deg,#E74D56 0%,#95256E 100%);color:#ffffff;text-decoration:none;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:15px;font-weight:700;padding:13px 26px;border-radius:8px;">Reset password</a>
</p>
<p style="margin:0 0 12px;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:13px;line-height:1.55;color:#6B6B80;">Or copy this link:<br><a href="{{ reset_url }}" style="color:#E74D56;word-break:break-all;">{{ reset_url }}</a></p>
<p style="margin:0;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:13px;color:#6B6B80;">If you did not request this, you can safely ignore this email.</p>
HTML,
                'variables' => ['first_name', 'site_name', 'reset_url'],
            ],
            'inquiry-reply' => [
                'subject' => 'Reply to your inquiry — {{ site_name }}',
                'body_html' => <<<'HTML'
<p style="margin:0 0 10px;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#E74D56;">Inquiry reply</p>
<h1 style="margin:0 0 14px;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:24px;line-height:1.3;color:#1A1A2E;">Thanks for reaching out</h1>
<p style="margin:0 0 14px;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:15px;line-height:1.6;color:#6B6B80;">Hello <strong style="color:#2C2C3A;">{{ first_name }}</strong>,</p>
<div style="margin:0 0 18px;padding:16px 18px;background:#FDF0F0;border:1px solid #F5D0D3;border-radius:10px;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:15px;line-height:1.6;color:#2C2C3A;">{{ reply }}</div>
<p style="margin:0;font-family:'DM Sans', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;font-size:15px;color:#6B6B80;">Regards,<br><strong style="color:#1A1A2E;">The BD Growth Suite Team</strong></p>
HTML,
                'variables' => ['first_name', 'reply', 'site_name'],
            ],
            default => null,
        };
    }
}
