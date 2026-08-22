@extends('layouts.app')

@php
    $fmtMoney = fn ($v, $d = 2) => ($v !== null && $v !== '') ? '₹' . number_format((float) $v, $d) : '—';
    $fmtNum   = fn ($v, $d = 2) => ($v !== null && $v !== '') ? number_format((float) $v, $d) : '—';
    $crVal    = fn ($row, $field) => ($row && $row->$field !== null && $row->$field !== '')
        ? number_format(((float) $row->$field * (float) ($row->UL_FIN_Unit ?: 1)) / 10000000, 2)
        : '—';
    $marginPct = fn ($row, $numField, $denField) => ($row && $row->$denField && (float) $row->$denField != 0)
        ? number_format(((float) $row->$numField / (float) $row->$denField) * 100, 1) . '%'
        : '—';

    $monthShort = ['3' => 'Mar', '6' => 'Jun', '9' => 'Sep', '12' => 'Dec'];
    $yearlyLabel = fn ($pe) => 'FY' . intdiv((int) $pe, 100);
    $quarterlyLabel = function ($pe) use ($monthShort) {
        $pe = (int) $pe;
        $y  = intdiv($pe, 100);
        $m  = $pe % 100;
        return ($monthShort[$m] ?? $m) . " '" . substr((string) $y, 2, 2);
    };

    $stars = function ($n) {
        $n = (int) $n;
        if ($n < 1) return '—';
        $n = min($n, 5);
        return str_repeat('★', $n) . str_repeat('☆', 5 - $n);
    };

    $founded = trim(($stock->UL_STOCKS_INC_MONTH ?? '') . ' ' . ($stock->UL_STOCKS_INC_YEAR ?? ''));
    $initials = collect(explode(' ', trim($stock->UL_STOCKS_COMPNAME)))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');
@endphp

@section('title', $stock->UL_STOCKS_COMPNAME . ' Unlisted Shares — Price, Financials & Fundamentals | StockWitty')

