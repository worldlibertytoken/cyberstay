<?php

namespace App\Services;

class CNICOCRService
{
    /**
     * Extract CNIC information from image using OCR text
     */
    public function parseCNICFromText(string $text, ?string $side = null): array
    {
        $normalizedText = preg_replace('/\r\n?/', "\n", $text) ?? $text;

        $data = [
            'name' => null,
            'father_name' => null,
            'cnic' => null,
            'address' => null,
        ];

        if ($side !== 'back') {
            // Extract CNIC number (format: XXXXX-XXXXXXX-X)
            if (preg_match('/\b(\d{5}-\d{7}-\d{1})\b/', $normalizedText, $matches)) {
                $data['cnic'] = $matches[1];
            }

            // Extract name after Name label.
            if (preg_match('/(?:^|\n)\s*Name[:\s]+([A-Z][A-Za-z\s]+?)(?:\n|$|Father|Husband|CNIC|Address|Date)/i', $normalizedText, $matches)) {
                $data['name'] = trim($matches[1]);
            }

            // Extract father/husband name.
            if (preg_match('/(?:^|\n)\s*Father[\'s]*\s+Name[:\s]+([A-Z][A-Za-z\s]+?)(?:\n|$|CNIC|Address|Husband|Name)/i', $normalizedText, $matches)) {
                $data['father_name'] = trim($matches[1]);
            } elseif (preg_match('/(?:^|\n)\s*Husband[\'s]*\s+Name[:\s]+([A-Z][A-Za-z\s]+?)(?:\n|$|CNIC|Address|Father|Name)/i', $normalizedText, $matches)) {
                $data['father_name'] = trim($matches[1]);
            }
        }

        if ($side !== 'front') {
            // Extract address from common labels on CNIC backside.
            if (preg_match('/(?:^|\n)\s*(?:Address|Permanent\s+Address|Present\s+Address)[:\s]+([^\n]+(?:\n(?!Name|Father|Husband|CNIC|DOB|Date|Issue|Expiry|Signature)[^\n]+){0,3})/i', $normalizedText, $matches)) {
                $data['address'] = trim($matches[1]);
            }
        }

        // Clean up extracted data
        return array_map(fn ($val) => $val ? preg_replace('/\s+/', ' ', trim($val)) : null, $data);
    }

    /**
     * Extract text from image file using OCR (client-side via API)
     * This is a helper for manual processing if needed
     */
    public function extractFromFile(string $filePath): string
    {
        // This would be called from JavaScript client-side using Tesseract.js
        // We're providing a fallback here if needed in future
        return '';
    }
}
