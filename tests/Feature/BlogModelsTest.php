<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSlugRedirect;
use App\Models\UnlistedStock;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Runs against the local MySQL database (phpunit.xml has no sqlite), so it
 * uses DatabaseTransactions — never RefreshDatabase, which would wipe it.
 */
class BlogModelsTest extends TestCase
{
    use DatabaseTransactions;

    private function makeAuthor(): User
    {
        return User::create([
            'name' => 'Test Author', 'email' => 'author_' . uniqid() . '@example.test',
            'phone' => '9' . random_int(100000000, 999999999), 'unlisted_user_type' => 'unlisted',
            'password' => 'secret-password', 'author_designation' => 'Research Analyst',
        ]);
    }

    private function makePost(array $attrs = []): BlogPost
    {
        return BlogPost::create($attrs + [
            'title' => 'Test post', 'slug' => 'test-post-' . uniqid(), 'content' => '<p>Hello</p>',
        ]);
    }

    public function test_categories_are_seeded_in_order(): void
    {
        $this->assertSame(
            ['Basics', 'Buying & Selling', 'Tax', 'Analysis', 'Glossary'],
            BlogCategory::active()->pluck('name')->take(5)->all()
        );
    }

    public function test_reading_time_is_calculated_from_intro_and_content(): void
    {
        $post = $this->makePost([
            'intro'   => '<p>' . str_repeat('word ', 150) . '</p>',
            'content' => '<h2>Heading</h2><p>' . str_repeat('word ', 300) . '</p>',
        ]);

        $this->assertSame(3, $post->reading_minutes); // ~451 words / 200 wpm, rounded up
        $this->assertSame('3 min read', $post->readLabel());

        $post->update(['intro' => null, 'content' => '<p>short</p>']);
        $this->assertSame(1, $post->fresh()->reading_minutes);
    }

    public function test_json_blocks_round_trip_and_faq_tabs_keep_first_seen_order(): void
    {
        $post = $this->makePost([
            'chips'     => ['Unlisted Shares', 'Tax'],
            'takeaways' => ['One', 'Two'],
            'faqs'      => [
                ['tab' => 'Rates', 'q' => 'Q1', 'a' => 'A1'],
                ['tab' => 'Basics', 'q' => 'Q2', 'a' => 'A2'],
                ['tab' => 'Rates', 'q' => 'Q3', 'a' => 'A3'],
            ],
            'sources'   => [['label' => 'SEBI', 'href' => 'https://www.sebi.gov.in/']],
            'video'     => ['url' => 'https://www.youtube.com/watch?v=x', 'caption' => 'Watch'],
        ])->fresh();

        $this->assertSame(['Unlisted Shares', 'Tax'], $post->chips);
        $this->assertSame('SEBI', $post->sources[0]['label']);
        $this->assertSame(['Rates', 'Basics'], $post->faqTabs());
    }

    public function test_published_scope_needs_status_and_date(): void
    {
        $live    = $this->makePost(['status' => BlogPost::STATUS_PUBLISHED, 'published_at' => now()]);
        $review  = $this->makePost(['status' => BlogPost::STATUS_DRAFT]);
        $noDate  = $this->makePost(['status' => BlogPost::STATUS_PUBLISHED]);

        $ids = BlogPost::published()->pluck('id');

        $this->assertTrue($ids->contains($live->id));
        $this->assertFalse($ids->contains($review->id));
        $this->assertFalse($ids->contains($noDate->id));
        $this->assertSame('/blog/' . $live->slug . '/', $live->url());
    }

    public function test_related_posts_keep_editor_order_and_skip_unpublished(): void
    {
        $a = $this->makePost(['status' => BlogPost::STATUS_PUBLISHED, 'published_at' => now()]);
        $b = $this->makePost(['status' => BlogPost::STATUS_PUBLISHED, 'published_at' => now()]);
        $draft = $this->makePost();

        $post = $this->makePost(['related_post_ids' => [$b->id, $draft->id, $a->id]]);

        $this->assertSame([$b->id, $a->id], $post->relatedPosts()->pluck('id')->all());
    }

    public function test_author_category_and_stock_relations(): void
    {
        $author   = $this->makeAuthor();
        $category = BlogCategory::where('slug', 'tax')->first();
        $post     = $this->makePost(['created_by' => $author->uid, 'category_id' => $category->id]);

        $stock = UnlistedStock::first();
        if ($stock) {
            $post->unlistedStocks()->sync([$stock->UL_STOCKS_FINCODE]);
            $this->assertSame($stock->UL_STOCKS_FINCODE, $post->unlistedStocks()->first()->UL_STOCKS_FINCODE);
        }

        $this->assertSame('Research Analyst', $post->author->author_designation);
        $this->assertSame('Tax', $post->category->name);
        $this->assertTrue($author->blogPosts()->whereKey($post->id)->exists());
    }

    public function test_deleting_the_author_keeps_the_post(): void
    {
        $author = $this->makeAuthor();
        $post   = $this->makePost(['created_by' => $author->uid]);

        $author->delete();

        $this->assertNull($post->fresh()->created_by);
    }

    public function test_soft_delete_and_slug_redirect(): void
    {
        $post = $this->makePost();
        BlogSlugRedirect::create(['old_slug' => 'old-' . $post->slug, 'blog_post_id' => $post->id]);

        $this->assertSame($post->id, BlogSlugRedirect::where('old_slug', 'old-' . $post->slug)->first()->post->id);

        $post->delete();
        $this->assertNull(BlogPost::find($post->id));
        $this->assertNotNull(BlogPost::withTrashed()->find($post->id));
    }
}
