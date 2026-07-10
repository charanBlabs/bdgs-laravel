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
<p><span style="font-size: 22px; padding: 15px 0px; font-weight: normal; font-family: arial; color: #0d83dd;">Reset Your Password</span></p>
<p><span style="font-size: 14px;">Hello <strong>{{ first_name }}</strong>,</span></p>
<p><span style="font-size: 14px;">We received a request to reset the password for your {{ site_name }} account. Click the link below to choose a new password:</span></p>
<p><a href="{{ reset_url }}" style="font-weight: bold; font-size: 15px;">{{ reset_url }}</a></p>
<p><span style="font-size: 14px;">If you did not request a password reset, you can safely ignore this email.</span></p>
<p><span style="font-size: 14px;">Thank you,</span></p>
<p><span style="font-size: 14px;">The {{ site_name }} Team.</span></p>
HTML,
                'variables' => ['first_name', 'site_name', 'reset_url'],
            ],
            'inquiry-reply' => [
                'subject' => 'Reply to your inquiry — {{ site_name }}',
                'body_html' => <<<'HTML'
<p dir="ltr" style="line-height:1.38;text-align: justify;margin-top:0pt;margin-bottom:8pt;"><span style="font-size:16pt;font-family:Calibri,sans-serif;font-weight:700;">Thank You for Choosing BD Growth Suite</span></p>
<p dir="ltr" style="line-height:1.38;text-align: justify;margin-top:0pt;margin-bottom:8pt;"><span style="font-size:12pt;font-family:Calibri,sans-serif;">Hello <strong>{{ first_name }}</strong>,</span></p>
<p dir="ltr" style="line-height:1.38;text-align: justify;margin-top:0pt;margin-bottom:8pt;"><span style="font-size:12pt;font-family:Calibri,sans-serif;">{{ reply }}</span></p>
<p dir="ltr" style="line-height:1.38;text-align: justify;margin-top:0pt;margin-bottom:8pt;"><span style="font-size:12pt;font-family:Calibri,sans-serif;">Regards,</span></p>
<p dir="ltr" style="line-height:1.38;text-align: justify;margin-top:0pt;margin-bottom:8pt;"><span style="font-size:12pt;font-family:Calibri,sans-serif;">The BD Growth Suite Team.</span></p>
HTML,
                'variables' => ['first_name', 'reply', 'site_name'],
            ],
            default => null,
        };
    }
}
