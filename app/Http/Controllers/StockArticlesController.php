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

    public function index(Request $request, string $type)
    {
        $status = $request->query('status', '');
        $search = trim((string) $request->query('search', ''));

        $query = StockArticle::ofType($type)->with('stock', 'author')->orderByDesc('updated_at');

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
            'label'    => StockArticle::typeLabel($type),
            'articles' => $query->paginate(20)->withQueryString(),
            'status'   => $status,
            'search'   => $search,
            'coverage' => [
                'published' => StockArticle::ofType($type)->published()->count(),
                'stocks'    => UnlistedStock::where('UL_STOCKS_STATUS', '1')->count(),
            ],
        ]);
    }

    public function create(Request $request, string $type)
    {
        $article = new StockArticle([
            'type'              => $type,
            'hero_icon'         => StockArticle::TYPES[$type]['icon'],
            'ul_stocks_fincode' => $request->integer('stock') ?: null,
        ]);

        return view('admin.stock-articles.form', $this->formData($article) + [
            // Stocks that don't have this page yet; trashed ones still count — restore those instead.
            'availableStocks' => UnlistedStock::query()
                ->whereNotIn('UL_STOCKS_FINCODE', StockArticle::withTrashed()->ofType($type)->select('ul_stocks_fincode'))
                ->orderBy('UL_STOCKS_COMPNAME')
                ->get(['UL_STOCKS_FINCODE', 'UL_STOCKS_COMPNAME', 'UL_STOCKS_STATUS']),
        ]);
    }

    public function store(Request $request, string $type)
    {
        $request->validate([
            'ul_stocks_fincode' => ['required', 'integer', Rule::exists('unlisted_stocks', 'UL_STOCKS_FINCODE')],
        ], ['ul_stocks_fincode.required' => 'Pick the stock this page is for.']);

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
