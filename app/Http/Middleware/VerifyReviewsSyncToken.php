<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyReviewsSyncToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('services.reviews_sync.token', '');

        if ($expected === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Unauthorized: sync token not configured on server',
            ], 503);
        }

        $provided = $this->extractToken($request);

        if ($provided === '' || ! hash_equals($expected, $provided)) {
            return response()->json([
                'ok' => false,
                'message' => 'Unauthorized: Invalid sync token',
            ], 401);
        }

        return $next($request);
    }

    private function extractToken(Request $request): string
    {
        $authorization = trim((string) $request->header('Authorization', ''));
        if (str_starts_with(strtolower($authorization), 'bearer ')) {
            return trim(substr($authorization, 7));
        }

        $apiKey = trim((string) $request->header('X-Api-Key', ''));
        if ($apiKey !== '') {
            return $apiKey;
        }

        $bodyKey = $request->input('bd_api_key');
        if (is_string($bodyKey) && trim($bodyKey) !== '') {
            return trim($bodyKey);
        }

        return '';
    }
}
