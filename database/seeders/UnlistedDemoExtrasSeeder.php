<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds the fields that exist only to support the new Tailwind UI and have
 * no equivalent in the old admin panel: WittyScore pillars, the marketing
 * Tag / DRHP flag, structured About-page sections, and tabbed FAQs.
 * Run after UnlistedDemoStocksSeeder, which creates the base stock rows.
 */
class UnlistedDemoExtrasSeeder extends Seeder
{
    private const LIGHT_ABOUT_EXTRA_COMPANIES = [
        'hdb-financial-services'        => 'HDB Financial Services',
        'reliance-retail'               => 'Reliance Retail',
        'lenskart-solutions'            => 'Lenskart Solutions',
        'kiranakart-technologies-zepto' => 'Zepto',
        'imagine-marketing-boat'        => 'boAt',
        'api-holdings-pharmeasy'        => 'PharmEasy',
    ];

    public function run(): void
    {
        $this->seedTagsAndDrhp();
        $this->seedWittyScores();
        $this->seedAboutExtraForNseIndia();
        $this->seedAboutExtraLight('tata-capital', 'Tata Capital');
        $this->seedAboutExtraLight('oravel-stays-oyo', 'OYO');
        foreach (self::LIGHT_ABOUT_EXTRA_COMPANIES as $slug => $name) {
            $this->seedAboutExtraLight($slug, $name);
        }
        $this->seedNseIndiaAboutFaqs();
        $this->seedCompanyInsightsForNseIndia();
        $this->seedCompanyInsightsLight('tata-capital', 'Tata Capital');
        $this->seedCompanyInsightsLight('oravel-stays-oyo', 'OYO');
        foreach (self::LIGHT_ABOUT_EXTRA_COMPANIES as $slug => $name) {
            $this->seedCompanyInsightsLight($slug, $name);
        }
    }

    private function fincode(string $slug): ?int
    {
        $fincode = DB::table('unlisted_stocks')->where('UL_STOCKS_SLUG', $slug)->value('UL_STOCKS_FINCODE');
        return $fincode ? (int) $fincode : null;
    }

    private function seedTagsAndDrhp(): void
    {
        $rows = [
            'nse-india'                     => ['tag' => 'Pre-IPO',  'drhp' => 'Yes'],
            'tata-capital'                  => ['tag' => 'Pre-IPO',  'drhp' => 'Yes'],
            'hdb-financial-services'        => ['tag' => 'Pre-IPO',  'drhp' => 'Yes'],
            'reliance-retail'               => ['tag' => 'Trending', 'drhp' => 'No'],
            'lenskart-solutions'            => ['tag' => 'Trending', 'drhp' => 'No'],
            'oravel-stays-oyo'              => ['tag' => 'Unicorn',  'drhp' => 'No'],
            'kiranakart-technologies-zepto' => ['tag' => 'Unicorn',  'drhp' => 'No'],
            'imagine-marketing-boat'        => ['tag' => 'Hot',      'drhp' => 'No'],
            'api-holdings-pharmeasy'        => ['tag' => null,       'drhp' => 'No'],
        ];

        foreach ($rows as $slug => $r) {
            DB::table('unlisted_stocks')->where('UL_STOCKS_SLUG', $slug)->update([
                'UL_STOCKS_TAG'       => $r['tag'],
                'UL_STOCKS_DRHP_FLAG' => $r['drhp'],
            ]);
        }
    }

