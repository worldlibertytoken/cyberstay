<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CNICOCRTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test tenant and user
        $tenant = Tenant::create([
            'name' => 'Test Hotel',
            'slug' => 'test-hotel',
        ]);
        $this->user = User::factory()->create([
            'role' => 'owner',
            'tenant_id' => $tenant->id,
        ]);
        session(['current_tenant_id' => $tenant->id]);
    }

    public function test_parse_text_endpoint(): void
    {
        $response = $this->actingAs($this->user)->post(route('ocr.parse-text'), [
            'text' => 'Name: Ahmed Khan
Father Name: Muhammad Khan
CNIC: 12345-1234567-1
Address: Street 5, Karachi',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'data' => ['name', 'father_name', 'cnic'],
        ]);

        $response->assertJsonPath('data.name', 'Ahmed Khan');
        $response->assertJsonPath('data.father_name', 'Muhammad Khan');
        $response->assertJsonPath('data.cnic', '12345-1234567-1');
    }

    public function test_parse_text_validates_input(): void
    {
        $response = $this->actingAs($this->user)->post(route('ocr.parse-text'), [
            'text' => 'short',
        ]);

        $response->assertStatus(422);
    }

    public function test_requires_authentication(): void
    {
        $response = $this->post(route('ocr.parse-text'), [
            'text' => 'Some OCR text that is long enough but user is not authenticated',
        ]);

        $response->assertStatus(401);
    }
}
