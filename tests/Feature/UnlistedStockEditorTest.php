<?php

namespace Tests\Feature;

use App\Helpers\Privilege;
use App\Models\StockArticle;
use App\Models\UnlistedFaq;
use App\Models\UnlistedStock;
use App\Models\UnlistedWittyScore;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * The one-page unlisted stock editor: who gets in, and that a single save
 * writes every section the way the public company pages read it.
 * Uses DatabaseTransactions against the local MySQL DB — never RefreshDatabase.
 */
class UnlistedStockEditorTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resetPrivilegeCache();
    }

    /** Privilege::get() memoises per process; each acting user needs a fresh read. */
    private function resetPrivilegeCache(): void
    {
        (new \ReflectionProperty(Privilege::class, 'cache'))->setValue(null, null);
    }

    private function makeUser(array $privilege): User
    {
        return User::create([
            'name' => 'Stock Editor', 'email' => 'stock_' . uniqid() . '@example.test',
            'phone' => '9' . random_int(100000000, 999999999), 'unlisted_user_type' => 'unlisted',
            'password' => 'secret-password', 'privilege' => $privilege,
        ]);
    }

    private function actingAsAgent(?User $user = null): static
    {
        $user ??= $this->makeUser(['unlisted' => ['unlisted_stocks' => true]]);
        $this->resetPrivilegeCache();

        return $this->withSession(['uid' => $user->uid, 'name' => $user->name, 'privilege' => $user->privilege]);
    }

    private function makeStock(array $overrides = []): UnlistedStock
    {
        return UnlistedStock::create($overrides + [
            'UL_STOCKS_COMPNAME'      => 'Editor Test Co',
            'UL_STOCKS_SLUG'          => 'editor-test-co-' . uniqid(),
            'UL_STOCKS_COMPNAME_TYPE' => 'unlisted',
            'UL_STOCKS_STATUS'        => '1',
        ]);
    }

    /** The minimum the form always sends: name, status and every section switch. */
    private function form(array $overrides = []): array
    {
        return $overrides + [
            'UL_STOCKS_COMPNAME' => 'Editor Test Co',
            'UL_STOCKS_STATUS'   => '1',
            'UL_CI_ACTIVE'       => '1',
            'UL_SEO_ACTIVE'      => '1',
        ];
    }

    public function test_only_stock_agents_get_in(): void
    {
        $stock = $this->makeStock();
        $url   = "/admin/unlisted/stocks/{$stock->UL_STOCKS_FINCODE}/edit";

        $this->get($url)->assertRedirect(route('login'));
        $this->actingAsAgent($this->makeUser(['unlisted' => ['leads' => true]]))->get($url)->assertForbidden();
        $this->actingAsAgent($this->makeUser(['unlisted' => ['leads' => true]]))
            ->put("/admin/unlisted/stocks/{$stock->UL_STOCKS_FINCODE}", $this->form(['UL_STOCKS_COMPNAME' => 'Hijacked']))
            ->assertForbidden();
        $this->assertSame('Editor Test Co', $stock->fresh()->UL_STOCKS_COMPNAME);

        $this->actingAsAgent()->get($url)->assertOk()->assertSee('Save all changes');
        // Same gate as the dashboard: the route needs an unlisted sub-privilege.
        $this->actingAsAgent($this->makeUser(['user_master' => true]))->get($url)->assertForbidden();
        $this->actingAsAgent($this->makeUser(['user_master' => true]))->get('/admin/unlisted')->assertForbidden();
    }

    public function test_one_save_writes_basics_sections_and_faqs(): void
    {
        $stock   = $this->makeStock();
        $fincode = $stock->UL_STOCKS_FINCODE;

        $this->actingAsAgent()->put("/admin/unlisted/stocks/{$fincode}", $this->form([
            'UL_STOCKS_COMPNAME'     => 'Renamed Co',
            'UL_STOCKS_TAG'          => 'Hot',
            'UL_STOCKS_ABOUT'        => '<p>Profile</p>',
            'UL_CI_AI_SUMMARY'       => 'Thirty-second summary.',
            'UL_SEO_COMPANY_TITLE'   => 'Buy Renamed Co shares',
            // A stale form still sending the moved sections: none of it is written.
            'UL_WS_FINANCIAL_HEALTH' => '7.5',
            'UL_THESIS_CONTENT'      => '<p>Old thesis box</p>',
            'UL_CI_TLDR'             => 'Old thesis TL;DR',
            'UL_SEO_ABOUT_TITLE'     => 'Old about meta',
            'faqs' => [
                ['q' => 'What is it?', 'a' => 'A company.', 'active' => '1'],
                ['tab' => 'Risks', 'q' => 'Is it risky?', 'active' => '0'],
                ['q' => '', 'a' => ''], // blank row: dropped, not an error
            ],
        ]))->assertRedirect("/admin/unlisted/stocks/{$fincode}/edit")->assertSessionHasNoErrors();

        $stock->refresh();
        $this->assertSame('Renamed Co', $stock->UL_STOCKS_COMPNAME);
        $this->assertSame('Hot', $stock->UL_STOCKS_TAG);
        $this->assertSame('<p>Profile</p>', $stock->UL_STOCKS_ABOUT);

        $insight = DB::table('unlisted_company_insights')->where('UL_CI_FINCODE', $fincode)->first();
        $this->assertSame('Thirty-second summary.', $insight->UL_CI_AI_SUMMARY);
        $this->assertNull($insight->UL_CI_TLDR);

        $seo = DB::table('unlisted_seo_meta')->where('UL_SEO_FINCODE', $fincode)->first();
        $this->assertSame('Buy Renamed Co shares', $seo->UL_SEO_COMPANY_TITLE);
        $this->assertNull($seo->UL_SEO_ABOUT_TITLE);

        // About / Thesis / WittyScore are CMS pages now; the stock editor never touches them.
        $this->assertFalse(UnlistedWittyScore::where('UL_WS_FINCODE', $fincode)->exists());
        $this->assertFalse(DB::table('unlisted_thesis')->where('UL_THESIS_FINCODE', $fincode)->exists());

        $faqs = UnlistedFaq::where('UL_FAQ_FINCODE', $fincode)->orderBy('UL_FAQ_SORT_ORDER')->get();
        $this->assertCount(2, $faqs);
        $this->assertSame(['What is it?', 'Is it risky?'], $faqs->pluck('UL_FAQ_QUESTION')->all());
        $this->assertSame(['1', '0'], $faqs->pluck('UL_FAQ_ACTIVE')->all());
        $this->assertSame(['overview', 'overview'], $faqs->pluck('UL_FAQ_TARGET')->all());
        $this->assertSame('Risks', $faqs[1]->UL_FAQ_TAB);

        // The editor no longer carries the sections that moved to the CMS.
        $this->actingAsAgent()->get("/admin/unlisted/stocks/{$fincode}/edit")
            ->assertOk()->assertSee('Insights')
            ->assertDontSee('Investment Thesis')->assertDontSee('Old About')->assertDontSee('name="UL_WS_VALUATION"', false);
    }

    public function test_switches_turn_a_saved_section_and_the_stock_off(): void
    {
        $stock   = $this->makeStock();
        $fincode = $stock->UL_STOCKS_FINCODE;
        DB::table('unlisted_company_insights')->insert(['UL_CI_FINCODE' => $fincode, 'UL_CI_AI_SUMMARY' => 'Summary', 'UL_CI_ACTIVE' => '1']);

        $this->actingAsAgent()->put("/admin/unlisted/stocks/{$fincode}", $this->form([
            'UL_STOCKS_STATUS' => '0',
            'UL_CI_ACTIVE'     => '0',
            'UL_CI_AI_SUMMARY' => 'Summary',
        ]))->assertSessionHasNoErrors();

        $this->assertSame('0', $stock->fresh()->UL_STOCKS_STATUS);
        $this->assertSame(1, DB::table('unlisted_company_insights')->where('UL_CI_FINCODE', $fincode)->count());
        $this->assertSame('0', DB::table('unlisted_company_insights')->where('UL_CI_FINCODE', $fincode)->value('UL_CI_ACTIVE'));
    }

    public function test_faq_rows_on_the_page_are_the_company_page_list(): void
    {
        $stock   = $this->makeStock();
        $fincode = $stock->UL_STOCKS_FINCODE;
        $other   = $this->makeStock(['UL_STOCKS_COMPNAME' => 'Someone Else']);

        $keep   = UnlistedFaq::create(['UL_FAQ_FINCODE' => $fincode, 'UL_FAQ_TARGET' => 'overview', 'UL_FAQ_QUESTION' => 'Old Q', 'UL_FAQ_SORT_ORDER' => 1, 'UL_FAQ_ACTIVE' => '1']);
        $drop   = UnlistedFaq::create(['UL_FAQ_FINCODE' => $fincode, 'UL_FAQ_TARGET' => 'overview', 'UL_FAQ_QUESTION' => 'Gone', 'UL_FAQ_SORT_ORDER' => 2, 'UL_FAQ_ACTIVE' => '1']);
        // An old FAQ aimed at the About page: not shown here, never touched.
        $aboutQ = UnlistedFaq::create(['UL_FAQ_FINCODE' => $fincode, 'UL_FAQ_TARGET' => 'about', 'UL_FAQ_QUESTION' => 'About Q', 'UL_FAQ_SORT_ORDER' => 1, 'UL_FAQ_ACTIVE' => '1']);
        $theirs = UnlistedFaq::create(['UL_FAQ_FINCODE' => $other->UL_STOCKS_FINCODE, 'UL_FAQ_TARGET' => 'overview', 'UL_FAQ_QUESTION' => 'Not yours', 'UL_FAQ_SORT_ORDER' => 1, 'UL_FAQ_ACTIVE' => '1']);

        $this->actingAsAgent()->get("/admin/unlisted/stocks/{$fincode}/edit")->assertSee('Old Q')->assertDontSee('About Q');

        $this->actingAsAgent()->put("/admin/unlisted/stocks/{$fincode}", $this->form([
            'faqs' => [
                ['q' => 'Brand new', 'active' => '1'],
                ['id' => $keep->UL_FAQ_ID, 'q' => 'Old Q, edited', 'active' => '1'],
                // Another stock's FAQ id (or the About one) is ignored: it becomes a new row here.
                ['id' => $theirs->UL_FAQ_ID, 'q' => 'Tampered', 'active' => '1'],
            ],
        ]))->assertSessionHasNoErrors();

        $this->assertNull(UnlistedFaq::find($drop->UL_FAQ_ID));
        $keep->refresh();
        $this->assertSame('Old Q, edited', $keep->UL_FAQ_QUESTION);
        $this->assertSame(2, (int) $keep->UL_FAQ_SORT_ORDER);
        $this->assertSame('Not yours', $theirs->fresh()->UL_FAQ_QUESTION);
        $this->assertSame('About Q', $aboutQ->fresh()->UL_FAQ_QUESTION);
        $this->assertSame(
            ['Brand new', 'Old Q, edited', 'Tampered'],
            UnlistedFaq::where('UL_FAQ_FINCODE', $fincode)->where('UL_FAQ_TARGET', 'overview')->orderBy('UL_FAQ_SORT_ORDER')->pluck('UL_FAQ_QUESTION')->all()
        );
    }

    public function test_slug_changes_only_when_free(): void
    {
        $taken = $this->makeStock(['UL_STOCKS_SLUG' => 'taken-slug-' . uniqid()]);
        $stock = $this->makeStock();
        $url   = "/admin/unlisted/stocks/{$stock->UL_STOCKS_FINCODE}";
        $old   = $stock->UL_STOCKS_SLUG;

        $this->actingAsAgent()->put($url, $this->form(['UL_STOCKS_SLUG' => $taken->UL_STOCKS_SLUG]))
            ->assertSessionHasErrors('UL_STOCKS_SLUG');
        $this->assertSame($old, $stock->fresh()->UL_STOCKS_SLUG);

        $new = 'Fresh Slug ' . uniqid();
        $this->actingAsAgent()->put($url, $this->form(['UL_STOCKS_SLUG' => $new]))->assertSessionHasNoErrors();
        $this->assertSame(\Illuminate\Support\Str::slug($new), $stock->fresh()->UL_STOCKS_SLUG);
    }

    public function test_price_list_feeds_the_existing_price_endpoints(): void
    {
        $stock   = $this->makeStock();
        $fincode = $stock->UL_STOCKS_FINCODE;
        DB::table('unlisted_price_data')->insert([
            ['UL_PD_FINCODE' => $fincode, 'UL_PD_DATE' => '2026-06-19 18:30:00', 'UL_PD_BID_PRICE' => 2065, 'UL_PD_INVALID_FLAG' => 0],
            ['UL_PD_FINCODE' => $fincode, 'UL_PD_DATE' => '2026-05-01 00:00:00', 'UL_PD_BID_PRICE' => 1900, 'UL_PD_INVALID_FLAG' => 1],
        ]);

        $this->actingAsAgent($this->makeUser(['unlisted' => ['leads' => true]]))
            ->getJson("/admin/unlisted/stocks/{$fincode}/prices")->assertForbidden();

        $rows = $this->actingAsAgent()->getJson("/admin/unlisted/stocks/{$fincode}/prices")
            ->assertOk()->assertJsonPath('total', 2)->json('rows');
        $this->assertSame(['19 Jun 2026', '01 May 2026'], array_column($rows, 'label'));
        $this->assertSame([true, false], array_column($rows, 'valid'));

        // The page sends `key` back URL-encoded, exactly like this.
        $key = rawurlencode($rows[0]['key']);
        $this->actingAsAgent()->patchJson("/admin/unlisted/stocks/{$fincode}/price/{$key}", ['UL_PD_BID_PRICE' => 2100])->assertOk();
        $this->actingAsAgent()->deleteJson("/admin/unlisted/stocks/{$fincode}/price/{$key}")->assertOk()->assertJsonPath('invalid', true);

        $row = DB::table('unlisted_price_data')->where('UL_PD_FINCODE', $fincode)->where('UL_PD_DATE', '2026-06-19 18:30:00')->first();
        $this->assertEquals(2100, $row->UL_PD_BID_PRICE);
        $this->assertEquals(1, $row->UL_PD_INVALID_FLAG);
    }

    public function test_financials_list_feeds_the_existing_financials_endpoints(): void
    {
        $stock   = $this->makeStock();
        $fincode = $stock->UL_STOCKS_FINCODE;
        $this->actingAsAgent()->postJson("/admin/unlisted/stocks/{$fincode}/financials", [
            'UL_FIN_Period_end' => 202503, 'UL_FIN_Type' => 'C', 'UL_FIN_No_months' => 12,
            'UL_FIN_Unit' => 10000000, 'UL_FIN_NET_SALES' => 500,
        ])->assertOk();

        $this->actingAsAgent()->get("/admin/unlisted/stocks/{$fincode}/edit")
            ->assertOk()->assertSee('Price History')->assertSee('Add period');

        $rows = $this->actingAsAgent()->getJson("/admin/unlisted/stocks/{$fincode}/financials")->assertOk()->json();
        $this->assertSame([[
            'periodEnd' => 202503, 'label' => '2025-03', 'type' => 'C', 'months' => 12,
            'unit' => '10000000 (1 Crore)', 'active' => true,
        ]], $rows);

        $key = "{$rows[0]['periodEnd']}/{$rows[0]['type']}/{$rows[0]['months']}";
        $this->actingAsAgent()->getJson("/admin/unlisted/stocks/{$fincode}/financials/{$key}/edit")
            ->assertOk()->assertJsonPath('UL_FIN_NET_SALES', 500);
        $this->actingAsAgent()->deleteJson("/admin/unlisted/stocks/{$fincode}/financials/{$key}")
            ->assertOk()->assertJsonPath('status', '0');
        $this->assertFalse($this->actingAsAgent()->getJson("/admin/unlisted/stocks/{$fincode}/financials")->json('0.active'));
    }

    public function test_new_stock_is_created_from_the_same_page(): void
    {
        $this->actingAsAgent($this->makeUser(['unlisted' => ['leads' => true]]))->get('/admin/unlisted/stocks/create')->assertForbidden();
        $this->actingAsAgent()->get('/admin/unlisted/stocks/create')
            ->assertOk()->assertSee('Create stock')->assertSee('Create the stock first');
        $this->actingAsAgent()->get('/admin/unlisted')->assertOk()->assertSee('/admin/unlisted/stocks/create');

        $name = 'Brand New Co ' . uniqid();
        $user = $this->makeUser(['unlisted' => ['unlisted_stocks' => true]]);
        $response = $this->actingAsAgent($user)->post('/admin/unlisted/stocks', [
            'UL_STOCKS_COMPNAME' => $name,
            'UL_STOCKS_STATUS'   => '0',
            'UL_CI_ACTIVE'       => '1',
            'UL_CI_AI_SUMMARY'   => 'New company summary.',
            'UL_SEO_ACTIVE'      => '1',
            'faqs' => [['q' => 'First question?', 'active' => '1']],
        ])->assertSessionHasNoErrors();

        $stock = UnlistedStock::where('UL_STOCKS_COMPNAME', $name)->firstOrFail();
        $response->assertRedirect("/admin/unlisted/stocks/{$stock->UL_STOCKS_FINCODE}/edit");
        $this->assertSame(\Illuminate\Support\Str::slug($name), $stock->UL_STOCKS_SLUG);
        $this->assertSame('0', $stock->UL_STOCKS_STATUS);
        $this->assertSame('unlisted', $stock->UL_STOCKS_COMPNAME_TYPE);
        $this->assertSame((string) $user->uid, (string) $stock->UL_STOCKS_INSERT_BY);
        $this->assertSame('Yes', $stock->UL_STOCKS_Qtr_Data_Publish);
        $this->assertSame('New company summary.', DB::table('unlisted_company_insights')->where('UL_CI_FINCODE', $stock->UL_STOCKS_FINCODE)->value('UL_CI_AI_SUMMARY'));
        $this->assertFalse(DB::table('unlisted_seo_meta')->where('UL_SEO_FINCODE', $stock->UL_STOCKS_FINCODE)->exists());
        $this->assertSame(1, UnlistedFaq::where('UL_FAQ_FINCODE', $stock->UL_STOCKS_FINCODE)->count());

        // Same name again: the slug gets a suffix instead of colliding.
        $this->actingAsAgent()->post('/admin/unlisted/stocks', ['UL_STOCKS_COMPNAME' => $name])->assertSessionHasNoErrors();
        $this->assertTrue(UnlistedStock::where('UL_STOCKS_SLUG', $stock->UL_STOCKS_SLUG . '-2')->exists());
    }

    public function test_new_stock_with_a_taken_slug_is_refused(): void
    {
        $taken = $this->makeStock();
        $name  = 'Refused Co ' . uniqid();

        $this->actingAsAgent()->post('/admin/unlisted/stocks', [
            'UL_STOCKS_COMPNAME' => $name,
            'UL_STOCKS_SLUG'     => $taken->UL_STOCKS_SLUG,
        ])->assertSessionHasErrors('UL_STOCKS_SLUG');

        $this->actingAsAgent()->post('/admin/unlisted/stocks', ['UL_STOCKS_COMPNAME' => ''])
            ->assertSessionHasErrors('UL_STOCKS_COMPNAME');

        $this->assertFalse(UnlistedStock::where('UL_STOCKS_COMPNAME', $name)->exists());
    }

    public function test_dashboard_lists_searches_and_shows_what_is_filled(): void
    {
        $tag    = uniqid();
        $live   = $this->makeStock(['UL_STOCKS_COMPNAME' => "Dash Live {$tag}", 'UL_STOCKS_ABOUT' => '<p>x</p>']);
        $hidden = $this->makeStock(['UL_STOCKS_COMPNAME' => "Dash Hidden {$tag}", 'UL_STOCKS_STATUS' => '0']);
        // CMS pages: a published About and a draft Thesis.
        StockArticle::create(['ul_stocks_fincode' => $live->UL_STOCKS_FINCODE, 'type' => 'about', 'title' => 'A', 'status' => 'published', 'published_at' => now()]);
        StockArticle::create(['ul_stocks_fincode' => $live->UL_STOCKS_FINCODE, 'type' => 'thesis', 'title' => 'T']);
        DB::table('unlisted_price_data')->insert(['UL_PD_FINCODE' => $live->UL_STOCKS_FINCODE, 'UL_PD_DATE' => '2026-10-01', 'UL_PD_BID_PRICE' => 123.5, 'UL_PD_INVALID_FLAG' => 0]);

        $this->actingAsAgent()->get('/admin/unlisted?search=' . $tag)
            ->assertOk()
            ->assertSee("Dash Live {$tag}")->assertSee("Dash Hidden {$tag}")
            ->assertSee("/admin/unlisted/stocks/{$live->UL_STOCKS_FINCODE}/edit")
            ->assertSee('&#8377;123.50', false)
            ->assertSee('title="Profile: live on the site"', false)
            ->assertSee('title="About: live on the site"', false)
            ->assertSee('title="Thesis: saved but not live (switched off, or a draft)"', false)
            ->assertSee('title="WittyScore: not filled in yet"', false)
            ->assertSee('title="SEO: not filled in yet"', false)
            ->assertDontSee('ptf-icon-edit');

        $this->actingAsAgent()->get('/admin/unlisted?status=inactive&search=' . $tag)
            ->assertSee("Dash Hidden {$tag}")->assertDontSee("Dash Live {$tag}");
        $this->actingAsAgent()->get('/admin/unlisted?status=active&search=' . $tag)
            ->assertSee("Dash Live {$tag}")->assertDontSee("Dash Hidden {$tag}");
        $this->actingAsAgent()->get('/admin/unlisted?search=' . $hidden->UL_STOCKS_FINCODE)
            ->assertSee("Dash Hidden {$tag}");
    }

    public function test_stock_endpoints_need_the_stocks_privilege_and_old_modal_routes_are_gone(): void
    {
        $stock   = $this->makeStock();
        $fincode = $stock->UL_STOCKS_FINCODE;
        $leads   = $this->makeUser(['unlisted' => ['leads' => true]]);

        $this->actingAsAgent($leads)->postJson("/admin/unlisted/stocks/{$fincode}/price", ['UL_PD_DATE' => '2026-10-01', 'UL_PD_BID_PRICE' => 1])->assertForbidden();
        $this->actingAsAgent($leads)->postJson("/admin/unlisted/stocks/{$fincode}/toggle")->assertForbidden();
        $this->actingAsAgent($leads)->postJson("/admin/unlisted/stocks/{$fincode}/financials", ['UL_FIN_Period_end' => 202503, 'UL_FIN_Type' => 'C', 'UL_FIN_No_months' => 12])->assertForbidden();
        $this->actingAsAgent($leads)->postJson('/admin/unlisted/industries', ['name' => 'Nope'])->assertForbidden();
        $this->assertSame('1', $stock->fresh()->UL_STOCKS_STATUS);
        $this->assertFalse(DB::table('unlisted_price_data')->where('UL_PD_FINCODE', $fincode)->exists());

        $this->actingAsAgent()->postJson("/admin/unlisted/stocks/{$fincode}/toggle")->assertOk()->assertJsonPath('status', '0');

        foreach (['overview', 'thesis', 'about', 'witty-score', 'about-extra', 'seo', 'insights', 'faqs-list'] as $old) {
            $this->actingAsAgent()->get("/admin/unlisted/stocks/{$fincode}/{$old}")->assertNotFound();
        }
    }

    public function test_a_bad_field_saves_nothing(): void
    {
        $stock   = $this->makeStock();
        $fincode = $stock->UL_STOCKS_FINCODE;

        $this->actingAsAgent()->put("/admin/unlisted/stocks/{$fincode}", $this->form([
            'UL_STOCKS_COMPNAME'   => 'Should Not Stick',
            'UL_CI_AI_SUMMARY'     => 'Nope',
            'UL_SEO_COMPANY_TITLE' => str_repeat('x', 256),
            'faqs'                 => [['q' => '', 'a' => 'Answer without a question']],
        ]))->assertSessionHasErrors(['UL_SEO_COMPANY_TITLE', 'faqs.0.q']);

        $this->assertSame('Editor Test Co', $stock->fresh()->UL_STOCKS_COMPNAME);
        $this->assertFalse(DB::table('unlisted_company_insights')->where('UL_CI_FINCODE', $fincode)->exists());
        $this->assertFalse(UnlistedFaq::where('UL_FAQ_FINCODE', $fincode)->exists());
    }
}
