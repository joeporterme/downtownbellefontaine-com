<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_public_pages_load(): void
    {
        // Note: /robots.txt is a static public/ file served by the web server,
        // not a Laravel route, so it is not exercised here.
        foreach (['/', '/businesses', '/events', '/contact', '/sitemap.xml'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_sitemap_is_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml');
        $this->assertStringContainsString('<urlset', $response->getContent());
    }

    public function test_home_page_exposes_open_graph_and_structured_data(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('property="og:title"', $html);
        $this->assertStringContainsString('application/ld+json', $html);
        $this->assertStringContainsString('rel="canonical"', $html);
    }
}
