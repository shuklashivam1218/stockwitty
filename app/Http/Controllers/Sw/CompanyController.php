<?php

namespace App\Http\Controllers\Sw;

use App\Http\Controllers\Controller;
use App\Models\UnlistedAboutExtra;
use App\Models\UnlistedCompanyInsight;
use App\Models\UnlistedFaq;
use App\Models\UnlistedStock;
use App\Models\UnlistedThesis;
use App\Models\UnlistedWittyScore;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Real-DB-backed replacement for the nse-india-only static demo pages
 * (resources/views/sw/unlisted-shares/nse-india/*). Works for any company
 * slug — the price/financials computations are ported from
 * StocksController::company(), the old Bootstrap page's proven logic.
 */
class CompanyController extends Controller
{
    private function findStock(string $slug): UnlistedStock
    {
        return UnlistedStock::query()
            ->where('UL_STOCKS_SLUG', $slug)
            ->where('UL_STOCKS_STATUS', '1')
            ->firstOrFail();
    }

    public function index(string $slug)
    {
        $stock   = $this->findStock($slug);
        $fincode = $stock->UL_STOCKS_FINCODE;

        $priceData = DB::table('unlisted_price_data')
            ->where('UL_PD_FINCODE', $fincode)
            ->where('UL_PD_INVALID_FLAG', 0)
            ->orderByDesc('UL_PD_DATE')
            ->first();

        $latestFin = DB::table('unlisted_financials')
            ->where('UL_FIN_FINCODE', $fincode)
            ->where('UL_FIN_STATUS', 1)
            ->where('UL_FIN_No_months', '12')
            ->orderByDesc('UL_FIN_Period_end')
            ->first();

        $financials = DB::table('unlisted_financials')
            ->where('UL_FIN_FINCODE', $fincode)
            ->where('UL_FIN_STATUS', 1)
            ->where('UL_FIN_No_months', '12')
            ->orderByDesc('UL_FIN_Period_end')
            ->limit(5)
            ->get();

        $quarterlyFin = DB::table('unlisted_financials')
            ->where('UL_FIN_FINCODE', $fincode)
            ->where('UL_FIN_STATUS', 1)
            ->where('UL_FIN_No_months', '3')
            ->orderByDesc('UL_FIN_Period_end')
            ->limit(4)
            ->get();

        $yearAgo = now()->subYear();
        $priceStats = DB::table('unlisted_price_data')
            ->where('UL_PD_FINCODE', $fincode)
            ->where('UL_PD_INVALID_FLAG', 0)
            ->where('UL_PD_DATE', '>=', $yearAgo)
            ->selectRaw('MAX(UL_PD_BID_PRICE) as high, MIN(UL_PD_BID_PRICE) as low')
            ->first();
        $high52w = $priceStats?->high;
        $low52w  = $priceStats?->low;

        $weekAgoPrice = DB::table('unlisted_price_data')
            ->where('UL_PD_FINCODE', $fincode)
            ->where('UL_PD_INVALID_FLAG', 0)
            ->where('UL_PD_DATE', '<=', now()->subDays(7))
            ->orderByDesc('UL_PD_DATE')
            ->value('UL_PD_BID_PRICE');

        $allPriceHistory = DB::table('unlisted_price_data')
            ->where('UL_PD_FINCODE', $fincode)
            ->where('UL_PD_INVALID_FLAG', 0)
            ->orderBy('UL_PD_DATE')
            ->get(['UL_PD_DATE', 'UL_PD_BID_PRICE'])
            ->map(fn ($r) => ['date' => Carbon::parse($r->UL_PD_DATE), 'price' => (float) $r->UL_PD_BID_PRICE]);

        $currentPrice = $priceData?->UL_PD_BID_PRICE;
        $unit         = (float) ($latestFin?->UL_FIN_Unit ?? 1);
        $numShares    = $latestFin?->UL_FIN_NUM_SHARES;

        $marketCap = ($numShares && $currentPrice)
            ? round(($numShares * $currentPrice) / 10000000, 1)
            : null;

        $pat = $latestFin?->UL_FIN_PAT;
        $peRatio = ($marketCap && $pat && (float) $pat != 0)
            ? round($marketCap / ((float) $pat * $unit / 10000000), 1)
            : null;

        if ($latestFin && $latestFin->UL_FIN_ADJUSTED_EPS !== null && $latestFin->UL_FIN_ADJUSTED_EPS !== '') {
            $eps = (float) $latestFin->UL_FIN_ADJUSTED_EPS;
        } elseif ($pat && $numShares && (float) $numShares != 0) {
            $computed = ((float) $pat * $unit) / (float) $numShares;
            $eps = (abs($computed) <= 99999) ? round($computed, 2) : null;
        } else {
            $eps = null;
        }

        $bookValue = ($latestFin?->UL_FIN_SHAREHOLDER_FUNDS && $numShares && (float) $numShares != 0)
            ? round(((float) $latestFin->UL_FIN_SHAREHOLDER_FUNDS * $unit) / (float) $numShares, 2)
            : null;

        $pbRatio = ($currentPrice && $bookValue && (float) $bookValue != 0)
            ? round($currentPrice / $bookValue, 2)
            : null;

        $shareholderFunds = $latestFin?->UL_FIN_SHAREHOLDER_FUNDS;
        $roe = ($pat && $shareholderFunds && (float) $shareholderFunds != 0)
            ? round(((float) $pat / (float) $shareholderFunds) * 100, 1)
            : null;

        $debtToEquity = ($latestFin?->UL_FIN_TOTAL_DEBT !== null && $shareholderFunds && (float) $shareholderFunds != 0)
            ? round((float) $latestFin->UL_FIN_TOTAL_DEBT / (float) $shareholderFunds, 2)
            : null;

        $weekChange = null;
        $weekChangePct = null;
        if ($currentPrice !== null && $weekAgoPrice) {
            $weekChange    = round((float) $currentPrice - (float) $weekAgoPrice, 2);
            $weekChangePct = round($weekChange / (float) $weekAgoPrice * 100, 2);
        }

        $wittyScore = UnlistedWittyScore::where('UL_WS_FINCODE', $fincode)
            ->where('UL_WS_ACTIVE', '1')
            ->orderByDesc('UL_WS_ID')
            ->first();

        $insight = UnlistedCompanyInsight::where('UL_CI_FINCODE', $fincode)
            ->where('UL_CI_ACTIVE', '1')
            ->orderByDesc('UL_CI_ID')
            ->first();

        $aboutExtra = UnlistedAboutExtra::where('UL_ABX_FINCODE', $fincode)
            ->where('UL_ABX_ACTIVE', '1')
            ->orderByDesc('UL_ABX_ID')
            ->first();

        $overviewFaqs = UnlistedFaq::where('UL_FAQ_FINCODE', $fincode)
            ->where('UL_FAQ_TARGET', 'overview')
            ->where('UL_FAQ_ACTIVE', '1')
            ->orderBy('UL_FAQ_SORT_ORDER')
            ->get();

        $company = [
            'slug'       => $stock->UL_STOCKS_SLUG,
            'name'       => $stock->UL_STOCKS_COMPNAME,
            'initials'   => $this->initials($stock->UL_STOCKS_COMPNAME),
            'price'      => (float) ($currentPrice ?? 0),
            'changeAbs'  => $weekChange ?? 0,
            'changePct'  => $weekChangePct ?? 0,
            'high52'     => (float) ($high52w ?? 0),
            'low52'      => (float) ($low52w ?? 0),
            'lot'        => (int) ($stock->UL_STOCKS_LOT_SIZE ?: 1),
            'mktCap'     => $marketCap ? '₹' . number_format($marketCap, 1) . ' Cr' : '—',
            'pe'         => $peRatio ? number_format($peRatio, 1) . 'x' : 'NA',
            'wittyScore' => $wittyScore?->overall() ?? 0,
            'tag'        => $stock->UL_STOCKS_TAG,
        ];

        $series = $this->buildChartSeries($allPriceHistory);

        $revenueTrend = $financials->reverse()->values()->map(function ($f) {
            $rowUnit = (float) ($f->UL_FIN_Unit ?: 1);
            return [
                'label'    => 'FY' . intdiv((int) $f->UL_FIN_Period_end, 100),
                'netSales' => round(((float) $f->UL_FIN_NET_SALES * $rowUnit) / 10000000, 1),
                'pat'      => round(((float) $f->UL_FIN_PAT * $rowUnit) / 10000000, 1),
            ];
        })->values();

        $financialTables = $this->buildFinancialTables($financials, $quarterlyFin);

        return view('sw.unlisted-shares.company.index', [
            'stock' => $stock, 'company' => $company, 'series' => $series,
            'currentPrice' => $currentPrice, 'marketCap' => $marketCap, 'peRatio' => $peRatio,
            'eps' => $eps, 'bookValue' => $bookValue, 'pbRatio' => $pbRatio, 'roe' => $roe,
            'debtToEquity' => $debtToEquity, 'high52w' => $high52w, 'low52w' => $low52w,
            'weekChange' => $weekChange, 'weekChangePct' => $weekChangePct,
            'priceAsOf' => $priceData?->UL_PD_DATE, 'revenueTrend' => $revenueTrend,
            'financialTables' => $financialTables, 'wittyScore' => $wittyScore, 'insight' => $insight,
            'aboutExtra' => $aboutExtra, 'overviewFaqs' => $overviewFaqs, 'numShares' => $numShares,
            'unit' => $unit,
        ]);
    }

    public function about(string $slug)
    {
        $stock   = $this->findStock($slug);
        $fincode = $stock->UL_STOCKS_FINCODE;

        $extra = UnlistedAboutExtra::where('UL_ABX_FINCODE', $fincode)
            ->where('UL_ABX_ACTIVE', '1')
            ->orderByDesc('UL_ABX_ID')
            ->first();

        $aboutFaqs = UnlistedFaq::where('UL_FAQ_FINCODE', $fincode)
            ->where('UL_FAQ_TARGET', 'about')
            ->where('UL_FAQ_ACTIVE', '1')
            ->orderBy('UL_FAQ_SORT_ORDER')
            ->get()
            ->groupBy(fn ($f) => $f->UL_FAQ_TAB ?: 'General');

        return view('sw.unlisted-shares.company.about', [
            'stock' => $stock,
            'verticals'    => UnlistedAboutExtra::parsePairs($extra?->UL_ABX_VERTICALS),
            'revenue'      => UnlistedAboutExtra::parsePairs($extra?->UL_ABX_REVENUE_SEGMENTS),
            'products'     => UnlistedAboutExtra::parsePairs($extra?->UL_ABX_PRODUCTS_SERVICES),
            'history'      => UnlistedAboutExtra::parseHistory($extra?->UL_ABX_HISTORY),
            'sources'      => UnlistedAboutExtra::parseSources($extra?->UL_ABX_SOURCES),
            'strengths'    => UnlistedAboutExtra::parseLines($extra?->UL_ABX_SWOT_STRENGTHS),
            'weaknesses'   => UnlistedAboutExtra::parseLines($extra?->UL_ABX_SWOT_WEAKNESSES),
            'opportunities' => UnlistedAboutExtra::parseLines($extra?->UL_ABX_SWOT_OPPORTUNITIES),
            'threats'      => UnlistedAboutExtra::parseLines($extra?->UL_ABX_SWOT_THREATS),
            'overview'      => $extra?->UL_ABX_OVERVIEW,
            'operations'    => $extra?->UL_ABX_OPERATIONS,
            'geography'     => $extra?->UL_ABX_GEOGRAPHY,
            'industryPos'   => $extra?->UL_ABX_INDUSTRY_POSITION,
            'shareholding'  => $extra?->UL_ABX_SHAREHOLDING,
            'investorInterest' => $extra?->UL_ABX_INVESTOR_INTEREST,
            'marketLandscape'  => $extra?->UL_ABX_MARKET_LANDSCAPE,
            'competitive'      => $extra?->UL_ABX_COMPETITIVE_STRENGTH,
            'aboutFaqs' => $aboutFaqs,
        ]);
    }

    public function thesis(string $slug)
    {
        $stock   = $this->findStock($slug);
        $fincode = $stock->UL_STOCKS_FINCODE;

        $thesis = UnlistedThesis::where('UL_THESIS_FINCODE', $fincode)
            ->where('UL_THESIS_ACTIVE', '1')
            ->orderByDesc('UL_THESIS_ID')
            ->first();

        $insight = UnlistedCompanyInsight::where('UL_CI_FINCODE', $fincode)
            ->where('UL_CI_ACTIVE', '1')
            ->orderByDesc('UL_CI_ID')
            ->first();

        $wittyScore = UnlistedWittyScore::where('UL_WS_FINCODE', $fincode)
            ->where('UL_WS_ACTIVE', '1')
            ->orderByDesc('UL_WS_ID')
            ->first();

        $thesisFaqs = UnlistedFaq::where('UL_FAQ_FINCODE', $fincode)
            ->where('UL_FAQ_TARGET', 'thesis')
            ->where('UL_FAQ_ACTIVE', '1')
            ->orderBy('UL_FAQ_SORT_ORDER')
            ->get();

        $fixImageSrc = fn (?string $html) => $html
            ? preg_replace('/src="(?!https?:\/\/|\/)/', 'src="/', $html)
            : null;

        return view('sw.unlisted-shares.company.thesis', [
            'stock' => $stock,
            'thesisHtml'  => $fixImageSrc($thesis?->UL_THESIS_CONTENT),
            'wittyScore'  => $wittyScore,
            'tldr'        => $insight?->UL_CI_TLDR,
            'bullCase'    => UnlistedCompanyInsight::parseLines($insight?->UL_CI_BULL_CASE),
            'bearCase'    => UnlistedCompanyInsight::parseLines($insight?->UL_CI_BEAR_CASE),
            'suitsIf'     => UnlistedCompanyInsight::parseLines($insight?->UL_CI_SUITS_IF),
            'notSuitsIf'  => UnlistedCompanyInsight::parseLines($insight?->UL_CI_NOT_SUITS_IF),
            'risks'       => UnlistedCompanyInsight::parsePairs($insight?->UL_CI_RISKS),
            'verdictLong' => $insight?->UL_CI_VERDICT_LONG,
            'thesisFaqs'  => $thesisFaqs,
        ]);
    }

    private function initials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));
        $initials = collect($words)->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
        return $initials ?: 'SW';
    }

    /** Bucket the full price history into the chart's period tabs. */
    private function buildChartSeries($allPriceHistory): array
    {
        $now = now();
        $cutoffs = [
            '1M' => $now->copy()->subMonth(),
            '6M' => $now->copy()->subMonths(6),
            '1Y' => $now->copy()->subYear(),
            '3Y' => $now->copy()->subYears(3),
            '5Y' => $now->copy()->subYears(5),
        ];

        $series = [];
        foreach ($cutoffs as $key => $cutoff) {
            $points = $allPriceHistory->filter(fn ($p) => $p['date']->gte($cutoff));
            $series[$key] = $this->sample($points, 24)->map(fn ($p) => [
                'label' => $p['date']->format('d M'),
                'price' => $p['price'],
            ])->values()->all();
        }
        $series['Max'] = $this->sample($allPriceHistory, 24)->map(fn ($p) => [
            'label' => $p['date']->format('M \'y'),
            'price' => $p['price'],
        ])->values()->all();

        return $series;
    }

    /** Evenly sample a collection down to at most $max points, always keeping the last one. */
    private function sample($collection, int $max)
    {
        $count = $collection->count();
        if ($count <= $max || $count === 0) {
            return $collection->values();
        }

        $step = $count / $max;
        $indices = collect(range(0, $max - 1))->map(fn ($i) => (int) round($i * $step))->unique()->values();
        $indices->push($count - 1);

        return $indices->unique()->sort()->map(fn ($i) => $collection->values()[$i])->values();
    }

    /** Build the Yearly/Quarterly x Income-Statement/Balance-Sheet/Cash-Flow table structure. */
    private function buildFinancialTables($yearly, $quarterly): array
    {
        $periodLabel = fn ($row, $quarterly = false) => $quarterly
            ? 'Q' . (intdiv((int) $row->UL_FIN_Period_end % 100, 3) ?: 4) . ' FY' . intdiv((int) $row->UL_FIN_Period_end, 100)
            : 'FY' . intdiv((int) $row->UL_FIN_Period_end, 100);

        $cr = fn ($row, $field) => $row->$field !== null
            ? number_format(((float) $row->$field * (float) ($row->UL_FIN_Unit ?: 1)) / 10000000, 1)
            : '—';

        $buildRange = function ($rows, bool $isQuarterly) use ($periodLabel, $cr) {
            $rows = $rows->reverse()->values();
            $cols = $rows->map(fn ($r) => $periodLabel($r, $isQuarterly))->all();

            $line = fn ($label, $field, $strong = false) => [
                'label'  => $label,
                'values' => $rows->map(fn ($r) => $cr($r, $field))->all(),
                'strong' => $strong,
            ];

            return [
                'Income Statement' => ['cols' => $cols, 'rows' => [
                    $line('Revenue from operations', 'UL_FIN_NET_SALES'),
                    $line('Other Income', 'UL_FIN_OTHER_INCOME'),
                    $line('Total Revenue', 'UL_FIN_TOTAL_INCOME', true),
                    $line('Total Expenditure', 'UL_FIN_TOTAL_EXPENDITURE'),
                    $line('Operating Profit', 'UL_FIN_OPERATING_PROFIT'),
                    $line('Interest', 'UL_FIN_INTEREST'),
                    $line('Depreciation', 'UL_FIN_DEPRECIATION'),
                    $line('Profit Before Tax', 'UL_FIN_PBT'),
                    $line('Tax', 'UL_FIN_TAX'),
                    $line('Profit After Tax', 'UL_FIN_PAT', true),
                ]],
                'Balance Sheet' => ['cols' => $cols, 'rows' => [
                    $line('Total Assets', 'UL_FIN_TOTAL_ASSETS', true),
                    $line('Current Assets', 'UL_FIN_CURRENT_ASSETS'),
                    $line('Non-Current Assets', 'UL_FIN_NON_CURRENT_ASSETS'),
                    $line('Total Debt', 'UL_FIN_TOTAL_DEBT'),
                    $line('Current Liabilities', 'UL_FIN_CURRENT_LIABILITIES'),
                    $line('Non-Current Liabilities', 'UL_FIN_NON_CURRENT_LIABILITIES'),
                    $line("Shareholders' Equity", 'UL_FIN_SHAREHOLDER_FUNDS', true),
                ]],
                'Cash Flow' => ['cols' => $cols, 'rows' => [
                    $line('Cash from Operations', 'UL_FIN_CASH_FLOW_FROM_OPERATING_ACTIVITIES', true),
                    $line('Cash from Investing', 'UL_FIN_CASH_FLOW_FORM_INVESTING_ACTIVITIES'),
                    $line('Cash from Financing', 'UL_FIN_CASH_FLOW_FROM_FINANCING_ACTIVITIES'),
                    $line('Free Cash Flow', 'UL_FIN_FREE_CASH_FLOW', true),
                ]],
            ];
        };

        return [
            'Yearly'    => $buildRange($yearly, false),
            'Quarterly' => $buildRange($quarterly, true),
        ];
    }
}
