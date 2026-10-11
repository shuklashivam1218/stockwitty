<?php

namespace App\Http\Controllers\Concerns;

use App\Exceptions\ImageUploadException;
use App\Helpers\ImageUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * CMS editing shared by blog posts and stock articles: the structured blocks
 * around the body (validated and cleaned the same way), publishing, trash,
 * images, and the edit lock that keeps two editors from overwriting each
 * other. A using controller says how to find its articles, where its images
 * go and what must be filled in before publishing.
 *
 * Everything except `content` is plain text: it is strip_tags'd here and
 * escaped again when rendered. `content` is the one HTML field and is stored
 * only after the `blog` purifier profile has cleaned it.
 */
trait EditsArticles
{
    // How long an editor's tab is considered "still open" without a heartbeat.
    // Keep in sync with HEARTBEAT_MS in the article form's script.
    private const LOCK_TTL_MINUTES = 3;

    /** One article by id; with $trashed, only among trashed ones. */
    abstract protected function findArticle(int $id, bool $trashed = false): Model;

    /** Folder under images/ for this CMS's uploads, e.g. "blog". */
    abstract protected function imageFolder(): string;

    /** field => label of what must be filled before the list's Publish button works. */
    abstract protected function publishChecks(): array;

    public function heartbeat(int $id)
    {
        $article = $this->findArticle($id);
        $uid     = session('uid');

        if ($this->isLockedByOther($article, $uid)) {
            return response()->json(['locked' => true] + $this->lockInfo($article));
        }

        $this->claimLock($article, $uid);

        return response()->json(['locked' => false]);
    }

    public function releaseLock(int $id)
    {
        // Query builder, not Eloquent, so updated_at ("Last updated") isn't bumped.
        DB::table($this->findArticle($id)->getTable())
            ->where('id', $id)
            ->where('locked_by', session('uid'))
            ->update(['locked_by' => null, 'locked_at' => null]);

        return response()->json(['success' => true]);
    }

