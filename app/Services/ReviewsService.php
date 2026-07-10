<?php

namespace App\Services;

use App\Models\BdgsBlabsReview;
use App\Support\ReviewPresenter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ReviewsService
{
    public const PER_PAGE = 30;

    public const CACHE_KEY_COUNT = 'reviews.published.count';

    public const FALLBACK_COUNT = 176;

    public function publishedCount(): int
    {
        $dbCount = $this->publishedDbCount();

        return $dbCount > 0 ? $dbCount : self::FALLBACK_COUNT;
    }

    public function publishedDbCount(): int
    {
        return (int) Cache::remember(
            self::CACHE_KEY_COUNT,
            3600,
            fn () => BdgsBlabsReview::published()->count()
        );
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_COUNT);
        Cache::forget('reviews.count');
    }

    /**
     * @return Collection<int, BdgsBlabsReview>
     */
    public function publishedPage(int $offset = 0, int $limit = self::PER_PAGE): Collection
    {
        return BdgsBlabsReview::published()
            ->ordered()
            ->offset(max(0, $offset))
            ->limit(max(1, min(200, $limit)))
            ->get();
    }

    /**
     * All published reviews for JSON-LD schema (each review as a Review item).
     *
     * @return Collection<int, BdgsBlabsReview>
     */
    public function allPublishedForSchema(): Collection
    {
        return BdgsBlabsReview::published()
            ->ordered()
            ->get();
    }

    /**
     * @return Collection<int, BdgsBlabsReview>
     */
    public function carouselReviews(): Collection
    {
        $filtered = BdgsBlabsReview::published()
            ->whereIn('marketplace_review_id', ReviewPresenter::CAROUSEL_MARKETPLACE_IDS)
            ->ordered()
            ->get();

        if ($filtered->isNotEmpty()) {
            return $filtered;
        }

        return BdgsBlabsReview::published()
            ->ordered()
            ->limit(11)
            ->get();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function carouselReviewArrays(): array
    {
        return $this->carouselReviews()
            ->map(fn (BdgsBlabsReview $review) => ReviewPresenter::toCarouselArray($review))
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listPayload(int $offset, int $limit, bool $publishedOnly = true): array
    {
        $query = BdgsBlabsReview::query()->ordered();
        if ($publishedOnly) {
            $query->published();
        }

        $total = (clone $query)->count();
        $rows = $query->offset(max(0, $offset))->limit(max(1, min(200, $limit)))->get();

        return [
            'ok' => true,
            'action' => 'list',
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'reviews' => $rows->map(fn (BdgsBlabsReview $review) => $this->formatListRow($review))->values()->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function formatListRow(BdgsBlabsReview $row): array
    {
        return [
            'review_id' => (int) $row->review_id,
            'marketplace_review_id' => $row->marketplace_review_id,
            'overall_rating' => (int) $row->overall_rating,
            'service_rating' => (int) $row->service_rating,
            'responsiveness_rating' => (int) $row->responsiveness_rating,
            'expertise_rating' => (int) $row->expertise_rating,
            'results_rating' => (int) $row->results_rating,
            'communication_rating' => (int) $row->communication_rating,
            'title' => ReviewPresenter::cleanTitle($row),
            'review_text' => ReviewPresenter::cleanBody($row),
            'review_date' => $row->review_date?->format('Y-m-d') ?? '',
            'verify_link' => ReviewPresenter::verifyLink($row),
            'submitter_label' => $row->submitter_label,
            'pmp_pid' => $row->pmp_pid,
            'is_published' => (int) $row->is_published,
            'sort_order' => (int) $row->sort_order,
        ];
    }
}
