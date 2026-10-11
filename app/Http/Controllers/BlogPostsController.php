<?php

namespace App\Http\Controllers;

use App\Helpers\ImageUpload;
use App\Http\Controllers\Concerns\EditsArticles;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSlugRedirect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Admin CRUD for blog posts, ported from unlisted-stocks' CmsArticleController.
 * Every route here sits behind privilege:author,reviewer, and both may do
 * everything (write, publish, trash) — the edit lock is what keeps two
 * people from overwriting each other. Publishing, trash, images and the
 * lock are shared with stock articles through EditsArticles.
 */
class BlogPostsController extends Controller
{
    use EditsArticles;

    public function index(Request $request)
    {
        $status = $request->query('status', '');
        $search = trim((string) $request->query('search', ''));

        $query = BlogPost::query()->with('author', 'category')->orderByDesc('updated_at');

        if ($status === 'trash') {
            $query->onlyTrashed();
        } elseif (in_array($status, BlogPost::STATUSES, true)) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $posts = $query->paginate(20)->withQueryString();

        return view('admin.blog.index', compact('posts', 'status', 'search'));
    }

    public function create()
    {
        $post = new BlogPost();

        return view('admin.blog.form', $this->formData($post));
    }

    public function store(Request $request)
    {
        $data = $this->validatePost($request);

        // A slug typed by the author is used as-is (or refused if taken);
        // left blank, one is made from the title with a -2/-3 suffix if needed.
        $chosen             = $this->requestedSlug($request);
        $data['slug']       = $chosen ?? $this->uniqueSlug($data['title']);
        $data['created_by'] = session('uid');
        $this->applyPublishState($data);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->storeFeaturedImage($request);
        }

        $post = BlogPost::create($data);
        $post->unlistedStocks()->sync($request->input('stock_tickers', []));

