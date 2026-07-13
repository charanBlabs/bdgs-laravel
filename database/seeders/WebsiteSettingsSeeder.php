<?php

namespace Database\Seeders;

use App\Services\SettingsService;
use Illuminate\Database\Seeder;

class WebsiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = app(SettingsService::class);

        $rows = [
            ['general', 'site_name', 'BD Growth Suite', 'string'],
            ['general', 'site_tagline', 'Brilliant Directories Growth Partner', 'string'],
            ['seo', 'default_meta_title', 'BD Growth Suite — Brilliant Directories Services', 'string'],
            ['seo', 'default_meta_description', 'Setup, customization, tools, and growth services for Brilliant Directories websites.', 'text'],
            ['mail', 'admin_notification_email', 'admin@bdgrowthsuite.com', 'string'],
            ['social', 'twitter_url', '', 'string'],
            ['social', 'linkedin_url', '', 'string'],
            ['cal', 'origin', 'https://app.cal.com', 'string'],
            ['cal', 'link', 'charan-tej-vattikuti-unbo7v/30min', 'string'],
            ['cal', 'namespace', '30min', 'string'],
            ['cal', 'config', [
                'layout' => 'month_view',
                'useSlotsViewOnSmallScreen' => 'true',
            ], 'json'],
        ];

        foreach ($rows as [$group, $key, $value, $type]) {
            $settings->set($group, $key, $value, $type);
        }
    }
}
