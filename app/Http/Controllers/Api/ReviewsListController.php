<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReviewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewsListController extends Controller
{
    public function __construct(private readonly ReviewsService $reviews)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $limit = max(1, min(200, (int) $request->input('limit', ReviewsService::PER_PAGE)));
        $offset = max(0, (int) $request->input('offset', 0));
        $publishedOnly = (bool) (int) $request->input('published_only', 1);

        return response()->json(
            $this->reviews->listPayload($offset, $limit, $publishedOnly)
        );
    }

    public function count(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'total' => $this->reviews->publishedCount(),
        ]);
    }
}