    public function publishToggle(int $id)
    {
        $article = $this->findArticle($id);
        if ($locked = $this->lockedResponse($article)) {
            return $locked;
        }

        if ($article->isPublished()) {
            $article->status = $article::STATUS_DRAFT;
        } else {
            $missing = array_values(array_filter(
                $this->publishChecks(),
                fn ($label, $field) => blank($article->$field),
                ARRAY_FILTER_USE_BOTH
            ));
            if ($missing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fill these before publishing: ' . implode(', ', $missing) . '.',
                ], 422);
            }
            $article->status       = $article::STATUS_PUBLISHED;
            $article->published_at ??= now();
            $article->published_by = session('uid');
        }
        $article->save();

        return response()->json(['success' => true, 'status' => $article->status]);
    }

    public function trash(int $id)
    {
        $article = $this->findArticle($id);
        if ($locked = $this->lockedResponse($article)) {
            return $locked;
        }

        $article->delete();

        return response()->json(['success' => true]);
    }

    public function restore(int $id)
    {
        $this->findArticle($id, trashed: true)->restore();

        return response()->json(['success' => true]);
    }

    public function forceDelete(int $id)
    {
        $article = $this->findArticle($id, trashed: true);
        ImageUpload::delete($article->featured_image);
        $article->forceDelete();

        return response()->json(['success' => true]);
    }

    public function uploadContentImage(Request $request)
    {
        $request->validate(['file' => 'required|' . ImageUpload::RULES]);

        $path = ImageUpload::store($request->file('file'), $this->imageFolder() . '/content', 'post');

        return response()->json(['location' => ImageUpload::url($path)]);
    }

    /**
     * Rules for the blocks every article has. Drafts may be half-written;
     * what a public page needs is only required once it's being published.
     */
    protected function articleRules(bool $publishing): array
    {
        $needed = $publishing ? 'required' : 'nullable';

        return [
            'summary'            => [$needed, 'string', 'max:1000'],
            'intro'              => 'nullable|string|max:3000',
            'content'            => [$needed, 'string'],
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
            'related_post_ids.*' => ['integer', Rule::exists('blog_posts', 'id')->whereNull('deleted_at')],
            'lead_heading'       => 'nullable|string|max:255',
            'lead_subtext'       => 'nullable|string|max:1000',

            'meta_title'         => [$needed, 'string', 'max:255'],
            'meta_description'   => [$needed, 'string', 'max:500'],
            'meta_keywords'      => 'nullable|string|max:500',
        ];
    }

    protected function articleMessages(): array
    {
        return [
            'faqs.*.tab.regex' => 'FAQ tab names can only use letters, numbers, spaces, &, / and -.',
        ];
    }

    /**
     * The form sends chips as one comma-separated field, takeaways one per
     * line, and FAQ/source repeater rows that may be left blank. Turn those
     * into clean arrays before validation so errors point at real entries.
     */
    protected function normaliseBlocks(Request $request): void
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

    /** Validated block data → what gets stored: plain text, purified body, tidy arrays. */
    protected function cleanArticle(array $data): array
    {
        $videoId = static::youtubeId($data['video_url'] ?? null);
        $data['video'] = $videoId ? ['youtube_id' => $videoId, 'caption' => $this->plain($data['video_caption'] ?? null)] : null;
        unset($data['featured_image'], $data['video_url'], $data['video_caption']);

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

    /** 11-character video id from any common YouTube URL shape, or null. */
    public static function youtubeId(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $pattern = '~^(?:https?://)?(?:www\.|m\.)?(?:youtube\.com/(?:watch\?(?:.*&)?v=|shorts/|embed/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})(?:[?&#/].*)?$~';

        return preg_match($pattern, trim($url), $m) ? $m[1] : null;
    }

    protected function plain(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim(strip_tags($value));

        return $value === '' ? null : $value;
    }

    protected function plainList(array $items): ?array
    {
        $items = array_values(array_filter(array_map(fn ($v) => $this->plain($v), $items)));

        return $items ?: null;
    }

    protected function applyPublishState(array &$data, ?Model $article = null): void
    {
        if ($data['status'] === 'published' && !$article?->isPublished()) {
            $data['published_at'] = $article?->published_at ?? now();
            $data['published_by'] = session('uid');
        }
    }

    protected function storeFeaturedImage(Request $request): string
    {
        try {
            return ImageUpload::store($request->file('featured_image'), $this->imageFolder() . '/featured', 'post');
        } catch (ImageUploadException $e) {
            throw $e->forField('featured_image');
        }
    }

    /**
     * First person to open an article holds the lock; returns who has it when
     * that's someone else, so the form can show read-only.
     */
    protected function openForEditing(Model $article): ?array
    {
        $uid = session('uid');

        if ($this->isLockedByOther($article, $uid)) {
            return $this->lockInfo($article);
        }

        $this->claimLock($article, $uid);

        return null;
    }

    protected function isLockedByOther(Model $article, $uid): bool
    {
        $isLive = $article->locked_by
            && $article->locked_at
            && $article->locked_at->gt(now()->subMinutes(self::LOCK_TTL_MINUTES));

        return $isLive && (int) $article->locked_by !== (int) $uid;
    }

    /** List-page actions must not pull an article out from under someone who has it open. */
    protected function lockedResponse(Model $article)
    {
        if (!$this->isLockedByOther($article, session('uid'))) {
            return null;
        }

        $lock = $this->lockInfo($article);

        return response()->json([
            'success' => false,
            'message' => "{$lock['name']} is editing this right now (started {$lock['since']}). Try again once they're done.",
        ], 423);
    }

    protected function lockInfo(Model $article): array
    {
        return [
            'name'  => $article->lockedByUser->name ?? 'Another user',
            'since' => $article->locked_at->diffForHumans(),
        ];
    }

    // Claiming the lock is metadata, not an edit — a query-builder update keeps
    // updated_at ("Last updated") untouched.
    protected function claimLock(Model $article, $uid): void
    {
        $now = now();
        DB::table($article->getTable())->where('id', $article->id)->update(['locked_by' => $uid, 'locked_at' => $now]);
        $article->locked_by = $uid;
        $article->locked_at = $now;
    }
}
