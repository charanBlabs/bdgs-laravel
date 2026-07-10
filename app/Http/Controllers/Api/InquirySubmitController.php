<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InquirySubmitRequest;
use App\Support\InquiryGuard;
use App\Services\InquiryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InquirySubmitController extends Controller
{
    public function __construct(private readonly InquiryService $inquiries)
    {
    }

    public function spec(Request $request): JsonResponse
    {
        return response()->json(
            $this->inquiries->openApiSpec($request->getSchemeAndHttpHost())
        );
    }

    public function store(InquirySubmitRequest $request): JsonResponse
    {
        if (InquiryGuard::honeypotTripped($request)) {
            return response()->json(InquiryGuard::fakeSuccessResponse(), 201);
        }

        $validated = $request->safe()->only([
            'name', 'email', 'phone', 'directory_url', 'need', 'message', 'source',
        ]);
        $validated['source'] = $validated['source'] ?? 'llm-agent';

        $inquiry = $this->inquiries->submit(
            $validated,
            $request->ip(),
            $request->userAgent()
        );

        return response()->json([
            'ok' => true,
            'inquiry_id' => $inquiry->inquiry_id,
            'message' => 'Inquiry received. The BD Growth Suite team will follow up by email.',
        ], 201);
    }
}
