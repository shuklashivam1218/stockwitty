<?php

namespace App\Support\AgentView;

/**
 * Row builders for <x-sw.agent-data-table>: turn the arrays the controllers
 * already pass to the views (the ones Alpine renders for humans) into plain
 * table rows for the Markdown twin. Formatting matches the human page.
 */
final class AgentTables
{
    public const COMPANY_HEAD = [
        'Company', 'Sector', 'Tag', 'Indicative price', '1-week change', 'Lot size', 'Min investment', 'WittyScore', 'DRHP filed',
    ];

    public const SNAPSHOT_HEAD = [
        'Company', 'Indicative price', '1-week change', '52W high', '52W low', 'Lot size', 'Min investment', 'Market cap', 'P/E', 'WittyScore',
    ];

    public const PRICE_HISTORY_HEAD = ['Period', 'First point', 'Latest point', 'Change', 'High', 'Low'];

    /** Directory rows: one per company, as built by CompanyController::directory(). */
    public static function companyRows(iterable $companies): array
    {
        $rows = [];

        foreach ($companies as $c) {
            $rows[] = [
                self::companyLink($c),
                $c['sector'] ?: '—',
                $c['tag'] ?: '—',
                self::price($c['price']),
                self::change($c['changePct'], $c['price']),
                self::indianNumber($c['lot']),
                self::minInvestment($c['price'], $c['lot']),
                self::score($c['wittyScore']),
                ! empty($c['drhp']) ? 'Yes' : 'No',
            ];
        }

        return $rows;
    }

    /** Homepage showcase rows, as built by HomeController::index(). */
    public static function snapshotRows(iterable $companies): array
    {
        $rows = [];

        foreach ($companies as $c) {
            $rows[] = [
                self::companyLink($c),
                self::price($c['price']),
                self::change($c['changePct'], $c['price']),
                self::price($c['high52']),
                self::price($c['low52']),
                self::indianNumber($c['lot']),
                self::minInvestment($c['price'], $c['lot']),
                $c['mktCap'],
                $c['pe'],
                self::score($c['wittyScore']),
            ];
        }

        return $rows;
    }

    /**
     * One row per chart period (1M, 6M, ... Max) from CompanyController's
     * chart series: where the price started, where it is now, high and low.
     * A period with a single price point has no movement to report and is skipped.
     *
     * @param array<string, array<int, array{label: string, price: float}>> $series
     */
    public static function priceHistoryRows(array $series): array
    {
        $rows = [];

        foreach ($series as $period => $points) {
            if (count($points) < 2) {
                continue;
            }

            $first  = $points[0];
            $latest = $points[count($points) - 1];
            $prices = array_column($points, 'price');

            $rows[] = [
                $period,
                self::price($first['price']) . ' (' . $first['label'] . ')',
                self::price($latest['price']) . ' (' . $latest['label'] . ')',
                $first['price'] > 0 ? self::percent(($latest['price'] - $first['price']) / $first['price'] * 100) : '—',
                self::price(max($prices)),
                self::price(min($prices)),
            ];
        }

        return $rows;
    }

    /** Same thresholds as minInvestment() in resources/js/sw/unlisted-shares.js. */
    public static function minInvestment(float $price, int $lot): string
    {
        $total = $price * $lot;

        if ($total <= 0) {
            return '—';
        }

        return $total >= 100000
            ? '₹' . number_format($total / 100000, 2) . 'L'
            : self::inr($total);
    }

    public static function price(float $amount, int $decimals = 0): string
    {
        return $amount > 0 ? self::inr($amount, $decimals) : '—';
    }

    /** "₹12,00,000" — rupees in Indian digit grouping, like toLocaleString('en-IN') on the page. */
    public static function inr(float $amount, int $decimals = 0): string
    {
        return ($amount < 0 ? '−₹' : '₹') . self::indianNumber(abs($amount), $decimals);
    }

    /** 1234567.8 -> "12,34,567.8": last three digits, then groups of two. */
    public static function indianNumber(float $value, int $decimals = 0): string
    {
        [$whole, $fraction] = array_pad(explode('.', number_format($value, $decimals, '.', '')), 2, null);

        $lastThree = substr($whole, -3);
        $rest      = substr($whole, 0, -3);
        $grouped   = $rest === '' ? $lastThree : preg_replace('/\B(?=(\d{2})+$)/', ',', $rest) . ',' . $lastThree;

        return $fraction === null ? $grouped : $grouped . '.' . $fraction;
    }

    public static function percent(float $pct): string
    {
        return ($pct >= 0 ? '+' : '−') . number_format(abs($pct), 2) . '%';
    }

    private static function change(float $pct, float $price): string
    {
        return $price > 0 ? self::percent($pct) : '—';
    }

    private static function score(float $score): string
    {
        return $score > 0 ? number_format($score, 1) . '/10' : 'Not rated yet';
    }

    private static function companyLink(array $c): array
    {
        return ['text' => $c['name'], 'href' => '/unlisted-shares/' . $c['slug'] . '/'];
    }
}
