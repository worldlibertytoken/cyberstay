# CNIC OCR Auto-Fill Feature

## Overview
When uploading CNIC (ID card) pictures in the "Other guests" section of a booking, the system will automatically extract text from the image using OCR (Optical Character Recognition) and attempt to populate the guest information fields.

## How It Works

### Frontend (Client-Side)
1. **Image Upload Detection**: When you select a file for "ID front" photo in a companion row
2. **Tesseract.js Processing**: The image is processed using Tesseract.js (browser-based OCR)
3. **Text Extraction**: Extracted text is sent to the backend for intelligent parsing
4. **Auto-Fill**: Form fields are automatically populated with extracted data

### Backend (Server-Side)
The `CNICOCRService` parses extracted OCR text using intelligent regex patterns to identify:
- **Name**: Extracted after "Name:" label
- **Father/Husband Name**: Extracted after "Father Name:" or "Husband Name:" label
- **CNIC Number**: Matches standard format (12345-1234567-1)

## Technical Implementation

### Files Modified/Created
- [app/Services/CNICOCRService.php](app/Services/CNICOCRService.php) - CNIC text parsing logic
- [app/Http/Controllers/CNICOCRController.php](app/Http/Controllers/CNICOCRController.php) - API endpoint
- [resources/views/components/cnic-ocr-script.blade.php](resources/views/components/cnic-ocr-script.blade.php) - Client-side OCR script
- [resources/views/bookings/_form.blade.php](resources/views/bookings/_form.blade.php) - Integration in booking form
- [routes/web.php](routes/web.php) - API route registration

### API Endpoint
**Route**: `POST /api/ocr/parse-text`  
**Authentication**: Required (JWT/session)  
**Request**:
```json
{
  "text": "Extracted OCR text from image..."
}
```

**Response**:
```json
{
  "success": true,
  "data": {
    "name": "Ahmed Khan",
    "father_name": "Muhammad Khan",
    "cnic": "12345-1234567-1"
  }
}
```

## Usage

### In the Booking Form
1. Navigate to Create/Edit Booking
2. Scroll to "4. Other guests" section
3. Click "Add guest" to create a companion row
4. Upload an ID card front photo in the "ID front" field
5. The system will:
   - Show "🔄 Reading CNIC..." loading indicator
   - Extract text from the image
   - Parse the extracted data
   - Show "✓ CNIC information extracted" confirmation
   - Auto-fill Name, Father name, and CNIC fields

### Manual Entry
If OCR fails to extract data, or if you prefer to enter data manually:
- Fields remain editable
- Type or paste information directly
- No required fields except for the primary guest

## Performance Considerations

- OCR processing happens in the browser - no server load
- First image upload initializes Tesseract worker (~5-10MB download from CDN)
- Subsequent uploads are faster (worker cached in memory)
- Processing time: ~2-5 seconds per image
- Workers are terminated on page unload to free memory

## Accuracy & Reliability

**OCR Success Factors:**
- Image quality (clear, well-lit ID cards)
- Text contrast (dark text on light background)
- Image angle (straight-on shots work better)
- Common OCR issues:
  - Blurry images may fail
  - Ornate fonts may be misread
  - Stamps or watermarks may interfere

**Fallback Behavior:**
If OCR fails to extract data, you can still:
- Click "View current" to see uploaded image
- Manually type guest information
- Continue booking without OCR data

## Testing

Unit tests verify parsing logic:
```bash
php vendor/bin/phpunit tests/Unit/CNICOCRServiceTest.php
```

Feature tests verify API endpoint:
```bash
php vendor/bin/phpunit tests/Feature/CNICOCRTest.php
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| "⚠ Could not extract CNIC info" | Image quality too low. Try again with a clearer photo. |
| OCR doesn't start after upload | Check browser console for JavaScript errors. Try refreshing page. |
| Fields not auto-filling | Text extraction may have failed. Check "View current" to see uploaded image. |
| Worker stuck/high memory usage | Refresh page. Browser cache will limit worker instances. |

## Future Enhancements

- Support for OCR from back side of ID card
- Address field auto-extraction (currently only name, father_name, cnic)
- Multi-language support (currently English only)
- Batch processing for multiple bookings
- Manual OCR text review interface before auto-fill
