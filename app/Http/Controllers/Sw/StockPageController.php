<?php

namespace App\Http\Controllers\Sw;

use App\Http\Controllers\Controller;
use App\Models\StockArticle;
use App\Models\UnlistedStock;
use App\Models\UnlistedWittyScore;
use App\Support\ArticlePage;

/**
 * /unlisted-shares/{slug}/about/, /thesis/ and /wittyscore/ — each stock's
 * pages written in the CMS, rendered like blog posts. Until one is published
 * the URL shows a short "being written" page, kept out of search, so the
 * links on the company page and homepage never break.
 */
class StockPageController extends Controller
{
    private const DEFAULT_LEAD_SUBTEXT = 'Leave your details and a StocksWitty specialist will call you back to explain current availability, pricing and the end-to-end process — with no obligation to buy.';

    public function show(string $slug, string $type)
    {
        $stock = UnlistedStock::where('UL_STOCKS_SLUG', $slug)->where('UL_STOCKS_STATUS', '1')->firstOrFail();

        $article = StockArticle::published()->ofType($type)
            ->where('ul_stocks_fincode', $stock->UL_STOCKS_FINCODE)
            ->with('author')
            ->first();

        if (!$article) {
            return response()
                ->view('sw.unlisted-shares.company.page-pending', $this->pending($stock, $type))
                ->header('X-Robots-Tag', 'noindex, follow');
        }

        return view('sw.unlisted-shares.company.page', $this->page($stock, $article));
    }

    /** Draft or trashed preview for CMS editors (admin route, author/reviewer only). */
    public function preview(int $id)
    {
        $article = StockArticle::withTrashed()->with('stock', 'author')->findOrFail($id);

        return response()
            ->view('sw.unlisted-shares.company.page', ['preview' => true] + $this->page($article->stock, $article))
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    private function page(UnlistedStock $stock, StockArticle $article): array
    {
        $name  = $stock->UL_STOCKS_COMPNAME;
        $label = StockArticle::typeLabel($article->type);

        // The WittyScore page leads with the stock's score breakdown when it has one.
        $score = $article->type === StockArticle::TYPE_WITTYSCORE
            ? UnlistedWittyScore::where('UL_WS_FINCODE', $stock->UL_STOCKS_FINCODE)->where('UL_WS_ACTIVE', '1')->orderByDesc('UL_WS_ID')->first()
            : null;
        $score = $score?->overall() !== null ? $score : null;

        return ArticlePage::data(
            $article,
            crumbs: $this->crumbs($stock, $label),
            chips: [$name, $label, 'Unlisted'],
            lead: ['heading' => 'Interested in ' . $name . ' unlisted shares?', 'subtext' => self::DEFAULT_LEAD_SUBTEXT],
            related: $this->siblings($stock, $article->type),
            tocBefore: $score ? [['id' => 'score-breakdown', 'label' => 'Score breakdown']] : [],
        ) + ['stock' => $stock, 'wittyScore' => $score];
    }

    private function pending(UnlistedStock $stock, string $type): array
    {
        $label = StockArticle::typeLabel($type);

        return [
            'stock'    => $stock,
            'type'     => $type,
            'title'    => StockArticle::defaultTitle($type, $stock->UL_STOCKS_COMPNAME),
            'crumbs'   => $this->crumbs($stock, $label),
            'siblings' => $this->siblings($stock, $type),
        ];
    }

    private function crumbs(UnlistedStock $stock, string $label): array
    {
        return [
            ['label' => 'Home', 'href' => '/'],
            ['label' => 'Unlisted Shares', 'href' => '/unlisted-shares/'],
            ['label' => $stock->UL_STOCKS_COMPNAME, 'href' => '/unlisted-shares/' . $stock->UL_STOCKS_SLUG . '/'],
            ['label' => $label],
        ];
    }

    /** The company page, then the stock's other published pages, as related-reading cards. */
    private function siblings(UnlistedStock $stock, string $currentType): array
    {
        $cards = [[
            'title'    => $stock->UL_STOCKS_COMPNAME . ' share price & overview',
            'href'     => '/unlisted-shares/' . $stock->UL_STOCKS_SLUG . '/',
            'category' => 'Company',
            'read'     => 'Live price',
        ]];

        $others = $stock->articles()->published()->where('type', '!=', $currentType)->get();
        foreach (array_keys(StockArticle::TYPES) as $type) {
            if ($article = $others->firstWhere('type', $type)) {
                $article->setRelation('stock', $stock);
                $cards[] = [
                    'title'    => $article->title,
                    'href'     => $article->url(),
                    'category' => StockArticle::typeLabel($type),
                    'read'     => $article->readLabel(),
                ];
            }
        }

        return $cards;
    }
}
