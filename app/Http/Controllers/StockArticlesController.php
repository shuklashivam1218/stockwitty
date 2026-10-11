<?php

namespace App\Http\Controllers;

use App\Helpers\ImageUpload;
use App\Http\Controllers\Concerns\EditsArticles;
use App\Models\BlogPost;
use App\Models\StockArticle;
use App\Models\UnlistedStock;
use App\Models\UnlistedWittyScore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * CMS for each stock's About, Thesis and WittyScore pages — written like
 * blog posts, one per stock per type. Same privilege as the blog
 * (author/reviewer); publishing, trash, images and the edit lock come from
 * EditsArticles. The WittyScore editor also holds the stock's five pillar
 * scores, which drive the WittyScore widget on the company page.
 */
class StockArticlesController extends Controller
{
    use EditsArticles;

    public const PILLARS = [
        'UL_WS_FINANCIAL_HEALTH' => 'Financial Health',
        'UL_WS_VALUATION'        => 'Valuation',
        'UL_WS_GROWTH_POTENTIAL' => 'Growth Potential',
        'UL_WS_IPO_PROBABILITY'  => 'IPO Probability',
        'UL_WS_LIQUIDITY_SAFETY' => 'Liquidity & Safety',
    ];

    /** One list for all three page types; ?type= narrows it to one. */
    public function index(Request $request)
    {
        $type   = $this->typeFrom($request);
        $status = $request->query('status', '');
        $search = trim((string) $request->query('search', ''));

        $query = StockArticle::query()
            ->when($type, fn ($q) => $q->ofType($type))
            ->with('stock', 'author')
            ->orderByDesc('updated_at');

        if ($status === 'trash') {
            $query->onlyTrashed();
        } elseif (in_array($status, StockArticle::STATUSES, true)) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(fn ($q) => $q
                ->where('title', 'like', '%' . $search . '%')
                ->orWhereHas('stock', fn ($s) => $s->where('UL_STOCKS_COMPNAME', 'like', '%' . $search . '%')));
        }

