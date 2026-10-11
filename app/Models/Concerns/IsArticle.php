<?php

namespace App\Models\Concerns;

use App\Models\BlogPost;
use App\Models\User;

/**
 * What blog posts and per-stock articles share: draft/published status,
 * reading time, the author/publisher/edit-lock users, the structured blocks
 * the blog post layout renders, and related blog posts.
 */
trait IsArticle
{
    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public const STATUSES = [self::STATUS_DRAFT, self::STATUS_PUBLISHED];

    private const WORDS_PER_MINUTE = 200;

    public static function bootIsArticle(): void
    {
        static::saving(function ($article) {
            if ($article->isDirty(['intro', 'content'])) {
                $article->reading_minutes = static::readingMinutes($article->intro . ' ' . $article->content);
            }
        });
    }

    public function initializeIsArticle(): void
    {
        $this->mergeCasts([
            'chips'            => 'array',
            'takeaways'        => 'array',
            'faqs'             => 'array',
            'sources'          => 'array',
            'video'            => 'array',
            'related_post_ids' => 'array',
            'published_at'     => 'datetime',
            'locked_at'        => 'datetime',
        ]);
    }

    public static function readingMinutes(?string $html): int
    {
        $words = str_word_count(strip_tags((string) $html));

        return max(1, (int) ceil($words / self::WORDS_PER_MINUTE));
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

    /** Related blog posts in the order the editor picked them; unpublished ones drop out. */
    public function relatedPosts()
    {
        $ids = array_values(array_filter((array) $this->related_post_ids, 'is_numeric'));
        if ($ids === []) {
            return collect();
        }

        return BlogPost::published()->whereKey($ids)->with('category')->get()
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
