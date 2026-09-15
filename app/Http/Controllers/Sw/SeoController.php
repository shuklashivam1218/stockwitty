<?php

namespace App\Http\Controllers\Sw;

use App\Http\Controllers\Controller;
use App\Models\UnlistedStock;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

/**
 * Serves /sitemap.xml and /llms.txt. Both are generated on request rather
 * than committed as static files so the unlisted-company list (128+ and
 * growing) can never drift from what's actually live in unlisted_stocks.
 */
class SeoController extends Controller
{
    private const BASE_URL = 'https://www.stockswitty.com';

    /**
     * Static routes, keyed by the Blade view they render. lastmod is read
     * from the view file's mtime so it tracks real content edits instead
     * of a hand-maintained date that goes stale.
     */
    private const PAGES = [
        ['path' => '/',                                           'view' => 'sw.home',                                        'changefreq' => 'weekly',  'priority' => '1.0'],
        ['path' => '/unlisted-shares/',                            'view' => 'sw.unlisted-shares.index',                       'changefreq' => 'weekly',  'priority' => '0.9'],
        ['path' => '/screener/',                                   'view' => 'sw.screener.index',                              'changefreq' => 'weekly',  'priority' => '0.9'],
        ['path' => '/compare/',                                    'view' => 'sw.compare.index',                               'changefreq' => 'weekly',  'priority' => '0.9'],
        ['path' => '/compare/nse-india-vs-nayara-energy/',         'view' => 'sw.compare.nse-india-vs-nayara-energy',          'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/calculators/',                                'view' => 'sw.calculators.index',                           'changefreq' => 'weekly',  'priority' => '0.9'],
        ['path' => '/wittyscore/',                                 'view' => 'sw.wittyscore.index',                            'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/why-witty/',                                  'view' => 'sw.why-witty.index',                             'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/listed/',                                     'view' => 'sw.listed.index',                                'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/listed/reliance/',                            'view' => 'sw.listed.reliance',                             'changefreq' => 'weekly',  'priority' => '0.6'],
        ['path' => '/mutual-funds/',                               'view' => 'sw.mutual-funds.index',                          'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/pms/',                                        'view' => 'sw.pms.index',                                   'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/fixed-deposits/',                             'view' => 'sw.fixed-deposits.index',                        'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/fixed-deposits/suryoday/',                    'view' => 'sw.fixed-deposits.suryoday',                     'changefreq' => 'monthly', 'priority' => '0.6'],
        ['path' => '/digital-gold/',                               'view' => 'sw.digital-gold.index',                          'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/digital-silver/',                             'view' => 'sw.digital-silver.index',                        'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/etf/',                                        'view' => 'sw.etf.index',                                   'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/blog/',                                       'view' => 'sw.blog.index',                                  'changefreq' => 'weekly',  'priority' => '0.9'],
        ['path' => '/blog/what-are-unlisted-shares/',              'view' => 'sw.blog.what-are-unlisted-shares',               'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/blog/how-to-buy-unlisted-shares/',            'view' => 'sw.blog.how-to-buy-unlisted-shares',             'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/blog/how-to-sell-unlisted-shares/',           'view' => 'sw.blog.how-to-sell-unlisted-shares',            'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/blog/unlisted-shares-vs-listed-shares/',      'view' => 'sw.blog.unlisted-shares-vs-listed-shares',       'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/blog/tax-on-unlisted-shares/',                'view' => 'sw.blog.tax-on-unlisted-shares',                 'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/blog/is-it-safe-to-buy-unlisted-shares/',     'view' => 'sw.blog.is-it-safe-to-buy-unlisted-shares',      'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/blog/risks-of-investing-in-unlisted-shares/', 'view' => 'sw.blog.risks-of-investing-in-unlisted-shares',  'changefreq' => 'monthly', 'priority' => '0.7'],
        ['path' => '/news/',                                       'view' => 'sw.news.index',                                  'changefreq' => 'weekly',  'priority' => '0.9'],
        ['path' => '/news/nse-ipo-sebi-noc-2026/',                 'view' => 'sw.news.nse-ipo-sebi-noc-2026',                  'changefreq' => 'weekly',  'priority' => '0.6'],
        ['path' => '/case-studies/',                               'view' => 'sw.case-studies.index',                          'changefreq' => 'weekly',  'priority' => '0.9'],
        ['path' => '/case-studies/nse-pre-ipo-journey/',           'view' => 'sw.case-studies.nse-pre-ipo-journey',            'changefreq' => 'monthly', 'priority' => '0.6'],
        ['path' => '/case-studies/first-time-unlisted-kyc/',       'view' => 'sw.case-studies.first-time-unlisted-kyc',        'changefreq' => 'monthly', 'priority' => '0.6'],
        ['path' => '/case-studies/research-over-hype/',            'view' => 'sw.case-studies.research-over-hype',             'changefreq' => 'monthly', 'priority' => '0.6'],
    ];

    public function sitemap(): Response
    {
        $urls = [];

        foreach (self::PAGES as $page) {
            $urls[] = [
                'loc'        => self::BASE_URL . $page['path'],
                'lastmod'    => $this->viewLastMod($page['view']),
                'changefreq' => $page['changefreq'],
                'priority'   => $page['priority'],
            ];
        }

        foreach ($this->activeCompanies() as $company) {
            $path    = "/unlisted-shares/{$company->UL_STOCKS_SLUG}/";
            $lastmod = $company->UL_STOCKS_UPDATE_TIME?->format('Y-m-d') ?? now()->toDateString();

            $urls[] = ['loc' => self::BASE_URL . $path,           'lastmod' => $lastmod, 'changefreq' => 'weekly',  'priority' => '0.8'];
            $urls[] = ['loc' => self::BASE_URL . $path . 'about/',  'lastmod' => $lastmod, 'changefreq' => 'monthly', 'priority' => '0.6'];
            $urls[] = ['loc' => self::BASE_URL . $path . 'thesis/', 'lastmod' => $lastmod, 'changefreq' => 'monthly', 'priority' => '0.6'];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
             . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n"
                  . '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . "</loc>\n"
                  . '    <lastmod>' . $url['lastmod'] . "</lastmod>\n"
                  . '    <changefreq>' . $url['changefreq'] . "</changefreq>\n"
                  . '    <priority>' . $url['priority'] . "</priority>\n"
                  . "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function llms(): Response
    {
        $text = view('sw.seo.llms', ['companies' => $this->activeCompanies()])->render();

        return response($text, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    private function activeCompanies(): Collection
    {
        return UnlistedStock::where('UL_STOCKS_STATUS', '1')
            ->orderBy('UL_STOCKS_COMPNAME')
            ->get(['UL_STOCKS_SLUG', 'UL_STOCKS_COMPNAME', 'UL_STOCKS_UPDATE_TIME']);
    }

    private function viewLastMod(string $view): string
    {
        $path = resource_path('views/' . str_replace('.', '/', $view) . '.blade.php');

        return file_exists($path) ? date('Y-m-d', filemtime($path)) : now()->toDateString();
    }
}
