<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnlistedAboutExtra extends Model
{
    protected $table      = 'unlisted_about_extra';
    protected $primaryKey = 'UL_ABX_ID';
    public    $timestamps = false;

    protected $fillable = [
        'UL_ABX_FINCODE',
        'UL_ABX_OVERVIEW',
        'UL_ABX_OPERATIONS',
        'UL_ABX_GEOGRAPHY',
        'UL_ABX_INDUSTRY_POSITION',
        'UL_ABX_SHAREHOLDING',
        'UL_ABX_INVESTOR_INTEREST',
        'UL_ABX_MARKET_LANDSCAPE',
        'UL_ABX_COMPETITIVE_STRENGTH',
        'UL_ABX_VERTICALS',
        'UL_ABX_REVENUE_SEGMENTS',
        'UL_ABX_HISTORY',
        'UL_ABX_PRODUCTS_SERVICES',
        'UL_ABX_SOURCES',
        'UL_ABX_SWOT_STRENGTHS',
        'UL_ABX_SWOT_WEAKNESSES',
        'UL_ABX_SWOT_OPPORTUNITIES',
        'UL_ABX_SWOT_THREATS',
        'UL_ABX_ACTIVE',
        'UL_ABX_INSERT_TIME',
        'UL_ABX_UPDATE_TIME',
    ];

    /**
     * "Title | description" per line -> [['title' => ..., 'text' => ...], ...]
     * Used for verticals, revenue segments and products/services.
     */
    public static function parsePairs(?string $raw): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $raw))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function ($line) {
                [$title, $text] = array_pad(explode('|', $line, 2), 2, '');
                return ['title' => trim($title), 'text' => trim($text)];
            })
            ->values()
            ->all();
    }

    /** "Year | milestone text" per line -> [['year' => ..., 'text' => ...], ...] */
    public static function parseHistory(?string $raw): array
    {
        return collect(self::parsePairs($raw))
            ->map(fn ($row) => ['year' => $row['title'], 'text' => $row['text']])
            ->all();
    }

    /** "Label | https://..." per line -> [['label' => ..., 'href' => ...], ...] */
    public static function parseSources(?string $raw): array
    {
        return collect(self::parsePairs($raw))
            ->map(fn ($row) => ['label' => $row['title'], 'href' => $row['text']])
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
