<?php

namespace App\Http\Controllers;

use App\Services\CNICOCRService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CNICOCRController extends Controller
{
    public function __construct(private CNICOCRService $ocrService) {}

    /**
     * Parse extracted OCR text and return structured CNIC data
     */
    public function parseText(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string|min:10',
            'side' => 'nullable|in:front,back',
        ]);

        $data = $this->ocrService->parseCNICFromText($validated['text'], $validated['side'] ?? null);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
