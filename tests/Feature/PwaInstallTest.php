<?php

namespace Tests\Feature;

use Tests\TestCase;

class PwaInstallTest extends TestCase
{
    public function test_login_page_offers_pwa_download(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Download App', false)
            ->assertSee('manifest.json', false)
            ->assertSee('data-pwa-install', false);
    }

    public function test_web_app_manifest_file_exists(): void
    {
        $path = public_path('manifest.json');

        $this->assertFileExists($path);

        $manifest = json_decode((string) file_get_contents($path), true);

        $this->assertSame('HotelDesk', $manifest['name'] ?? null);
        $this->assertSame('standalone', $manifest['display'] ?? null);
        $this->assertSame('/', $manifest['start_url'] ?? null);
    }
}
