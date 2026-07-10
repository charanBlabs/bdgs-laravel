<?php

declare(strict_types=1);

namespace Database\Seeders\Support;

/**
 * Parse Brilliant Directories email_templates SQL dump into structured rows.
 */
final class OldEmailTemplateSqlParser
{
    public static function parseFile(string $path, ?array $onlyNames = null): array
    {
        $onlyNames ??= LegacyEmailTemplateMapper::legacySourceNames();
        $onlyLookup = array_fill_keys($onlyNames, true);
        $sql = file_get_contents($path);
        if ($sql === false) {
            throw new RuntimeException("Unable to read SQL file: {$path}");
        }

        $rows = [];
        $offset = 0;

        while (($start = strpos($sql, '(', $offset)) !== false) {
            if (! preg_match('/^\((\d+),\s*\'/', substr($sql, $start, 20))) {
                $offset = $start + 1;
                continue;
            }

            $parsed = self::parseRowAt($sql, $start);
            if ($parsed === null) {
                $offset = $start + 1;
                continue;
            }

            [$row, $endPos] = $parsed;
            if (isset($onlyLookup[$row['email_name']])) {
                $rows[$row['email_name']] = $row;
            }

            $offset = $endPos;
        }

        return $rows;
    }

    /** @return array{0: array<string, mixed>, 1: int}|null */
    private static function parseRowAt(string $sql, int $start): ?array
    {
        $pos = $start + 1;
        $fields = [];

        for ($i = 0; $i < 16; $i++) {
            $field = self::readField($sql, $pos);
            if ($field === null) {
                return null;
            }
            [$value, $pos] = $field;
            $fields[] = $value;
        }

        if ($pos >= strlen($sql) || $sql[$pos] !== ')') {
            return null;
        }

        return [[
            'email_id' => (int) $fields[0],
            'email_name' => (string) $fields[1],
            'email_type' => (string) $fields[2],
            'email_subject' => (string) $fields[3],
            'email_body' => (string) $fields[4],
            'date_created' => (string) $fields[5],
            'triggers' => (string) $fields[6],
            'website' => (int) $fields[7],
            'email_from' => (string) $fields[8],
            'priority' => (int) $fields[9],
            'signature' => (int) $fields[10],
            'category_id' => (int) $fields[11],
            'notemplate' => (int) $fields[12],
            'content_type' => (string) $fields[13],
            'revision_timestamp' => (string) $fields[14],
            'unsubscribe_link' => (int) $fields[15],
        ], $pos + 1];
    }

    /** @return array{0: string|int, 1: int}|null */
    private static function readField(string $sql, int &$pos): ?array
    {
        self::skipWhitespace($sql, $pos);
        if ($pos >= strlen($sql)) {
            return null;
        }

        $char = $sql[$pos];

        if ($char === "'") {
            return self::readQuotedString($sql, $pos);
        }

        if (ctype_digit($char) || ($char === '-' && isset($sql[$pos + 1]) && ctype_digit($sql[$pos + 1]))) {
            return self::readNumber($sql, $pos);
        }

        return null;
    }

    /** @return array{0: string, 1: int} */
    private static function readQuotedString(string $sql, int &$pos): array
    {
        $pos++; // opening quote
        $value = '';

        while ($pos < strlen($sql)) {
            $char = $sql[$pos];

            if ($char === '\\' && $pos + 1 < strlen($sql)) {
                $value .= $sql[$pos + 1];
                $pos += 2;
                continue;
            }

            if ($char === "'") {
                $pos++;
                if ($pos < strlen($sql) && $sql[$pos] === "'") {
                    $value .= "'";
                    $pos++;
                    continue;
                }

                self::skipWhitespace($sql, $pos);
                if ($pos < strlen($sql) && $sql[$pos] === ',') {
                    $pos++;
                }

                return [$value, $pos];
            }

            $value .= $char;
            $pos++;
        }

        return [$value, $pos];
    }

    /** @return array{0: int, 1: int} */
    private static function readNumber(string $sql, int &$pos): array
    {
        $start = $pos;
        if ($sql[$pos] === '-') {
            $pos++;
        }

        while ($pos < strlen($sql) && ctype_digit($sql[$pos])) {
            $pos++;
        }

        $number = (int) substr($sql, $start, $pos - $start);
        self::skipWhitespace($sql, $pos);

        if ($pos < strlen($sql) && $sql[$pos] === ',') {
            $pos++;
        }

        return [$number, $pos];
    }

    private static function skipWhitespace(string $sql, int &$pos): void
    {
        while ($pos < strlen($sql) && ctype_space($sql[$pos])) {
            $pos++;
        }
    }
}
