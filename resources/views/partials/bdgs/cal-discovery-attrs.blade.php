@php($cal = $cal ?? app(\App\Services\SettingsService::class)->cal())
data-cal-link="{{ $cal['link'] }}"
data-cal-namespace="{{ $cal['namespace'] }}"
data-cal-config="{{ e(json_encode($cal['config'])) }}"