@section('styles')
<style>
  .cp-back { display: inline-flex; align-items: center; gap: 6px; font-size: .82rem; color: var(--text-muted); margin: 24px 0 20px; }
  .cp-back:hover { color: var(--green-dark); }

  .cp-header { display: flex; align-items: center; gap: 16px; margin-bottom: 28px; flex-wrap: wrap; }
  .cp-logo, .cp-avatar { width: 64px; height: 64px; border-radius: 14px; flex-shrink: 0; }
  .cp-logo { object-fit: contain; border: 1px solid var(--border); background: #fff; }
  .cp-avatar { background: var(--green-dark); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; }
  .cp-header h1 { font-size: 1.6rem; font-weight: 700; margin: 0 0 8px; color: var(--text-dark); }
  .cp-pill { display: inline-flex; align-items: center; font-size: .72rem; font-weight: 600; background: var(--green-light); color: var(--green-dark); border-radius: 20px; padding: 3px 12px; margin-right: 6px; }
  .cp-pill.status-inactive { background: #fdeaea; color: #c0392b; }

  .cp-about-content { font-size: 15px; line-height: 1.9; color: rgba(0,0,0,.7); }
  .cp-about-content img { max-width: 100%; height: auto; border-radius: 10px; }

  .cp-fund-grid { display: grid; grid-template-columns: repeat(4, 1fr); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
  .cp-fg-cell { padding: 16px 18px; border-right: 1px solid var(--border); border-bottom: 1px solid var(--border); background: #fff; }
  .cp-fg-cell:nth-child(4n) { border-right: none; }
  .cp-fg-cell:nth-last-child(-n+4) { border-bottom: none; }
  .cp-fg-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: var(--text-muted); margin-bottom: 6px; }
  .cp-fg-value { font-size: 15px; font-weight: 700; color: var(--text-dark); }
  .cp-fg-value.green { color: var(--green-dark); }

  .cp-sidebar { position: sticky; top: 90px; }
  .cp-empty-note { font-size: .85rem; color: var(--text-muted); padding: 24px 4px; }

  .cp-growth-row { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
  .cp-growth-pill { display: inline-flex; align-items: center; gap: 4px; font-size: .78rem; font-weight: 700; border-radius: 20px; padding: 5px 14px; }
  .cp-growth-pill.up { background: #e6f7ea; color: #16A218; }
  .cp-growth-pill.down { background: #fdeaea; color: #FF3B30; }

  .cp-stars { color: #d4a017; letter-spacing: 1px; font-size: 14px !important; }
  .cp-price-asof { font-size: .72rem; color: var(--text-muted); margin-top: -8px; margin-bottom: 12px; }

  @media (max-width: 767px) {
    .cp-fund-grid { grid-template-columns: repeat(2, 1fr); }
    .cp-fg-cell:nth-child(4n) { border-right: 1px solid var(--border); }
    .cp-fg-cell:nth-child(2n) { border-right: none; }
    .cp-fg-cell:nth-last-child(-n+4) { border-bottom: 1px solid var(--border); }
    .cp-fg-cell:nth-last-child(-n+2) { border-bottom: none; }
    .cp-sidebar { position: static; margin-top: 24px; }
  }
</style>
@endsection

@section('content')
<div class="container">

  <a href="{{ url('/unlisted') }}" class="cp-back">
    <i class="bi bi-arrow-left"></i> All Unlisted Shares
  </a>

  <div class="cp-header">
    @if($stock->UL_STOCKS_LOGO_LINK)
      <img src="{{ asset($stock->UL_STOCKS_LOGO_LINK) }}" alt="{{ $stock->UL_STOCKS_COMPNAME }}" class="cp-logo">
    @else
      <div class="cp-avatar">{{ $initials ?: '—' }}</div>
    @endif
    <div>
      <h1>{{ $stock->UL_STOCKS_COMPNAME }}</h1>
      <div>
        @if($stock->UL_STOCKS_INDUSTRY)
          <span class="cp-pill"><i class="bi bi-grid me-1"></i>{{ $stock->UL_STOCKS_INDUSTRY }}</span>
        @endif
        @if($stock->UL_STOCKS_ISIN)
          <span class="cp-pill">{{ $stock->UL_STOCKS_ISIN }}</span>
        @endif
        <span class="cp-pill {{ $stock->UL_STOCKS_STATUS == '1' ? '' : 'status-inactive' }}">
          {{ $stock->UL_STOCKS_STATUS == '1' ? 'Active' : 'Inactive' }}
        </span>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">

      {{-- ABOUT --}}
      @if($aboutHtml)
        <section class="about-section">
          <div class="label-tag">// ABOUT</div>
          <h2>{{ $stock->UL_STOCKS_COMPNAME }}</h2>
          <div class="cp-about-content">{!! $aboutHtml !!}</div>
        </section>
      @endif

      @include('partials.faq-accordion', ['faqs' => $aboutFaqs, 'prefix' => 'faq-about', 'faqTitle' => 'About ' . $stock->UL_STOCKS_COMPNAME . ' — FAQs'])

      {{-- PRICE MOVEMENT --}}
      <section class="chart-section">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <h3>Price Movement</h3>
          @if($priceHistory->isNotEmpty())
            <div class="period-tabs" id="cpPeriodTabs">
              <button type="button" class="btn" data-range="30">1M</button>
              <button type="button" class="btn active" data-range="180">6M</button>
              <button type="button" class="btn" data-range="365">1Y</button>
              <button type="button" class="btn" data-range="1095">3Y</button>
              <button type="button" class="btn" data-range="1825">5Y</button>
              <button type="button" class="btn" data-range="max">Max</button>
            </div>
          @endif
        </div>
        @if($priceHistory->isNotEmpty())
          <div class="chart-canvas-wrap">
            <canvas id="priceChart"></canvas>
          </div>
        @else
          <p class="cp-empty-note">Price history will appear here once trading data is published.</p>
        @endif
      </section>

      {{-- FUNDAMENTALS --}}
      <section class="chart-section">
        <div class="label-tag">// FUNDAMENTALS</div>
        <h2>Key Metrics</h2>
        @if($netSalesGrowthPct !== null || $patGrowthPct !== null)
          <div class="cp-growth-row">
            @if($netSalesGrowthPct !== null)
              <span class="cp-growth-pill {{ $netSalesGrowthPct >= 0 ? 'up' : 'down' }}">
                <i class="bi bi-arrow-{{ $netSalesGrowthPct >= 0 ? 'up' : 'down' }}-short"></i>
                Net Sales YoY {{ $netSalesGrowthPct >= 0 ? '+' : '' }}{{ $netSalesGrowthPct }}%
              </span>
            @endif
            @if($patGrowthPct !== null)
              <span class="cp-growth-pill {{ $patGrowthPct >= 0 ? 'up' : 'down' }}">
                <i class="bi bi-arrow-{{ $patGrowthPct >= 0 ? 'up' : 'down' }}-short"></i>
                PAT YoY {{ $patGrowthPct >= 0 ? '+' : '' }}{{ $patGrowthPct }}%
              </span>
            @endif
          </div>
        @endif
        <div class="cp-fund-grid">
          <div class="cp-fg-cell"><div class="cp-fg-label">Share Price</div><div class="cp-fg-value green">{{ $fmtMoney($currentPrice) }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">Lot Size</div><div class="cp-fg-value">{{ $stock->UL_STOCKS_LOT_SIZE ? $stock->UL_STOCKS_LOT_SIZE . ' shares' : '—' }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">52W High</div><div class="cp-fg-value">{{ $fmtMoney($high52w) }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">52W Low</div><div class="cp-fg-value">{{ $fmtMoney($low52w) }}</div></div>

          <div class="cp-fg-cell"><div class="cp-fg-label">Market Cap</div><div class="cp-fg-value">{{ $marketCap !== null ? '₹' . $marketCap . ' Cr' : '—' }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">P/E Ratio</div><div class="cp-fg-value">{{ $peRatio !== null ? $peRatio . 'x' : '—' }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">P/B Ratio</div><div class="cp-fg-value">{{ $pbRatio !== null ? $pbRatio . 'x' : '—' }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">Debt to Equity</div><div class="cp-fg-value">{{ $debtToEquity ?? '—' }}</div></div>

          <div class="cp-fg-cell"><div class="cp-fg-label">ROE</div><div class="cp-fg-value">{{ $roe !== null ? $roe . '%' : '—' }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">Book Value</div><div class="cp-fg-value">{{ $fmtMoney($bookValue) }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">Face Value</div><div class="cp-fg-value">{{ $fmtMoney($latestFin->UL_FIN_FV ?? null) }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">EPS</div><div class="cp-fg-value">{{ $fmtMoney($eps) }}</div></div>

          <div class="cp-fg-cell"><div class="cp-fg-label">Total Shares</div><div class="cp-fg-value">{{ isset($latestFin->UL_FIN_NUM_SHARES) ? number_format($latestFin->UL_FIN_NUM_SHARES / 10000000, 2) . ' Cr' : '—' }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">Depository</div><div class="cp-fg-value">{{ $stock->UL_STOCKS_DEMAT_ACCOUNT_REQ ?: '—' }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">ISIN</div><div class="cp-fg-value">{{ $stock->UL_STOCKS_ISIN ?: '—' }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">Founded</div><div class="cp-fg-value">{{ $founded ?: '—' }}</div></div>

          <div class="cp-fg-cell"><div class="cp-fg-label">Headquarters</div><div class="cp-fg-value green">{{ $stock->UL_STOCKS_CITY_NAME ?: '—' }}</div></div>
          <div class="cp-fg-cell">
            <div class="cp-fg-label">Website</div>
            <div class="cp-fg-value">
              @if($stock->UL_STOCKS_WEBSITE)
                <a href="{{ str_starts_with($stock->UL_STOCKS_WEBSITE, 'http') ? $stock->UL_STOCKS_WEBSITE : 'https://' . $stock->UL_STOCKS_WEBSITE }}" target="_blank" rel="noopener nofollow" style="color:var(--green-dark);">Visit <i class="bi bi-box-arrow-up-right" style="font-size:11px;"></i></a>
              @else
                —
              @endif
            </div>
          </div>
          <div class="cp-fg-cell"><div class="cp-fg-label">ROFR</div><div class="cp-fg-value">{{ $stock->UL_STOCKS_ROFR_FLAG ?: '—' }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">Company Rating</div><div class="cp-fg-value cp-stars">{{ $stars($stock->UL_STOCKS_COMP_RATING) }}</div></div>
          <div class="cp-fg-cell"><div class="cp-fg-label">Valuation Rating</div><div class="cp-fg-value cp-stars">{{ $stars($stock->UL_STOCKS_VALUATION_RATING) }}</div></div>
        </div>
      </section>

      {{-- REVENUE & PROFIT TREND --}}
      @if($revenueTrend->isNotEmpty())
        <section class="chart-section">
          <h3>Revenue &amp; Profit Trend (₹ Cr)</h3>
          <div class="chart-canvas-wrap" style="height:220px;">
            <canvas id="revenueTrendChart"></canvas>
          </div>
        </section>
      @endif

      {{-- FINANCIALS --}}
      <section class="financials-section">
        <div class="label-tag">// FINANCIALS</div>
        <h2>Financial Performance (₹ in Cr)</h2>
        <p class="sub">Income statement, balance sheet and cash flow — sourced from company filings.</p>

        @if($financials->isEmpty() && $quarterlyFin->isEmpty())
          <p class="cp-empty-note">Financial statements will be published soon.</p>
        @else
          <div class="fin-period-wrap">
            <button type="button" class="fin-period-btn cp-fin-period-btn active" data-period="yearly">Yearly</button>
            <button type="button" class="fin-period-btn cp-fin-period-btn" data-period="quarterly">Quarterly</button>
            <div class="fin-period-line"></div>
          </div>

          <div class="fin-card">
            <div class="fin-subtabs">
              <button type="button" class="fin-sub-btn cp-fin-sub-btn active" data-table="income">Income Statement</button>
              <button type="button" class="fin-sub-btn cp-fin-sub-btn" data-table="balance">Balance Sheet</button>
              <button type="button" class="fin-sub-btn cp-fin-sub-btn" data-table="cashflow">Cash Flow</button>
            </div>

            @foreach(['yearly' => $financials, 'quarterly' => $quarterlyFin] as $period => $rows)
              @php $rowsAsc = $rows->reverse()->values(); $label = $period === 'yearly' ? $yearlyLabel : $quarterlyLabel; @endphp

              {{-- Income Statement --}}
              <div class="fin-table-wrap cp-fin-panel" id="cpTbl-income-{{ $period }}" style="{{ $period === 'yearly' ? 'display:block' : 'display:none' }}">
                @if($rowsAsc->isEmpty())
                  <p class="cp-empty-note">No {{ $period }} data yet.</p>
                @else
                <div class="table-responsive">
                  <table class="fin-table">
                    <thead><tr><th>Particulars (Cr)</th>@foreach($rowsAsc as $r)<th>{{ $label($r->UL_FIN_Period_end) }}</th>@endforeach</tr></thead>
                    <tbody>
                      <tr class="section-head"><td colspan="{{ $rowsAsc->count() + 1 }}">Revenue</td></tr>
                      <tr><td>Net Sales</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_NET_SALES') }}</td>@endforeach</tr>
                      <tr><td>Other Income</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_OTHER_INCOME') }}</td>@endforeach</tr>
                      <tr class="total-row highlight"><td>Total Income</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_TOTAL_INCOME') }}</td>@endforeach</tr>
                      <tr class="section-head highlight"><td colspan="{{ $rowsAsc->count() + 1 }}">Expenses</td></tr>
                      <tr><td>Total Expenditure</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_TOTAL_EXPENDITURE') }}</td>@endforeach</tr>
                      <tr><td>Operating Profit</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_OPERATING_PROFIT') }}</td>@endforeach</tr>
                      <tr><td>Interest</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_INTEREST') }}</td>@endforeach</tr>
                      <tr><td>Depreciation</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_DEPRECIATION') }}</td>@endforeach</tr>
                      <tr><td>PBT</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_PBT') }}</td>@endforeach</tr>
                      <tr><td>Tax</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_TAX') }}</td>@endforeach</tr>
                      <tr class="total-row highlight"><td>PAT</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_PAT') }}</td>@endforeach</tr>
                      <tr class="section-head"><td colspan="{{ $rowsAsc->count() + 1 }}">Margins</td></tr>
                      <tr><td>Operating Margin</td>@foreach($rowsAsc as $r)<td>{{ $marginPct($r, 'UL_FIN_OPERATING_PROFIT', 'UL_FIN_TOTAL_INCOME') }}</td>@endforeach</tr>
                      <tr><td>Net Margin</td>@foreach($rowsAsc as $r)<td>{{ $marginPct($r, 'UL_FIN_PAT', 'UL_FIN_TOTAL_INCOME') }}</td>@endforeach</tr>
                    </tbody>
                  </table>
                </div>
                @endif
              </div>

              {{-- Balance Sheet --}}
              <div class="fin-table-wrap cp-fin-panel" id="cpTbl-balance-{{ $period }}" style="display:none">
                @if($rowsAsc->isEmpty())
                  <p class="cp-empty-note">No {{ $period }} data yet.</p>
                @else
                <div class="table-responsive">
                  <table class="fin-table">
                    <thead><tr><th>Particulars (Cr)</th>@foreach($rowsAsc as $r)<th>{{ $label($r->UL_FIN_Period_end) }}</th>@endforeach</tr></thead>
                    <tbody>
                      <tr class="section-head"><td colspan="{{ $rowsAsc->count() + 1 }}">Assets</td></tr>
                      <tr><td>Current Assets</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_CURRENT_ASSETS') }}</td>@endforeach</tr>
                      <tr><td>Non-Current Assets</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_NON_CURRENT_ASSETS') }}</td>@endforeach</tr>
                      <tr class="total-row highlight"><td>Total Assets</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_TOTAL_ASSETS') }}</td>@endforeach</tr>
                      <tr class="section-head highlight"><td colspan="{{ $rowsAsc->count() + 1 }}">Liabilities</td></tr>
                      <tr><td>Shareholder Funds</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_SHAREHOLDER_FUNDS') }}</td>@endforeach</tr>
                      <tr><td>Total Debt</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_TOTAL_DEBT') }}</td>@endforeach</tr>
                      <tr><td>Current Liabilities</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_CURRENT_LIABILITIES') }}</td>@endforeach</tr>
                      <tr><td>Non-Current Liabilities</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_NON_CURRENT_LIABILITIES') }}</td>@endforeach</tr>
                      <tr class="total-row highlight"><td>Total Liabilities</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_TOTAL_LIABILITIES') }}</td>@endforeach</tr>
                    </tbody>
                  </table>
                </div>
                @endif
              </div>

              {{-- Cash Flow --}}
              <div class="fin-table-wrap cp-fin-panel" id="cpTbl-cashflow-{{ $period }}" style="display:none">
                @if($rowsAsc->isEmpty())
                  <p class="cp-empty-note">No {{ $period }} data yet.</p>
                @else
                <div class="table-responsive">
                  <table class="fin-table">
                    <thead><tr><th>Particulars (Cr)</th>@foreach($rowsAsc as $r)<th>{{ $label($r->UL_FIN_Period_end) }}</th>@endforeach</tr></thead>
                    <tbody>
                      <tr class="section-head"><td colspan="{{ $rowsAsc->count() + 1 }}">Cash Flow Activities</td></tr>
                      <tr><td>Operating Activities</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_CASH_FLOW_FROM_OPERATING_ACTIVITIES') }}</td>@endforeach</tr>
                      <tr><td>Investing Activities</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_CASH_FLOW_FORM_INVESTING_ACTIVITIES') }}</td>@endforeach</tr>
                      <tr><td>Financing Activities</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_CASH_FLOW_FROM_FINANCING_ACTIVITIES') }}</td>@endforeach</tr>
                      <tr class="total-row highlight"><td>Free Cash Flow</td>@foreach($rowsAsc as $r)<td>{{ $crVal($r, 'UL_FIN_FREE_CASH_FLOW') }}</td>@endforeach</tr>
                    </tbody>
                  </table>
                </div>
                @endif
              </div>
            @endforeach

          </div>
        @endif
      </section>

      @include('partials.faq-accordion', ['faqs' => $overviewFaqs, 'prefix' => 'faq-overview', 'faqTitle' => 'Common Questions About ' . $stock->UL_STOCKS_COMPNAME])

      {{-- IPO ROADMAP / THESIS --}}
      @if($thesisHtml)
        <section class="about-section">
          <div class="label-tag">// IPO ROADMAP</div>
          <h2>What's Next for {{ $stock->UL_STOCKS_COMPNAME }}</h2>
          <div class="cp-about-content">{!! $thesisHtml !!}</div>
        </section>
      @endif

      @include('partials.faq-accordion', ['faqs' => $thesisFaqs, 'prefix' => 'faq-thesis', 'faqTitle' => 'IPO Roadmap FAQs'])

    </div>

    {{-- SIDEBAR --}}
    <div class="col-lg-4">
      <div class="cp-sidebar">
        <div class="buy-sell-card">
          <div class="live-label">Live Unlisted Share Price</div>
          <div class="price-display">{{ $fmtMoney($currentPrice) }}</div>
          @if($priceAsOf)
            <div class="cp-price-asof">As of {{ \Illuminate\Support\Carbon::parse($priceAsOf)->format('d M Y') }}</div>
          @endif
          @if($weekChange !== null)
            <div class="change-sm" style="color: {{ $weekChange >= 0 ? '#16A218' : '#FF3B30' }}">
              {{ $weekChange >= 0 ? '↑' : '↓' }} {{ $fmtMoney(abs($weekChange)) }} ({{ $weekChangePct }}%) this week
            </div>
          @endif

          <div class="bs-btn-row">
            <button type="button" class="btn-buy"><i class="bi bi-bag-fill"></i> Buy</button>
            <button type="button" class="btn-sell"><i class="bi bi-shield"></i> Sell</button>
          </div>

          <div class="qty-control">
            <button type="button" class="qty-btn" id="cpQtyMinus">−</button>
            <span id="cpQtyVal">{{ $stock->UL_STOCKS_LOT_SIZE ?: 1 }}</span>
            <button type="button" class="qty-btn" id="cpQtyPlus">+</button>
          </div>
          <p class="qty-min-note" id="cpQtyNote">
            Min lot: {{ $stock->UL_STOCKS_LOT_SIZE ?: 1 }} shares
            @if($currentPrice) &middot; Total: {{ $fmtMoney(($stock->UL_STOCKS_LOT_SIZE ?: 1) * $currentPrice, 0) }} @endif
          </p>

          <button type="button" class="btn-whatsapp">
            Get Quote on WhatsApp
            <span class="wa-icon"><i class="bi bi-whatsapp"></i></span>
          </button>
          <button type="button" class="btn-inquiry">Fill inquiry form instead</button>
        </div>

        @if($similarCompanies->isNotEmpty())
          <div class="stock-list-card">
            @foreach($similarCompanies as $s)
              <a href="{{ route('stocks.company', $s->UL_STOCKS_SLUG) }}" class="stock-row" style="text-decoration:none;">
                <div class="slogo">
                  @if($s->UL_STOCKS_LOGO_LINK)
                    <img src="{{ asset($s->UL_STOCKS_LOGO_LINK) }}" alt="{{ $s->UL_STOCKS_COMPNAME }}" class="img-fluid">
                  @else
                    <div style="width:36px;height:36px;border-radius:8px;background:var(--green-light);color:var(--green-dark);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">
                      {{ mb_strtoupper(mb_substr($s->UL_STOCKS_COMPNAME, 0, 1)) }}
                    </div>
                  @endif
                </div>
                <div class="sinfo">
                  <div class="sname">{{ $s->UL_STOCKS_COMPNAME }}</div>
                  <div class="smeta">{{ $s->current_price ? '₹' . number_format($s->current_price, 2) : 'Price N/A' }}</div>
                </div>
              </a>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
  var revenueTrend = @json($revenueTrend);
  var trendCanvas = document.getElementById('revenueTrendChart');
  if (trendCanvas && revenueTrend.length) {
    new Chart(trendCanvas.getContext('2d'), {
      type: 'bar',
      data: {
        labels: revenueTrend.map(function (r) { return r.label; }),
        datasets: [
          {
            type: 'bar',
            label: 'Net Sales (₹ Cr)',
            data: revenueTrend.map(function (r) { return r.netSales; }),
            backgroundColor: 'rgba(26,92,64,.18)',
            borderRadius: 4,
          },
          {
            type: 'line',
            label: 'PAT (₹ Cr)',
            data: revenueTrend.map(function (r) { return r.pat; }),
            borderColor: '#076550',
            backgroundColor: '#076550',
            borderWidth: 2.5,
            tension: .3,
            pointRadius: 4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: true, position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } },
        scales: {
          y: { grid: { color: '#f0f0f0' }, ticks: { callback: function (v) { return '₹' + v.toLocaleString(); } } },
          x: { grid: { display: false } },
        },
      },
    });
  }

  var priceHistory = @json($priceHistory);

  if (priceHistory.length) {
    var chart = null;

    function filterHistory(range) {
      if (range === 'max') return priceHistory;
      var cutoff = new Date();
      cutoff.setDate(cutoff.getDate() - parseInt(range, 10));
      return priceHistory.filter(function (p) { return new Date(p.date) >= cutoff; });
    }

    function renderChart(range) {
      var data   = filterHistory(range);
      var labels = data.map(function (p) { return p.date; });
      var prices = data.map(function (p) { return p.price; });

      if (chart) chart.destroy();
      var ctx = document.getElementById('priceChart').getContext('2d');
      chart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Price (₹)',
            data: prices,
            borderColor: '#1a5c40',
            backgroundColor: 'rgba(26,92,64,.08)',
            borderWidth: 2.5,
            tension: .35,
            fill: true,
            pointRadius: data.length > 60 ? 0 : 3,
            pointBackgroundColor: '#1a5c40'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            y: { grid: { color: '#f0f0f0' }, ticks: { callback: function (v) { return '₹' + v.toLocaleString(); } } },
            x: { grid: { display: false }, ticks: { maxTicksLimit: 8 } }
          }
        }
      });
    }

    document.querySelectorAll('#cpPeriodTabs .btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        document.querySelectorAll('#cpPeriodTabs .btn').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        renderChart(btn.dataset.range);
      });
    });

    renderChart('180');
  }

  // ── Financials: period + sub-tab switching ─────────────────
  var finPeriod = 'yearly';
  var finTable  = 'income';

  function renderFin() {
    document.querySelectorAll('.cp-fin-panel').forEach(function (el) { el.style.display = 'none'; });
    var target = document.getElementById('cpTbl-' + finTable + '-' + finPeriod);
    if (target) target.style.display = 'block';
  }

  document.querySelectorAll('.cp-fin-period-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.cp-fin-period-btn').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      finPeriod = btn.dataset.period;
      renderFin();
    });
  });

  document.querySelectorAll('.cp-fin-sub-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.cp-fin-sub-btn').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      finTable = btn.dataset.table;
      renderFin();
    });
  });

  // ── Qty stepper ──────────────────────────────────────────────
  var lot = {{ (int) ($stock->UL_STOCKS_LOT_SIZE ?: 1) }};
  var price = {{ $currentPrice !== null ? (float) $currentPrice : 'null' }};
  var qty = lot;
  var qtyEl  = document.getElementById('cpQtyVal');
  var noteEl = document.getElementById('cpQtyNote');

  function updateQty() {
    if (qtyEl) qtyEl.textContent = qty;
    if (noteEl) {
      var text = 'Min lot: ' + lot + ' shares';
      if (price !== null) {
        text += ' · Total: ₹' + (qty * price).toLocaleString('en-IN', { maximumFractionDigits: 0 });
      }
      noteEl.textContent = text;
    }
  }

  var minusBtn = document.getElementById('cpQtyMinus');
  var plusBtn  = document.getElementById('cpQtyPlus');
  if (minusBtn) minusBtn.addEventListener('click', function () { if (qty > lot) { qty -= lot; updateQty(); } });
  if (plusBtn)  plusBtn.addEventListener('click', function () { qty += lot; updateQty(); });
})();
</script>
@endpush
