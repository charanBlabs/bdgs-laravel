<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(SettingsService $settings): View
    {
        $cal = $settings->cal();

        return view('admin.settings.index', [
            'general' => $settings->group('general'),
            'seo' => $settings->group('seo'),
            'mail' => $settings->group('mail'),
            'social' => $settings->group('social'),
            'cal' => [
                'origin' => $cal['origin'],
                'link' => $cal['link'],
                'namespace' => $cal['namespace'],
                'config' => json_encode($cal['config'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
            ],
        ]);
    }

    public function update(Request $request, SettingsService $settings): RedirectResponse
    {
        $paste = trim((string) $request->input('cal_embed_paste', ''));
        $parsedFromPaste = [];

        if ($paste !== '') {
            $parsedFromPaste = $settings->applyCalEmbedPaste($paste);
        }

        foreach ($request->input('settings', []) as $group => $pairs) {
            if (! is_array($pairs)) {
                continue;
            }

            foreach ($pairs as $key => $value) {
                if ($group === 'cal' && $paste !== '' && array_key_exists($key, $parsedFromPaste)) {
                    continue;
                }

                if ($group === 'cal' && $key === 'config') {
                    $decoded = json_decode((string) $value, true);
                    if (! is_array($decoded)) {
                        return back()
                            ->withInput()
                            ->withErrors(['settings.cal.config' => 'Cal.com config must be valid JSON.']);
                    }
                    $settings->set('cal', 'config', $decoded, 'json');
                    continue;
                }

                $settings->set((string) $group, (string) $key, $value);
            }
        }

        $message = $paste !== '' && $parsedFromPaste !== []
            ? 'Settings saved. Cal.com embed values updated from pasted code.'
            : 'Settings saved.';

        return back()->with('status', $message);
    }
}
