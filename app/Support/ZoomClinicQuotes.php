<?php

namespace App\Support;

use App\Models\BdgsZoomClinic;

class ZoomClinicQuotes
{
    /**
     * Quotes for hero rotation: topic match(es) first, then fallbacks (deduped).
     *
     * @return list<string>
     */
    public static function quotesFor(?BdgsZoomClinic $clinic): array
    {
        $fromAdmin = self::hooksFromClinic($clinic);
        if ($fromAdmin !== []) {
            return array_slice($fromAdmin, 0, 4);
        }

        $topic = self::topicQuotesFor($clinic);
        $fallbacks = config('zoom-clinic.fallback_quotes', []);
        $merged = array_values(array_unique([...$topic, ...$fallbacks]));

        if ($merged === []) {
            return ['Bring your screen. Leave with the fix.'];
        }

        if (count($merged) === 1) {
            return $merged;
        }

        // Cap hero rotation at 4 lines so the card does not feel like a carousel.
        return array_slice($merged, 0, 4);
    }

    /**
     * Short Zoom Clinics hooks for the hero visual card only (never topic/admin).
     *
     * @return list<string>
     */
    public static function heroQuotesFor(): array
    {
        $quotes = config('zoom-clinic.hero_quotes', []);

        if ($quotes === []) {
            return ['Free Zoom Clinics — drop in live.'];
        }

        return array_slice(array_values($quotes), 0, 4);
    }

    /** Primary hook for card footers (one static line). */
    public static function primaryQuote(?BdgsZoomClinic $clinic): string
    {
        return self::quotesFor($clinic)[0];
    }

    public static function shouldShowParticipants(int $registrationCount): bool
    {
        return $registrationCount >= (int) config('zoom-clinic.min_registrations_to_show', 30);
    }

    /**
     * Per-clinic hooks from admin (one line per row). Highest priority.
     *
     * @return list<string>
     */
    public static function hooksFromClinic(?BdgsZoomClinic $clinic): array
    {
        if (! $clinic || blank($clinic->registration_hooks)) {
            return [];
        }

        $lines = preg_split('/\R/', (string) $clinic->registration_hooks) ?: [];

        return array_values(array_unique(array_filter(array_map(
            static fn (string $line): string => trim($line),
            $lines
        ))));
    }

    /**
     * @return list<string>
     */
    private static function topicQuotesFor(?BdgsZoomClinic $clinic): array
    {
        if (! $clinic) {
            return [];
        }

        $haystack = strtolower(implode(' ', array_filter([
            $clinic->title,
            $clinic->agenda,
            $clinic->description,
        ])));

        $matched = [];

        foreach (config('zoom-clinic.topic_quotes', []) as $entry) {
            foreach ($entry['keywords'] as $keyword) {
                if (str_contains($haystack, strtolower($keyword))) {
                    $matched[] = $entry['quote'];
                    break;
                }
            }
        }

        return array_values(array_unique($matched));
    }
}
