<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyInquiryAgentToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('services.inquiry_agent.token', '');

        if ($expected === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Inquiry API is not configured on this server.',
            ], 503);
        }

        $provided = $this->extractToken($request);

        if ($provided === '' || ! hash_equals($expected, $provided)) {
            return response()->json([
                'ok' => false,
                'message' => 'Unauthorized: invalid or missing agent token.',
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

        $agentKey = trim((string) $request->header('X-BDGS-Agent-Key', ''));
        if ($agentKey !== '') {
            return $agentKey;
        }

        return '';
    }
}
