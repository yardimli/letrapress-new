<?php

namespace Tests\Feature;

use Tests\TestCase;

class MarketingPagesTest extends TestCase
{
    public function test_public_marketing_pages_are_available(): void
    {
        foreach (['/', '/how-it-works', '/documentation', '/about'] as $uri) {
            $this->get($uri)
                ->assertOk()
                ->assertSee('Explore the demo')
                ->assertDontSee('Book Review Packages');
        }
    }

    public function test_legacy_about_url_redirects_to_friendly_url(): void
    {
        $this->get('/about-page-one')->assertRedirect('/about');
    }

    public function test_book_review_packages_page_was_not_added(): void
    {
        $this->get('/book-review-packages')->assertNotFound();
    }
}
