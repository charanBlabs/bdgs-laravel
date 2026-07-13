<?php

namespace App\Http\Controllers\Concerns;

use App\Models\BdgsDataMeta;
use App\Models\BdgsDataPost;
use App\Models\BdgsDataType;
use App\Models\BdgsMedia;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

trait ManagesSolutionPosts
{
    /** @return array<string, mixed> */
    protected function solutionFieldRules(): array
    {
        return [
            'live' => ['nullable', 'in:yes,no'],
            'short_title' => ['nullable', 'string', 'max:255'],
            'demo_video_url' => ['nullable', 'string'],
            'pricing_type' => ['nullable', 'in:free,fixed_price,starts_from,subscription,ask_for_quote'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'annual_price' => ['nullable', 'numeric', 'min:0'],
            'commitment_price' => ['nullable', 'numeric', 'min:0'],
            'implementation_type' => ['nullable', 'in:quick,semi_custom,readytoimplement,fully_custom'],
            'delivery_time' => ['nullable', 'in:3_days,1_week,2_weeks'],
            'warranty' => ['nullable', 'string', 'max:100'],
            'wysiwyg_cta' => ['nullable', 'in:yes,no'],
            'product_type' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:bdgs_categories,id'],
        ];
    }

    /**
     * Only columns that belong on bdgs_data_posts (excludes live/category_id/product_type).
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function postFillAttributes(array $validated): array
    {
        return Arr::only($validated, [
            'title',
            'slug',
            'excerpt',
            'content',
            'status',
            'visibility',
            'featured_media_id',
            'published_at',
            'pinned',
            'short_title',
            'demo_video_url',
            'pricing_type',
            'price',
            'annual_price',
            'commitment_price',
            'implementation_type',
            'delivery_time',
            'warranty',
        ]);
    }

    /**
     * When Live=Yes, ensure published_at is set so public listings and "Posted on" work.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function withPublishTimestamp(array $validated, ?BdgsDataPost $existing = null): array
    {
        if (($validated['status'] ?? null) === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = $existing?->published_at ?? now();
        }

        return $validated;
    }

    /** @param array<string, mixed> $validated */
    protected function applySolutionFields(BdgsDataPost $post, array $validated, Request $request): void
    {
        if ($request->has('live')) {
            $post->status = $request->input('live') === 'yes' ? 'published' : 'draft';
            if ($post->status === 'published' && ! $post->published_at) {
                $post->published_at = now();
            }
        }

        $post->fill([
            'short_title' => $validated['short_title'] ?? $post->short_title,
            'demo_video_url' => $validated['demo_video_url'] ?? $post->demo_video_url,
            'pricing_type' => $validated['pricing_type'] ?? $post->pricing_type,
            'price' => $validated['price'] ?? $post->price,
            'annual_price' => $validated['annual_price'] ?? $post->annual_price,
            'commitment_price' => $validated['commitment_price'] ?? $post->commitment_price,
            'implementation_type' => $validated['implementation_type'] ?? $post->implementation_type,
            'delivery_time' => $validated['delivery_time'] ?? $post->delivery_time,
            'warranty' => $validated['warranty'] ?? $post->warranty,
            'wysiwyg_cta' => ($request->input('wysiwyg_cta') === 'yes'),
            'excerpt' => array_key_exists('excerpt', $validated) ? $validated['excerpt'] : $post->excerpt,
        ]);

        if ($request->filled('category_id')) {
            $post->categories()->sync([(int) $request->input('category_id')]);
        } elseif ($request->has('category_id')) {
            $post->categories()->sync([]);
        }

        if ($request->has('product_type')) {
            $this->syncSolutionMeta($post, 'product_type', $request->input('product_type'));
        }
    }

    protected function syncSolutionMeta(BdgsDataPost $post, string $key, mixed $value): void
    {
        if ($value === null || $value === '') {
            BdgsDataMeta::query()->where('post_id', $post->id)->where('key', $key)->delete();

            return;
        }

        BdgsDataMeta::query()->updateOrCreate(
            ['post_id' => $post->id, 'key' => $key],
            ['value' => (string) $value]
        );
    }

    protected function replaceFeaturedMedia(BdgsDataPost $post, BdgsMedia $media, MediaService $mediaService): void
    {
        $previousId = $post->featured_media_id;
        $post->update(['featured_media_id' => $media->id]);

        if ($previousId && $previousId !== $media->id) {
            $previous = BdgsMedia::query()->find($previousId);
            if ($previous) {
                $mediaService->delete($previous);
            }
        }
    }

    protected function clearFeaturedMedia(BdgsDataPost $post, MediaService $mediaService): void
    {
        $previousId = $post->featured_media_id;
        $post->update(['featured_media_id' => null]);

        if ($previousId) {
            $previous = BdgsMedia::query()->find($previousId);
            if ($previous) {
                $mediaService->delete($previous);
            }
        }
    }

    protected function publicPostUrl(BdgsDataType $dataType, BdgsDataPost $post): string
    {
        return url($dataType->publicBasePath().'/'.$post->slug);
    }
}
