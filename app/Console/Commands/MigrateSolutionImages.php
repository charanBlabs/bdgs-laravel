<?php

namespace App\Console\Commands;

use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use App\Models\BdgsMedia;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use App\Services\MediaService;

class MigrateSolutionImages extends Command
{
    protected $signature = 'solutions:migrate-images {source}';
    protected $description = 'Import solution thumbnail images from a folder, matching filenames to post slugs';

    public function handle(MediaService $mediaService): int
    {
        $source = $this->argument('source');
        if (! is_dir($source)) {
            $this->error("Directory not found: {$source}");
            return 1;
        }

        $dataType = BdgsDataType::where('slug', 'solution')->first();
        if (! $dataType) {
            $this->error('Solution data type not found.');
            return 1;
        }

        $posts = BdgsDataPost::where('post_type_id', $dataType->id)->get()->keyBy('slug');
        $files = glob($source . '/*');
        $matched = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($files as $filepath) {
            $basename = pathinfo($filepath, PATHINFO_FILENAME);
            $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $this->warn("Skipping non-image: " . basename($filepath));
                $skipped++;
                continue;
            }

            $slug = str_replace('_', '-', $basename);

            // Handle special characters in slugs
            $slug = str_replace(['–', '—'], '-', $slug);

            $post = $posts->get($slug);

            if (! $post) {
                // Try partial match
                $post = $posts->first(function ($p) use ($slug) {
                    return str_contains($p->slug, $slug) || str_contains($slug, $p->slug);
                });
            }

            if (! $post) {
                $this->warn("No matching post for: {$basename} (tried slug: {$slug})");
                $failed++;
                continue;
            }

            if ($post->featured_media_id) {
                $this->info("Post already has thumbnail: {$post->slug} — overwriting");
            }

            try {
                $uploadedFile = new UploadedFile(
                    $filepath,
                    basename($filepath),
                    mime_content_type($filepath),
                    null,
                    true
                );

                $altText = ucwords(str_replace(['-', '_'], ' ', $basename));
                $media = $mediaService->upload($uploadedFile, $altText);
                $post->update(['featured_media_id' => $media->id]);

                $this->info("✓ {$post->slug} ← " . basename($filepath) . " (alt: {$altText})");
                $matched++;
            } catch (\Throwable $e) {
                $this->error("Failed for {$basename}: " . $e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Done: {$matched} matched, {$skipped} skipped, {$failed} failed out of " . count($files) . " files.");

        return 0;
    }
}
