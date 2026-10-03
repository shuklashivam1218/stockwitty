<?php

namespace App\Support\AgentView;

/**
 * Server-side copies of the on-page calculators, used to print a worked
 * example into the Markdown twin (agents can't run the JavaScript ones).
 * Each method must stay identical to the JS it mirrors — named per method.
 */
final class CalculatorFormulas
{
    /**
     * SIP future value, payments at the start of each month (annuity due).
     * Mirrors resources/js/sw/sip-calculator.js -> total / invested / returns.
     *
     * @return array{invested: float, total: float, returns: float}
     */
    public static function sip(float $monthly, float $annualRatePct, float $years): array
    {
        $months = max(1, (int) round($years * 12));
        $i = $annualRatePct / 100 / 12;

        $total = $i == 0.0
            ? $monthly * $months
            : $monthly * ((pow(1 + $i, $months) - 1) / $i) * (1 + $i);
        $invested = $monthly * $months;

        return ['invested' => $invested, 'total' => $total, 'returns' => $total - $invested];
    }

    /**
     * Fixed deposit maturity with quarterly compounding.
     * Mirrors the x-data calculator in resources/views/sw/fixed-deposits/suryoday.blade.php.
     *
     * @return array{maturity: float, interest: float}
     */
    public static function fdQuarterly(float $principal, float $annualRatePct, int $years): array
    {
        $maturity = round($principal * pow(1 + $annualRatePct / 100 / 4, 4 * $years));

        return ['maturity' => $maturity, 'interest' => $maturity - $principal];
    }

    /**
     * Digital gold / silver purchase: grams = amount / rate, 3% GST on purchase.
     * Mirrors the x-data calculators in resources/views/sw/digital-{gold,silver}/index.blade.php.
     *
     * @return array{grams: float, gst: float}
     */
    public static function metalPurchase(float $amount, float $ratePerGram): array
    {
        return ['grams' => $amount / $ratePerGram, 'gst' => $amount * 0.03];
    }
}
