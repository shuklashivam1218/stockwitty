<?php

namespace App\Models;

use App\Models\Concerns\IsArticle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A stock's About, Thesis or WittyScore page, written in the CMS like a blog
 * post. One per stock per type; it lives at /unlisted-shares/{slug}/{type}/.
 */
class StockArticle extends Model
{
    use IsArticle;
    use SoftDeletes;

    public const TYPE_ABOUT      = 'about';
    public const TYPE_THESIS     = 'thesis';
    public const TYPE_WITTYSCORE = 'wittyscore';

    /** type => label, default hero icon and the default title after the company name. */
    public const TYPES = [
        self::TYPE_ABOUT      => ['label' => 'About',      'icon' => 'landmark',    'title' => 'Company Profile'],
        self::TYPE_THESIS     => ['label' => 'Thesis',     'icon' => 'file-search', 'title' => 'Investment Thesis'],
        self::TYPE_WITTYSCORE => ['label' => 'WittyScore', 'icon' => 'bar-chart-3', 'title' => 'WittyScore Explained'],
    ];

    protected $fillable = [
        'ul_stocks_fincode',
        'type',
        'title',
        'summary',
        'intro',
        'content',
        'featured_image',
        'featured_image_alt',
        'hero_icon',
        'chips',
        'takeaways',
        'faqs',
        'sources',
        'video',
        'related_post_ids',
        'lead_heading',
        'lead_subtext',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'published_at',
        'created_by',
        'published_by',
    ];

    public function stock()
    {
        return $this->belongsTo(UnlistedStock::class, 'ul_stocks_fincode', 'UL_STOCKS_FINCODE');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public static function typeLabel(string $type): string
    {
        return self::TYPES[$type]['label'] ?? ucfirst($type);
    }

    /** The default title for a new article, e.g. "Nayara Energy: Investment Thesis". */
    public static function defaultTitle(string $type, string $companyName): string
    {
        return $companyName . ': ' . (self::TYPES[$type]['title'] ?? self::typeLabel($type));
    }

    public function url(): string
    {
        return '/unlisted-shares/' . $this->stock->UL_STOCKS_SLUG . '/' . $this->type . '/';
    }
}