        return redirect()->route('admin.blog.posts.edit', $post->id)->with('success', 'Post created.');
    }

    public function edit(int $id)
    {
        $post = BlogPost::findOrFail($id);

        // First person to open the post holds the lock — everyone else gets a
        // read-only form until the holder saves/leaves or their tab goes quiet.
        return view('admin.blog.form', ['lockedBy' => $this->openForEditing($post)] + $this->formData($post));
    }

    public function update(Request $request, int $id)
    {
        $post = BlogPost::findOrFail($id);

        // Enforced here too, not just via the disabled form — a client can replay the POST.
        if ($this->isLockedByOther($post, session('uid'))) {
            $lock = $this->lockInfo($post);
            return redirect()->route('admin.blog.posts.edit', $post->id)
                ->with('lock_error', "{$lock['name']} is currently editing this post (started {$lock['since']}). Your changes were not saved.");
        }

        $data = $this->validatePost($request, $post);
        $this->applyPublishState($data, $post);

        // The URL only changes through its own field — editing the title never
        // touches it, so published links and rankings stay put. Checked before
        // any upload so a refused slug doesn't leave an orphaned image behind.
        $newSlug = $this->requestedSlug($request, $post);
        $newSlug = $newSlug !== $post->slug ? $newSlug : null;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->storeFeaturedImage($request);
            ImageUpload::delete($post->featured_image);
        }

        DB::transaction(function () use ($request, $post, $data, $newSlug) {
            if ($newSlug) {
                $data['slug'] = $newSlug;
                $this->rememberOldSlug($post, $newSlug);
            }

            $post->update($data);
            $post->unlistedStocks()->sync($request->input('stock_tickers', []));
        });

        return redirect()->route('admin.blog.posts.edit', $post->id)->with('success', 'Post updated.');
    }

    public function searchStocks(Request $request)
    {
        $term = trim((string) $request->query('term', ''));

        $stocks = DB::table('unlisted_stocks')
            ->where('UL_STOCKS_STATUS', '1')
            ->when($term !== '', fn ($q) => $q->where('UL_STOCKS_COMPNAME', 'like', '%' . $term . '%'))
            ->orderBy('UL_STOCKS_COMPNAME')
            ->limit(20)
            ->get(['UL_STOCKS_FINCODE as fincode', 'UL_STOCKS_COMPNAME as name']);

        return response()->json($stocks);
    }

    protected function findArticle(int $id, bool $trashed = false): Model
    {
        return ($trashed ? BlogPost::onlyTrashed() : BlogPost::query())->findOrFail($id);
    }

    protected function imageFolder(): string
    {
        return 'blog';
    }

    protected function publishChecks(): array
    {
        return [
            'summary'          => 'summary',
            'content'          => 'content',
            'category_id'      => 'category',
            'meta_title'       => 'meta title',
            'meta_description' => 'meta description',
        ];
    }

    private function formData(BlogPost $post): array
    {
        return [
            'post'           => $post,
            'categories'     => BlogCategory::active()->get(),
            'heroIcons'      => config('blog.hero_icons'),
            'otherPosts'     => BlogPost::query()
                ->when($post->exists, fn ($q) => $q->whereKeyNot($post->id))
                ->orderByDesc('published_at')->orderBy('title')
                ->get(['id', 'title', 'status']),
            'selectedStocks' => $post->exists
                ? $post->unlistedStocks()->get(['unlisted_stocks.UL_STOCKS_FINCODE', 'unlisted_stocks.UL_STOCKS_COMPNAME'])
                : collect(),
            'lockedBy'       => null,
        ];
    }

    /** The shared article blocks plus what only blog posts have: title, category, featured flag, stocks. */
    private function validatePost(Request $request, ?BlogPost $post = null): array
    {
        $this->normaliseBlocks($request);

        $publishing = $request->input('status') === BlogPost::STATUS_PUBLISHED;
        $rules      = $this->articleRules($publishing);
        $rules['related_post_ids.*'][] = Rule::notIn(array_filter([$post?->id]));

        $data = $request->validate($rules + [
            'title'           => 'required|string|max:255',
            'category_id'     => [$publishing ? 'required' : 'nullable', 'integer', Rule::exists('blog_categories', 'id')],
            'status'          => ['required', Rule::in(BlogPost::STATUSES)],
            'is_featured'     => 'nullable|boolean',
            'stock_tickers'   => 'nullable|array',
            'stock_tickers.*' => 'exists:unlisted_stocks,UL_STOCKS_FINCODE',
        ], $this->articleMessages());

        unset($data['stock_tickers']);
        $data = $this->cleanArticle($data);
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }

    /**
     * The slug the author typed, normalised — or null if the field was left
     * blank. Refused (not silently suffixed) when another post has it now or
     * used to have it, so the author always knows the exact URL they get.
     */
    private function requestedSlug(Request $request, ?BlogPost $post = null): ?string
    {
        $request->validate(['slug' => 'nullable|string|max:200']);

        $slug = Str::slug((string) $request->input('slug', ''));
        if ($slug === '') {
            return null;
        }

        if ($this->slugTaken($slug, $post?->id)) {
            throw ValidationException::withMessages([
                'slug' => "/blog/{$slug}/ is already used by another post (or redirects to one). Pick a different URL.",
            ]);
        }

        return $slug;
    }

    /** A slug no other post uses now or used to use (so old links keep redirecting correctly). */
    private function uniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source) ?: 'post';
        $slug = $base;
        $n    = 2;

        while ($this->slugTaken($slug, $ignoreId)) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }

    private function slugTaken(string $slug, ?int $ignoreId): bool
    {
        $usedByPost = BlogPost::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->exists();

        $usedByRedirect = BlogSlugRedirect::where('old_slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('blog_post_id', '!=', $ignoreId))
            ->exists();

        return $usedByPost || $usedByRedirect;
    }

    private function rememberOldSlug(BlogPost $post, string $newSlug): void
    {
        // Taking back one of this post's own old slugs: that redirect would now loop.
        BlogSlugRedirect::where('old_slug', $newSlug)->where('blog_post_id', $post->id)->delete();

        BlogSlugRedirect::updateOrCreate(['old_slug' => $post->slug], ['blog_post_id' => $post->id]);
    }
}
