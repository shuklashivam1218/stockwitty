<?php

namespace App\Http\Controllers;

use App\Helpers\ImageUpload;
use App\Helpers\Privilege;
use App\Models\UnlistedFinancials;
use App\Models\UnlistedPriceData;
use App\Models\UnlistedStock;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * The unlisted stocks dashboard, plus the price, financials, status and
 * image endpoints the one-page editor (UnlistedStockEditorController) calls.
 */
class UnlistedStocksController extends Controller implements HasMiddleware
{
    /**
     * Content chips on the dashboard: label => [table, fincode column, "live"
     * SQL expression, extra where]. About / Thesis / WittyScore are the
     * stock's CMS pages: live once published, "off" while a draft.
     */
    private const CONTENT = [
        'Price'      => ['unlisted_price_data', 'UL_PD_FINCODE', 'UL_PD_INVALID_FLAG = 0', []],
        'Financials' => ['unlisted_financials', 'UL_FIN_FINCODE', "UL_FIN_STATUS = '1'", []],
        'Insights'   => ['unlisted_company_insights', 'UL_CI_FINCODE', "UL_CI_ACTIVE = '1'", []],
        'FAQs'       => ['unlisted_faqs', 'UL_FAQ_FINCODE', "UL_FAQ_ACTIVE = '1'", ['UL_FAQ_TARGET' => 'overview']],
        'SEO'        => ['unlisted_seo_meta', 'UL_SEO_FINCODE', "UL_SEO_ACTIVE = '1'", []],
        'About'      => ['stock_articles', 'ul_stocks_fincode', "status = 'published'", ['type' => 'about', 'deleted_at' => null]],
        'Thesis'     => ['stock_articles', 'ul_stocks_fincode', "status = 'published'", ['type' => 'thesis', 'deleted_at' => null]],
        'WittyScore' => ['stock_articles', 'ul_stocks_fincode', "status = 'published'", ['type' => 'wittyscore', 'deleted_at' => null]],
    ];

    public static function middleware(): array
    {
        return [fn ($request, $next) => Privilege::canManageStocks() ? $next($request) : abort(403)];
    }

