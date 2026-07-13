<?php

namespace App\Services;

use App\Models\BdgsWebsiteSetting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public function get(string $group, string $key, mixed $default = null): mixed
    {
        $cacheKey = "bdgs_setting.{$group}.{$key}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $setting = BdgsWebsiteSetting::query()
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        if (! $setting) {
            return $default;
        }

        $value = $this->castValue($setting->value, $setting->type);
        Cache::put($cacheKey, $value, 3600);

        return $value;
    }

    public function set(string $group, string $key, mixed $value, string $type = 'string'): void
    {
        BdgsWebsiteSetting::query()->updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => is_array($value) ? json_encode($value) : (string) $value, 'type' => $type]
        );

        Cache::forget("bdgs_setting.{$group}.{$key}");

        if ($group === 'cal') {
            Cache::forget('bdgs_setting.cal.booking');
        }
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

    /**
     * Cal.com Discovery Call booking settings (DB overrides config defaults).
     *
     * @return array{origin: string, link: string, namespace: string, config: array<string, mixed>}
     */
    public function cal(): array
    {
        return Cache::remember('bdgs_setting.cal.booking', 3600, function () {
            $stored = $this->group('cal');
            $defaults = config('services.cal', []);

            $config = $stored['config'] ?? null;
            if (is_string($config)) {
                $config = json_decode($config, true);
            }
            if (! is_array($config) || $config === []) {
                $config = $defaults['config'] ?? ['layout' => 'month_view', 'useSlotsViewOnSmallScreen' => 'true'];
            }

            return [
                'origin' => filled($stored['origin'] ?? null) ? (string) $stored['origin'] : (string) ($defaults['origin'] ?? 'https://app.cal.com'),
                'link' => filled($stored['link'] ?? null) ? (string) $stored['link'] : (string) ($defaults['link'] ?? ''),
                'namespace' => filled($stored['namespace'] ?? null) ? (string) $stored['namespace'] : (string) ($defaults['namespace'] ?? '30min'),
                'config' => $config,
            ];
        });
    }

    /**
     * Parse a Cal.com element-click embed snippet and persist discovered values.
     *
     * @return array{link?: string, namespace?: string, origin?: string, config?: array<string, mixed>}
     */
    public function applyCalEmbedPaste(string $paste): array
    {
        $parsed = [];

        if (preg_match('/data-cal-link=["\']([^"\']+)["\']/', $paste, $m)
            || preg_match('/\bcalLink\s*:\s*["\']([^"\']+)["\']/', $paste, $m)) {
            $parsed['link'] = trim($m[1]);
        }

        if (preg_match('/data-cal-namespace=["\']([^"\']+)["\']/', $paste, $m)
            || preg_match('/Cal\s*\(\s*["\']init["\']\s*,\s*["\']([^"\']+)["\']/', $paste, $m)) {
            $parsed['namespace'] = trim($m[1]);
        }

        if (preg_match('/origin\s*:\s*["\']([^"\']+)["\']/', $paste, $m)
            || preg_match('/data-cal-origin=["\']([^"\']+)["\']/', $paste, $m)) {
            $parsed['origin'] = trim($m[1]);
        }

        if (preg_match('/data-cal-config\s*=\s*\'(\{.*?\})\'/s', $paste, $m)
            || preg_match('/data-cal-config\s*=\s*"(\{.*?\})"/s', $paste, $m)) {
            $decoded = json_decode($m[1], true);
            if (is_array($decoded)) {
                $parsed['config'] = $decoded;
            }
        }

        if (! isset($parsed['config']) && preg_match('/\(\s*["\']ui["\']\s*,\s*(\{.*?\})\s*\)/s', $paste, $m)) {
            $decoded = json_decode($m[1], true);
            if (is_array($decoded)) {
                $parsed['config'] = array_filter([
                    'layout' => $decoded['layout'] ?? null,
                    'useSlotsViewOnSmallScreen' => $decoded['useSlotsViewOnSmallScreen'] ?? 'true',
                    'theme' => $decoded['theme'] ?? null,
                ], fn ($v) => $v !== null);
            }
        }

        foreach ($parsed as $key => $value) {
            $this->set('cal', $key, $value, is_array($value) ? 'json' : 'string');
        }

        return $parsed;
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
