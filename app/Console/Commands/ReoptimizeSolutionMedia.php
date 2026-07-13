<?php

namespace App\Console\Commands;

use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use App\Models\BdgsMedia;
use App\Services\MediaService;
use Illuminate\Console\Command;

class ReoptimizeSolutionMedia extends Command
{
    protected $signature = 'solutions:reoptimize-images
                            {--all-media : Reoptimize every image in the media library, not only solution thumbnails}
                            {--force : Also re-encode images that are already WebP}';

    protected $description = 'Convert solution featured images to WebP and regenerate thumb/medium/large variants';

    public function handle(MediaService $mediaService): int
    {
        $force = (bool) $this->option('force');

        if ($this->option('all-media')) {
            $query = BdgsMedia::query()->where('mime_type', 'like', 'image/%');
        } else {
            $type = BdgsDataType::query()->where('slug', 'solution')->first();
            if (! $type) {
                $this->error('Solution data type not found.');

                return 1;
            }

            $ids = BdgsDataPost::query()
                ->where('post_type_id', $type->id)
                ->whereNotNull('featured_media_id')
                ->pluck('featured_media_id');

            $query = BdgsMedia::query()->whereIn('id', $ids);
        }

        $mediaItems = $query->with('variants')->get();
        $converted = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($mediaItems as $media) {
            if ($media->mime_type === 'image/webp' && ! $force) {
                $skipped++;

                continue;
            }

            if (str_contains((string) $media->mime_type, 'svg')) {
                $skipped++;

                continue;
            }

            try {
                $before = $media->size_bytes;
                $updated = $mediaService->reoptimizeAsWebp($media);
                $after = $updated->size_bytes;
                $this->info("✓ #{$media->id} {$media->filename} → WebP ({$before} → {$after} bytes, {$updated->variants->count()} variants)");
                $converted++;
            } catch (\Throwable $e) {
                $this->error("Failed #{$media->id}: ".$e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Done: {$converted} converted, {$skipped} skipped, {$failed} failed (total {$mediaItems->count()}).");

        return $failed > 0 ? 1 : 0;
    }
}