    public function index(Request $request)
    {
        $status = (string) $request->query('status', '');
        $search = trim((string) $request->query('search', ''));

        $stocks = UnlistedStock::query()
            ->when($status === 'active', fn ($q) => $q->where('UL_STOCKS_STATUS', '1'))
            ->when($status === 'inactive', fn ($q) => $q->where(fn ($w) => $w->where('UL_STOCKS_STATUS', '!=', '1')->orWhereNull('UL_STOCKS_STATUS')))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('UL_STOCKS_COMPNAME', 'like', '%' . $search . '%')
                ->orWhere('UL_STOCKS_S_NAME', 'like', '%' . $search . '%')
                ->orWhere('UL_STOCKS_ISIN', 'like', '%' . $search . '%')
                ->orWhere('UL_STOCKS_FINCODE', $search)))
            ->orderByDesc('UL_STOCKS_FINCODE')
            ->paginate(20)
            ->withQueryString();

        $fincodes = $stocks->pluck('UL_STOCKS_FINCODE');

        $latestPrices = DB::table('unlisted_price_data as pd')
            ->joinSub(
                DB::table('unlisted_price_data')
                    ->selectRaw('UL_PD_FINCODE, MAX(UL_PD_DATE) as max_date')
                    ->where('UL_PD_INVALID_FLAG', '!=', '1')
                    ->whereIn('UL_PD_FINCODE', $fincodes)
                    ->groupBy('UL_PD_FINCODE'),
                'latest',
                fn($j) => $j->on('pd.UL_PD_FINCODE', '=', 'latest.UL_PD_FINCODE')
                             ->on('pd.UL_PD_DATE', '=', 'latest.max_date')
            )
            ->select('pd.UL_PD_FINCODE', 'pd.UL_PD_DATE', 'pd.UL_PD_BID_PRICE')
            ->get()
            ->keyBy('UL_PD_FINCODE');

        $counts = UnlistedStock::query()
            ->selectRaw("COUNT(*) as total, SUM(UL_STOCKS_STATUS = '1') as active")
            ->first();

        return view('admin.unlisted.index', [
            'stocks'       => $stocks,
            'latestPrices' => $latestPrices,
            'content'      => $this->contentStatus($fincodes->all()),
            'status'       => $status,
            'search'       => $search,
            'counts'       => [
                ''         => (int) $counts->total,
                'active'   => (int) $counts->active,
                'inactive' => (int) $counts->total - (int) $counts->active,
            ],
        ]);
    }

    /**
     * For each stock on the page and each content area: 'on' when something
     * is live, 'off' when it exists but is switched off, absent when empty.
     * The company profile lives on the stock row itself.
     */
    private function contentStatus(array $fincodes): array
    {
        $status = [];

        foreach (self::CONTENT as $label => [$table, $column, $live, $where]) {
            $rows = DB::table($table)
                ->where($where)
                ->whereIn($column, $fincodes)
                ->groupBy($column)
                ->selectRaw("{$column} as fincode, MAX({$live}) as live")
                ->get();

            foreach ($rows as $row) {
                $status[$row->fincode][$label] = $row->live ? 'on' : 'off';
            }
        }

        return $status;
    }

    public function storeIndustry(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100'], [
            'name.required' => 'Industry name is required.',
        ]);

        $name     = trim($request->input('name'));
        $baseSlug = Str::slug($name);
        $slug     = $baseSlug;
        $counter  = 1;
        while (DB::table('industry_master')->where('IM_SLUG', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $newCode = (DB::table('industry_master')->max('IM_IND_CODE') ?? 0) + 1;

        DB::table('industry_master')->insert([
            'IM_IND_CODE'    => $newCode,
            'IM_INDUSTRY'    => $name,
            'IM_FLAG'        => 'A',
            'IM_INSERT_TIME' => now(),
            'IM_UPDATE_TIME' => now(),
            'IM_SLUG'        => $slug,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Industry added successfully.',
            'industry' => ['code' => $newCode, 'name' => $name, 'slug' => $slug],
        ]);
    }

    public function toggleStockStatus(string $fincode)
    {
        $stock     = UnlistedStock::where('UL_STOCKS_FINCODE', $fincode)->firstOrFail();
        $newStatus = $stock->UL_STOCKS_STATUS === '1' ? '0' : '1';
        $stock->update(['UL_STOCKS_STATUS' => $newStatus]);

        return response()->json(['success' => true, 'status' => $newStatus]);
    }

    public function storePriceData(Request $request, string $fincode)
    {
        UnlistedStock::where('UL_STOCKS_FINCODE', $fincode)->firstOrFail();

        $request->validate([
            'UL_PD_DATE'      => 'required|date',
            'UL_PD_BID_PRICE' => 'required|numeric|min:0',
        ], [
            'UL_PD_DATE.required'      => 'Date is required.',
            'UL_PD_DATE.date'          => 'Enter a valid date.',
            'UL_PD_BID_PRICE.required' => 'Bid price is required.',
            'UL_PD_BID_PRICE.numeric'  => 'Bid price must be a number.',
        ]);

        UnlistedPriceData::updateOrInsert(
            ['UL_PD_FINCODE' => $fincode, 'UL_PD_DATE' => $request->input('UL_PD_DATE')],
            [
                'UL_PD_BID_PRICE'    => $request->input('UL_PD_BID_PRICE'),
                'UL_PD_INVALID_FLAG' => 0,
                'UL_PD_UPDTIME'      => now(),
            ]
        );

        return response()->json(['success' => true, 'message' => 'Price saved successfully.']);
    }

    public function updatePriceEntry(Request $request, string $fincode, string $date)
    {
        UnlistedStock::where('UL_STOCKS_FINCODE', $fincode)->firstOrFail();

        $request->validate([
            'UL_PD_BID_PRICE' => 'required|numeric|min:0',
        ]);

        $updated = UnlistedPriceData::where('UL_PD_FINCODE', $fincode)
                    ->where('UL_PD_DATE', $date)
                    ->update([
                        'UL_PD_BID_PRICE' => $request->input('UL_PD_BID_PRICE'),
                        'UL_PD_UPDTIME'   => now(),
                    ]);

        abort_if(!$updated, 404, 'Record not found.');

        return response()->json(['success' => true, 'message' => 'Updated successfully.']);
    }

    public function togglePriceInvalid(string $fincode, string $date)
    {
        UnlistedStock::where('UL_STOCKS_FINCODE', $fincode)->firstOrFail();

        $entry = UnlistedPriceData::where('UL_PD_FINCODE', $fincode)
                    ->where('UL_PD_DATE', $date)
                    ->first();

        abort_if(!$entry, 404, 'Record not found.');

        $newFlag = $entry->UL_PD_INVALID_FLAG ? 0 : 1;

        UnlistedPriceData::where('UL_PD_FINCODE', $fincode)
            ->where('UL_PD_DATE', $date)
            ->update(['UL_PD_INVALID_FLAG' => $newFlag, 'UL_PD_UPDTIME' => now()]);

        return response()->json(['success' => true, 'invalid' => (bool) $newFlag]);
    }

    public function storeFinancialsData(Request $request, string $fincode)
    {
        UnlistedStock::where('UL_STOCKS_FINCODE', $fincode)->firstOrFail();

        $request->validate([
            'UL_FIN_Period_end' => 'required|integer',
            'UL_FIN_Type'       => 'required|in:C,S',
            'UL_FIN_No_months'  => 'required|in:3,6,12',
        ], [
            'UL_FIN_Period_end.required' => 'Period End is required.',
            'UL_FIN_Type.required'       => 'Type is required.',
            'UL_FIN_Type.in'             => 'Type must be Consolidated or Standalone.',
            'UL_FIN_No_months.required'  => 'No. Months is required.',
            'UL_FIN_No_months.in'        => 'No. Months must be 3, 6, or 12.',
        ]);

        $numericFields = [
            'UL_FIN_Unit', 'UL_FIN_FV', 'UL_FIN_NUM_SHARES',
            'UL_FIN_NET_SALES', 'UL_FIN_OTHER_INCOME', 'UL_FIN_TOTAL_INCOME',
            'UL_FIN_TOTAL_EXPENDITURE', 'UL_FIN_OPERATING_PROFIT', 'UL_FIN_INTEREST',
            'UL_FIN_DEPRECIATION', 'UL_FIN_EXCEPTIONAL_INCOME', 'UL_FIN_PBT',
            'UL_FIN_TAX', 'UL_FIN_PAT', 'UL_FIN_ADJUSTMENTS',
            'UL_FIN_PROFIT_AFTER_ADJUSTMENTS', 'UL_FIN_ADJUSTED_EPS', 'UL_FIN_DPS',
            'UL_FIN_SHAREHOLDER_FUNDS', 'UL_FIN_MINORITY_INTEREST', 'UL_FIN_BORROWINGS',
            'UL_FIN_OTHER_NONCURRENT_LIABILITIES', 'UL_FIN_TOTAL_CURRENT_LIABILITIES',
            'UL_FIN_TOTAL_LIABILITIES', 'UL_FIN_FIXED_ASSETS', 'UL_FIN_OTHER_NONCURRENT_ASSETS',
            'UL_FIN_TOTAL_CURRENT_ASSETS', 'UL_FIN_TOTAL_ASSETS', 'UL_FIN_TOTAL_DEBT',
            'UL_FIN_OPENING_CASH', 'UL_FIN_CFO', 'UL_FIN_CFI', 'UL_FIN_CFF',
            'UL_FIN_NET_CASH_FLOW', 'UL_FIN_CLOSING_CASH',
            'UL_FIN_CURRENT_LIABILITIES', 'UL_FIN_NON_CURRENT_LIABILITIES',
            'UL_FIN_CURRENT_ASSETS', 'UL_FIN_NON_CURRENT_ASSETS',
            'UL_FIN_CASH_FLOW_FROM_OPERATING_ACTIVITIES',
            'UL_FIN_CASH_FLOW_FORM_INVESTING_ACTIVITIES',
            'UL_FIN_CASH_FLOW_FROM_FINANCING_ACTIVITIES',
            'UL_FIN_FREE_CASH_FLOW',
        ];

        $data = [];
        foreach ($numericFields as $field) {
            $val          = $request->input($field);
            $data[$field] = ($val !== null && $val !== '') ? $val : null;
        }

        $totalAssets = $request->input('UL_FIN_TOTAL_ASSETS');
        $totalLiab   = $request->input('UL_FIN_TOTAL_LIABILITIES');
        if ($totalAssets !== null && $totalAssets !== '' && $totalLiab !== null && $totalLiab !== '') {
            if ((float) $totalAssets !== (float) $totalLiab) {
                return response()->json(['success' => false, 'message' => 'Total Assets must equal Total Liabilities.'], 422);
            }
        }

        $exists = UnlistedFinancials::where('UL_FIN_FINCODE',   $fincode)
                    ->where('UL_FIN_Period_end', $request->input('UL_FIN_Period_end'))
                    ->where('UL_FIN_Type',       $request->input('UL_FIN_Type'))
                    ->where('UL_FIN_No_months',  $request->input('UL_FIN_No_months'))
                    ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Record already exists for this Period End, Type, and No. Months combination.'], 422);
        }

        UnlistedFinancials::insert(array_merge($data, [
            'UL_FIN_FINCODE'     => $fincode,
            'UL_FIN_Period_end'  => $request->input('UL_FIN_Period_end'),
            'UL_FIN_Type'        => $request->input('UL_FIN_Type'),
            'UL_FIN_No_months'   => $request->input('UL_FIN_No_months'),
            'UL_FIN_STATUS'      => '1',
            'UL_FIN_INSERT_BY'   => session('uid'),
            'UL_FIN_INSERT_TIME' => now(),
            'UL_FIN_UPDATE_TIME' => now(),
        ]));

        return response()->json(['success' => true, 'message' => 'Financials saved successfully.']);
    }

    public function getFinancialsEditModal(string $fincode, string $periodEnd, string $type, string $noMonths)
    {
        UnlistedStock::where('UL_STOCKS_FINCODE', $fincode)->firstOrFail();

        $financial = UnlistedFinancials::where('UL_FIN_FINCODE',   $fincode)
                        ->where('UL_FIN_Period_end', $periodEnd)
                        ->where('UL_FIN_Type',       $type)
                        ->where('UL_FIN_No_months',  $noMonths)
                        ->firstOrFail();

        return response()->json($financial->toArray());
    }

    public function updateFinancialsData(Request $request, string $fincode, string $periodEnd, string $type, string $noMonths)
    {
        UnlistedStock::where('UL_STOCKS_FINCODE', $fincode)->firstOrFail();

        $request->validate([
            'UL_FIN_Period_end' => 'required|integer',
            'UL_FIN_Type'       => 'required|in:C,S',
            'UL_FIN_No_months'  => 'required|in:3,6,12',
        ]);

        $newPeriod  = (string) $request->input('UL_FIN_Period_end');
        $newType    = $request->input('UL_FIN_Type');
        $newMonths  = (string) $request->input('UL_FIN_No_months');
        $keyChanged = ($newPeriod !== $periodEnd || $newType !== $type || $newMonths !== $noMonths);

        if ($keyChanged) {
            $exists = UnlistedFinancials::where('UL_FIN_FINCODE',   $fincode)
                        ->where('UL_FIN_Period_end', $newPeriod)
                        ->where('UL_FIN_Type',       $newType)
                        ->where('UL_FIN_No_months',  $newMonths)
                        ->exists();
            if ($exists) {
                return response()->json(['success' => false, 'message' => 'A record already exists for this Period End / Type / No. Months combination.'], 422);
            }
        }

        $numericFields = [
            'UL_FIN_Unit', 'UL_FIN_FV', 'UL_FIN_NUM_SHARES',
            'UL_FIN_NET_SALES', 'UL_FIN_OTHER_INCOME', 'UL_FIN_TOTAL_INCOME',
            'UL_FIN_TOTAL_EXPENDITURE', 'UL_FIN_OPERATING_PROFIT', 'UL_FIN_INTEREST',
            'UL_FIN_DEPRECIATION', 'UL_FIN_EXCEPTIONAL_INCOME', 'UL_FIN_PBT',
            'UL_FIN_TAX', 'UL_FIN_PAT',
            'UL_FIN_SHAREHOLDER_FUNDS', 'UL_FIN_TOTAL_LIABILITIES', 'UL_FIN_TOTAL_ASSETS',
            'UL_FIN_TOTAL_DEBT', 'UL_FIN_CURRENT_LIABILITIES', 'UL_FIN_NON_CURRENT_LIABILITIES',
            'UL_FIN_CURRENT_ASSETS', 'UL_FIN_NON_CURRENT_ASSETS',
            'UL_FIN_CASH_FLOW_FROM_OPERATING_ACTIVITIES',
            'UL_FIN_CASH_FLOW_FORM_INVESTING_ACTIVITIES',
            'UL_FIN_CASH_FLOW_FROM_FINANCING_ACTIVITIES',
            'UL_FIN_FREE_CASH_FLOW',
        ];

        $totalAssets = $request->input('UL_FIN_TOTAL_ASSETS');
        $totalLiab   = $request->input('UL_FIN_TOTAL_LIABILITIES');
        if ($totalAssets !== null && $totalAssets !== '' && $totalLiab !== null && $totalLiab !== '') {
            if ((float) $totalAssets !== (float) $totalLiab) {
                return response()->json(['success' => false, 'message' => 'Total Assets must equal Total Liabilities.'], 422);
            }
        }

        $data = [];
        foreach ($numericFields as $field) {
            $val          = $request->input($field);
            $data[$field] = ($val !== null && $val !== '') ? $val : null;
        }

        $data['UL_FIN_Period_end']  = $newPeriod;
        $data['UL_FIN_Type']        = $newType;
        $data['UL_FIN_No_months']   = $newMonths;
        $data['UL_FIN_UPDATE_TIME'] = now();

        UnlistedFinancials::where('UL_FIN_FINCODE',   $fincode)
            ->where('UL_FIN_Period_end', $periodEnd)
            ->where('UL_FIN_Type',       $type)
            ->where('UL_FIN_No_months',  $noMonths)
            ->update($data);

        return response()->json(['success' => true, 'message' => 'Updated successfully.']);
    }

    public function toggleFinancialStatus(string $fincode, string $periodEnd, string $type, string $noMonths)
    {
        $row = UnlistedFinancials::where('UL_FIN_FINCODE',   $fincode)
                    ->where('UL_FIN_Period_end', $periodEnd)
                    ->where('UL_FIN_Type',       $type)
                    ->where('UL_FIN_No_months',  $noMonths)
                    ->first();

        abort_if(!$row, 404, 'Record not found.');

        $newStatus = $row->UL_FIN_STATUS === '1' ? '0' : '1';

        UnlistedFinancials::where('UL_FIN_FINCODE',   $fincode)
            ->where('UL_FIN_Period_end', $periodEnd)
            ->where('UL_FIN_Type',       $type)
            ->where('UL_FIN_No_months',  $noMonths)
            ->update(['UL_FIN_STATUS' => $newStatus, 'UL_FIN_UPDATE_TIME' => now()]);

        return response()->json(['success' => true, 'status' => $newStatus]);
    }

    public function uploadAboutImage(Request $request, string $fincode)
    {
        $request->validate(['file' => 'required|' . ImageUpload::RULES]);

        $path = ImageUpload::store($request->file('file'), 'unlisted-about-images', 'about_' . $fincode);

        return response()->json(['location' => ImageUpload::url($path)]);
    }

}
