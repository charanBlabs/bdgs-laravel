<?php

namespace App\Support;

class MarkdownMirror
{
    /**
     * Resolve a request path to hand-maintained markdown content.
     *
     * Mirrors live at /{slug}.md (home: /index.md). Source files live in
     * content/md/ as plain .md — not Blade templates.
     */
    public static function resolve(string $pathInfo): ?string
    {
        $relative = self::relativePath($pathInfo);

        if ($relative === null) {
            return null;
        }

        $file = base_path('content/md/'.$relative);

        if (! is_file($file)) {
            return null;
        }

        $contents = file_get_contents($file);

        return $contents === false ? null : $contents;
    }

    public static function exists(string $pathInfo): bool
    {
        return self::resolve($pathInfo) !== null;
    }

    private static function relativePath(string $pathInfo): ?string
    {
        $path = trim($pathInfo, '/');

        if ($path === '' || $path === 'index.md') {
            return 'index.md';
        }

        if (str_ends_with($path, '/index.md')) {
            $path = substr($path, 0, -strlen('/index.md')).'.md';
        } elseif (! str_ends_with($path, '.md')) {
            return null;
        }

        if (! preg_match('#^[a-z0-9\-/]+\.md$#', $path)) {
            return null;
        }

        return $path;
    }
}
