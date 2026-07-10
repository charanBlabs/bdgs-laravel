<?php

namespace Database\Seeders\Support;

/**
 * Parses users_portfolio_groups INSERT rows from solutions_data.sql.
 */
class SolutionSqlParser
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function parseFile(string $path): array
    {
        $content = file_get_contents($path);
        if ($content === false) {
            throw new \RuntimeException("Cannot read SQL file: {$path}");
        }

        $cut = strpos($content, '-- Triggers');
        if ($cut !== false) {
            $content = substr($content, 0, $cut);
        }

        if (! preg_match_all('/\((\d+),\s*101,\s*/', $content, $starts, PREG_OFFSET_CAPTURE)) {
            throw new \RuntimeException('No solution rows found in SQL file.');
        }

        $parsed = [];
        $offsets = $starts[0];

        for ($i = 0; $i < count($offsets); $i++) {
            $start = $offsets[$i][1];
            $end = isset($offsets[$i + 1]) ? $offsets[$i + 1][1] : strlen($content);
            $segment = substr($content, $start, $end - $start);
            $segment = rtrim($segment, ", \r\n\t");

            if (! str_starts_with($segment, '(')) {
                continue;
            }

            if (str_ends_with($segment, ')')) {
                $row = substr($segment, 1, -1);
            } else {
                $row = substr($segment, 1);
            }

            $fields = $this->parseRowFields($row);
            if (count($fields) < 55) {
                continue;
            }

            $filename = $this->clean($fields[10]);
            $slug = $filename;
            if ($slug && str_starts_with($slug, 'solutions/')) {
                $slug = substr($slug, strlen('solutions/'));
            }

            $parsed[] = [
                'old_group_id' => (int) $fields[0],
                'title' => $this->clean($fields[2]),
                'content' => $this->clean($fields[3]),
                'slug' => rawurldecode((string) $slug),
                'group_order' => (int) ($fields[8] ?: 0),
                'status' => (int) $fields[11] === 1 ? 'published' : 'draft',
                'old_categories' => $this->clean($fields[18]),
                'published_at' => $this->clean($fields[19]) ?: null,
                'excerpt' => $this->clean($fields[39]) ?: null,
                'pricing_type' => $this->normalizePricingType($this->clean($fields[40])),
                'product_type' => $this->clean($fields[41]) ?: null,
                'delivery_time' => $this->normalizeDeliveryTime($this->clean($fields[45])),
                'warranty' => $this->clean($fields[46]) ?: null,
                'demo_video_url' => $this->clean($fields[48]) ?: null,
                'short_title' => $this->clean($fields[49]) ?: null,
                'short_title_results' => $this->clean($fields[50]) ?: null,
                'implementation_type' => $this->normalizeImplementation($this->clean($fields[51])),
                'subscription_type' => $this->clean($fields[52]) ?: null,
                'price' => $this->decimal($fields[28]),
                'annual_price' => $this->decimal($fields[29]),
                'commitment_price' => $this->decimal($fields[53]),
                'starts_from_type' => $this->clean($fields[54]) ?: null,
                'pinned' => (int) ($fields[33] ?? 0) === 1,
            ];
        }

        return $parsed;
    }

    /** @return array<int, string> */
    private function splitRows(string $block): array
    {
        $rows = [];
        $depth = 0;
        $inString = false;
        $escape = false;
        $current = '';

        $len = strlen($block);
        for ($i = 0; $i < $len; $i++) {
            $ch = $block[$i];

            if ($escape) {
                $current .= $ch;
                $escape = false;

                continue;
            }

            if ($ch === '\\' && $inString) {
                $current .= $ch;
                $escape = true;

                continue;
            }

            if ($ch === "'") {
                $inString = ! $inString;
                $current .= $ch;

                continue;
            }

            if (! $inString) {
                if ($ch === '(') {
                    if ($depth === 0) {
                        $current = '';
                    } else {
                        $current .= $ch;
                    }
                    $depth++;

                    continue;
                }

                if ($ch === ')') {
                    $depth--;
                    if ($depth === 0) {
                        $rows[] = $current;
                        $current = '';

                        continue;
                    }
                    $current .= $ch;

                    continue;
                }
            }

            if ($depth > 0) {
                $current .= $ch;
            }
        }

        return $rows;
    }

    /** @return array<int, string|null> */
    private function parseRowFields(string $row): array
    {
        $fields = [];
        $current = '';
        $inString = false;
        $escape = false;
        $len = strlen($row);

        for ($i = 0; $i < $len; $i++) {
            $ch = $row[$i];

            if ($escape) {
                $current .= $ch;
                $escape = false;

                continue;
            }

            if ($ch === '\\' && $inString) {
                $current .= $ch;
                $escape = true;

                continue;
            }

            if ($ch === "'") {
                $inString = ! $inString;
                $current .= $ch;

                continue;
            }

            if ($ch === ',' && ! $inString) {
                $fields[] = trim($current);
                $current = '';

                continue;
            }

            $current .= $ch;
        }

        if ($current !== '') {
            $fields[] = trim($current);
        }

        return $fields;
    }

    private function clean(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        if ($value === '' || strtoupper($value) === 'NULL') {
            return null;
        }

        if (strlen($value) >= 2 && $value[0] === "'" && $value[strlen($value) - 1] === "'") {
            $value = substr($value, 1, -1);
            $value = str_replace(["\\'", '\\"', '\\\\'], ["'", '"', '\\'], $value);
        }

        return $value === ' ' ? null : $value;
    }

    private function decimal(?string $value): ?float
    {
        $clean = $this->clean($value);
        if ($clean === null || $clean === '') {
            return null;
        }

        return (float) $clean;
    }

    private function normalizePricingType(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $value = strtolower(str_replace(' ', '_', $value));

        return match ($value) {
            'fixed_price', 'fixed price' => 'fixed_price',
            'starts_from', 'starts from' => 'starts_from',
            'subscription' => 'subscription',
            'ask_for_quote', 'ask for quote' => 'ask_for_quote',
            'free' => 'free',
            default => $value,
        };
    }

    private function normalizeImplementation(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $value = strtolower(trim($value));

        return match ($value) {
            'quick' => 'quick',
            'semi_custom', 'semi custom' => 'semi_custom',
            'readytoimplement', 'ready_to_implement', 'ready to implement' => 'readytoimplement',
            'fully_custom', 'fully custom' => 'fully_custom',
            'quick_service' => 'quick',
            default => str_replace(' ', '_', $value),
        };
    }

    private function normalizeDeliveryTime(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $value = strtolower(trim($value));

        return match (true) {
            str_contains($value, '3_day') || $value === '3 days' => '3_days',
            str_contains($value, '1_week') || $value === '1 week' || str_contains($value, '< 1 week') => '1_week',
            str_contains($value, '2_week') || str_contains($value, '12_week') => '2_weeks',
            default => str_replace(' ', '_', $value),
        };
    }
}