    private function seedWittyScores(): void
    {
        // [financial, valuation, growth, ipoProbability, liquiditySafety]
        $pillars = [
            'nse-india'                     => [9.2, 4.5, 7.8, 8.0, 9.5],
            'tata-capital'                  => [8.0, 6.5, 7.5, 8.5, 7.0],
            'hdb-financial-services'        => [7.8, 6.0, 7.0, 8.2, 6.5],
            'reliance-retail'               => [8.5, 5.0, 8.0, 3.0, 6.0],
            'lenskart-solutions'            => [6.0, 5.5, 7.5, 4.0, 5.0],
            'oravel-stays-oyo'              => [4.5, 6.0, 6.0, 2.5, 4.0],
            'kiranakart-technologies-zepto' => [3.5, 4.0, 8.5, 2.0, 4.5],
            'imagine-marketing-boat'        => [6.5, 6.0, 6.5, 3.5, 5.5],
            'api-holdings-pharmeasy'        => [3.0, 5.0, 5.0, 2.0, 3.5],
        ];

        foreach ($pillars as $slug => [$fh, $val, $gr, $ipo, $liq]) {
            $fincode = $this->fincode($slug);
            if (!$fincode) {
                continue;
            }

            DB::table('unlisted_witty_scores')->updateOrInsert(
                ['UL_WS_FINCODE' => $fincode],
                [
                    'UL_WS_FINANCIAL_HEALTH' => $fh,
                    'UL_WS_VALUATION'        => $val,
                    'UL_WS_GROWTH_POTENTIAL' => $gr,
                    'UL_WS_IPO_PROBABILITY'  => $ipo,
                    'UL_WS_LIQUIDITY_SAFETY' => $liq,
                    'UL_WS_ACTIVE'           => '1',
                    'UL_WS_INSERT_TIME'      => now(),
                    'UL_WS_UPDATE_TIME'      => now(),
                ]
            );
        }
    }

    private function seedAboutExtraForNseIndia(): void
    {
        $fincode = $this->fincode('nse-india');
        if (!$fincode) {
            return;
        }

        DB::table('unlisted_about_extra')->updateOrInsert(
            ['UL_ABX_FINCODE' => $fincode],
            [
                'UL_ABX_OVERVIEW' => "The National Stock Exchange of India (NSE) is India's largest stock exchange by turnover and a core piece of the country's financial market infrastructure. It runs a fully automated, screen-based electronic trading system across equities, derivatives, currency and debt, and it owns and maintains the NIFTY 50 index.",
                'UL_ABX_OPERATIONS' => 'Orders are matched electronically on a price-time priority basis, with the order book, traded prices and volumes visible to all participants. Surveillance systems, circuit filters, member obligations and clearing through NSE Clearing Ltd add further oversight under SEBI regulation.',
                'UL_ABX_GEOGRAPHY' => 'Headquartered in Mumbai with a nationwide member and broker network, plus data centres and colocation facilities serving trading members across India.',
                'UL_ABX_INDUSTRY_POSITION' => "NSE has ranked as the world's largest derivatives exchange by number of contracts traded in recent years, driven by index and stock derivatives volumes alongside currency contracts.",
                'UL_ABX_SHAREHOLDING' => 'Owned by a diversified group of domestic financial institutions, banks, insurance companies and foreign portfolio investors, with no single promoter holding a controlling stake.',
                'UL_ABX_INVESTOR_INTEREST' => 'Investors study NSE because it is a market-infrastructure business with several revenue streams — transaction fees, listing charges, clearing, data licensing and index services — in a market where retail participation and product breadth have both been expanding.',
                'UL_ABX_MARKET_LANDSCAPE' => 'Competes primarily with BSE domestically, while maintaining a global leadership position in derivatives volume among all exchanges worldwide.',
                'UL_ABX_COMPETITIVE_STRENGTH' => 'Brand credibility as India\'s primary exchange, advanced trading and surveillance technology, and a large investor, member and broker network built up over three decades.',
                'UL_ABX_VERTICALS' => implode("\n", [
                    'Equity Trading | Cash market trading across listed equities on a fully electronic order-matching system.',
                    'Equity & Currency Derivatives | Futures and options on indices, stocks and currency pairs.',
                    'Debt Market | Wholesale debt and corporate bond trading segments.',
                    'Clearing & Settlement | Trade clearing and settlement through NSE Clearing Ltd.',
                    'Data & Index Services | Market data licensing and index products including NIFTY 50.',
                    'NSE Emerge | SME and startup listing platform with lighter requirements than the main board.',
                ]),
                'UL_ABX_REVENUE_SEGMENTS' => implode("\n", [
                    'Transaction charges | Fees on traded volumes across all segments.',
                    'Listing fees | New listing and annual listing charges from issuers.',
                    'Clearing & settlement | Income from post-trade clearing services.',
                    'Data & index licensing | Market data feeds and index licensing for ETFs and derivatives.',
                    'Other services | Education, certification and market infrastructure services.',
                ]),
                'UL_ABX_HISTORY' => implode("\n", [
                    '1992 | Operations begin, introducing electronic trading to India.',
                    '1993 | Recognised as a stock exchange.',
                    '1994 | Wholesale debt and capital market segments launched.',
                    '1996 | NIFTY 50 index launched.',
                    '2000 | Derivatives trading introduced in India.',
                    '2010+ | Currency and commodity segments expanded.',
                    '2021-2024 | Became the world\'s largest derivatives exchange by contracts traded.',
                ]),
                'UL_ABX_PRODUCTS_SERVICES' => implode("\n", [
                    'NIFTY 50 & sectoral indices | Benchmark and thematic equity indices licensed to fund managers.',
                    'NSE Emerge | SME listing and capital-raising platform.',
                    'Market data feeds | Real-time and historical data products for institutions.',
                    'Certification programmes | Market education (NCFM/NISM-aligned) for professionals.',
                ]),
                'UL_ABX_SOURCES' => implode("\n", [
                    'NSE India official website | https://www.nseindia.com',
                    'SEBI | https://www.sebi.gov.in',
                ]),
                'UL_ABX_SWOT_STRENGTHS' => implode("\n", [
                    "Brand credibility as India's primary exchange.",
                    'Global leadership in derivatives volumes.',
                    'Advanced trading and surveillance technology.',
                    'A large investor, member and broker network.',
                ]),
                'UL_ABX_SWOT_WEAKNESSES' => implode("\n", [
                    'Dependence on the regulatory framework and fee approvals.',
                    'Competition from BSE in some segments.',
                    'Limited international trading presence relative to global peers.',
                ]),
                'UL_ABX_SWOT_OPPORTUNITIES' => implode("\n", [
                    'Growth in ETFs and index-linked products.',
                    'International expansion and cross-listing partnerships.',
                    'Rising demand for market data and analytics.',
                    'SME and startup listings through NSE Emerge.',
                ]),
                'UL_ABX_SWOT_THREATS' => implode("\n", [
                    'Cybersecurity and operational-resilience risk.',
                    'Regulatory change affecting fees or product structures.',
                    'Technology disruption in trading and matching.',
                    'Competition from global exchange groups.',
                ]),
                'UL_ABX_ACTIVE'      => '1',
                'UL_ABX_INSERT_TIME' => now(),
                'UL_ABX_UPDATE_TIME' => now(),
            ]
        );
    }

