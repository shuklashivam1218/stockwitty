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

        $data = $this->validatePost($request, $post);
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

    /**
     * Drafts may be half-written; what a public page needs (summary, body,
     * category, SEO) is only required once the post is being published.
     *
     * Everything except `content` is plain text: it is strip_tags'd here and
     * escaped again when rendered. `content` is the one HTML field and is
     * stored only after the `blog` purifier profile has cleaned it.
     */
    private function validatePost(Request $request, ?BlogPost $post = null): array
    {
        $this->normaliseBlocks($request);

        $publishing = $request->input('status') === BlogPost::STATUS_PUBLISHED;
        $needed     = $publishing ? 'required' : 'nullable';

        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'summary'            => [$needed, 'string', 'max:1000'],
            'intro'              => 'nullable|string|max:3000',
            'content'            => [$needed, 'string'],
            'category_id'        => [$needed, 'integer', Rule::exists('blog_categories', 'id')],
            'status'             => ['required', Rule::in(BlogPost::STATUSES)],
            'is_featured'        => 'nullable|boolean',
            'featured_image'     => 'nullable|' . ImageUpload::RULES,
            'featured_image_alt' => 'nullable|string|max:255',
            'hero_icon'          => ['nullable', Rule::in(config('blog.hero_icons'))],

            'chips'              => 'nullable|array|max:' . config('blog.max_chips'),
            'chips.*'            => 'string|max:40',
            'takeaways'          => 'nullable|array|max:' . config('blog.max_takeaways'),
            'takeaways.*'        => 'string|max:300',
            'faqs'               => 'nullable|array|max:' . config('blog.max_faqs'),
            // Tab names end up inside Alpine expressions on the public page,
            // so they are restricted to characters that can't break out of one.
            'faqs.*.tab'         => ['nullable', 'string', 'max:40', 'regex:/^[\pL\pN &\/-]+$/u'],
            'faqs.*.q'           => 'required|string|max:300',
            'faqs.*.a'           => 'required|string|max:2000',
            'sources'            => 'nullable|array|max:' . config('blog.max_sources'),
            'sources.*.label'    => 'required|string|max:150',
            'sources.*.href'     => 'required|url:http,https|max:500',
            'video_url'          => ['nullable', 'string', 'max:255', function ($attr, $value, $fail) {
                if (static::youtubeId($value) === null) {
                    $fail('Enter a YouTube link (youtube.com/watch?v=…, youtu.be/… or youtube.com/shorts/…).');
                }
            }],
            'video_caption'      => 'nullable|string|max:200',
            'related_post_ids'   => 'nullable|array|max:' . config('blog.max_related'),
            'related_post_ids.*' => ['integer', Rule::exists('blog_posts', 'id')->whereNull('deleted_at'), Rule::notIn(array_filter([$post?->id]))],
            'lead_heading'       => 'nullable|string|max:255',
            'lead_subtext'       => 'nullable|string|max:1000',

            'meta_title'         => [$needed, 'string', 'max:255'],
            'meta_description'   => [$needed, 'string', 'max:500'],
            'meta_keywords'      => 'nullable|string|max:500',
            'stock_tickers'      => 'nullable|array',
            'stock_tickers.*'    => 'exists:unlisted_stocks,UL_STOCKS_FINCODE',
        ], [
            'faqs.*.tab.regex' => 'FAQ tab names can only use letters, numbers, spaces, &, / and -.',
        ]);

        $videoId = static::youtubeId($data['video_url'] ?? null);
        $data['video'] = $videoId ? ['youtube_id' => $videoId, 'caption' => $this->plain($data['video_caption'] ?? null)] : null;
        unset($data['stock_tickers'], $data['featured_image'], $data['video_url'], $data['video_caption']);

        $data['is_featured']      = $request->boolean('is_featured');
        $data['content']          = isset($data['content']) ? clean($data['content'], 'blog') : null;
        $data['chips']            = $this->plainList($data['chips'] ?? []);
        $data['takeaways']        = $this->plainList($data['takeaways'] ?? []);
        $data['related_post_ids'] = array_values(array_unique(array_map('intval', $data['related_post_ids'] ?? []))) ?: null;
        $data['faqs'] = array_map(fn ($f) => [
            'tab' => $this->plain($f['tab'] ?? null) ?: 'General',
            'q'   => $this->plain($f['q']),
            'a'   => $this->plain($f['a']),
        ], $data['faqs'] ?? []) ?: null;
        $data['sources'] = array_map(fn ($s) => [
            'label' => $this->plain($s['label']),
            'href'  => trim($s['href']),
        ], $data['sources'] ?? []) ?: null;

        foreach (['title', 'summary', 'intro', 'featured_image_alt', 'lead_heading', 'lead_subtext', 'meta_title', 'meta_description', 'meta_keywords'] as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = $this->plain($data[$field]);
            }
        }

        return $data;
    }

    /**
     * The form sends chips as one comma-separated field, takeaways one per
     * line, and FAQ/source repeater rows that may be left blank. Turn those
     * into clean arrays before validation so errors point at real entries.
     */
    private function normaliseBlocks(Request $request): void
    {
        $split = fn (?string $text, string $pattern) => array_values(array_filter(
            array_map('trim', preg_split($pattern, (string) $text)),
            fn ($v) => $v !== ''
        ));

        $rows = fn (string $key, array $fields) => array_values(array_filter(
            (array) $request->input($key, []),
            fn ($row) => is_array($row) && collect($fields)->contains(fn ($f) => trim((string) ($row[$f] ?? '')) !== '')
        ));

        $request->merge([
            'chips'            => $split($request->input('chips_text'), '/,/'),
            'takeaways'        => $split($request->input('takeaways_text'), '/\R/'),
            'faqs'             => $rows('faqs', ['q', 'a']),
            'sources'          => $rows('sources', ['label', 'href']),
            'related_post_ids' => array_values(array_filter((array) $request->input('related_post_ids', []))),
        ]);
    }

    /** 11-character video id from any common YouTube URL shape, or null. */
    public static function youtubeId(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $pattern = '~^(?:https?://)?(?:www\.|m\.)?(?:youtube\.com/(?:watch\?(?:.*&)?v=|shorts/|embed/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})(?:[?&#/].*)?$~';

        return preg_match($pattern, trim($url), $m) ? $m[1] : null;
    }

    private function plain(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim(strip_tags($value));

        return $value === '' ? null : $value;
    }

    private function plainList(array $items): ?array
    {
        $items = array_values(array_filter(array_map(fn ($v) => $this->plain($v), $items)));

        return $items ?: null;
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
