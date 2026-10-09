<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use SoftDeletes;

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public const STATUSES = [self::STATUS_DRAFT, self::STATUS_PUBLISHED];

    private const WORDS_PER_MINUTE = 200;

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
            'chips'            => 'array',
            'takeaways'        => 'array',
            'faqs'             => 'array',
            'sources'          => 'array',
            'video'            => 'array',
            'related_post_ids' => 'array',
            'is_featured'      => 'boolean',
            'published_at'     => 'datetime',
            'locked_at'        => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (BlogPost $post) {
            if ($post->isDirty(['intro', 'content'])) {
                $post->reading_minutes = static::readingMinutes($post->intro . ' ' . $post->content);
            }
        });
    }

    public static function readingMinutes(?string $html): int
    {
        $words = str_word_count(strip_tags((string) $html));

        return max(1, (int) ceil($words / self::WORDS_PER_MINUTE));
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by', 'uid');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by', 'uid');
    }

    public function lockedByUser()
    {
        return $this->belongsTo(User::class, 'locked_by', 'uid');
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

    /** Related posts in the order the editor picked them; unpublished ones drop out. */
    public function relatedPosts()
    {
        $ids = array_values(array_filter((array) $this->related_post_ids, 'is_numeric'));
        if ($ids === []) {
            return collect();
        }

        return static::published()->whereKey($ids)->with('category')->get()
            ->sortBy(fn ($p) => array_search($p->id, $ids))
            ->values();
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)->whereNotNull('published_at');
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function url(): string
    {
        return '/blog/' . $this->slug . '/';
    }

    /** FAQ tab names in first-seen order, for the layout's FAQ filter. */
    public function faqTabs(): array
    {
        return collect($this->faqs ?? [])->pluck('tab')->filter()->unique()->values()->all();
    }

    public function readLabel(): string
    {
        return $this->reading_minutes . ' min read';
    }
}
