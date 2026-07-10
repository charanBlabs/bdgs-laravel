<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BdgsBlabsReview;
use App\Services\ReviewsService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewsSyncController extends Controller
{
    public function __construct(private readonly ReviewsService $reviews)
    {
    }

    public function handle(Request $request): JsonResponse
    {
        $action = trim((string) $request->input('action', 'list'));
        if ($action === '') {
            $action = 'list';
        }

        return match ($action) {
            'list' => $this->listReviews($request),
            'get' => $this->getReview($request),
            'create', 'upsert' => $this->upsertReview($request, $action),
            default => response()->json([
                'ok' => false,
                'message' => 'Unknown action: '.$action,
            ], 400),
        };
    }

    private function listReviews(Request $request): JsonResponse
    {
        $limit = max(1, min(200, (int) $request->input('limit', 30)));
        $offset = max(0, (int) $request->input('offset', 0));
        $publishedOnly = (bool) (int) $request->input('published_only', 0);

        $query = BdgsBlabsReview::query()->orderByDesc('review_date')->orderByDesc('review_id');
        if ($publishedOnly) {
            $query->published();
        }

        $total = (clone $query)->count();
        $reviews = $query->offset($offset)->limit($limit)->get()->map(fn ($row) => $this->formatRow($row));

        return response()->json([
            'ok' => true,
            'action' => 'list',
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'reviews' => $reviews,
        ]);
    }

    private function getReview(Request $request): JsonResponse
    {
        $review = $this->findReview($request);
        if (! $review) {
            return response()->json(['ok' => false, 'message' => 'Review not found'], 404);
        }

        return response()->json([
            'ok' => true,
            'action' => 'get',
            'review' => $this->formatRow($review),
        ]);
    }

    private function upsertReview(Request $request, string $action): JsonResponse
    {
        $payload = $this->buildPayload($request);
        $error = $this->validatePayload($payload);
        if ($error !== '') {
            return response()->json(['ok' => false, 'message' => $error], 422);
        }

        $existing = BdgsBlabsReview::query()
            ->where('marketplace_review_id', $payload['marketplace_review_id'])
            ->first();

        if ($existing) {
            $existing->update($payload);
            $this->clearReviewCaches();

            return response()->json([
                'ok' => true,
                'action' => 'upsert',
                'mode' => 'updated',
                'review' => $this->formatRow($existing->fresh()),
            ]);
        }

        $review = BdgsBlabsReview::query()->create($payload);
        $this->clearReviewCaches();

        return response()->json([
            'ok' => true,
            'action' => $action,
            'mode' => 'created',
            'review' => $this->formatRow($review),
        ]);
    }

    private function findReview(Request $request): ?BdgsBlabsReview
    {
        $reviewId = (int) $request->input('review_id', 0);
        if ($reviewId > 0) {
            return BdgsBlabsReview::query()->find($reviewId);
        }

        $marketplaceId = trim((string) $request->input('marketplace_review_id', $request->input('id', '')));
        if ($marketplaceId === '') {
            return null;
        }

        return BdgsBlabsReview::query()
            ->where('marketplace_review_id', $marketplaceId)
            ->first();
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPayload(Request $request): array
    {
        $marketplaceReviewId = trim((string) $request->input('marketplace_review_id', $request->input('id', '')));
        $reviewText = trim((string) $request->input('review_text', $request->input('text', '')));
        $reviewDate = $this->normalizeDate((string) $request->input('review_date', $request->input('date', '')));
        $verifyLink = trim((string) $request->input('verify_link', $request->input('verify_review', '')));
        $submitterLabel = trim((string) $request->input('submitter_label', 'Verified Client'));

        $serviceRating = $request->input('service_rating', $request->input('service', $request->input('surveys', 5)));

        return [
            'marketplace_review_id' => $marketplaceReviewId,
            'overall_rating' => $this->clampRating($request->input('overall_rating', 5)),
            'service_rating' => $this->clampRating($serviceRating),
            'responsiveness_rating' => $this->clampRating($request->input('responsiveness_rating', $request->input('responsiveness', 5))),
            'expertise_rating' => $this->clampRating($request->input('expertise_rating', $request->input('expertise', 5))),
            'results_rating' => $this->clampRating($request->input('results_rating', $request->input('results', 5))),
            'communication_rating' => $this->clampRating($request->input('communication_rating', $request->input('communication', 5))),
            'title' => trim((string) $request->input('title', '')),
            'review_text' => $reviewText,
            'review_date' => $reviewDate,
            'verify_link' => $verifyLink,
            'submitter_label' => $submitterLabel !== '' ? $submitterLabel : 'Verified Client',
            'pmp_pid' => $this->nullableInt($request->input('pmp_pid')),
            'is_published' => (int) ((bool) (int) $request->input('is_published', 1)),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function validatePayload(array $payload): string
    {
        if ($payload['marketplace_review_id'] === '') {
            return 'marketplace_review_id is required';
        }
        if ($payload['review_text'] === '') {
            return 'review_text is required';
        }
        if ($payload['review_date'] === '') {
            return 'review_date is invalid or missing';
        }

        return '';
    }

    private function normalizeDate(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return '';
        }
    }

    private function clampRating(mixed $value): int
    {
        $rating = (int) $value;
        if ($rating < 1) {
            $rating = 5;
        }

        return min(5, max(1, $rating));
    }

    private function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatRow(BdgsBlabsReview $row): array
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
            'title' => $row->title,
            'review_text' => $row->review_text,
            'review_date' => $row->review_date?->format('Y-m-d') ?? '',
            'verify_link' => $row->verify_link,
            'submitter_label' => $row->submitter_label,
            'pmp_pid' => $row->pmp_pid,
            'is_published' => (int) $row->is_published,
            'sort_order' => (int) $row->sort_order,
            'created_at' => $row->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $row->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    private function clearReviewCaches(): void
    {
        $this->reviews->clearCache();
    }
}
