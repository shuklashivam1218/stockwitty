<?php

namespace App\Http\Controllers\Sw;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSlugRedirect;
use App\Support\HtmlToc;
use Illuminate\Support\Str;

/** Public /blog/ pages, rendered from blog_posts. */
class BlogController extends Controller
{
    private const DEFAULT_LEAD = [
        'heading' => 'Questions about unlisted shares? Talk to a human.',
        'subtext' => 'Tell us what you are looking at. A StocksWitty specialist will call you back on a working day — nothing is bought or sold without your written confirmation.',
    ];

    public function index()
    {
        $posts = BlogPost::published()->with('category')->orderByDesc('published_at')->get();

        $featured = $posts->firstWhere('is_featured', true) ?? $posts->first();
        $rest     = $posts->reject(fn ($p) => $featured && $p->is($featured))->values();

        // Only offer filters that would show something.
        $used = $posts->pluck('category.name')->filter()->unique();
        $cats = BlogCategory::active()->pluck('name')->filter(fn ($n) => $used->contains($n))->prepend('All')->values()->all();

        return view('sw.blog.index', [
            'cats'     => $cats,
            'featured' => $featured ? $this->card($featured) : null,
            'rest'     => $rest->map(fn ($p) => $this->card($p))->all(),
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()->with('category', 'author')->where('slug', $slug)->first();

        if (!$post) {
            $moved = BlogSlugRedirect::where('old_slug', $slug)->first()?->post;
            abort_unless($moved && $moved->isPublished(), 404);

            return redirect($moved->url(), 301);
        }

        return view('sw.blog.show', $this->page($post));
    }

    /** Admin-only (privilege:author,reviewer) — any post, any status, never indexed. */
    public function preview(int $id)
    {
        $post = BlogPost::withTrashed()->with('category', 'author')->findOrFail($id);

        return response()
            ->view('sw.blog.show', ['preview' => true] + $this->page($post))
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    /** The shape the listing cards and homepage teaser render. */
    public static function card(BlogPost $p): array
    {
        return [
            'slug'      => $p->slug,
            'href'      => $p->url(),
            'title'     => $p->title,
            'category'  => $p->category->name ?? 'Blog',
            'excerpt'   => $p->summary,
            'read'      => $p->readLabel(),
            'date'      => $p->published_at?->format('d M Y'),
            'image'     => $p->featured_image ? asset($p->featured_image) : null,
            'imageAlt'  => $p->featured_image_alt ?: $p->title,
            'heroIcon'  => $p->hero_icon,
        ];
    }

    private function page(BlogPost $post): array
    {
        $reserved = array_filter([
            $post->sources ? 'sources' : null,
            $post->faqs ? 'faq' : null,
            'related',
        ]);
        ['toc' => $toc, 'html' => $body] = HtmlToc::build((string) $post->content, $reserved);

        $related = $post->relatedPosts()->map(fn ($r) => [
            'title'    => $r->title,
            'href'     => $r->url(),
            'category' => $r->category->name ?? 'Blog',
            'read'     => $r->readLabel(),
        ])->all();

        if ($post->sources) {
            $toc[] = ['id' => 'sources', 'label' => 'Sources & references'];
        }
        if ($post->faqs) {
            $toc[] = ['id' => 'faq', 'label' => 'FAQ'];
        }

        $author   = $post->author;
        $category = $post->category->name ?? null;

        return [
            'post'       => $post,
            'metaTitle'  => $this->metaTitle($post),
            'metaDesc'   => $post->meta_description ?: $post->summary,
            'crumbs'     => array_values(array_filter([
                ['label' => 'Home', 'href' => '/'],
                ['label' => 'Blog', 'href' => '/blog/'],
                $category ? ['label' => $category, 'href' => '/blog/'] : null,
                ['label' => Str::limit($post->title, 48)],
            ])),
            'chips'      => $post->chips ?: array_filter([$category]),
            'authorLine' => $author ? trim($author->name . ($author->author_designation ? ' · ' . $author->author_designation : '')) : 'StocksWitty Research',
            'author'     => $author,
            'dateLabel'  => $post->published_at?->format('d M Y') ?? 'Draft',
            'hero'       => $post->featured_image
                ? ['src' => asset($post->featured_image), 'alt' => $post->featured_image_alt ?: $post->title]
                : null,
            'introParas' => array_values(array_filter(array_map('trim', preg_split('/\R\s*\R/', (string) $post->intro)))),
            'toc'        => $toc,
            'body'       => $body,
            'related'    => $related,
            'leadForm'   => [
                'heading' => $post->lead_heading ?: self::DEFAULT_LEAD['heading'],
                'subtext' => $post->lead_subtext ?: self::DEFAULT_LEAD['subtext'],
            ],
            'preview'    => false,
        ];
    }

    private function metaTitle(BlogPost $post): string
    {
        $title = $post->meta_title ?: $post->title;

        return str_contains($title, 'StocksWitty') ? $title : $title . ' | StocksWitty';
    }
}
