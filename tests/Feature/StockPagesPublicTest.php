<?php

namespace Tests\Feature;

use App\Helpers\Privilege;
use App\Models\StockArticle;
use App\Models\UnlistedStock;
use App\Models\UnlistedWittyScore;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Public /unlisted-shares/{slug}/about|thesis|wittyscore/ pages rendered from
 * CMS articles, the "being written" fallback, previews, sitemap and llms.txt.
 * Uses DatabaseTransactions against the local MySQL DB — never RefreshDatabase.
 */
class StockPagesPublicTest extends TestCase
{
    use DatabaseTransactions;

    private function makeStock(array $attrs = []): UnlistedStock
    {
        return UnlistedStock::create($attrs + [
            'UL_STOCKS_COMPNAME' => 'Public Page Co', 'UL_STOCKS_SLUG' => 'public-page-co-' . uniqid(),
            'UL_STOCKS_COMPNAME_TYPE' => 'unlisted', 'UL_STOCKS_STATUS' => '1',
        ]);
    }

    private function publish(UnlistedStock $stock, string $type, array $attrs = []): StockArticle
    {
        return StockArticle::create($attrs + [
            'ul_stocks_fincode' => $stock->UL_STOCKS_FINCODE,
            'type'              => $type,
            'title'             => StockArticle::defaultTitle($type, $stock->UL_STOCKS_COMPNAME),
            'summary'           => 'Short summary.',
            'content'           => '<h2>Business</h2><p>What it does.</p>',
            'meta_title'        => 'Meta for ' . $type,
            'meta_description'  => 'Desc.',
            'status'            => 'published',
            'published_at'      => now(),
        ]);
    }

    public function test_unwritten_page_shows_a_noindex_placeholder(): void
    {
        $stock = $this->makeStock();
        $base  = "/unlisted-shares/{$stock->UL_STOCKS_SLUG}";

        foreach (['about' => 'Company Profile', 'thesis' => 'Investment Thesis', 'wittyscore' => 'WittyScore Explained'] as $type => $suffix) {
            $this->get("{$base}/{$type}/")
                ->assertOk()
                ->assertHeader('X-Robots-Tag', 'noindex, follow')
                ->assertSee("Public Page Co: {$suffix}")
                ->assertSee('still writing this page')
                ->assertSee("{$base}/", false);
        }

        // A draft is still "being written" to the public.
        $this->publish($stock, 'thesis', ['status' => 'draft', 'published_at' => null, 'content' => '<p>Secret draft</p>']);
        $this->get("{$base}/thesis/")->assertOk()->assertSee('still writing this page')->assertDontSee('Secret draft');
    }

    public function test_published_page_renders_like_a_blog_post(): void
    {
        $stock = $this->makeStock();
        $this->publish($stock, 'about', [
            'chips'     => ['Energy'],
            'takeaways' => ['Big refiner'],
            'faqs'      => [['tab' => 'General', 'q' => 'Is it listed?', 'a' => 'Not yet.']],
            'intro'     => 'Opening line.',
        ]);
        $thesis = $this->publish($stock, 'thesis');

        $this->get("/unlisted-shares/{$stock->UL_STOCKS_SLUG}/about/")
            ->assertOk()
            ->assertHeaderMissing('X-Robots-Tag')
            ->assertSee('<title>Meta for about | StocksWitty</title>', false)
            ->assertSee('Public Page Co: Company Profile')
            ->assertSee('What it does.')
            ->assertSee('Big refiner')
            ->assertSee('Is it listed?')
            ->assertSee('Energy')
            ->assertSee('"@type":"Article"', false)
            // Breadcrumb back to the company, and the sibling thesis as related reading.
            ->assertSee("/unlisted-shares/{$stock->UL_STOCKS_SLUG}/", false)
            ->assertSee($thesis->url(), false)
            ->assertSee('Interested in Public Page Co unlisted shares?')
            ->assertDontSee('still writing this page');
    }

    public function test_wittyscore_page_leads_with_the_score_breakdown(): void
    {
        $stock = $this->makeStock();
        $this->publish($stock, 'wittyscore');

        $this->get("/unlisted-shares/{$stock->UL_STOCKS_SLUG}/wittyscore/")->assertOk()->assertDontSee('score-breakdown');

        UnlistedWittyScore::insert(['UL_WS_FINCODE' => $stock->UL_STOCKS_FINCODE, 'UL_WS_FINANCIAL_HEALTH' => 8, 'UL_WS_VALUATION' => 6, 'UL_WS_ACTIVE' => '1']);

        $this->get("/unlisted-shares/{$stock->UL_STOCKS_SLUG}/wittyscore/")
            ->assertOk()
            ->assertSee('id="score-breakdown"', false)
            ->assertSee('Score breakdown')
            ->assertSee('7.2')            // (8×30 + 6×20) / 50
            ->assertSee('Financial Health');
    }

    public function test_inactive_or_unknown_stock_is_not_found(): void
    {
        $stock = $this->makeStock(['UL_STOCKS_STATUS' => '0']);
        $this->publish($stock, 'about');

        $this->get("/unlisted-shares/{$stock->UL_STOCKS_SLUG}/about/")->assertNotFound();
        $this->get('/unlisted-shares/no-such-company-' . uniqid() . '/thesis/')->assertNotFound();
    }

    public function test_editors_can_preview_drafts(): void
    {
        $draft = $this->publish($this->makeStock(), 'thesis', ['status' => 'draft', 'published_at' => null, 'content' => '<p>Draft body</p>']);

        $this->get("/admin/stock-pages/{$draft->id}/preview")->assertRedirect(route('login'));

        $author = User::create([
            'name' => 'Previewer', 'email' => 'prev_' . uniqid() . '@example.test', 'phone' => '9' . random_int(100000000, 999999999),
            'unlisted_user_type' => 'unlisted', 'password' => 'secret-password', 'privilege' => ['author' => true],
        ]);
        (new \ReflectionProperty(Privilege::class, 'cache'))->setValue(null, null);

        $this->withSession(['uid' => $author->uid, 'privilege' => $author->privilege])
            ->get("/admin/stock-pages/{$draft->id}/preview")
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('Draft body')
            ->assertSee('draft — not visible to the public');
    }

    public function test_sitemap_and_llms_list_only_published_pages(): void
    {
        $stock = $this->makeStock(['UL_STOCKS_COMPNAME' => 'Sitemap Page Co']);
        $about = $this->publish($stock, 'about');
        $this->publish($stock, 'thesis', ['status' => 'draft', 'published_at' => null]);
        $base  = 'https://www.stockswitty.com/unlisted-shares/' . $stock->UL_STOCKS_SLUG;

        $this->get('/sitemap.xml')->assertOk()
            ->assertSee("<loc>{$base}/</loc>", false)
            ->assertSee("<loc>{$base}/about/</loc>", false)
            ->assertDontSee("<loc>{$base}/thesis/</loc>", false)
            ->assertDontSee("<loc>{$base}/wittyscore/</loc>", false);

        $llms = $this->get('/llms.txt')->assertOk()->getContent();
        $this->assertStringContainsString("- [Sitemap Page Co]({$base}/) · [About]({$base}/about/)\n", $llms);
        $this->assertStringNotContainsString("{$base}/thesis/", $llms);
        $this->assertSame($about->url(), "/unlisted-shares/{$stock->UL_STOCKS_SLUG}/about/");
    }
}
