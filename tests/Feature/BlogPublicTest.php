<?php

namespace Tests\Feature;

use App\Helpers\Privilege;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSlugRedirect;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Public /blog/ pages rendered from blog_posts (Phase 4). Relies on the
 * seven posts imported by the 2026_10_09_100006 data migration.
 * DatabaseTransactions only — never RefreshDatabase.
 */
class BlogPublicTest extends TestCase
{
    use DatabaseTransactions;

    private function makePost(array $attrs = []): BlogPost
    {
        return BlogPost::create($attrs + [
            'title'        => 'Public test post',
            'slug'         => 'public-test-' . uniqid(),
            'summary'      => 'Summary for the card.',
            'content'      => '<h2>First section</h2><p>Body.</p><h2>Second section</h2><div class="sw-callout"><p><strong>Heads up</strong></p><p>Text</p></div>',
            'category_id'  => BlogCategory::where('slug', 'tax')->value('id'),
            'status'       => BlogPost::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);
    }

    public function test_imported_posts_render_at_their_old_urls(): void
    {
        foreach (BlogPost::published()->pluck('slug') as $slug) {
            $this->get("/blog/{$slug}/")->assertOk();
        }

        $this->get('/blog/tax-on-unlisted-shares/')
            ->assertSee('<title>Tax on Unlisted Shares in India: Complete Guide (2026) | StocksWitty</title>', false)
            ->assertSee('class="sw-callout"', false)
            ->assertSee('class="sw-table"', false)
            ->assertSee('Frequently asked questions')
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('"datePublished"', false)
            ->assertSee('Related reading')
            ->assertDontSee('Replace with your StocksWitty YouTube video');
    }

    public function test_index_lists_published_posts_with_featured_first(): void
    {
        $draft = $this->makePost(['title' => 'Secret draft', 'status' => BlogPost::STATUS_DRAFT, 'published_at' => null]);

        $this->get('/blog/')
            ->assertOk()
            ->assertSeeInOrder(['Featured', 'How to Buy Unlisted Shares'])
            ->assertSee('What Are Unlisted Shares?')
            ->assertDontSee($draft->title);
    }

    /** Adds $n published posts newer than the imported ones; returns their titles, newest first. */
    private function morePosts(int $n, ?int $categoryId = null): array
    {
        $titles = [];
        foreach (range(1, $n) as $i) {
            $titles[] = $title = sprintf('Paged post %02d', $i);
            $this->makePost([
                'title' => $title, 'published_at' => now()->subMinutes($n - $i),
                'category_id' => $categoryId ?? BlogCategory::where('slug', 'basics')->value('id'),
            ]);
        }

        return array_reverse($titles);
    }

    public function test_index_pages_nine_cards_with_featured_only_on_page_one(): void
    {
        $titles = $this->morePosts(12); // 7 imported (1 featured) + 12 = 18 in the grid

        $page1 = $this->get('/blog/')->assertOk();
        $page1->assertSee('Featured · Buying &amp; Selling', false)
              ->assertSee('<span class="font-bold text-foreground">19</span> guides', false)
              ->assertSeeInOrder(array_slice($titles, 0, 9))
              ->assertDontSee($titles[9])
              ->assertSee('href="' . url('/blog/?page=2') . '#posts" data-blog-nav rel="next"', false)
              ->assertSee('aria-current="page"', false);
        $this->assertSame(9, substr_count($page1->getContent(), '<li class="animate-fade-up-in"'));

        $page2 = $this->get('/blog/?page=2')->assertOk();
        $page2->assertDontSee('Featured ·')
              ->assertSee($titles[9])
              ->assertSee('<title>Unlisted Shares Blog — Page 2 | StocksWitty</title>', false)
              ->assertSee('<link rel="canonical" href="' . rtrim(config('app.url'), '/') . '/blog/?page=2" />', false);
        $this->assertSame(9, substr_count($page2->getContent(), '<li class="animate-fade-up-in"'));

        $this->get('/blog/?page=3')->assertNotFound();
        $this->get('/blog/')->assertSee('<link rel="canonical" href="' . rtrim(config('app.url'), '/') . '/blog/" />', false);
    }

    public function test_in_page_requests_get_just_the_listing(): void
    {
        $titles = $this->morePosts(12);

        $res = $this->get('/blog/?page=2', ['X-Requested-With' => 'XMLHttpRequest'])->assertOk();
        $res->assertHeader('Vary', 'X-Requested-With')
            ->assertSee($titles[9])
            ->assertSee('data-blog-status="Page 2 of 2"', false)
            ->assertDontSee('<html', false)
            ->assertDontSee('Unlisted shares, explained');

        $this->get('/blog/')->assertHeader('Vary', 'X-Requested-With')->assertSee('<html', false);
    }

    public function test_category_filter_is_server_side_and_survives_paging(): void
    {
        $tax = BlogCategory::where('slug', 'tax')->first();
        $this->morePosts(10, $tax->id); // 1 imported tax post + 10 = 11 → 2 pages, no featured card

        $res = $this->get('/blog/?category=tax')->assertOk();
        $res->assertDontSee('Featured ·')
            ->assertDontSee('What Are Unlisted Shares?')
            ->assertSee('11</span> guides in Tax', false)
            ->assertSee('aria-current="true"', false)
            ->assertSee('href="' . url('/blog/?category=tax&amp;page=2') . '#posts"', false);

        $this->get('/blog/?category=tax&page=2')->assertOk()->assertSee('Tax on Unlisted Shares in India');
        $this->get('/blog/?category=no-such-category')->assertNotFound();
    }

    public function test_toc_comes_from_h2s_and_body_keeps_its_markup(): void
    {
        $post = $this->makePost();

        $this->get($post->url())
            ->assertOk()
            ->assertSee('<h2 id="first-section"', false)
            ->assertSee('href="#second-section"', false)
            ->assertSee('<p><strong>Heads up</strong></p>', false)
            ->assertDontSee('id="sources"', false)   // no sources: no section, no TOC entry
            ->assertDontSee('id="faq"', false);
    }

    public function test_drafts_trash_and_unknown_slugs_are_404(): void
    {
        $draft = $this->makePost(['status' => BlogPost::STATUS_DRAFT, 'published_at' => null]);
        $trashed = $this->makePost();
        $trashed->delete();

        $this->get($draft->url())->assertNotFound();
        $this->get($trashed->url())->assertNotFound();
        $this->get('/blog/no-such-post/')->assertNotFound();
    }

    public function test_old_slug_redirects_permanently(): void
    {
        $post = $this->makePost();
        BlogSlugRedirect::create(['old_slug' => 'an-older-slug-' . $post->id, 'blog_post_id' => $post->id]);

        $this->get('/blog/an-older-slug-' . $post->id . '/')->assertRedirect($post->url())->assertStatus(301);
    }

    public function test_admin_text_cannot_break_out_of_alpine_expressions(): void
    {
        // Written straight to the DB to skip the form's tab validation.
        $evil = "x'); alert(1); ('";
        $cat  = BlogCategory::create(['name' => $evil, 'slug' => 'evil-' . uniqid()]);
        $post = $this->makePost([
            'category_id' => $cat->id,
            'faqs' => [['tab' => $evil, 'q' => 'Q', 'a' => 'A'], ['tab' => 'Other', 'q' => 'Q2', 'a' => 'A2']],
        ]);

        foreach ([$post->url(), '/blog/'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertStringNotContainsString("'x&#039;); alert(1); (&#039;'", $html);
            $this->assertStringNotContainsString("== 'x'); alert(1)", $html);
        }
        // ...and it does come through, as an inert JS string literal.
        $this->get($post->url())->assertSee('@click="active = ' . \Illuminate\Support\Js::from($evil) . '"', false);
        $this->assertStringContainsString(chr(92) . 'u0027', (string) \Illuminate\Support\Js::from($evil)); // the quote is escaped
    }

    public function test_preview_is_admin_only_and_not_indexed(): void
    {
        $draft = $this->makePost(['status' => BlogPost::STATUS_DRAFT, 'published_at' => null]);

        $this->get("/admin/blog/{$draft->id}/preview")->assertRedirect(route('login'));

        (new \ReflectionProperty(Privilege::class, 'cache'))->setValue(null, null);
        $user = User::create([
            'name' => 'Previewer', 'email' => 'preview_' . uniqid() . '@example.test',
            'phone' => '9' . random_int(100000000, 999999999), 'unlisted_user_type' => 'unlisted',
            'password' => 'secret-password', 'privilege' => ['author' => true],
        ]);

        $this->withSession(['uid' => $user->uid])->get("/admin/blog/{$draft->id}/preview")
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('draft — not visible to the public')
            ->assertDontSee('"@type":"Article"', false);
    }

    public function test_author_byline_and_card(): void
    {
        $author = User::create([
            'name' => 'Asha Rao', 'email' => 'asha_' . uniqid() . '@example.test',
            'phone' => '9' . random_int(100000000, 999999999), 'unlisted_user_type' => 'unlisted',
            'password' => 'secret-password', 'author_designation' => 'Research Analyst',
            'author_bio' => 'Covers pre-IPO tax.', 'author_linkedin' => 'https://www.linkedin.com/in/asha',
        ]);
        $post = $this->makePost(['created_by' => $author->uid]);

        $this->get($post->url())
            ->assertSee('Asha Rao · Research Analyst')
            ->assertSee('Covers pre-IPO tax.')
            ->assertSee('href="https://www.linkedin.com/in/asha"', false)
            ->assertSee('"@type":"Person"', false);
    }

    public function test_homepage_teaser_shows_latest_posts(): void
    {
        $post = $this->makePost(['title' => 'Brand new teaser post', 'published_at' => now()->addMinute()]);

        $this->get('/')->assertOk()->assertSee('Brand new teaser post')->assertSee($post->url());
    }

    public function test_markdown_twin_and_company_about_still_work(): void
    {
        $this->get('/blog/tax-on-unlisted-shares.md')->assertOk()->assertSee('Tax on Unlisted Shares');

        $slug = \App\Models\UnlistedStock::where('UL_STOCKS_STATUS', '1')->value('UL_STOCKS_SLUG');
        if ($slug) {
            $this->get("/unlisted-shares/{$slug}/about/")->assertOk();
        }
    }
}
