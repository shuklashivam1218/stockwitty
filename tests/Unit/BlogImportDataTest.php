<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * The JSON the static-post import migration loads must meet the same rules
 * the admin form enforces — it bypasses the form, so it is checked here.
 */
class BlogImportDataTest extends TestCase
{
    private function posts(): array
    {
        return json_decode(file_get_contents(database_path('data/blog_posts_2026_10.json')), true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_every_imported_post_meets_the_admin_rules(): void
    {
        $posts = $this->posts();
        $slugs = array_column($posts, 'slug');
        $this->assertCount(7, $posts);
        $this->assertSame($slugs, array_unique($slugs));

        foreach ($posts as $p) {
            $at = $p['slug'];
            $this->assertMatchesRegularExpression('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $at);
            foreach (['title', 'summary', 'content', 'category', 'meta_title', 'meta_description', 'published_at', 'updated_at'] as $required) {
                $this->assertNotEmpty($p[$required], "{$at}: {$required}");
            }

            $this->assertSame($p['content'], clean($p['content'], 'blog'), "{$at}: body is not already purified");
            $this->assertTrue($p['hero_icon'] === null || in_array($p['hero_icon'], config('blog.hero_icons'), true), "{$at}: hero icon");
            $this->assertTrue($p['featured_image'] === null || is_file(public_path($p['featured_image'])), "{$at}: featured image file");

            $this->assertLessThanOrEqual(config('blog.max_takeaways'), count($p['takeaways']));
            $this->assertLessThanOrEqual(config('blog.max_faqs'), count($p['faqs']));
            foreach ($p['faqs'] as $f) {
                $this->assertMatchesRegularExpression('/^[\pL\pN &\/-]+$/u', $f['tab'], "{$at}: FAQ tab");
                $this->assertNotEmpty($f['q']);
                $this->assertNotEmpty($f['a']);
            }
            foreach ($p['sources'] as $s) {
                $this->assertStringStartsWith('https://', $s['href'], "{$at}: source link");
            }
            foreach ($p['related_slugs'] as $r) {
                $this->assertContains($r, $slugs, "{$at}: related post {$r} is not imported");
            }
            foreach (['intro', 'summary', 'title'] as $plain) {
                $this->assertSame(strip_tags($p[$plain]), $p[$plain], "{$at}: {$plain} has markup");
            }
        }
    }
}
