<?php

namespace App\Http\Controllers;

use App\Exceptions\ImageUploadException;
use App\Helpers\ImageUpload;
use App\Helpers\Privilege;
use App\Models\UnlistedAbout;
use App\Models\UnlistedAboutExtra;
use App\Models\UnlistedCompanyInsight;
use App\Models\UnlistedFaq;
use App\Models\UnlistedFinancials;
use App\Models\UnlistedPriceData;
use App\Models\UnlistedSeoMeta;
use App\Models\UnlistedStock;
use App\Models\UnlistedThesis;
use App\Models\UnlistedWittyScore;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * One page that edits everything about an unlisted stock — basics, profile,
 * WittyScore, insights, thesis, about, FAQs and SEO — saved in one go.
 * Each content section still lives in its own table with its own active flag,
 * exactly as the public company pages read them.
 */
class UnlistedStockEditorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [fn ($request, $next) => Privilege::canManageStocks() ? $next($request) : abort(403)];
    }

    public const CATEGORIES = ['startup_funding' => 'Startup Funding', 'pre_ipo' => 'Pre IPO', 'delisted' => 'Delisted'];
    public const TAGS       = ['Pre-IPO', 'Trending', 'Unicorn', 'Hot', 'New'];
    public const DEMAT      = ['NSDL' => 'NSDL', 'CDSL' => 'CDSL', 'Both' => 'Both (NSDL/CDSL)'];
    public const MONTHS     = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    public const FAQ_PAGES  = ['overview' => 'Company page', 'about' => 'About page', 'thesis' => 'Thesis page'];
    public const UNITS      = [
        1        => '1 (One)',
        100      => '100 (Hundred)',
        1000     => '1000 (Thousand)',
        10000    => '10000 (10 Thousand)',
        100000   => '100000 (1 Lakh)',
        1000000  => '1000000 (10 Lakh)',
        10000000 => '10000000 (1 Crore)',
    ];

    /**
     * Single-row content tables: key => [model, column prefix, editable fields].
     * Every table follows the same shape: {PREFIX}_ID, _FINCODE, _ACTIVE,
     * _INSERT_TIME, _UPDATE_TIME, and the latest row per fincode is the live one.
     */
    private const SECTIONS = [
        'witty_score' => [UnlistedWittyScore::class, 'UL_WS', [
            'UL_WS_FINANCIAL_HEALTH', 'UL_WS_VALUATION', 'UL_WS_GROWTH_POTENTIAL',
            'UL_WS_IPO_PROBABILITY', 'UL_WS_LIQUIDITY_SAFETY',
        ]],
        'insights' => [UnlistedCompanyInsight::class, 'UL_CI', [
            'UL_CI_AI_SUMMARY', 'UL_CI_TLDR', 'UL_CI_FOUNDERS_INTRO', 'UL_CI_FOUNDERS_QUOTE',
            'UL_CI_FOUNDERS_VERDICT', 'UL_CI_IPO_TIMELINE', 'UL_CI_IPO_FACTS',
            'UL_CI_BULL_CASE', 'UL_CI_BEAR_CASE', 'UL_CI_SUITS_IF', 'UL_CI_NOT_SUITS_IF',
            'UL_CI_RISKS', 'UL_CI_VERDICT_LONG',
        ]],
        'thesis' => [UnlistedThesis::class, 'UL_THESIS', ['UL_THESIS_CONTENT']],
        'about_extra' => [UnlistedAboutExtra::class, 'UL_ABX', [
            'UL_ABX_OVERVIEW', 'UL_ABX_OPERATIONS', 'UL_ABX_GEOGRAPHY', 'UL_ABX_INDUSTRY_POSITION',
            'UL_ABX_SHAREHOLDING', 'UL_ABX_INVESTOR_INTEREST', 'UL_ABX_MARKET_LANDSCAPE',
            'UL_ABX_COMPETITIVE_STRENGTH', 'UL_ABX_VERTICALS', 'UL_ABX_REVENUE_SEGMENTS',
            'UL_ABX_HISTORY', 'UL_ABX_PRODUCTS_SERVICES', 'UL_ABX_SOURCES',
            'UL_ABX_SWOT_STRENGTHS', 'UL_ABX_SWOT_WEAKNESSES', 'UL_ABX_SWOT_OPPORTUNITIES', 'UL_ABX_SWOT_THREATS',
        ]],
        'about' => [UnlistedAbout::class, 'UL_ABOUT', ['UL_ABOUT_CONTENT']],
        'seo' => [UnlistedSeoMeta::class, 'UL_SEO', [
            'UL_SEO_COMPANY_TITLE', 'UL_SEO_COMPANY_DESCRIPTION', 'UL_SEO_COMPANY_KEYWORDS',
            'UL_SEO_ABOUT_TITLE', 'UL_SEO_ABOUT_DESCRIPTION', 'UL_SEO_ABOUT_KEYWORDS',
            'UL_SEO_THESIS_TITLE', 'UL_SEO_THESIS_DESCRIPTION', 'UL_SEO_THESIS_KEYWORDS',
        ]],
    ];

    public function edit(string $fincode)
    {
        $stock = UnlistedStock::where('UL_STOCKS_FINCODE', $fincode)->firstOrFail();

        // $filled drives the green dots in the jump nav: which sections have content.
        $sections = [];
        $filled   = [
            'profile'    => filled($stock->UL_STOCKS_ABOUT),
            'prices'     => UnlistedPriceData::where('UL_PD_FINCODE', $fincode)->exists(),
            'financials' => UnlistedFinancials::where('UL_FIN_FINCODE', $fincode)->exists(),
        ];
        foreach (self::SECTIONS as $key => [$model, $prefix, $fields]) {
            $row            = $this->latestRow($model, $prefix, $fincode);
            $sections[$key] = $row;
            $filled[$key]   = $row && collect($fields)->contains(fn ($f) => filled($row->$f));
        }

        $faqs = UnlistedFaq::where('UL_FAQ_FINCODE', $fincode)
            ->orderBy('UL_FAQ_TARGET')
            ->orderBy('UL_FAQ_SORT_ORDER')
            ->get()
            ->map(fn ($f) => [
                'id'     => $f->UL_FAQ_ID,
                'target' => $f->UL_FAQ_TARGET,
                'tab'    => $f->UL_FAQ_TAB,
                'q'      => $f->UL_FAQ_QUESTION,
                'a'      => $f->UL_FAQ_ANSWER,
                'active' => $f->UL_FAQ_ACTIVE === '1',
            ])
            ->all();
        $filled['faqs'] = $faqs !== [];

        return view('admin.unlisted.editor', [
            'stock'      => $stock,
            'sections'   => $sections,
            'filled'     => $filled,
            'faqs'       => $faqs,
            'industries' => $this->industries(),
        ]);
    }

    /**
     * The same page, empty. New stocks start inactive so a half-filled company
     * page never goes live by accident; prices and financials unlock once the
     * stock exists.
     */
    public function create()
    {
        return view('admin.unlisted.editor', [
            'stock'      => new UnlistedStock(['UL_STOCKS_STATUS' => '0']),
            'sections'   => array_fill_keys(array_keys(self::SECTIONS), null),
            'filled'     => array_fill_keys(['profile', 'prices', 'financials', 'faqs', ...array_keys(self::SECTIONS)], false),
            'faqs'       => [],
            'industries' => $this->industries(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateStock($request);
        $slug = $this->requestedSlug($request);
        $this->attachLogo($request, $data, $slug);

        $stock = DB::transaction(function () use ($request, $data, $slug) {
            $stock = UnlistedStock::create($data['stock'] + [
                'UL_STOCKS_SLUG'          => $slug,
                'UL_STOCKS_COMPNAME_TYPE' => 'unlisted',
                'UL_STOCKS_INSERT_BY'     => session('uid'),
            ]);
            $this->saveSections($request, $data, $stock->UL_STOCKS_FINCODE);
            $this->syncFaqs($data['faqs'], $stock->UL_STOCKS_FINCODE);

            return $stock;
        });

        return redirect()->route('admin.unlisted.stocks.edit', $stock->UL_STOCKS_FINCODE)
            ->with('success', 'Stock created. You can now add its prices and financials below.');
    }

    public function update(Request $request, string $fincode)
    {
        $stock = UnlistedStock::where('UL_STOCKS_FINCODE', $fincode)->firstOrFail();
        $data  = $this->validateStock($request);
        $slug  = $this->requestedSlug($request, $stock);
        $this->attachLogo($request, $data, $slug);

        DB::transaction(function () use ($request, $stock, $data, $slug) {
            $stock->update($data['stock'] + ['UL_STOCKS_SLUG' => $slug]);
            $this->saveSections($request, $data, $stock->UL_STOCKS_FINCODE);
            $this->syncFaqs($data['faqs'], $stock->UL_STOCKS_FINCODE);
        });

        return redirect()->route('admin.unlisted.stocks.edit', $stock->UL_STOCKS_FINCODE)
            ->with('success', 'All changes saved.');
    }

    /**
     * Price history for the editor's Price section, newest first. `key` is the
     * raw stored date — the existing edit/validity endpoints address rows by it.
     */
    public function prices(string $fincode)
    {
        UnlistedStock::where('UL_STOCKS_FINCODE', $fincode)->firstOrFail();

        $page = UnlistedPriceData::where('UL_PD_FINCODE', $fincode)
            ->orderByDesc('UL_PD_DATE')
            ->paginate(10, ['UL_PD_DATE', 'UL_PD_BID_PRICE', 'UL_PD_INVALID_FLAG']);

        return response()->json([
            'rows' => collect($page->items())->map(fn ($p) => [
                'key'   => $p->UL_PD_DATE,
                'date'  => Carbon::parse($p->UL_PD_DATE)->format('Y-m-d'),
                'label' => Carbon::parse($p->UL_PD_DATE)->format('d M Y'),
                'price' => $p->UL_PD_BID_PRICE,
                'valid' => !$p->UL_PD_INVALID_FLAG,
            ]),
            'page'     => $page->currentPage(),
            'lastPage' => $page->lastPage(),
            'total'    => $page->total(),
        ]);
    }

    /** Every financial period for the editor's Financials section, newest first. */
    public function financials(string $fincode)
    {
        UnlistedStock::where('UL_STOCKS_FINCODE', $fincode)->firstOrFail();

        $rows = UnlistedFinancials::where('UL_FIN_FINCODE', $fincode)
            ->orderByDesc('UL_FIN_Period_end')
            ->orderBy('UL_FIN_Type')
            ->orderByDesc('UL_FIN_No_months')
            ->get(['UL_FIN_Period_end', 'UL_FIN_Type', 'UL_FIN_No_months', 'UL_FIN_Unit', 'UL_FIN_STATUS']);

        return response()->json($rows->map(fn ($f) => [
            'periodEnd' => (int) $f->UL_FIN_Period_end,
            'label'     => self::periodLabel((int) $f->UL_FIN_Period_end),
            'type'      => $f->UL_FIN_Type,
            'months'    => (int) $f->UL_FIN_No_months,
            'unit'      => self::UNITS[(int) $f->UL_FIN_Unit] ?? ($f->UL_FIN_Unit ?: null),
            'active'    => $f->UL_FIN_STATUS === '1',
        ]));
    }

    /** Quarter ends from now back 15 years, as stored in UL_FIN_Period_end (YYYYMM). */
    public static function periodOptions(): array
    {
        $year    = (int) now()->format('Y');
        $month   = (int) now()->format('m');
        $options = [];

        for ($y = $year; $y >= $year - 15; $y--) {
            foreach ([12, 9, 6, 3] as $q) {
                if ($y === $year && $q > $month) {
                    continue;
                }
                $options[$y * 100 + $q] = self::periodLabel($y * 100 + $q);
            }
        }

        return $options;
    }

    public static function periodLabel(int $periodEnd): string
    {
        return intdiv($periodEnd, 100) . '-' . str_pad((string) ($periodEnd % 100), 2, '0', STR_PAD_LEFT);
    }

    private function latestRow(string $model, string $prefix, $fincode)
    {
        return $model::where("{$prefix}_FINCODE", $fincode)->orderByDesc("{$prefix}_ID")->first();
    }

    private function industries()
    {
        return DB::table('industry_master')
            ->where('IM_FLAG', 'A')
            ->where('IM_IND_CODE', '>', 0)
            ->orderBy('IM_INDUSTRY')
            ->get(['IM_IND_CODE', 'IM_INDUSTRY']);
    }

    /**
     * Returns ['stock' => columns for unlisted_stocks, 'sections' => [key => fields],
     * 'faqs' => cleaned rows]. Column limits mirror the table definitions so a
     * long value is a validation message, not a database error.
     */
    private function validateStock(Request $request): array
    {
        // Rows the agent added but left completely blank are dropped, not rejected.
        $request->merge([
            'faqs' => array_values(array_filter(
                (array) $request->input('faqs', []),
                fn ($row) => is_array($row) && (trim((string) ($row['q'] ?? '')) !== '' || trim((string) ($row['a'] ?? '')) !== '')
            )),
        ]);

        $yesNo = Rule::in(['Yes', 'No']);
        $score = 'nullable|numeric|min:0|max:10';

        $valid = $request->validate([
            'UL_STOCKS_COMPNAME'          => 'required|string|max:255',
            'UL_STOCKS_IND_CODE'          => ['nullable', 'integer', Rule::exists('industry_master', 'IM_IND_CODE')],
            'UL_STOCKS_ISIN'              => 'nullable|string|max:20',
            'UL_STOCKS_S_NAME'            => 'nullable|string|max:100',
            'UL_STOCKS_CATEGORY'          => ['nullable', Rule::in(array_keys(self::CATEGORIES))],
            'UL_STOCKS_TAG'               => ['nullable', Rule::in(self::TAGS)],
            'UL_STOCKS_DRHP_FLAG'         => ['nullable', $yesNo],
            'UL_STOCKS_INC_MONTH'         => ['nullable', Rule::in(self::MONTHS)],
            'UL_STOCKS_INC_YEAR'          => 'nullable|digits:4',
            'UL_STOCKS_WEBSITE'           => 'nullable|string|max:150',
            'UL_STOCKS_STATUS'            => 'nullable|boolean',
            'UL_STOCKS_COMP_RATING'       => 'nullable|integer|between:1,5',
            'UL_STOCKS_VALUATION_RATING'  => 'nullable|integer|between:1,5',
            'UL_STOCKS_BUY_SELL_FLAG'     => ['nullable', $yesNo],
            'UL_STOCKS_LOT_SIZE'          => 'nullable|string|max:5',
            'UL_STOCKS_ROFR_FLAG'         => ['nullable', $yesNo],
            'UL_STOCKS_DEMAT_ACCOUNT_REQ' => ['nullable', Rule::in(array_keys(self::DEMAT))],
            'UL_STOCKS_Qtr_Data_Publish'  => ['nullable', $yesNo],
            'UL_STOCKS_ABOUT'             => 'nullable|string',
            'logo'                        => 'nullable|file|mimes:png,jpg,jpeg,webp,svg|max:2048',

            'UL_WS_FINANCIAL_HEALTH'      => $score,
            'UL_WS_VALUATION'             => $score,
            'UL_WS_GROWTH_POTENTIAL'      => $score,
            'UL_WS_IPO_PROBABILITY'       => $score,
            'UL_WS_LIQUIDITY_SAFETY'      => $score,
            'UL_CI_FOUNDERS_INTRO'        => 'nullable|string|max:500',
            'UL_SEO_COMPANY_TITLE'        => 'nullable|string|max:255',
            'UL_SEO_COMPANY_DESCRIPTION'  => 'nullable|string|max:500',
            'UL_SEO_COMPANY_KEYWORDS'     => 'nullable|string|max:500',
            'UL_SEO_ABOUT_TITLE'          => 'nullable|string|max:255',
            'UL_SEO_ABOUT_DESCRIPTION'    => 'nullable|string|max:500',
            'UL_SEO_ABOUT_KEYWORDS'       => 'nullable|string|max:500',
            'UL_SEO_THESIS_TITLE'         => 'nullable|string|max:255',
            'UL_SEO_THESIS_DESCRIPTION'   => 'nullable|string|max:500',
            'UL_SEO_THESIS_KEYWORDS'      => 'nullable|string|max:500',

            'faqs'                        => 'array',
            'faqs.*.id'                   => 'nullable|integer',
            'faqs.*.target'               => ['required', Rule::in(array_keys(self::FAQ_PAGES))],
            'faqs.*.tab'                  => 'nullable|string|max:60',
            'faqs.*.q'                    => 'required|string|max:500',
            'faqs.*.a'                    => 'nullable|string',
            'faqs.*.active'               => 'nullable|boolean',
        ], [
            'UL_STOCKS_COMPNAME.required' => 'Company name is required.',
            'logo.mimes'                  => 'Logo must be a PNG, JPG, WEBP or SVG file.',
            'logo.max'                    => 'Logo must not exceed 2 MB.',
            'UL_WS_*.max'                 => 'Each WittyScore pillar is scored out of 10.',
            'faqs.*.target.required'      => 'Every FAQ needs a page to show on.',
            'faqs.*.q.required'           => 'Every FAQ needs a question (or remove the row).',
        ]);

        $stockFields = [
            'UL_STOCKS_COMPNAME', 'UL_STOCKS_IND_CODE', 'UL_STOCKS_ISIN', 'UL_STOCKS_S_NAME',
            'UL_STOCKS_CATEGORY', 'UL_STOCKS_TAG', 'UL_STOCKS_DRHP_FLAG', 'UL_STOCKS_INC_MONTH',
            'UL_STOCKS_INC_YEAR', 'UL_STOCKS_WEBSITE', 'UL_STOCKS_COMP_RATING', 'UL_STOCKS_VALUATION_RATING',
            'UL_STOCKS_BUY_SELL_FLAG', 'UL_STOCKS_LOT_SIZE', 'UL_STOCKS_ROFR_FLAG',
            'UL_STOCKS_DEMAT_ACCOUNT_REQ', 'UL_STOCKS_Qtr_Data_Publish', 'UL_STOCKS_ABOUT',
        ];

        $stock = [];
        foreach ($stockFields as $field) {
            $stock[$field] = $request->input($field);
        }
        $stock['UL_STOCKS_COMPNAME'] = trim($valid['UL_STOCKS_COMPNAME']);
        $stock['UL_STOCKS_STATUS']   = $request->boolean('UL_STOCKS_STATUS') ? '1' : '0';
        // NOT NULL columns with a 'Yes' default in the table.
        $stock['UL_STOCKS_BUY_SELL_FLAG']    ??= 'Yes';
        $stock['UL_STOCKS_Qtr_Data_Publish'] ??= 'Yes';
        $stock['UL_STOCKS_INDUSTRY'] = $stock['UL_STOCKS_IND_CODE']
            ? DB::table('industry_master')->where('IM_IND_CODE', $stock['UL_STOCKS_IND_CODE'])->value('IM_INDUSTRY')
            : null;

        $sections = [];
        foreach (self::SECTIONS as $key => [, , $fields]) {
            $sections[$key] = Arr::only($request->all(), $fields) + array_fill_keys($fields, null);
        }

        $faqs = array_map(fn ($row) => [
            'id'     => isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null,
            'target' => $row['target'],
            'tab'    => isset($row['tab']) && trim($row['tab']) !== '' ? trim($row['tab']) : null,
            'q'      => trim($row['q']),
            'a'      => $row['a'] ?? null,
            'active' => !empty($row['active']) ? '1' : '0',
        ], $valid['faqs'] ?? []);

        return ['stock' => $stock, 'sections' => $sections, 'faqs' => $faqs];
    }

    /**
     * Uploaded before the transaction so a bad file fails validation without
     * leaving half the sections saved.
     */
    private function attachLogo(Request $request, array &$data, string $slug): void
    {
        if (!$request->hasFile('logo')) {
            return;
        }

        try {
            $data['stock']['UL_STOCKS_LOGO_LINK'] = ImageUpload::replace($request->file('logo'), 'company-logo', $slug, allowSvg: true);
        } catch (ImageUploadException $e) {
            throw $e->forField('logo');
        }
    }

    /**
     * A typed slug is used as-is, or refused if another stock has it, so the
     * URL is never a surprise. Left blank: an existing stock keeps its slug,
     * a new one gets one made from the name (with -2, -3… if needed).
     */
    private function requestedSlug(Request $request, ?UnlistedStock $stock = null): string
    {
        $request->validate(['UL_STOCKS_SLUG' => 'nullable|string|max:255']);

        $slug = Str::slug((string) $request->input('UL_STOCKS_SLUG', ''));
        if ($stock && ($slug === '' || $slug === $stock->UL_STOCKS_SLUG)) {
            return $stock->UL_STOCKS_SLUG;
        }
        if ($slug === '') {
            return $this->uniqueSlug($request->input('UL_STOCKS_COMPNAME'));
        }

        $taken = UnlistedStock::where('UL_STOCKS_SLUG', $slug)
            ->when($stock, fn ($q) => $q->where('UL_STOCKS_FINCODE', '!=', $stock->UL_STOCKS_FINCODE))
            ->exists();

        if ($taken) {
            throw ValidationException::withMessages([
                'UL_STOCKS_SLUG' => "/unlisted-shares/{$slug}/ is already used by another stock. Pick a different URL.",
            ]);
        }

        return $slug;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'stock';
        $slug = $base;
        $n    = 2;

        while (UnlistedStock::where('UL_STOCKS_SLUG', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }

    /**
     * Updates each section's live row, or creates one — but only when the
     * section has something in it, so saving the page never adds empty rows
     * for sections nobody has written yet.
     */
    private function saveSections(Request $request, array $data, $fincode): void
    {
        $now = now();

        foreach (self::SECTIONS as $key => [$model, $prefix]) {
            $values = $data['sections'][$key];
            $row    = $this->latestRow($model, $prefix, $fincode);

            if (!$row && collect($values)->every(fn ($v) => blank($v))) {
                continue;
            }

            $payload = $values + [
                "{$prefix}_ACTIVE"      => $request->boolean("{$prefix}_ACTIVE") ? '1' : '0',
                "{$prefix}_UPDATE_TIME" => $now,
            ];

            if ($row) {
                $model::where("{$prefix}_ID", $row->{"{$prefix}_ID"})->update($payload);
            } else {
                $model::insert($payload + ["{$prefix}_FINCODE" => $fincode, "{$prefix}_INSERT_TIME" => $now]);
            }
        }
    }

    /** The FAQ rows on the page are the full list: kept rows update, new ones insert, missing ones go. */
    private function syncFaqs(array $faqs, $fincode): void
    {
        $now  = now();
        $keep = [];

        foreach ($faqs as $position => $row) {
            $attrs = [
                'UL_FAQ_TARGET'      => $row['target'],
                'UL_FAQ_TAB'         => $row['tab'],
                'UL_FAQ_QUESTION'    => $row['q'],
                'UL_FAQ_ANSWER'      => $row['a'],
                'UL_FAQ_SORT_ORDER'  => $position + 1,
                'UL_FAQ_ACTIVE'      => $row['active'],
                'UL_FAQ_UPDATE_TIME' => $now,
            ];

            $faq = $row['id']
                ? UnlistedFaq::where('UL_FAQ_FINCODE', $fincode)->where('UL_FAQ_ID', $row['id'])->first()
                : null;

            if ($faq) {
                $faq->update($attrs);
            } else {
                $faq = UnlistedFaq::create($attrs + ['UL_FAQ_FINCODE' => $fincode, 'UL_FAQ_INSERT_TIME' => $now]);
            }

            $keep[] = $faq->UL_FAQ_ID;
        }

        UnlistedFaq::where('UL_FAQ_FINCODE', $fincode)->whereNotIn('UL_FAQ_ID', $keep)->delete();
    }
}
