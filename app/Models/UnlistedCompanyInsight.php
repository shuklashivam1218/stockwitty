<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnlistedCompanyInsight extends Model
{
    protected $table      = 'unlisted_company_insights';
    protected $primaryKey = 'UL_CI_ID';
    public    $timestamps = false;

    protected $fillable = [
        'UL_CI_FINCODE',
        'UL_CI_AI_SUMMARY',
        'UL_CI_TLDR',
        'UL_CI_FOUNDERS_INTRO',
        'UL_CI_FOUNDERS_QUOTE',
        'UL_CI_FOUNDERS_VERDICT',
        'UL_CI_IPO_TIMELINE',
        'UL_CI_IPO_FACTS',
        'UL_CI_BULL_CASE',
        'UL_CI_BEAR_CASE',
        'UL_CI_SUITS_IF',
        'UL_CI_NOT_SUITS_IF',
        'UL_CI_RISKS',
        'UL_CI_VERDICT_LONG',
        'UL_CI_ACTIVE',
        'UL_CI_INSERT_TIME',
        'UL_CI_UPDATE_TIME',
    ];

    /** "Label | Value" per line -> [['label' => ..., 'value' => ...], ...] */
    public static function parsePairs(?string $raw): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $raw))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function ($line) {
                [$label, $value] = array_pad(explode('|', $line, 2), 2, '');
                return ['label' => trim($label), 'value' => trim($value)];
            })
            ->values()
            ->all();
    }

    /** One plain point per line -> ['point 1', 'point 2', ...] */
    public static function parseLines(?string $raw): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $raw))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
