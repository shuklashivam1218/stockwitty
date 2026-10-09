<?php

namespace App\Http\Controllers;

use App\Exceptions\ImageUploadException;
use App\Helpers\ImageUpload;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSlugRedirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Admin CRUD for blog posts, ported from unlisted-stocks' CmsArticleController.
 * Every route here sits behind privilege:author,reviewer, and both may do
 * everything (write, publish, trash) — the edit lock is what keeps two
 * people from overwriting each other.
 */
class BlogPostsController extends Controller
{
    // How long an editor's tab is considered "still open" without a heartbeat.
    // Keep in sync with HEARTBEAT_MS in admin/blog/form.blade.php.
    private const LOCK_TTL_MINUTES = 3;

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

        $data['slug']       = $this->uniqueSlug($data['title']);
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
        $uid  = session('uid');
        $lockedBy = null;

        // First person to open the post holds the lock — everyone else gets a
        // read-only form until the holder saves/leaves or their tab goes quiet.
        if ($this->isLockedByOther($post, $uid)) {
            $lockedBy = $this->lockInfo($post);
        } else {
            $this->claimLock($post, $uid);
        }

        return view('admin.blog.form', ['lockedBy' => $lockedBy] + $this->formData($post));
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

        $data = $this->validatePost($request);
        $this->applyPublishState($data, $post);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->storeFeaturedImage($request);
            ImageUpload::delete($post->featured_image);
        }

        DB::transaction(function () use ($request, $post, $data) {
            // The URL only changes through its own field — editing the title
            // never touches it, so published links and rankings stay put.
            $newSlug = Str::slug((string) $request->input('slug', ''));
            if ($newSlug !== '' && $newSlug !== $post->slug) {
                $data['slug'] = $this->uniqueSlug($newSlug, $post->id);
                $this->rememberOldSlug($post, $data['slug']);
            }

            $post->update($data);
            $post->unlistedStocks()->sync($request->input('stock_tickers', []));
        });

        return redirect()->route('admin.blog.posts.edit', $post->id)->with('success', 'Post updated.');
    }

    public function heartbeat(int $id)
    {
        $post = BlogPost::findOrFail($id);
        $uid  = session('uid');

        if ($this->isLockedByOther($post, $uid)) {
            return response()->json(['locked' => true] + $this->lockInfo($post));
        }

        $this->claimLock($post, $uid);

        return response()->json(['locked' => false]);
    }

    public function releaseLock(int $id)
    {
        // Query builder, not Eloquent, so updated_at ("Last updated") isn't bumped.
        DB::table('blog_posts')
            ->where('id', $id)
            ->where('locked_by', session('uid'))
            ->update(['locked_by' => null, 'locked_at' => null]);

        return response()->json(['success' => true]);
    }

    public function publishToggle(int $id)
    {
        $post = BlogPost::findOrFail($id);
        if ($locked = $this->lockedResponse($post)) {
            return $locked;
        }

        if ($post->isPublished()) {
            $post->status = BlogPost::STATUS_DRAFT;
        } else {
            $missing = $this->missingForPublish($post);
            if ($missing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fill these before publishing: ' . implode(', ', $missing) . '.',
                ], 422);
            }
            $post->status       = BlogPost::STATUS_PUBLISHED;
            $post->published_at ??= now();
            $post->published_by = session('uid');
        }
        $post->save();

        return response()->json(['success' => true, 'status' => $post->status]);
    }

    public function trash(int $id)
    {
        $post = BlogPost::findOrFail($id);
        if ($locked = $this->lockedResponse($post)) {
            return $locked;
        }

        $post->delete();

        return response()->json(['success' => true]);
    }

    public function restore(int $id)
    {
        BlogPost::onlyTrashed()->findOrFail($id)->restore();

        return response()->json(['success' => true]);
    }

    public function forceDelete(int $id)
    {
        $post = BlogPost::onlyTrashed()->findOrFail($id);
        ImageUpload::delete($post->featured_image);
        $post->forceDelete();

        return response()->json(['success' => true]);
    }

    public function uploadContentImage(Request $request)
    {
        $request->validate(['file' => 'required|' . ImageUpload::RULES]);

        $path = ImageUpload::store($request->file('file'), 'blog/content', 'post');

        return response()->json(['location' => ImageUpload::url($path)]);
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

    private function formData(BlogPost $post): array
    {
        return [
            'post'           => $post,
            'categories'     => BlogCategory::active()->get(),
            'selectedStocks' => $post->exists
                ? $post->unlistedStocks()->get(['unlisted_stocks.UL_STOCKS_FINCODE', 'unlisted_stocks.UL_STOCKS_COMPNAME'])
                : collect(),
            'lockedBy'       => null,
        ];
    }

    /**
     * Drafts may be half-written; what a public page needs (summary, body,
     * category, SEO) is only required once the post is being published.
     */
    private function validatePost(Request $request): array
    {
        $publishing = $request->input('status') === BlogPost::STATUS_PUBLISHED;
        $needed     = $publishing ? 'required' : 'nullable';

        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'summary'            => [$needed, 'string', 'max:1000'],
            'content'            => [$needed, 'string'],
            'category_id'        => [$needed, 'integer', Rule::exists('blog_categories', 'id')],
            'status'             => ['required', Rule::in(BlogPost::STATUSES)],
            'is_featured'        => 'nullable|boolean',
            'featured_image'     => 'nullable|' . ImageUpload::RULES,
            'featured_image_alt' => 'nullable|string|max:255',
            'meta_title'         => [$needed, 'string', 'max:255'],
            'meta_description'   => [$needed, 'string', 'max:500'],
            'meta_keywords'      => 'nullable|string|max:500',
            'stock_tickers'      => 'nullable|array',
            'stock_tickers.*'    => 'exists:unlisted_stocks,UL_STOCKS_FINCODE',
        ]);

        unset($data['stock_tickers'], $data['featured_image']);
        $data['is_featured'] = $request->boolean('is_featured');

        // The body is rendered raw on the public page, so it is always stored
        // purified; the plain-text fields end up in <title>/<meta>/cards.
        $data['content'] = isset($data['content']) ? clean($data['content']) : null;
        foreach (['title', 'summary', 'featured_image_alt', 'meta_title', 'meta_description', 'meta_keywords'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = trim(strip_tags($data[$field]));
            }
        }

        return $data;
    }

    private function applyPublishState(array &$data, ?BlogPost $post = null): void
    {
        if ($data['status'] === BlogPost::STATUS_PUBLISHED && !$post?->isPublished()) {
            $data['published_at'] = $post?->published_at ?? now();
            $data['published_by'] = session('uid');
        }
    }

    /** Labels of fields a post still needs before it can go live (for the list's Publish button). */
    private function missingForPublish(BlogPost $post): array
    {
        $checks = [
            'summary'          => 'summary',
            'content'          => 'content',
            'category_id'      => 'category',
            'meta_title'       => 'meta title',
            'meta_description' => 'meta description',
        ];

        return array_values(array_filter($checks, fn ($label, $field) => blank($post->$field), ARRAY_FILTER_USE_BOTH));
    }

    private function storeFeaturedImage(Request $request): string
    {
        try {
            return ImageUpload::store($request->file('featured_image'), 'blog/featured', 'post');
        } catch (ImageUploadException $e) {
            throw $e->forField('featured_image');
        }
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

    private function isLockedByOther(BlogPost $post, $uid): bool
    {
        $isLive = $post->locked_by
            && $post->locked_at
            && $post->locked_at->gt(now()->subMinutes(self::LOCK_TTL_MINUTES));

        return $isLive && (int) $post->locked_by !== (int) $uid;
    }

    /** List-page actions must not pull a post out from under someone who has it open. */
    private function lockedResponse(BlogPost $post)
    {
        if (!$this->isLockedByOther($post, session('uid'))) {
            return null;
        }

        $lock = $this->lockInfo($post);

        return response()->json([
            'success' => false,
            'message' => "{$lock['name']} is editing this post right now (started {$lock['since']}). Try again once they're done.",
        ], 423);
    }

    private function lockInfo(BlogPost $post): array
    {
        return [
            'name'  => $post->lockedByUser->name ?? 'Another user',
            'since' => $post->locked_at->diffForHumans(),
        ];
    }

    // Claiming the lock is metadata, not an edit — a query-builder update keeps
    // updated_at ("Last updated") untouched.
    private function claimLock(BlogPost $post, $uid): void
    {
        $now = now();
        DB::table('blog_posts')->where('id', $post->id)->update(['locked_by' => $uid, 'locked_at' => $now]);
        $post->locked_by = $uid;
        $post->locked_at = $now;
    }
}
