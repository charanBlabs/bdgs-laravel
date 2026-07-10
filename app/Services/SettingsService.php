<?php

namespace App\Services;

use App\Models\BdgsWebsiteSetting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public function get(string $group, string $key, mixed $default = null): mixed
    {
        $setting = BdgsWebsiteSetting::query()
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        if (! $setting) {
            return $default;
        }

        return $this->castValue($setting->value, $setting->type);
    }

    public function set(string $group, string $key, mixed $value, string $type = 'string'): void
    {
        BdgsWebsiteSetting::query()->updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => is_array($value) ? json_encode($value) : (string) $value, 'type' => $type]
        );

        Cache::forget("bdgs_setting.{$group}.{$key}");
    }

    /** @return array<string, mixed> */
    public function group(string $group): array
    {
        return BdgsWebsiteSetting::query()
            ->where('group', $group)
            ->get()
            ->mapWithKeys(fn ($s) => [$s->key => $this->castValue($s->value, $s->type)])
            ->all();
    }

    private function castValue(?string $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json' => json_decode($value ?? 'null', true),
            default => $value,
        };
    }
}