        return view('admin.stock-articles.index', [
            'type'     => $type,
            'articles' => $query->paginate(20)->withQueryString(),
            'status'   => $status,
            'search'   => $search,
            // How many active stocks have each page live.
            'live'     => StockArticle::published()->selectRaw('type, COUNT(*) as n')->groupBy('type')->pluck('n', 'type'),
            'stocks'   => UnlistedStock::where('UL_STOCKS_STATUS', '1')->count(),
        ]);
    }

    public function create(Request $request)
    {
        $type    = $this->typeFrom($request) ?? StockArticle::TYPE_ABOUT;
        $fincode = (int) old('ul_stocks_fincode', $request->integer('stock')) ?: null;
        $article = new StockArticle(['type' => $type, 'hero_icon' => StockArticle::TYPES[$type]['icon']]);

        // A stock handed in (?stock= or a bounced submit) starts selected, if it can still get this page.
        $selected = $fincode
            ? $this->stocksWithoutPage($type)->where('UL_STOCKS_FINCODE', $fincode)->first(['UL_STOCKS_FINCODE', 'UL_STOCKS_COMPNAME'])
            : null;

        return view('admin.stock-articles.form', $this->formData($article) + ['selectedStock' => $selected]);
    }

    /**
     * The stock picker's search: stocks that can still get a page of this
     * type, matching the typed name, 10 at a time.
     */
    public function stockOptions(Request $request)
    {
        $type = $this->typeFrom($request) ?? StockArticle::TYPE_ABOUT;
        $term = trim((string) $request->query('term', ''));

        $page = $this->stocksWithoutPage($type)
            ->when($term !== '', fn ($q) => $q->where('UL_STOCKS_COMPNAME', 'like', '%' . $term . '%'))
            ->orderBy('UL_STOCKS_COMPNAME')
            ->simplePaginate(10, ['UL_STOCKS_FINCODE', 'UL_STOCKS_COMPNAME', 'UL_STOCKS_STATUS']);

        return response()->json([
            'items' => collect($page->items())->map(fn ($s) => [
                'fincode' => $s->UL_STOCKS_FINCODE,
                'name'    => $s->UL_STOCKS_COMPNAME,
                'active'  => $s->UL_STOCKS_STATUS === '1',
            ]),
            'more'  => $page->hasMorePages(),
        ]);
    }

    /** Stocks without a page of this type; a trashed page still counts — restore it instead. */
    private function stocksWithoutPage(string $type)
    {
        return UnlistedStock::query()
            ->whereNotIn('UL_STOCKS_FINCODE', StockArticle::withTrashed()->ofType($type)->select('ul_stocks_fincode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'              => ['required', Rule::in(array_keys(StockArticle::TYPES))],
            'ul_stocks_fincode' => ['required', 'integer', Rule::exists('unlisted_stocks', 'UL_STOCKS_FINCODE')],
        ], [
            'type.required'              => 'Pick the page type.',
            'ul_stocks_fincode.required' => 'Pick the stock this page is for.',
        ]);

        $type     = $request->input('type');
        $fincode  = $request->integer('ul_stocks_fincode');
        $existing = StockArticle::withTrashed()->ofType($type)->where('ul_stocks_fincode', $fincode)->first();
        if ($existing) {
            throw ValidationException::withMessages([
                'ul_stocks_fincode' => $existing->trashed()
                    ? 'This stock already has a ' . StockArticle::typeLabel($type) . ' page in the trash — restore it instead.'
                    : 'This stock already has a ' . StockArticle::typeLabel($type) . ' page — edit that one.',
            ]);
        }

        $data = $this->validateArticle($request, $type);
        $data += ['ul_stocks_fincode' => $fincode, 'type' => $type, 'created_by' => session('uid')];
        $this->applyPublishState($data);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->storeFeaturedImage($request);
        }

        $article = DB::transaction(function () use ($request, $data, $fincode) {
            $article = StockArticle::create($data);
            $this->savePillars($request, $article->type, $fincode);

            return $article;
        });

        return redirect()->route('admin.stock-articles.edit', $article->id)
            ->with('success', StockArticle::typeLabel($type) . ' page created.');
    }

    public function edit(int $id)
    {
        $article = StockArticle::with('stock')->findOrFail($id);

        return view('admin.stock-articles.form', ['lockedBy' => $this->openForEditing($article)] + $this->formData($article));
    }

    public function update(Request $request, int $id)
    {
        $article = StockArticle::with('stock')->findOrFail($id);

        // Enforced here too, not just via the disabled form — a client can replay the PUT.
        if ($this->isLockedByOther($article, session('uid'))) {
            $lock = $this->lockInfo($article);
            return redirect()->route('admin.stock-articles.edit', $article->id)
                ->with('lock_error', "{$lock['name']} is currently editing this page (started {$lock['since']}). Your changes were not saved.");
        }

        $data = $this->validateArticle($request, $article->type);
        $this->applyPublishState($data, $article);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->storeFeaturedImage($request);
            ImageUpload::delete($article->featured_image);
        }

        DB::transaction(function () use ($request, $article, $data) {
            $article->update($data);
            $this->savePillars($request, $article->type, $article->ul_stocks_fincode);
        });

        return redirect()->route('admin.stock-articles.edit', $article->id)->with('success', 'Page updated.');
    }

    /** A valid ?type= / type input, or null. */
    private function typeFrom(Request $request): ?string
    {
        $type = (string) $request->input('type', '');

        return array_key_exists($type, StockArticle::TYPES) ? $type : null;
    }

    protected function findArticle(int $id, bool $trashed = false): Model
    {
        return ($trashed ? StockArticle::onlyTrashed() : StockArticle::query())->findOrFail($id);
    }

    protected function imageFolder(): string
    {
        return 'stock-articles';
    }

    protected function publishChecks(): array
    {
        return [
            'summary'          => 'summary',
            'content'          => 'content',
            'meta_title'       => 'meta title',
            'meta_description' => 'meta description',
        ];
    }

    private function formData(StockArticle $article): array
    {
        $fincode = $article->ul_stocks_fincode;

        return [
            'article'    => $article,
            'label'      => StockArticle::typeLabel($article->type),
            'otherPosts' => BlogPost::query()->orderByDesc('published_at')->orderBy('title')->get(['id', 'title', 'status']),
            'pillars'    => $article->type === StockArticle::TYPE_WITTYSCORE && $fincode
                ? UnlistedWittyScore::where('UL_WS_FINCODE', $fincode)->orderByDesc('UL_WS_ID')->first()
                : null,
            'lockedBy'   => null,
        ];
    }

    private function validateArticle(Request $request, string $type): array
    {
        $this->normaliseBlocks($request);

        $publishing = $request->input('status') === StockArticle::STATUS_PUBLISHED;

        $rules = $this->articleRules($publishing) + [
            'title'  => 'required|string|max:255',
            'status' => ['required', Rule::in(StockArticle::STATUSES)],
        ];
        if ($type === StockArticle::TYPE_WITTYSCORE) {
            $rules += array_fill_keys(array_keys(self::PILLARS), 'nullable|numeric|min:0|max:10');
        }

        $data = $request->validate($rules, $this->articleMessages() + [
            'UL_WS_*.max' => 'Each pillar is scored out of 10.',
        ]);

        return $this->cleanArticle(array_diff_key($data, self::PILLARS));
    }

    /**
     * WittyScore pages also carry the stock's pillar scores: update its live
     * row, or start one once any pillar is filled in.
     */
    private function savePillars(Request $request, string $type, $fincode): void
    {
        if ($type !== StockArticle::TYPE_WITTYSCORE) {
            return;
        }

        $values = [];
        foreach (array_keys(self::PILLARS) as $field) {
            $values[$field] = $request->filled($field) ? $request->input($field) : null;
        }

        $row = UnlistedWittyScore::where('UL_WS_FINCODE', $fincode)->orderByDesc('UL_WS_ID')->first();

        if ($row) {
            UnlistedWittyScore::where('UL_WS_ID', $row->UL_WS_ID)->update($values + ['UL_WS_UPDATE_TIME' => now()]);
        } elseif (array_filter($values, fn ($v) => $v !== null)) {
            UnlistedWittyScore::insert($values + [
                'UL_WS_FINCODE'     => $fincode,
                'UL_WS_ACTIVE'      => '1',
                'UL_WS_INSERT_TIME' => now(),
                'UL_WS_UPDATE_TIME' => now(),
            ]);
        }
    }
}
