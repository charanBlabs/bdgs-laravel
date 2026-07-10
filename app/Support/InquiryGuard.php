<?php

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class InquiryGuard
{
    public const MIN_FILL_SECONDS = 3;

    public const MAX_GUARD_AGE_SECONDS = 7200;

    public static function honeypotTripped(Request $request): bool
    {
        foreach (['company_website', 'fax_number'] as $field) {
            $value = $request->input($field);
            if (is_string($value) && trim($value) !== '') {
                return true;
            }
        }

        return false;
    }

    public static function issueFormGuard(): string
    {
        return Crypt::encryptString((string) now()->timestamp);
    }

    public static function verifyFormGuard(?string $guard): bool
    {
        if (! is_string($guard) || trim($guard) === '') {
            return false;
        }

        try {
            $issuedAt = (int) Crypt::decryptString($guard);
        } catch (DecryptException) {
            return false;
        }

        if ($issuedAt <= 0) {
            return false;
        }

        $age = now()->timestamp - $issuedAt;

        return $age >= self::MIN_FILL_SECONDS && $age <= self::MAX_GUARD_AGE_SECONDS;
    }

    /**
     * @return array<string, mixed>
     */
    public static function fakeSuccessResponse(): array
    {
        return [
            'ok' => true,
            'message' => 'Inquiry received. The BD Growth Suite team will follow up by email.',
        ];
    }
}
