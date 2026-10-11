<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\StockArticle;
use App\Models\UnlistedStock;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Per-stock About / Thesis / WittyScore articles and the import of the old
 * rich-text content. Uses DatabaseTransactions against the local MySQL DB —
 * never RefreshDatabase.
 */
class StockArticleModelTest extends TestCase
{
    use DatabaseTransactions;

    private function makeStock(string $name = 'Article Test Co'): UnlistedStock
    {
        return UnlistedStock::create([
            'UL_STOCKS_COMPNAME'      => $name,
            'UL_STOCKS_SLUG'          => 'article-test-co-' . uniqid(),
            'UL_STOCKS_COMPNAME_TYPE' => 'unlisted',
            'UL_STOCKS_STATUS'        => '1',
        ]);
    }

    private function makeArticle(UnlistedStock $stock, string $type, array $attrs = []): StockArticle
    {
        return StockArticle::create($attrs + [
            'ul_stocks_fincode' => $stock->UL_STOCKS_FINCODE,
            'type'              => $type,
            'title'             => StockArticle::defaultTitle($type, $stock->UL_STOCKS_COMPNAME),
        ]);
    }

    public function test_article_shares_the_blog_post_behaviour(): void
    {
        $stock   = $this->makeStock('Nayara Energy');
        $article = $this->makeArticle($stock, StockArticle::TYPE_THESIS, [
            'content' => '<p>' . str_repeat('word ', 450) . '</p>',
            'faqs'    => [['tab' => 'Basics', 'q' => 'Q?', 'a' => 'A.'], ['tab' => 'Risks', 'q' => 'Q2?', 'a' => 'A2.']],
            'chips'   => ['Unlisted', 'Energy'],
        ])->fresh();

        $this->assertSame('Nayara Energy: Investment Thesis', $article->title);
        $this->assertSame(StockArticle::STATUS_DRAFT, $article->status);
        $this->assertSame(3, $article->reading_minutes);
        $this->assertSame('3 min read', $article->readLabel());
        $this->assertSame(['Unlisted', 'Energy'], $article->chips);
        $this->assertSame(['Basics', 'Risks'], $article->faqTabs());
        $this->assertSame("/unlisted-shares/{$stock->UL_STOCKS_SLUG}/thesis/", $article->url());
        $this->assertTrue($stock->articles()->ofType('thesis')->exists());

        $this->assertFalse(StockArticle::published()->whereKey($article->id)->exists());
        $article->update(['status' => StockArticle::STATUS_PUBLISHED, 'published_at' => now()]);
        $this->assertTrue(StockArticle::published()->whereKey($article->id)->exists());

        // Blog posts keep the same constants and behaviour through the shared trait.
        $this->assertSame(['draft', 'published'], BlogPost::STATUSES);
        $this->assertSame(StockArticle::STATUSES, BlogPost::STATUSES);
    }

    public function test_related_posts_are_published_blog_posts_in_picked_order(): void
    {
        $a     = BlogPost::create(['title' => 'A', 'slug' => 'rel-a-' . uniqid(), 'status' => 'published', 'published_at' => now()]);
        $b     = BlogPost::create(['title' => 'B', 'slug' => 'rel-b-' . uniqid(), 'status' => 'published', 'published_at' => now()]);
        $draft = BlogPost::create(['title' => 'D', 'slug' => 'rel-d-' . uniqid()]);

        $article = $this->makeArticle($this->makeStock(), 'about', ['related_post_ids' => [$b->id, $draft->id, $a->id]]);

        $this->assertSame([$b->id, $a->id], $article->relatedPosts()->pluck('id')->all());
    }

    public function test_one_article_per_stock_per_type_even_when_trashed(): void
    {
        $stock = $this->makeStock();
        $this->makeArticle($stock, 'about')->delete();
        $this->makeArticle($stock, 'thesis');

        $this->expectException(QueryException::class);
        $this->makeArticle($stock, 'about');
    }

    /**
     * The old about/thesis tables are dropped by the next migration, so this
     * environment no longer has them. (The import itself was covered while
     * they existed — see this test's history.) Without them it must be a
     * harmless no-op, e.g. on a fresh database or a re-run.
     */
    public function test_import_migration_is_a_no_op_once_old_tables_are_gone(): void
    {
        $this->assertFalse(Schema::hasTable('unlisted_thesis'));
        $this->assertFalse(Schema::hasTable('unlisted_about'));

        $before    = StockArticle::withTrashed()->count();
        $migration = require database_path('migrations/2026_10_11_120001_import_old_stock_about_and_thesis.php');
        $migration->up();

        $this->assertSame($before, StockArticle::withTrashed()->count());
    }
}
