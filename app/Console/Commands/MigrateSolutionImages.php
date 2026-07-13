<?php

namespace App\Console\Commands;

use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use App\Models\BdgsMedia;
use App\Services\MediaService;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;

class MigrateSolutionImages extends Command
{
    protected $signature = 'solutions:migrate-images
                            {source : Folder of thumbnail images named to match post slugs}
                            {--force : Overwrite existing featured images}
                            {--skip-existing : Skip posts that already have a featured image (default)}';

    protected $description = 'Import solution thumbnail images as WebP with thumb/medium/large variants';

    public function handle(MediaService $mediaService): int
    {
        $source = $this->argument('source');
        if (! is_dir($source)) {
            $this->error("Directory not found: {$source}");

            return 1;
        }

        $dataType = BdgsDataType::query()->where('slug', 'solution')->first();
        if (! $dataType) {
            $this->error('Solution data type not found.');

            return 1;
        }

        $force = (bool) $this->option('force');
        $posts = BdgsDataPost::query()->where('post_type_id', $dataType->id)->get()->keyBy('slug');
        $files = glob($source.'/*') ?: [];
        $matched = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($files as $filepath) {
            if (! is_file($filepath)) {
                continue;
            }

            $basename = pathinfo($filepath, PATHINFO_FILENAME);
            $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                $this->warn('Skipping non-image: '.basename($filepath));
                $skipped++;

                continue;
            }

            $slug = str_replace(['_', '–', '—'], '-', $basename);
            $post = $posts->get($slug);

            if (! $post) {
                $post = $posts->first(function (BdgsDataPost $p) use ($slug) {
                    return str_contains($p->slug, $slug) || str_contains($slug, $p->slug);
                });
            }

            if (! $post) {
                $this->warn("No matching post for: {$basename} (tried slug: {$slug})");
                $failed++;

                continue;
            }

            if ($post->featured_media_id && ! $force) {
                $this->line("Skip (has thumbnail): {$post->slug}");
                $skipped++;

                continue;
            }

            try {
                $uploadedFile = new UploadedFile(
                    $filepath,
                    basename($filepath),
                    mime_content_type($filepath) ?: null,
                    null,
                    true
                );

                $altText = ucwords(str_replace(['-', '_'], ' ', $basename));
                $media = $mediaService->uploadAsWebp($uploadedFile, $altText);

                $previousId = $post->featured_media_id;
                $post->update(['featured_media_id' => $media->id]);

                if ($previousId && $previousId !== $media->id) {
                    $previous = BdgsMedia::query()->find($previousId);
                    if ($previous) {
                        $mediaService->delete($previous);
                    }
                }

                $this->info("✓ {$post->slug} ← ".basename($filepath).' (WebP + variants)');
                $matched++;
            } catch (\Throwable $e) {
                $this->error("Failed for {$basename}: ".$e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Done: {$matched} matched, {$skipped} skipped, {$failed} failed out of ".count($files).' files.');

        return 0;
    }
}
