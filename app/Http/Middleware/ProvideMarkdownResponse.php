<?php

namespace App\Http\Middleware;

use App\Support\MarkdownMirror;
use Closure;
use Illuminate\Http\Request;
use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Component\HttpFoundation\Response;

class ProvideMarkdownResponse
{
    private const AI_BOTS = [
        'GPTBot',
        'ChatGPT-User',
        'ClaudeBot',
        'Claude-Web',
        'PerplexityBot',
        'OAI-SearchBot',
        'Google-Extended',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->wantsMarkdown($request)) {
            $mirror = MarkdownMirror::resolve($request->getPathInfo());

            if ($mirror !== null) {
                return response($mirror, 200, [
                    'Content-Type' => 'text/markdown; charset=UTF-8',
                ]);
            }
        }

        $wantsMarkdown = $this->wantsMarkdown($request);
        $response = $next($request);

        if (! $wantsMarkdown) {
            return $response;
        }

        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        $contentType = $response->headers->get('Content-Type', '');

        if (! str_contains($contentType, 'text/html')) {
            return $response;
        }

        $html = $response->getContent();

        if ($html === false || $html === '') {
            return $response;
        }

        $converter = new HtmlConverter([
            'strip_tags' => true,
            'remove_nodes' => 'script style nav header footer',
        ]);

        $markdown = $converter->convert($html);

        return response($markdown, 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
        ]);
    }

    private function wantsMarkdown(Request $request): bool
    {
        if (str_ends_with($request->getPathInfo(), '.md')) {
            return true;
        }

        $accept = $request->header('Accept', '');

        if (str_contains($accept, 'text/markdown')) {
            return true;
        }

        $ua = $request->userAgent() ?? '';

        foreach (self::AI_BOTS as $bot) {
            if (stripos($ua, $bot) !== false) {
                return true;
            }
        }

        return false;
    }
}
