<?php

namespace Tests\Unit;

use App\Services\CNICOCRService;
use PHPUnit\Framework\TestCase;

class CNICOCRServiceTest extends TestCase
{
    private CNICOCRService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CNICOCRService;
    }

    public function test_extracts_cnic_number(): void
    {
        $text = 'Name: Ahmed Khan
Father Name: Muhammad Khan
CNIC: 12345-1234567-1
Address: Street 5, Karachi';

        $result = $this->service->parseCNICFromText($text);

        $this->assertEquals('12345-1234567-1', $result['cnic']);
    }

    public function test_extracts_name(): void
    {
        $text = 'Name: Fatima Ahmad
CNIC: 54321-7654321-0';

        $result = $this->service->parseCNICFromText($text);

        $this->assertEquals('Fatima Ahmad', $result['name']);
    }

    public function test_extracts_father_name(): void
    {
        $text = 'Name: Ali Hassan
Father Name: Hassan Mohammad
CNIC: 11111-1111111-1';

        $result = $this->service->parseCNICFromText($text);

        $this->assertEquals('Hassan Mohammad', $result['father_name']);
    }

    public function test_extracts_husband_name(): void
    {
        $text = 'Name: Sara Khan
Husband Name: Muhammad Ali
CNIC: 22222-2222222-2';

        $result = $this->service->parseCNICFromText($text);

        $this->assertEquals('Muhammad Ali', $result['father_name']);
    }

    public function test_returns_null_for_missing_fields(): void
    {
        $text = 'Some random OCR text without structured data';

        $result = $this->service->parseCNICFromText($text);

        $this->assertNull($result['cnic']);
        $this->assertNull($result['name']);
        $this->assertNull($result['father_name']);
    }

    public function test_extracts_address_from_back_side_text(): void
    {
        $text = 'Permanent Address: House 12 Street 7, Gulshan-e-Iqbal, Karachi';

        $result = $this->service->parseCNICFromText($text, 'back');

        $this->assertEquals('House 12 Street 7, Gulshan-e-Iqbal, Karachi', $result['address']);
    }
}
