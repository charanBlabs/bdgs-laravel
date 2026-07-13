<?php

namespace App\Support;

use App\Models\BdgsZoomClinic;
use Carbon\CarbonInterface;

class ZoomClinicTimes
{
    public const PAGE_TZ = 'America/New_York';

    public static function formatRange(CarbonInterface $start, CarbonInterface $end, bool $withDate = true): string
    {
        $startNy = $start->copy()->timezone(self::PAGE_TZ);
        $endNy = $end->copy()->timezone(self::PAGE_TZ);
        $range = $startNy->format('g:i A').' – '.$endNy->format('g:i A').' '.$startNy->format('T');

        if ($withDate) {
            return $startNy->format('D, M j').' · '.$range.' - New York';
        }

        return $range.' - New York';
    }

    public static function clinicRange(BdgsZoomClinic $clinic, bool $withDate = true): string
    {
        return self::formatRange($clinic->session_starts_at, $clinic->session_ends_at, $withDate);
    }

    public static function inPageTz(CarbonInterface $instant): \Carbon\Carbon
    {
        return $instant->copy()->timezone(self::PAGE_TZ);
    }

    public static function canonicalScheduleLine(?BdgsZoomClinic $clinic = null): string
    {
        if ($clinic) {
            return self::formatRange($clinic->session_starts_at, $clinic->session_ends_at, false);
        }

        $ist = 'Asia/Kolkata';
        $cursor = now($ist)->startOfDay();
        while (! $cursor->isTuesday() && ! $cursor->isThursday()) {
            $cursor->addDay();
        }
        $start = $cursor->copy()->setTime(18, 30);
        $end = $cursor->copy()->setTime(19, 30);

        return self::formatRange($start, $end, false);
    }
}