    private function seedAboutExtraLight(string $slug, string $name): void
    {
        $fincode = $this->fincode($slug);
        if (!$fincode) {
            return;
        }

        DB::table('unlisted_about_extra')->updateOrInsert(
            ['UL_ABX_FINCODE' => $fincode],
            [
                'UL_ABX_OVERVIEW' => "{$name} is one of the unlisted companies tracked on StockWitty. This is placeholder demo content seeded for UI testing — replace it from the admin panel with real, verified company information before publishing.",
                'UL_ABX_VERTICALS' => "Core business | Primary line of business for {$name} (demo placeholder).",
                'UL_ABX_SWOT_STRENGTHS'     => "Established market position (demo placeholder).",
                'UL_ABX_SWOT_WEAKNESSES'    => "Concentration risk in core segment (demo placeholder).",
                'UL_ABX_SWOT_OPPORTUNITIES' => "Category growth tailwinds (demo placeholder).",
                'UL_ABX_SWOT_THREATS'       => "Competitive intensity (demo placeholder).",
                'UL_ABX_ACTIVE'      => '1',
                'UL_ABX_INSERT_TIME' => now(),
                'UL_ABX_UPDATE_TIME' => now(),
            ]
        );
    }

    private function seedNseIndiaAboutFaqs(): void
    {
        $fincode = $this->fincode('nse-india');
        if (!$fincode) {
            return;
        }

        // These target the "about" page specifically (distinct from the
        // "overview" FAQs UnlistedDemoStocksSeeder already created) and
        // demonstrate the new topical-tab grouping.
        DB::table('unlisted_faqs')->where('UL_FAQ_FINCODE', $fincode)->where('UL_FAQ_TARGET', 'about')->delete();

        $faqs = [
            ['tab' => 'Basics',     'q' => 'What is NSE India?', 'a' => "The National Stock Exchange of India (NSE) is India's largest stock exchange by turnover and a core piece of the country's financial market infrastructure."],
            ['tab' => 'Investment', 'q' => 'Can retail investors buy NSE India unlisted shares?', 'a' => 'NSE is not listed on an exchange, so its shares change hands privately in the unlisted market as off-market transfers into a CDSL or NSDL demat account.'],
            ['tab' => 'Business',   'q' => 'Which sectors is NSE involved in?', 'a' => 'Equity trading, equity derivatives, currency derivatives, debt market instruments, clearing and settlement, index services and market education.'],
            ['tab' => 'Business',   'q' => "What are NSE's sources of income?", 'a' => 'Transaction fees, listing charges, clearing and settlement income, market data licensing, and index licensing.'],
            ['tab' => 'Basics',     'q' => 'What is the significance of NIFTY 50?', 'a' => "India's headline equity benchmark, maintained by NSE Indices and referenced by a large share of Indian index funds and ETFs."],
        ];

        $rows = [];
        foreach ($faqs as $i => $f) {
            $rows[] = [
                'UL_FAQ_FINCODE'     => $fincode,
                'UL_FAQ_TARGET'      => 'about',
                'UL_FAQ_TAB'         => $f['tab'],
                'UL_FAQ_QUESTION'    => $f['q'],
                'UL_FAQ_ANSWER'      => $f['a'],
                'UL_FAQ_SORT_ORDER'  => $i + 1,
                'UL_FAQ_ACTIVE'      => '1',
                'UL_FAQ_INSERT_TIME' => now(),
                'UL_FAQ_UPDATE_TIME' => now(),
            ];
        }

        DB::table('unlisted_faqs')->insert($rows);
    }

