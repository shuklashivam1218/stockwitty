<?php

namespace App\Http\Controllers\Sw;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSlugRedirect;
use App\Support\ArticlePage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/** Public /blog/ pages, rendered from blog_posts. */
class BlogController extends Controller
{
    public const PER_PAGE = 9;

    private const DEFAULT_LEAD = [
        'heading' => 'Questions about unlisted shares? Talk to a human.',
        'subtext' => 'Tell us what you are looking at. A StocksWitty specialist will call you back on a working day — nothing is bought or sold without your written confirmation.',
    ];

    /**
     * /blog/ — featured post on top (unfiltered first page only), then
     * PER_PAGE cards per page. The category filter is a query parameter
     * rather than a client-side toggle, so it keeps working across pages.
     */
    public function index(Request $request)
    {
        // Only offer filters that would show something.
        $categories = BlogCategory::active()
            ->whereHas('posts', fn ($q) => $q->published())
            ->get(['id', 'name', 'slug']);

        $active = null;
        if ($request->filled('category')) {
            $active = $categories->firstWhere('slug', (string) $request->query('category'));
            abort_unless($active, 404);
        }

        $base = BlogPost::published()->with('category')
            ->when($active, fn ($q) => $q->where('category_id', $active->id));

        // The featured post is always kept out of the grid, so paging is the
        // same whether or not its card is showing.
        $featured = $active ? null : (clone $base)->orderByDesc('is_featured')->orderByDesc('published_at')->first();

        $posts = (clone $base)
            ->when($featured, fn ($q) => $q->whereKeyNot($featured->id))
            ->orderByDesc('published_at')->orderByDesc('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString()
            ->fragment('posts');

        abort_if($posts->currentPage() > 1 && $posts->isEmpty(), 404);

        $page = $posts->currentPage();

        $data = [
            'categories' => $categories,
            'active'     => $active,
            'featured'   => $featured && $page === 1 ? $this->card($featured) : null,
            'posts'      => $posts->through(fn ($p) => $this->card($p)),
            'total'      => $posts->total() + ($featured ? 1 : 0),
            'pageTitle'  => trim(($active ? $active->name . ' — ' : '') . 'Unlisted Shares Blog' . ($page > 1 ? " — Page {$page}" : '')),
            'canonical'  => rtrim(config('app.url'), '/') . '/blog/' . (($q = http_build_query(array_filter([
                'category' => $active?->slug,
                'page'     => $page > 1 ? $page : null,
            ]))) ? '?' . $q : ''),
        ];

        // In-page page/filter changes (see sw/blog/index) only need the listing.
        $response = $request->ajax()
            ? response()->view('sw.blog.partials.listing', $data)
            : response()->view('sw.blog.index', $data);

        return $response->header('Vary', 'X-Requested-With');
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
        $category = $post->category->name ?? null;

        return ArticlePage::data(
            $post,
            crumbs: array_values(array_filter([
                ['label' => 'Home', 'href' => '/'],
                ['label' => 'Blog', 'href' => '/blog/'],
                $category ? ['label' => $category, 'href' => '/blog/'] : null,
                ['label' => Str::limit($post->title, 48)],
            ])),
            chips: [$category],
            lead: self::DEFAULT_LEAD,
        );
    }
}
