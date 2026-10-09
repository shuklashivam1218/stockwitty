<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/** sitemap.xml and llms.txt list blog posts from the database (Phase 5). */
class BlogSeoTest extends TestCase
{
    use DatabaseTransactions;

    private const BASE = 'https://www.stockswitty.com';

    public function test_sitemap_lists_published_posts_with_their_last_edit(): void
    {
        $live  = BlogPost::create(['title' => 'Sitemap live', 'slug' => 'sitemap-live-' . uniqid(), 'status' => 'published', 'published_at' => now()]);
        $live->timestamps = false;
        $live->updated_at = now()->setDate(2026, 9, 1);
        $live->save();
        $draft = BlogPost::create(['title' => 'Sitemap draft', 'slug' => 'sitemap-draft-' . uniqid()]);

        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringContainsString('<loc>' . self::BASE . $live->url() . "</loc>\n    <lastmod>2026-09-01</lastmod>", $xml);
        $this->assertStringNotContainsString($draft->slug, $xml);
        $this->assertSame(1, substr_count($xml, '<loc>' . self::BASE . "/blog/</loc>"));

        // Every imported post is there, each exactly once.
        foreach (BlogPost::published()->pluck('slug') as $slug) {
            $this->assertSame(1, substr_count($xml, self::BASE . "/blog/{$slug}/</loc>"), $slug);
        }
    }

    public function test_llms_txt_lists_posts_as_safe_markdown_links(): void
    {
        $post = BlogPost::create([
            'title' => "Tax & [links] guide\nline two", 'slug' => 'llms-' . uniqid(),
            'status' => 'published', 'published_at' => now(),
        ]);

        $this->get('/llms.txt')
            ->assertOk()
            ->assertSee('- [Tax & links guide line two](' . self::BASE . $post->url() . ')', false)
            ->assertSee('- [Tax on Unlisted Shares in India: Complete Guide (2026)](' . self::BASE . '/blog/tax-on-unlisted-shares/)', false);
    }
}
