<?php

namespace App\Models;

use App\Models\Concerns\IsArticle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use IsArticle;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
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
        'is_featured',
        'published_at',
        'created_by',
        'published_by',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function unlistedStocks()
    {
        return $this->belongsToMany(
            UnlistedStock::class,
            'blog_post_unlisted_stock',
            'blog_post_id',
            'ul_stocks_fincode',
            'id',
            'UL_STOCKS_FINCODE'
        );
    }

    public function slugRedirects()
    {
        return $this->hasMany(BlogSlugRedirect::class, 'blog_post_id');
    }

    public function url(): string
    {
        return '/blog/' . $this->slug . '/';
    }
}
