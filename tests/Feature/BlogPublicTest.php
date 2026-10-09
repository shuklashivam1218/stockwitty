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
