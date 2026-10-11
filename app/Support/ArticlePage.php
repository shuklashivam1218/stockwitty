<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

/**
 * The view data x-sw.blog-post renders from, for a blog post or a stock
 * article (anything using App\Models\Concerns\IsArticle). Callers supply
 * what differs between them: breadcrumbs, fallback chips, the default
 * callback-form text, and any extra related links or TOC entries.
 */
class ArticlePage
{
    /**
     * @param array $crumbs      [['label' => ..., 'href' => ...], ...]
     * @param array $chips       shown when the article has no chips of its own
     * @param array $lead        ['heading' => ..., 'subtext' => ...] when the article leaves them blank
     * @param array $related     extra related-reading cards, after the picked blog posts
     * @param array $tocBefore   TOC entries for blocks rendered above the body
     */
    public static function data(Model $article, array $crumbs, array $chips, array $lead, array $related = [], array $tocBefore = []): array
    {
        $reserved = array_filter([
            $article->sources ? 'sources' : null,
            $article->faqs ? 'faq' : null,
            'related',
            ...array_column($tocBefore, 'id'),
        ]);
        ['toc' => $toc, 'html' => $body] = HtmlToc::build((string) $article->content, $reserved);
        $toc = [...$tocBefore, ...$toc];

        $related = [
            ...$article->relatedPosts()->map(fn ($r) => [
                'title'    => $r->title,
                'href'     => $r->url(),
                'category' => $r->category->name ?? 'Blog',
                'read'     => $r->readLabel(),
            ])->all(),
            ...$related,
        ];

        if ($article->sources) {
            $toc[] = ['id' => 'sources', 'label' => 'Sources & references'];
        }
        if ($article->faqs) {
            $toc[] = ['id' => 'faq', 'label' => 'FAQ'];
        }

        $author = $article->author;
        $title  = $article->meta_title ?: $article->title;

        return [
            'post'       => $article,
            'metaTitle'  => str_contains($title, 'StocksWitty') ? $title : $title . ' | StocksWitty',
            'metaDesc'   => $article->meta_description ?: $article->summary,
            'crumbs'     => $crumbs,
            'chips'      => $article->chips ?: array_values(array_filter($chips)),
            'authorLine' => $author ? trim($author->name . ($author->author_designation ? ' · ' . $author->author_designation : '')) : 'StocksWitty Research',
            'author'     => $author,
            'dateLabel'  => $article->published_at?->format('d M Y') ?? 'Draft',
            'hero'       => $article->featured_image
                ? ['src' => asset($article->featured_image), 'alt' => $article->featured_image_alt ?: $article->title]
                : null,
            'introParas' => array_values(array_filter(array_map('trim', preg_split('/\R\s*\R/', (string) $article->intro)))),
            'toc'        => $toc,
            'body'       => $body,
            'related'    => $related,
            'leadForm'   => [
                'heading' => $article->lead_heading ?: $lead['heading'],
                'subtext' => $article->lead_subtext ?: $lead['subtext'],
            ],
            'preview'    => false,
        ];
    }
}
