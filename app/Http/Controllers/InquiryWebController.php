<?php

namespace App\Http\Controllers;

use App\Http\Requests\InquirySubmitRequest;
use App\Support\InquiryGuard;
use App\Services\InquiryService;
use Illuminate\Http\JsonResponse;

class InquiryWebController extends Controller
{
    public function __construct(private readonly InquiryService $inquiries)
    {
    }

    public function formGuard(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'form_guard' => InquiryGuard::issueFormGuard(),
        ]);
    }

    public function store(InquirySubmitRequest $request): JsonResponse
    {
        if (InquiryGuard::honeypotTripped($request)) {
            return response()->json(InquiryGuard::fakeSuccessResponse(), 201);
        }

        if (! InquiryGuard::verifyFormGuard($request->input('form_guard'))) {
            return response()->json([
                'ok' => false,
                'message' => 'Your session expired or the form was submitted too quickly. Please close the modal, open it again, and retry.',
            ], 422);
        }

        $validated = $request->safe()->only([
            'name', 'email', 'phone', 'directory_url', 'need', 'message',
        ]);
        $validated['source'] = 'web';

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
