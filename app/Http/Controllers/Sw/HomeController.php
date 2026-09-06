<?php

namespace App\Http\Controllers\Sw;

use App\Http\Controllers\Controller;
use App\Models\UnlistedStock;
use App\Models\UnlistedWittyScore;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $stocks   = UnlistedStock::where('UL_STOCKS_STATUS', '1')->get();
        $fincodes = $stocks->pluck('UL_STOCKS_FINCODE');

        $latestPrices = DB::table('unlisted_price_data as pd')
            ->joinSub(
                DB::table('unlisted_price_data')
                    ->selectRaw('UL_PD_FINCODE, MAX(UL_PD_DATE) as max_date')
                    ->where('UL_PD_INVALID_FLAG', 0)
                    ->whereIn('UL_PD_FINCODE', $fincodes)
                    ->groupBy('UL_PD_FINCODE'),
                'latest',
                fn ($j) => $j->on('pd.UL_PD_FINCODE', '=', 'latest.UL_PD_FINCODE')
                              ->on('pd.UL_PD_DATE', '=', 'latest.max_date')
            )
            ->select('pd.UL_PD_FINCODE', 'pd.UL_PD_BID_PRICE')
            ->get()
            ->keyBy('UL_PD_FINCODE');

        $weekAgoPrices = DB::table('unlisted_price_data')
            ->whereIn('UL_PD_FINCODE', $fincodes)
            ->where('UL_PD_INVALID_FLAG', 0)
            ->where('UL_PD_DATE', '<=', now()->subDays(7))
            ->orderByDesc('UL_PD_DATE')
            ->get(['UL_PD_FINCODE', 'UL_PD_BID_PRICE'])
            ->groupBy('UL_PD_FINCODE')
            ->map(fn ($rows) => $rows->first()->UL_PD_BID_PRICE);

        $priceStats = DB::table('unlisted_price_data')
            ->whereIn('UL_PD_FINCODE', $fincodes)
            ->where('UL_PD_INVALID_FLAG', 0)
            ->where('UL_PD_DATE', '>=', now()->subYear())
            ->selectRaw('UL_PD_FINCODE, MAX(UL_PD_BID_PRICE) as high, MIN(UL_PD_BID_PRICE) as low')
            ->groupBy('UL_PD_FINCODE')
            ->get()
            ->keyBy('UL_PD_FINCODE');

        $latestFinancials = DB::table('unlisted_financials as f')
            ->joinSub(
                DB::table('unlisted_financials')
                    ->selectRaw('UL_FIN_FINCODE, MAX(UL_FIN_Period_end) as max_period')
                    ->where('UL_FIN_STATUS', 1)
                    ->where('UL_FIN_No_months', '12')
                    ->whereIn('UL_FIN_FINCODE', $fincodes)
                    ->groupBy('UL_FIN_FINCODE'),
                'latest',
                fn ($j) => $j->on('f.UL_FIN_FINCODE', '=', 'latest.UL_FIN_FINCODE')
                              ->on('f.UL_FIN_Period_end', '=', 'latest.max_period')
            )
            ->where('f.UL_FIN_STATUS', 1)
            ->select('f.UL_FIN_FINCODE', 'f.UL_FIN_NUM_SHARES', 'f.UL_FIN_PAT', 'f.UL_FIN_Unit')
            ->get()
            ->keyBy('UL_FIN_FINCODE');

        $wittyScores = UnlistedWittyScore::whereIn('UL_WS_FINCODE', $fincodes)
            ->where('UL_WS_ACTIVE', '1')
            ->get()
            ->groupBy('UL_WS_FINCODE')
            ->map(fn ($rows) => $rows->sortByDesc('UL_WS_ID')->first());

        $companies = $stocks->map(function ($stock) use (
            $latestPrices, $weekAgoPrices, $priceStats, $latestFinancials, $wittyScores
        ) {
            $fincode = $stock->UL_STOCKS_FINCODE;
            $price   = (float) ($latestPrices->get($fincode)?->UL_PD_BID_PRICE ?? 0);
            $weekAgo = $weekAgoPrices->get($fincode);
            $stats   = $priceStats->get($fincode);
            $fin     = $latestFinancials->get($fincode);

            $unit      = (float) ($fin->UL_FIN_Unit ?? 1);
            $numShares = $fin?->UL_FIN_NUM_SHARES;
            $pat       = $fin?->UL_FIN_PAT;

            $marketCap = ($numShares && $price)
                ? round(($numShares * $price) / 10000000, 1)
                : null;

            $peRatio = ($marketCap && $pat && (float) $pat != 0)
                ? round($marketCap / ((float) $pat * $unit / 10000000), 1)
                : null;

            return [
                'fincode'    => $fincode,
                'slug'       => $stock->UL_STOCKS_SLUG,
                'name'       => $stock->UL_STOCKS_COMPNAME,
                'initials'   => $this->initials($stock->UL_STOCKS_COMPNAME),
                'price'      => $price,
                'changeAbs'  => $weekAgo ? round($price - (float) $weekAgo, 2) : 0,
                'changePct'  => $weekAgo ? round((($price - (float) $weekAgo) / (float) $weekAgo) * 100, 2) : 0,
                'high52'     => (float) ($stats->high ?? $price),
                'low52'      => (float) ($stats->low ?? $price),
                'lot'        => (int) ($stock->UL_STOCKS_LOT_SIZE ?: 1),
                'mktCap'     => $marketCap ? '₹' . number_format($marketCap, 1) . ' Cr' : '—',
                'pe'         => $peRatio ? number_format($peRatio, 1) . 'x' : 'NA',
                'wittyScore' => $wittyScores->get($fincode)?->overall() ?? 0,
                'tag'        => $stock->UL_STOCKS_TAG,
                'sector'     => $stock->UL_STOCKS_INDUSTRY,
                'drhp'       => $stock->UL_STOCKS_DRHP_FLAG === 'Yes',
                'insertTime' => $stock->UL_STOCKS_INSERT_TIME,
            ];
        });

        $showcaseCompanies = $companies
            ->filter(fn ($c) => $c['wittyScore'] > 0 && $c['price'] > 0)
            ->sortByDesc('wittyScore')
            ->take(10)
            ->map(fn ($c) => [...$c, 'series' => $this->weeklySeries($c['fincode'])])
            ->values()
            ->all();

        $newArrivals = $companies
            ->filter(fn ($c) => $c['tag'] && $c['price'] > 0)
            ->sortByDesc('insertTime')
            ->take(9)
            ->map(fn ($c) => [
                'name'   => $c['name'],
                'slug'   => $c['slug'],
                'short'  => $c['initials'],
                'price'  => $c['price'],
                'change' => $c['changePct'],
                'tags'   => array_values(array_filter([$c['tag'], $c['drhp'] ? 'DRHP' : null])),
            ])
            ->values()
            ->all();

        $sectors = $companies
            ->filter(fn ($c) => filled($c['sector']))
            ->groupBy('sector')
            ->map(fn ($rows, $sector) => [
                'name'  => $sector,
                'count' => $rows->count(),
                'icon'  => $this->sectorIcon($sector),
            ])
            ->sortByDesc('count')
            ->take(8)
            ->values()
            ->all();

        $spotlight = $wittyScores->sortByDesc(fn ($ws) => $ws->overall() ?? 0)->first();

        $wittyScoreCompany = null;
        $wittyScoreValue   = 0;
        $wittyScorePillars = [];

        if ($spotlight) {
            $wittyScoreCompany = $stocks->firstWhere('UL_STOCKS_FINCODE', $spotlight->UL_WS_FINCODE)?->UL_STOCKS_COMPNAME;
            $wittyScoreValue   = $spotlight->overall() ?? 0;
            $wittyScorePillars = [
                ['label' => 'Financial Health', 'value' => (float) $spotlight->UL_WS_FINANCIAL_HEALTH, 'weight' => 30],
                ['label' => 'Valuation', 'value' => (float) $spotlight->UL_WS_VALUATION, 'weight' => 20],
                ['label' => 'Growth Potential', 'value' => (float) $spotlight->UL_WS_GROWTH_POTENTIAL, 'weight' => 20],
                ['label' => 'IPO Probability', 'value' => (float) $spotlight->UL_WS_IPO_PROBABILITY, 'weight' => 15],
                ['label' => 'Liquidity & Safety', 'value' => (float) $spotlight->UL_WS_LIQUIDITY_SAFETY, 'weight' => 15],
            ];
        }

        return view('sw.home', [
            'showcaseCompanies' => $showcaseCompanies,
            'newArrivals'       => $newArrivals,
            'sectors'           => $sectors,
            'companiesTracked'  => $stocks->count(),
            'wittyScoreCompany' => $wittyScoreCompany ?? 'StockWitty pick',
            'wittyScoreValue'   => $wittyScoreValue,
            'wittyScorePillars' => $wittyScorePillars,
        ]);
    }

    private function initials(string $name): string
    {
        $words    = preg_split('/\s+/', trim($name));
        $initials = collect($words)->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
        return $initials ?: 'SW';
    }

    private function sectorIcon(string $industry): string
    {
        $industry = mb_strtolower($industry);

        return match (true) {
            str_contains($industry, 'finance') => 'landmark',
            str_contains($industry, 'bank') => 'building-2',
            str_contains($industry, 'software'), str_contains($industry, 'it -') => 'cpu',
            str_contains($industry, 'commerce') => 'shopping-bag',
            str_contains($industry, 'retail') => 'shopping-bag',
            str_contains($industry, 'pharma') => 'heart-pulse',
            str_contains($industry, 'hotel'), str_contains($industry, 'resort') => 'home',
            str_contains($industry, 'airline') => 'plane',
            str_contains($industry, 'electric') => 'zap',
            str_contains($industry, 'automobile') => 'car',
            str_contains($industry, 'consumer durables') => 'package',
            default => 'briefcase',
        };
    }

    /** Sample up to 6 evenly-spaced points from the last 35 days of price history. */
    private function weeklySeries(int $fincode): array
    {
        $history = DB::table('unlisted_price_data')
            ->where('UL_PD_FINCODE', $fincode)
            ->where('UL_PD_INVALID_FLAG', 0)
            ->where('UL_PD_DATE', '>=', now()->subDays(35))
            ->orderBy('UL_PD_DATE')
            ->pluck('UL_PD_BID_PRICE')
            ->values();

        $count = $history->count();
        if ($count <= 1) {
            return $history->map(fn ($p) => (float) $p)->all();
        }

        $points = min(6, $count);

        return collect(range(0, $points - 1))
            ->map(fn ($i) => (int) round($i * ($count - 1) / ($points - 1)))
            ->unique()
            ->map(fn ($i) => (float) $history[$i])
            ->values()
            ->all();
    }
}
