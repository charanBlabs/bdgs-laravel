<?php

namespace App\Console\Commands;

use App\Models\BdgsDataPost;
use Illuminate\Console\Command;

class RewriteImagekitContentUrls extends Command
{
    protected $signature = 'content:rewrite-imagekit-urls {--dry-run : Show count without saving}';

    protected $description = 'Replace ImageKit CDN image URLs in post body HTML with local /images/... paths';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $pattern = '#https://ik\.imagekit\.io/h1pfsvzlsf/bdgrowthsuite/images/([^\"\'\\s>]+)#i';
        $updated = 0;

        BdgsDataPost::query()->orderBy('id')->chunkById(50, function ($posts) use ($pattern, $dry, &$updated) {
            foreach ($posts as $post) {
                $content = (string) $post->content;
                if ($content === '' || ! str_contains($content, 'ik.imagekit.io')) {
                    continue;
                }

                $new = preg_replace_callback($pattern, function (array $m) {
                    $rel = explode('?', $m[1], 2)[0];
                    $rel = ltrim($rel, '/');

                    if (in_array($rel, ['logo.png', 'favicon.png', 'business-growth.jpg'], true)) {
                        return '/images/brand/'.basename($rel);
                    }
                    if (str_starts_with($rel, 'LogoP1/') || str_starts_with($rel, 'LogoP2/') || $rel === 'CCRG-LOGO-edit1.png') {
                        return '/images/clients/'.$rel;
                    }
                    if (str_starts_with($rel, 'stampready-email-template/')) {
                        return '/images/email/'.$rel;
                    }

                    return '/images/solutions/'.basename($rel);
                }, $content);

                if ($new === null || $new === $content) {
                    continue;
                }

                $updated++;
                if (! $dry) {
                    $post->content = $new;
                    $post->save();
                }
            }
        });

        $this->info(($dry ? 'Would update ' : 'Updated ').$updated.' post(s).');

        return self::SUCCESS;
    }
}
