<?php

namespace Database\Seeders;

use App\Models\BdgsWebsiteSetting;
use Illuminate\Database\Seeder;

class WebsiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => 'BD Growth Suite', 'type' => 'string'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Brilliant Directories Growth Partner', 'type' => 'string'],
            ['group' => 'seo', 'key' => 'default_meta_title', 'value' => 'BD Growth Suite — Brilliant Directories Services', 'type' => 'string'],
            ['group' => 'seo', 'key' => 'default_meta_description', 'value' => 'Setup, customization, tools, and growth services for Brilliant Directories websites.', 'type' => 'text'],
            ['group' => 'mail', 'key' => 'admin_notification_email', 'value' => 'admin@bdgrowthsuite.com', 'type' => 'string'],
            ['group' => 'social', 'key' => 'twitter_url', 'value' => '', 'type' => 'string'],
            ['group' => 'social', 'key' => 'linkedin_url', 'value' => '', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            BdgsWebsiteSetting::query()->updateOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                $setting
            );
        }
    }
}
