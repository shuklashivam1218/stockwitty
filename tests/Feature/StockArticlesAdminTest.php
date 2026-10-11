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
 * CMS for per-stock About / Thesis / WittyScore pages: who gets in, one page
 * per stock per type, publishing, pillar scores and the shared edit lock.
 * Uses DatabaseTransactions against the local MySQL DB — never RefreshDatabase.
 */
class StockArticlesAdminTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resetPrivilegeCache();
    }

    private function resetPrivilegeCache(): void
    {
        (new \ReflectionProperty(Privilege::class, 'cache'))->setValue(null, null);
    }

    private function makeUser(array $privilege): User
    {
        return User::create([
            'name' => 'CMS Tester', 'email' => 'cms_' . uniqid() . '@example.test',
            'phone' => '9' . random_int(100000000, 999999999), 'unlisted_user_type' => 'unlisted',
            'password' => 'secret-password', 'privilege' => $privilege,
        ]);
    }

    private function actingAsEditor(?User $user = null): static
    {
        $user ??= $this->makeUser(['author' => true]);
        $this->resetPrivilegeCache();

        return $this->withSession(['uid' => $user->uid, 'name' => $user->name, 'privilege' => $user->privilege]);
    }

    private function makeStock(string $name = 'Page Test Co'): UnlistedStock
    {
        return UnlistedStock::create([
            'UL_STOCKS_COMPNAME' => $name, 'UL_STOCKS_SLUG' => 'page-test-co-' . uniqid(),
            'UL_STOCKS_COMPNAME_TYPE' => 'unlisted', 'UL_STOCKS_STATUS' => '1',
        ]);
    }

    private function publishable(array $overrides = []): array
    {
        return $overrides + [
            'title'            => 'Page Test Co: Investment Thesis',
            'summary'          => 'Why we like it.',
            'content'          => '<h2>The case</h2><p>Body.</p>',
            'meta_title'       => 'Page Test Co thesis',
            'meta_description' => 'Our thesis.',
            'status'           => 'published',
        ];
    }

    public function test_only_authors_and_reviewers_get_in(): void
    {
        $this->get('/admin/stock-pages/thesis')->assertRedirect(route('login'));

        foreach ([['admin' => true], ['unlisted' => ['unlisted_stocks' => true]]] as $priv) {
            $this->actingAsEditor($this->makeUser($priv))->get('/admin/stock-pages/thesis')->assertForbidden();
        }

        foreach (['about', 'thesis', 'wittyscore'] as $type) {
            $this->actingAsEditor()->get("/admin/stock-pages/{$type}")->assertOk();
            $this->actingAsEditor($this->makeUser(['reviewer' => true]))->get("/admin/stock-pages/{$type}/create")->assertOk();
        }
        $this->actingAsEditor()->get('/admin/stock-pages/nonsense')->assertNotFound();

        // The CMS menu lists the three page types under the blog links.
        $this->actingAsEditor()->get('/admin/blog')
            ->assertSee('/admin/stock-pages/about')->assertSee('/admin/stock-pages/thesis')->assertSee('/admin/stock-pages/wittyscore');
    }

    public function test_draft_for_a_stock_then_publish(): void
    {
        $author  = $this->makeUser(['author' => true]);
        $stock   = $this->makeStock();
        $fincode = $stock->UL_STOCKS_FINCODE;

        $this->actingAsEditor()->get('/admin/stock-pages/thesis/create?stock=' . $fincode)
            ->assertOk()->assertSee('Page Test Co');

        $this->actingAsEditor($author)->post('/admin/stock-pages/thesis', [
            'ul_stocks_fincode' => $fincode, 'title' => 'Draft thesis', 'status' => 'draft',
            'chips_text' => 'Unlisted, Energy', 'faqs' => [['tab' => 'Basics', 'q' => 'Q?', 'a' => 'A.'], ['q' => '', 'a' => '']],
        ])->assertRedirect()->assertSessionHasNoErrors();

        $article = StockArticle::where('ul_stocks_fincode', $fincode)->sole();
        $this->assertSame('thesis', $article->type);
        $this->assertSame('draft', $article->status);
        $this->assertSame($author->uid, $article->created_by);
        $this->assertSame(['Unlisted', 'Energy'], $article->chips);
        $this->assertCount(1, $article->faqs);

        // Publishing from the list needs the public fields first.
        $this->actingAsEditor($author)->postJson("/admin/stock-pages/{$article->id}/publish-toggle")->assertStatus(422);

        $this->actingAsEditor($author)->put("/admin/stock-pages/{$article->id}", $this->publishable([
            'content' => '<h2>The case</h2><p onclick="x()">Body.</p><script>alert(1)</script>',
        ]))->assertRedirect("/admin/stock-pages/{$article->id}/edit")->assertSessionHasNoErrors();

        $article->refresh();
        $this->assertTrue($article->isPublished());
        $this->assertNotNull($article->published_at);
        $this->assertStringNotContainsString('<script', $article->content);
        $this->assertStringNotContainsString('onclick', $article->content);

        $this->actingAsEditor()->get('/admin/stock-pages/thesis?status=published')->assertSee('Page Test Co');
    }

    public function test_one_page_per_stock_per_type(): void
    {
        $stock = $this->makeStock();
        $this->actingAsEditor()->post('/admin/stock-pages/about', ['ul_stocks_fincode' => $stock->UL_STOCKS_FINCODE, 'title' => 'One', 'status' => 'draft']);
        $this->actingAsEditor()->post('/admin/stock-pages/thesis', ['ul_stocks_fincode' => $stock->UL_STOCKS_FINCODE, 'title' => 'Other type', 'status' => 'draft'])
            ->assertSessionHasNoErrors();

        $this->actingAsEditor()->post('/admin/stock-pages/about', ['ul_stocks_fincode' => $stock->UL_STOCKS_FINCODE, 'title' => 'Two', 'status' => 'draft'])
            ->assertSessionHasErrors('ul_stocks_fincode');

        // A trashed page still holds the slot: restore it rather than start again.
        $about = StockArticle::where('ul_stocks_fincode', $stock->UL_STOCKS_FINCODE)->where('type', 'about')->sole();
        $this->actingAsEditor()->deleteJson("/admin/stock-pages/{$about->id}")->assertOk();
        $this->actingAsEditor()->post('/admin/stock-pages/about', ['ul_stocks_fincode' => $stock->UL_STOCKS_FINCODE, 'title' => 'Three', 'status' => 'draft'])
            ->assertSessionHasErrors('ul_stocks_fincode');
        $this->actingAsEditor()->get('/admin/stock-pages/about/create')->assertDontSee('value="' . $stock->UL_STOCKS_FINCODE . '"', false);

        $this->actingAsEditor()->postJson("/admin/stock-pages/{$about->id}/restore")->assertOk();
        $this->assertFalse($about->fresh()->trashed());

        $this->actingAsEditor()->post('/admin/stock-pages/about', ['title' => 'No stock', 'status' => 'draft'])->assertSessionHasErrors('ul_stocks_fincode');
    }

    public function test_wittyscore_page_saves_the_pillar_scores(): void
    {
        $editor  = $this->makeUser(['author' => true]);
        $stock   = $this->makeStock();
        $fincode = $stock->UL_STOCKS_FINCODE;

        $this->actingAsEditor($editor)->post('/admin/stock-pages/wittyscore', [
            'ul_stocks_fincode' => $fincode, 'title' => 'Score', 'status' => 'draft',
            'UL_WS_FINANCIAL_HEALTH' => '7.5', 'UL_WS_VALUATION' => '6',
        ])->assertSessionHasNoErrors();

        $score = UnlistedWittyScore::where('UL_WS_FINCODE', $fincode)->sole();
        $this->assertEquals(7.5, $score->UL_WS_FINANCIAL_HEALTH);
        $this->assertSame('1', $score->UL_WS_ACTIVE);

        $article = StockArticle::where('ul_stocks_fincode', $fincode)->sole();
        $this->actingAsEditor($editor)->get("/admin/stock-pages/{$article->id}/edit")->assertOk()->assertSee('Pillar Scores')->assertSee('value="7.5"', false);

        $this->actingAsEditor($editor)->put("/admin/stock-pages/{$article->id}", [
            'title' => 'Score', 'status' => 'draft', 'UL_WS_FINANCIAL_HEALTH' => '8', 'UL_WS_VALUATION' => '11',
        ])->assertSessionHasErrors('UL_WS_VALUATION');
        $this->assertEquals(7.5, $score->fresh()->UL_WS_FINANCIAL_HEALTH);

        $this->actingAsEditor($editor)->put("/admin/stock-pages/{$article->id}", [
            'title' => 'Score', 'status' => 'draft', 'UL_WS_FINANCIAL_HEALTH' => '8', 'UL_WS_VALUATION' => '',
        ])->assertSessionHasNoErrors();
        $this->assertSame(1, UnlistedWittyScore::where('UL_WS_FINCODE', $fincode)->count());
        $this->assertEquals(8, $score->fresh()->UL_WS_FINANCIAL_HEALTH);
        $this->assertNull($score->fresh()->UL_WS_VALUATION);

        // Other page types never touch the scores.
        $this->actingAsEditor($editor)->post('/admin/stock-pages/thesis', [
            'ul_stocks_fincode' => $fincode, 'title' => 'T', 'status' => 'draft', 'UL_WS_FINANCIAL_HEALTH' => '1',
        ])->assertSessionHasNoErrors();
        $this->assertEquals(8, $score->fresh()->UL_WS_FINANCIAL_HEALTH);
    }

    public function test_second_editor_is_locked_out(): void
    {
        $alice   = $this->makeUser(['author' => true]);
        $bob     = $this->makeUser(['reviewer' => true]);
        $article = StockArticle::create([
            'ul_stocks_fincode' => $this->makeStock()->UL_STOCKS_FINCODE, 'type' => 'about', 'title' => 'Locked', 'created_by' => $alice->uid,
        ]);

        $this->actingAsEditor($alice)->get("/admin/stock-pages/{$article->id}/edit")->assertOk()->assertDontSee('is currently editing this page');
        $this->actingAsEditor($bob)->get("/admin/stock-pages/{$article->id}/edit")->assertOk()->assertSee('is currently editing this page');

        $this->actingAsEditor($bob)->put("/admin/stock-pages/{$article->id}", ['title' => 'Overwritten', 'status' => 'draft'])->assertSessionHas('lock_error');
        $this->actingAsEditor($bob)->postJson("/admin/stock-pages/{$article->id}/publish-toggle")->assertStatus(423);
        $this->actingAsEditor($bob)->deleteJson("/admin/stock-pages/{$article->id}")->assertStatus(423);
        $this->assertSame('Locked', $article->fresh()->title);

        $this->actingAsEditor($alice)->postJson("/admin/stock-pages/{$article->id}/release-lock")->assertOk();
        $this->actingAsEditor($bob)->get("/admin/stock-pages/{$article->id}/edit")->assertDontSee('is currently editing this page');
    }
}
