<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnlistedDemoStocksSeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name' => 'NSE India', 'slug' => 'nse-india', 'industry' => 'Finance - Stock Broking',
                'isin' => 'INE721I01024', 'category' => 'pre_ipo', 'incMonth' => 'April', 'incYear' => 1992,
                'city' => 'Mumbai', 'lotSize' => 250, 'demat' => 'Both', 'price' => 1960, 'trendPct' => 0.14,
                'fv' => 1, 'numSharesCr' => 247.5, 'netSalesCr' => 18600, 'patMarginPct' => 0.64,
                'shareholderFundsCr' => 15500, 'totalDebtCr' => 150,
                'website' => 'https://www.nseindia.com', 'compRating' => 5, 'valuationRating' => 3, 'rofr' => 'Yes',
                'about' => '<p>NSE is India\'s leading stock exchange, offering trading, clearing, indices and market data services. It pioneered electronic screen-based trading in India and remains one of the largest exchanges globally by trade volume.</p>',
            ],
            [
                'name' => 'Tata Capital', 'slug' => 'tata-capital', 'industry' => 'Finance - NBFC',
                'isin' => 'INE0RJ701010', 'category' => 'pre_ipo', 'incMonth' => 'November', 'incYear' => 1991,
                'city' => 'Mumbai', 'lotSize' => 100, 'demat' => 'Both', 'price' => 925, 'trendPct' => 0.10,
                'fv' => 10, 'numSharesCr' => 326, 'netSalesCr' => 12500, 'patMarginPct' => 0.18,
                'shareholderFundsCr' => 18000, 'totalDebtCr' => 95000,
                'website' => 'https://www.tatacapital.com', 'compRating' => 4, 'valuationRating' => 4, 'rofr' => 'Yes',
                'about' => '<p>Tata Capital is a diversified NBFC offering consumer loans, SME finance, wealth management and leasing services under the Tata Group umbrella, with a pan-India distribution network.</p>',
            ],
            [
                'name' => 'HDB Financial Services', 'slug' => 'hdb-financial-services', 'industry' => 'Finance - NBFC',
                'isin' => 'INE756I01015', 'category' => 'pre_ipo', 'incMonth' => 'June', 'incYear' => 2007,
                'city' => 'Ahmedabad', 'lotSize' => 50, 'demat' => 'Both', 'price' => 1350, 'trendPct' => 0.08,
                'fv' => 10, 'numSharesCr' => 79, 'netSalesCr' => 15200, 'patMarginPct' => 0.19,
                'shareholderFundsCr' => 14500, 'totalDebtCr' => 68000,
                'website' => 'https://www.hdbfs.com', 'compRating' => 4, 'valuationRating' => 4, 'rofr' => 'Yes',
                'about' => '<p>HDB Financial Services is an HDFC Bank-promoted NBFC providing secured and unsecured retail loans, enterprise lending and asset finance across urban and semi-urban India.</p>',
            ],
            [
                'name' => 'Reliance Retail', 'slug' => 'reliance-retail', 'industry' => 'Retailing',
                'isin' => 'INE885T01018', 'category' => 'pre_ipo', 'incMonth' => 'October', 'incYear' => 2006,
                'city' => 'Mumbai', 'lotSize' => 10, 'demat' => 'Both', 'price' => 2450, 'trendPct' => 0.12,
                'fv' => 10, 'numSharesCr' => 674, 'netSalesCr' => 305000, 'patMarginPct' => 0.09,
                'shareholderFundsCr' => 95000, 'totalDebtCr' => 42000,
                'website' => 'https://www.relianceretail.com', 'compRating' => 5, 'valuationRating' => 3, 'rofr' => 'Yes',
                'about' => '<p>Reliance Retail is India\'s largest retailer, spanning grocery, fashion, electronics and digital commerce through formats like Reliance Trends, Jio Mart and Reliance Digital.</p>',
            ],
            [
                'name' => 'Lenskart Solutions', 'slug' => 'lenskart-solutions', 'industry' => 'Retailing',
                'isin' => 'INE0LEN01011', 'category' => 'startup_funding', 'incMonth' => 'January', 'incYear' => 2010,
                'city' => 'Faridabad', 'lotSize' => 100, 'demat' => 'NSDL', 'price' => 440, 'trendPct' => 0.30,
                'fv' => 2, 'numSharesCr' => 42, 'netSalesCr' => 6300, 'patMarginPct' => 0.06,
                'shareholderFundsCr' => 1900, 'totalDebtCr' => 300,
                'website' => 'https://www.lenskart.com', 'compRating' => 3, 'valuationRating' => 3, 'rofr' => 'No',
                'about' => '<p>Lenskart is an omnichannel eyewear retailer combining an in-house manufacturing and supply chain with a large network of stores and a direct-to-consumer app across India and international markets.</p>',
            ],
            [
                'name' => 'Oravel Stays (OYO)', 'slug' => 'oravel-stays-oyo', 'industry' => 'Hotel, Resort & Restaurants',
                'isin' => 'INE0OYO01019', 'category' => 'startup_funding', 'incMonth' => 'May', 'incYear' => 2013,
                'city' => 'Gurugram', 'lotSize' => 1000, 'demat' => 'Both', 'price' => 58, 'trendPct' => 0.22,
                'fv' => 1, 'numSharesCr' => 850, 'netSalesCr' => 5900, 'patMarginPct' => 0.04,
                'shareholderFundsCr' => 1400, 'totalDebtCr' => 2200,
                'website' => 'https://www.oyorooms.com', 'compRating' => 2, 'valuationRating' => 3, 'rofr' => 'No',
                'about' => '<p>OYO operates a technology-driven network of budget and mid-scale hotels, homes and vacation rentals across India and international markets, franchising and leasing properties under its brand.</p>',
            ],
            [
                'name' => 'Kiranakart Technologies (Zepto)', 'slug' => 'kiranakart-technologies-zepto', 'industry' => 'e-Commerce',
                'isin' => 'INE0ZEP01012', 'category' => 'startup_funding', 'incMonth' => 'April', 'incYear' => 2021,
                'city' => 'Mumbai', 'lotSize' => 200, 'demat' => 'Both', 'price' => 180, 'trendPct' => 0.45,
                'fv' => 1, 'numSharesCr' => 210, 'netSalesCr' => 4400, 'patMarginPct' => -0.08,
                'shareholderFundsCr' => 1200, 'totalDebtCr' => 400,
                'website' => 'https://www.zeptonow.com', 'compRating' => 3, 'valuationRating' => 2, 'rofr' => 'No',
                'about' => '<p>Zepto runs a quick-commerce grocery delivery platform built around dark stores, promising delivery within minutes across major Indian metros. The company remains in an aggressive growth-investment phase.</p>',
            ],
            [
                'name' => 'Imagine Marketing (boAt)', 'slug' => 'imagine-marketing-boat', 'industry' => 'Consumer Durables - Electronics',
                'isin' => 'INE0BOAT01013', 'category' => 'pre_ipo', 'incMonth' => 'November', 'incYear' => 2013,
                'city' => 'Gurugram', 'lotSize' => 25, 'demat' => 'Both', 'price' => 1150, 'trendPct' => 0.05,
                'fv' => 10, 'numSharesCr' => 6.2, 'netSalesCr' => 2000, 'patMarginPct' => 0.05,
                'shareholderFundsCr' => 450, 'totalDebtCr' => 180,
                'website' => 'https://www.boat-lifestyle.com', 'compRating' => 3, 'valuationRating' => 4, 'rofr' => 'No',
                'about' => '<p>Imagine Marketing sells consumer electronics and lifestyle accessories under the boAt brand, including audio products, wearables and personal care devices, distributed online and offline across India.</p>',
            ],
            [
                'name' => 'API Holdings (PharmEasy)', 'slug' => 'api-holdings-pharmeasy', 'industry' => 'Pharmaceuticals & Drugs',
                'isin' => 'INE0PEZ01014', 'category' => 'startup_funding', 'incMonth' => 'March', 'incYear' => 2015,
                'city' => 'Mumbai', 'lotSize' => 500, 'demat' => 'Both', 'price' => 25, 'trendPct' => -0.20,
                'fv' => 1, 'numSharesCr' => 680, 'netSalesCr' => 5500, 'patMarginPct' => -0.05,
                'shareholderFundsCr' => 300, 'totalDebtCr' => 2800,
                'website' => 'https://pharmeasy.in', 'compRating' => 2, 'valuationRating' => 3, 'rofr' => 'No',
                'about' => '<p>API Holdings operates the PharmEasy platform, an online pharmacy and diagnostics marketplace connecting consumers with medicines, lab tests and healthcare products across India.</p>',
            ],
        ];

        foreach ($companies as $c) {
            $indCode = DB::table('industry_master')->where('IM_INDUSTRY', $c['industry'])->value('IM_IND_CODE');

            $stockData = [
                'UL_STOCKS_COMPNAME'          => $c['name'],
                'UL_STOCKS_SLUG'              => $c['slug'],
                'UL_STOCKS_IND_CODE'          => $indCode,
                'UL_STOCKS_INDUSTRY'          => $c['industry'],
                'UL_STOCKS_ISIN'              => $c['isin'],
                'UL_STOCKS_S_NAME'            => mb_strtoupper(mb_substr($c['name'], 0, 8)),
                'UL_STOCKS_CATEGORY'          => $c['category'],
                'UL_STOCKS_INC_MONTH'         => $c['incMonth'],
                'UL_STOCKS_INC_YEAR'          => (string) $c['incYear'],
                'UL_STOCKS_STATUS'            => '1',
                'UL_STOCKS_COMPNAME_TYPE'     => 'unlisted',
                'UL_STOCKS_LOT_SIZE'          => (string) $c['lotSize'],
                'UL_STOCKS_BUY_SELL_FLAG'     => 'Yes',
                'UL_STOCKS_DEMAT_ACCOUNT_REQ' => $c['demat'],
                'UL_STOCKS_Qtr_Data_Publish'  => 'Yes',
                'UL_STOCKS_CITY_NAME'         => $c['city'],
                'UL_STOCKS_ABOUT'             => $c['about'],
                'UL_STOCKS_WEBSITE'           => $c['website'],
                'UL_STOCKS_COMP_RATING'       => (string) $c['compRating'],
                'UL_STOCKS_VALUATION_RATING'  => (string) $c['valuationRating'],
                'UL_STOCKS_ROFR_FLAG'         => $c['rofr'],
                'UL_STOCKS_INSERT_TIME'       => now(),
                'UL_STOCKS_UPDATE_TIME'       => now(),
            ];

            DB::table('unlisted_stocks')->upsert(
                [$stockData],
                ['UL_STOCKS_SLUG'],
                array_diff(array_keys($stockData), ['UL_STOCKS_SLUG', 'UL_STOCKS_INSERT_TIME'])
            );

            $fincode = DB::table('unlisted_stocks')->where('UL_STOCKS_SLUG', $c['slug'])->value('UL_STOCKS_FINCODE');

            $this->seedPriceHistory((int) $fincode, (float) $c['price'], (float) $c['trendPct']);
            $this->seedFinancials((int) $fincode, $c);
            $this->seedFaqs((int) $fincode, $c['name'], (int) $c['lotSize']);
        }
    }

    private function seedFaqs(int $fincode, string $name, int $lotSize): void
    {
        // Demo FAQs all target the Overview page — About/Thesis stay empty until content exists there.
        DB::table('unlisted_faqs')->where('UL_FAQ_FINCODE', $fincode)->where('UL_FAQ_TARGET', 'overview')->delete();

        $faqs = [
            [
                'q' => "How do I buy {$name} unlisted shares?",
                'a' => "Share your CML copy, PAN, cancelled cheque and Aadhaar for KYC verification, then transfer payment to our verified company account. Shares are credited to your demat account after payment confirmation.",
            ],
            [
                'q' => "What is the minimum lot size for {$name}?",
                'a' => "The minimum lot size for {$name} is {$lotSize} shares. You can invest in multiples of this lot.",
            ],
            [
                'q' => 'How is the unlisted share price determined?',
                'a' => 'Unlisted share prices are set by prevailing demand-supply in the OTC market and are updated periodically by our research desk. Prices can vary between dealers and carry wider buy-sell spreads than listed markets.',
            ],
            [
                'q' => "Is investing in {$name} unlisted shares safe?",
                'a' => 'Unlisted shares carry additional risks including liquidity risk, valuation/price-discovery risk and IPO uncertainty. Please read our Disclaimer page before investing and consult a qualified adviser.',
            ],
            [
                'q' => 'How long does share delivery take after purchase?',
                'a' => 'Shares are typically credited to your demat account (NSDL/CDSL) within 1-3 business days of payment confirmation, subject to transfer formalities.',
            ],
        ];

        $rows = [];
        foreach ($faqs as $i => $faq) {
            $rows[] = [
                'UL_FAQ_FINCODE'     => $fincode,
                'UL_FAQ_TARGET'      => 'overview',
                'UL_FAQ_QUESTION'    => $faq['q'],
                'UL_FAQ_ANSWER'      => $faq['a'],
                'UL_FAQ_SORT_ORDER'  => $i + 1,
                'UL_FAQ_ACTIVE'      => '1',
                'UL_FAQ_INSERT_TIME' => now(),
                'UL_FAQ_UPDATE_TIME' => now(),
            ];
        }

        DB::table('unlisted_faqs')->insert($rows);
    }

    private function seedPriceHistory(int $fincode, float $currentPrice, float $trendPct): void
    {
        $days       = 400;
        $startPrice = $currentPrice / (1 + $trendPct);
        $rows       = [];

        for ($d = $days; $d >= 0; $d--) {
            $progress = ($days - $d) / $days;
            $base     = $startPrice + ($currentPrice - $startPrice) * $progress;
            $noise    = $base * (mt_rand(-150, 150) / 10000);
            $price    = $d === 0 ? $currentPrice : max(1, round($base + $noise, 2));

            $rows[] = [
                'UL_PD_FINCODE'      => $fincode,
                'UL_PD_DATE'         => now()->subDays($d)->format('Y-m-d') . ' 15:30:00',
                'UL_PD_BID_PRICE'    => $price,
                'UL_PD_INVALID_FLAG' => 0,
                'UL_PD_UPDTIME'      => now(),
            ];
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('unlisted_price_data')->upsert(
                $chunk,
                ['UL_PD_FINCODE', 'UL_PD_DATE'],
                ['UL_PD_BID_PRICE', 'UL_PD_INVALID_FLAG', 'UL_PD_UPDTIME']
            );
        }
    }

    private function seedFinancials(int $fincode, array $c): void
    {
        $now       = now();
        $fyEndYear = $now->month >= 4 ? $now->year : $now->year - 1;

        $periods = [
            ['period_end' => $fyEndYear * 100 + 3,       'no_months' => 12, 'scale' => 1.00],
            ['period_end' => ($fyEndYear - 1) * 100 + 3,  'no_months' => 12, 'scale' => 0.86],
            ['period_end' => $fyEndYear * 100 + 12,       'no_months' => 3,  'scale' => 0.27],
            ['period_end' => $fyEndYear * 100 + 9,        'no_months' => 3,  'scale' => 0.24],
            ['period_end' => $fyEndYear * 100 + 6,        'no_months' => 3,  'scale' => 0.23],
            ['period_end' => $fyEndYear * 100 + 3,        'no_months' => 3,  'scale' => 0.22],
        ];

        foreach ($periods as $p) {
            $row = $this->buildFinancialRow($fincode, $p['period_end'], $p['no_months'], $c, $p['scale']);

            DB::table('unlisted_financials')->upsert(
                [$row],
                ['UL_FIN_FINCODE', 'UL_FIN_Period_end', 'UL_FIN_Type', 'UL_FIN_No_months'],
                array_diff(
                    array_keys($row),
                    ['UL_FIN_FINCODE', 'UL_FIN_Period_end', 'UL_FIN_Type', 'UL_FIN_No_months', 'UL_FIN_INSERT_TIME']
                )
            );
        }
    }

    private function buildFinancialRow(int $fincode, int $periodEnd, int $noMonths, array $c, float $scale): array
    {
        $netSales         = $c['netSalesCr'] * $scale;
        $otherIncome      = $netSales * 0.03;
        $totalIncome      = $netSales + $otherIncome;
        $operatingMargin  = $c['patMarginPct'] + 0.12;
        $operatingProfit  = $totalIncome * $operatingMargin;
        $totalExpenditure = $totalIncome - $operatingProfit;
        $interest         = $c['totalDebtCr'] * 0.08 * $scale;
        $depreciation     = $totalIncome * 0.04;
        $pbt              = $operatingProfit - $interest - $depreciation;
        $tax              = max($pbt * 0.25, 0);
        $pat              = $pbt - $tax;

        $shareholderFunds = $c['shareholderFundsCr'] * (0.7 + 0.3 * $scale);
        $totalDebt        = $c['totalDebtCr'] * $scale;
        $totalAssets      = $shareholderFunds * 1.6 + $totalDebt * 0.5;
        $currentAssets    = $totalAssets * 0.45;
        $nonCurrentAssets = $totalAssets - $currentAssets;
        $totalLiab        = $totalAssets - $shareholderFunds;
        $currentLiab      = $totalLiab * 0.4;
        $nonCurrentLiab   = $totalLiab - $currentLiab;

        $cfo = $pat + $depreciation * 1.1;
        $cfi = -($totalAssets * 0.05);
        $cff = -($pat * 0.2);
        $fcf = $cfo + $cfi;

        return [
            'UL_FIN_FINCODE'                             => $fincode,
            'UL_FIN_Period_end'                           => $periodEnd,
            'UL_FIN_Type'                                 => 'C',
            'UL_FIN_No_months'                            => $noMonths,
            'UL_FIN_Unit'                                 => 10000000,
            'UL_FIN_FV'                                   => $c['fv'],
            'UL_FIN_NUM_SHARES'                           => $c['numSharesCr'] * 10000000,
            'UL_FIN_NET_SALES'                            => round($netSales, 2),
            'UL_FIN_OTHER_INCOME'                         => round($otherIncome, 2),
            'UL_FIN_TOTAL_INCOME'                         => round($totalIncome, 2),
            'UL_FIN_TOTAL_EXPENDITURE'                    => round($totalExpenditure, 2),
            'UL_FIN_OPERATING_PROFIT'                     => round($operatingProfit, 2),
            'UL_FIN_INTEREST'                              => round($interest, 2),
            'UL_FIN_DEPRECIATION'                         => round($depreciation, 2),
            'UL_FIN_PBT'                                  => round($pbt, 2),
            'UL_FIN_TAX'                                  => round($tax, 2),
            'UL_FIN_PAT'                                  => round($pat, 2),
            'UL_FIN_SHAREHOLDER_FUNDS'                    => round($shareholderFunds, 2),
            'UL_FIN_TOTAL_DEBT'                           => round($totalDebt, 2),
            'UL_FIN_TOTAL_ASSETS'                         => round($totalAssets, 2),
            'UL_FIN_CURRENT_ASSETS'                       => round($currentAssets, 2),
            'UL_FIN_NON_CURRENT_ASSETS'                   => round($nonCurrentAssets, 2),
            'UL_FIN_TOTAL_LIABILITIES'                    => round($totalLiab, 2),
            'UL_FIN_CURRENT_LIABILITIES'                  => round($currentLiab, 2),
            'UL_FIN_NON_CURRENT_LIABILITIES'              => round($nonCurrentLiab, 2),
            'UL_FIN_CASH_FLOW_FROM_OPERATING_ACTIVITIES'  => round($cfo, 2),
            'UL_FIN_CASH_FLOW_FORM_INVESTING_ACTIVITIES'  => round($cfi, 2),
            'UL_FIN_CASH_FLOW_FROM_FINANCING_ACTIVITIES'  => round($cff, 2),
            'UL_FIN_FREE_CASH_FLOW'                       => round($fcf, 2),
            'UL_FIN_STATUS'                               => '1',
            'UL_FIN_INSERT_TIME'                          => now(),
            'UL_FIN_UPDATE_TIME'                          => now(),
        ];
    }
}
