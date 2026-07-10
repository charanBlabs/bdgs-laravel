<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        if (app()->environment('staging')) {
            return response(
                "# Staging — do not index\nUser-agent: *\nDisallow: /\n",
                200,
                ['Content-Type' => 'text/plain; charset=UTF-8']
            );
        }

        $sitemap = rtrim(config('app.url'), '/').'/sitemap.xml';

        $body = <<<TXT
# robots.txt — BD Growth Suite
# Public marketing pages: allow. Utility pages: disallow.

User-agent: *
Allow: /
Disallow: /login
Disallow: /admin
Disallow: /dashboard
Disallow: /thank-you
Disallow: /*?*add-to-cart

# AI / LLM crawlers — explicitly allowed (we want to be read and quoted).
User-agent: GPTBot
Allow: /

User-agent: OAI-SearchBot
Allow: /

User-agent: ChatGPT-User
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: Claude-Web
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: Google-Extended
Allow: /

Sitemap: {$sitemap}

TXT;

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
