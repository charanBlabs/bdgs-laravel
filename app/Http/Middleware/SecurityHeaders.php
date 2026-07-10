<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://www.youtube.com https://www.youtube-nocookie.com https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
            "img-src 'self' data: https:",
            "font-src 'self' data: https://fonts.gstatic.com",
            "connect-src 'self' https://www.youtube-nocookie.com https://www.youtube.com https://www.google.com https://googleads.g.doubleclick.net https://static.doubleclick.net https://i.ytimg.com https://s.ytimg.com",
            "frame-src 'self' https://www.youtube-nocookie.com https://www.youtube.com https://www.loom.com https://player.vimeo.com",
            "frame-ancestors 'none'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