    private function seedCompanyInsightsForNseIndia(): void
    {
        $fincode = $this->fincode('nse-india');
        if (!$fincode) {
            return;
        }

        DB::table('unlisted_company_insights')->updateOrInsert(
            ['UL_CI_FINCODE' => $fincode],
            [
                'UL_CI_AI_SUMMARY' => "NSE India is the country's largest stock exchange, trading at ₹1,960 per unlisted share as of June 2026. It runs a near-monopoly with roughly 99.9% market share in equity derivatives, posting FY25 revenue of ₹19,177 crore and a ₹12,188 crore profit — an exceptional 63% net margin. Its WittyScore is 8.5 out of 10 (Strong). SEBI granted its IPO no-objection in January 2026, with a listing possible in late 2026, though a Delhi High Court petition is pending. At about 40x earnings it isn't cheap, but a successful IPO at the rumoured ₹5 lakh crore valuation could reward patient investors. Unlisted shares are illiquid and high-risk.",
                'UL_CI_FOUNDERS_INTRO' => 'NSE has been "about to IPO" since 2016 — here\'s the short version.',
                'UL_CI_FOUNDERS_QUOTE' => '"NSE going public is like that one friend who\'s been saying they\'ll quit their job and start a business for a decade. Eventually they will — just don\'t bet your rent on the timeline."',
                'UL_CI_FOUNDERS_VERDICT' => "At ₹1,960 you're buying a ~99.9% market-share business at roughly 40x earnings — expensive on paper, potentially cheap if the IPO lands by late 2026. WittyScore 8.5/10.",
                'UL_CI_IPO_TIMELINE' => implode("\n", [
                    '30 Jan 2026 | SEBI grants long-awaited NOC after NSE pays ₹1,600 Cr settlement for the co-location case.',
                    '6 Feb 2026 | NSE Board formally approves the IPO plan.',
                    'Mar 2026 | Writ petition filed in the Delhi High Court challenging NOC validity.',
                    'Apr 2026 | 20+ investment banks shortlisted.',
                    'Expected Jun 2026 | DRHP filing with SEBI.',
                    'Expected Q3/Q4 2026 | Listing on NSE and BSE.',
                ]),
                'UL_CI_IPO_FACTS' => implode("\n", [
                    'Issue Type | Offer for Sale (OFS)',
                    'Major Sellers | LIC, SBI, Temasek + PSU/private',
                    'Total Issue Size | ~$2.5 Bn (₹22,725 Cr)',
                    'Independent Advisor | Rothschild & Co',
                    'Probable Valuation | ₹5.19 L Cr (₹2,100+/share)',
                    'Our IPO Price Estimate | ₹1,950 – ₹2,200/share',
                ]),
                'UL_CI_TLDR' => "NSE India runs the exchange where most of India's stock-market trading happens — a business with a genuine moat, very high margins and a near-100% share of equity-derivatives volume. That quality is real. The catch is price: at roughly 40x earnings you are paying up, and the long-awaited IPO has been 'almost here' since 2016. If you can hold for years and treat the IPO as an upside option rather than a promise, the thesis holds. If you need liquidity or a fixed timeline, this is not for you.",
                'UL_CI_BULL_CASE' => implode("\n", [
                    'Monopoly-like economics: a moat competitors have not dented in two decades.',
                    'Trading volumes in India are structurally rising as participation broadens.',
                    'A listing could re-rate the business and unlock real liquidity for holders.',
                    'Debt-free balance sheet with very high operating margins.',
                ]),
                'UL_CI_BEAR_CASE' => implode("\n", [
                    'At roughly 40x earnings, a lot of the good news is already in the price.',
                    'The IPO timeline has slipped repeatedly and can slip again.',
                    'Regulation on transaction fees, structure or governance could dent profits.',
                    'A slowdown in volumes hits a transaction-linked revenue model directly.',
                ]),
                'UL_CI_SUITS_IF' => implode("\n", [
                    'You invest for years, not months.',
                    'You want a wide-moat business before it lists.',
                    'You treat the IPO as upside, not a guarantee.',
                    'Unlisted is a small, considered slice of your portfolio.',
                ]),
                'UL_CI_NOT_SUITS_IF' => implode("\n", [
                    'You may need to exit quickly or at a fixed date.',
                    'You are uncomfortable paying a premium for quality.',
                    'You are relying on a specific IPO timeline.',
                    'This would be a large, concentrated position.',
                ]),
                'UL_CI_RISKS' => implode("\n", [
                    'Timeline risk | The biggest one. Assume a listing takes longer than the current chatter suggests, and size your position so a two- or three-year delay changes nothing for you.',
                    'Liquidity risk | Exit is a negotiated transaction with a buyer, not a click on a live exchange. Never commit money you may need soon.',
                    'Regulatory risk | Market infrastructure is closely supervised. Changes to fees, market structure or governance requirements can move the story quickly.',
                    'Valuation risk | At roughly 40x earnings, the business must keep delivering. Any stumble in volumes or margins is felt in the price you paid.',
                ]),
                'UL_CI_VERDICT_LONG' => "We would own NSE India as a long-horizon holding, sized so that another delay is an inconvenience rather than a problem. The business is about as good as Indian financial infrastructure gets: a toll booth with pricing power, no debt and volumes that keep growing. The price simply asks you to be patient and to accept that the listing is an option, not a schedule.",
                'UL_CI_ACTIVE'      => '1',
                'UL_CI_INSERT_TIME' => now(),
                'UL_CI_UPDATE_TIME' => now(),
            ]
        );
    }

    private function seedCompanyInsightsLight(string $slug, string $name): void
    {
        $fincode = $this->fincode($slug);
        if (!$fincode) {
            return;
        }

        DB::table('unlisted_company_insights')->updateOrInsert(
            ['UL_CI_FINCODE' => $fincode],
            [
                'UL_CI_AI_SUMMARY' => "{$name} is tracked on StockWitty as an unlisted / pre-IPO name. This is placeholder demo content seeded for UI testing — replace it from the admin panel with a real, CA-reviewed summary before publishing.",
                'UL_CI_FOUNDERS_INTRO'   => null,
                'UL_CI_FOUNDERS_QUOTE'   => null,
                'UL_CI_FOUNDERS_VERDICT' => null,
                'UL_CI_IPO_TIMELINE'     => null,
                'UL_CI_IPO_FACTS'        => null,
                'UL_CI_ACTIVE'      => '1',
                'UL_CI_INSERT_TIME' => now(),
                'UL_CI_UPDATE_TIME' => now(),
            ]
        );
    }
}
