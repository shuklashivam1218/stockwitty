@extends('layouts.app')

@section('title', 'StockWitty – Invest Smart, Stay Witty')

@section('styles')
<style>
  /* ── HERO ───────────────────────────────────────────────── */
  .hero-section { position: relative; min-height: 100vh; overflow: hidden; display: flex; align-items: center; padding: 0; }
  .hero-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; z-index: 0; }
  .hero-content { position: relative; z-index: 2; width: 100%; padding-top: 100px; padding-bottom: 60px; }
  .hero-section h1 { font-size: 48px; font-weight: 400; line-height: 1.15; color: var(--text-dark); }
  .hero-section p { font-size: 1rem; color: var(--text-dark); line-height: 1.5; margin-top: 16px; }
  .btn-explore { background: var(--green-dark); color: #fff; border-radius: 10px; padding: 12px 24px; font-weight: 600; font-size: .9rem; margin-top: 16px; display: inline-flex; align-items: center; gap: 8px; }
  .btn-explore:hover { background: var(--green-btn); color: #fff; }

  /* ── TRUSTED BY ─────────────────────────────────────────── */
  .trusted-section { padding: 40px 0; border-top: 1px solid var(--border); }
  .trusted-section p { font-size: 18px; font-weight: 400; line-height: 24px; color: var(--text-dark); margin-bottom: 24px; }

  /* ── LIVE SHARE PRICE ───────────────────────────────────── */
  .live-section { padding: 30px 0; background: #fff; }
  .price-card { background: linear-gradient(135deg, #0d3d2b 0%, #1a5c40 60%, #1e7a50 100%); border-radius: 16px; color: #fff; padding: 28px 32px 0 32px; overflow: hidden; }
  .price-card .pc-top { display: flex; align-items: flex-start; gap: 32px; padding-bottom: 24px; }
  .price-card .pc-left { min-width: 200px; }
  .price-card .label { font-size: 15px; font-weight: 700; color: #f0f0f0; text-transform: uppercase; display: block; margin-bottom: 10px; }
  .price-card .big-price { font-size: 50px; font-weight: 400; line-height: 1; margin-bottom: 8px; }
  .price-card .change { font-size: .82rem; color: #f0f0f0; display: flex; align-items: center; gap: 4px; }
  .price-card .pc-stats { flex: 1; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0; }
  .price-card .stat-key { font-size: 12px; font-weight: 600; letter-spacing: .8px; opacity: .65; text-transform: uppercase; padding: 6px 16px 6px 0; }
  .price-card .stat-val { font-size: 12px; font-weight: 600; padding: 6px 16px 6px 0; opacity: .65; }
  .price-card .pc-divider { border: none; border-top: 1px solid rgba(255,255,255,.15); margin: 0 -32px; }
  .price-card .pc-bottom { padding: 12px 0; font-size: .72rem; display: flex; align-items: center; gap: 6px; }
  .price-card .pc-dot { width: 7px; height: 7px; border-radius: 50%; background: #f0f0f0; display: inline-block; flex-shrink: 0; }
  .world-map-area { position: relative; padding: 20px 0; }
  .world-map-area img { width: 100%; max-width: 480px; opacity: .25; }

  /* ── FEATURES ───────────────────────────────────────────── */
  .features-section { padding: 50px 0; background: #fff; }
  .feature-card { padding: 14px 12px; border-left: 2px solid #D4D4D3; background: #fff; transition: background .2s; cursor: default; }
  .feature-card:hover, .feature-card.active { background: var(--green-light); border-left: 2px solid #076550; box-shadow: 0 4px 22.3px 0 rgba(0,0,0,.25); }
  .feature-card .icon-wrap { width: 32px; height: 32px; border-radius: 7px; background: transparent; display: flex; align-items: flex-start; justify-content: center; padding-top: 2px; flex-shrink: 0; }
  .feature-card .icon-wrap i { color: var(--green-dark); font-size: 24px; }
  .feature-card h6 { font-size: .88rem; font-weight: 700; margin-bottom: 4px; color: var(--text-dark); }
  .feature-card p { font-size: .8rem; color: #555; line-height: 1.6; margin: 0; }
  .btn-load-more { background: linear-gradient(90deg, #0C7031 0%, #11F1C4 100%); color: #fff; border: none; border-radius: 10px; padding: 12px 32px; font-weight: 600; font-size: .9rem; }

  /* ── MEDIA SPLIT BANNER ─────────────────────────────────── */
  .media-split { display: grid; grid-template-columns: 1fr 1fr; min-height: 420px; }
  .media-split .panel { position: relative; overflow: hidden; min-height: 420px; }
  .media-split .panel img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: block; filter: brightness(.55); }
  .media-split .panel .overlay-bottom { position: absolute; bottom: 32px; left: 32px; right: 32px; color: #fff; }
  .media-split .panel .overlay-top { position: absolute; top: 36px; left: 32px; right: 32px; color: #fff; }
  .media-split .panel .overlay-bottom p,
  .media-split .panel .overlay-top p { font-size: 22px; font-weight: 600; line-height: 1.5; margin: 0; }
  .media-split .panel .chip { display: inline-block; background: linear-gradient(90deg, #0C7031 0%, #11F1C4 100%); color: #fff; border-radius: 6px; padding: 10px 14px; font-size: .78rem; font-weight: 700; }
  .bolt { font-size: 1.1rem; color: #f5c542; line-height: 1; }

  @media (max-width: 768px) {
    .hero-section h1 { font-size: 2rem; }
    .media-split { grid-template-columns: 1fr; }
    .price-card .pc-top { flex-direction: column; gap: 20px; width: 100%; }
  }
</style>
@endsection

@section('content')

<!-- HERO -->
<section class="hero-section">
  <img class="hero-bg" src="{{ asset('img/hero-bg-final.png') }}" alt="Hero Background"/>
  <div class="container hero-content">
    <div class="row">
      <div class="col-lg-6 col-md-7">
        <h1>Invest Smart, Stay Witty</h1>
        <p>We break down IPOs, unlisted shares, market trends, and everything in between with a fresh, witty take. Our content is sharp, fun, and built for retail investors, market geeks, and anyone tired of bland financial chatter.</p>
        <a href="{{ url('/unlisted') }}" class="btn-explore">Explore unlisted share <i class="bi bi-arrow-up-right"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- TRUSTED BY -->
<section class="trusted-section text-center">
  <div class="container">
    <p>Trusted by 45M+ users</p>
    <div class="row">
      <div class="col-md-10 mx-auto">
        <img src="{{ asset('img/trust.png') }}" class="img-fluid" alt="Trusted by brands">
      </div>
    </div>
  </div>
</section>

@php $topStock = $topStocks->first(); @endphp

<!-- LIVE SHARE PRICE -->
<section class="live-section">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="price-card mb-4">
          <div class="pc-top">
            <div class="pc-left">
              <span class="label">{{ $topStock->name ?? 'LIVE UNLISTED SHARE PRICE' }}</span>
              <div class="big-price">{{ $topStock && $topStock->price ? '₹' . number_format($topStock->price, 2) : '₹ —' }}</div>
              <div class="change">{{ $topStock->industry ?? 'Top unlisted share by market cap' }}</div>
            </div>
            <div class="pc-stats">
              <div class="stat-key mt-4">MARKET CAP</div>
              <div class="stat-val mt-4">{{ $topStock && $topStock->mcap ? '₹' . $topStock->mcap . ' Cr' : '—' }}</div>
              <div class="stat-key mt-4">P/E RATIO</div>
              <div class="stat-key">TOTAL COMPANIES TRACKED</div>
              <div class="stat-val">{{ $totalStocks }}</div>
              <div class="stat-val">{{ $topStock->pe ?? '—' }}</div>
              <div class="stat-key">TOTAL MARKET CAP</div>
              <div class="stat-val">₹{{ number_format($totalMcap) }} Cr</div>
              <div class="stat-key"></div>
            </div>
          </div>
          <hr class="pc-divider"/>
          <div class="pc-bottom">
            <span class="pc-dot"></span>
            Updated Daily
          </div>
        </div>
        <div class="">
          <img src="{{ asset('img/map-bg.png') }}" class="img-fluid" alt="World map">
        </div>
      </div>
      <div class="col-lg-4">
        @if($topStock)
        <div class="buy-sell-card">
          <div class="live-label">{{ $topStock->name }}</div>
          <div class="price-display">{{ $topStock->price ? '₹' . number_format($topStock->price, 2) : '—' }}</div>
          <div class="bs-btn-row">
            <button class="btn-buy" type="button"><i class="bi bi-bag-fill"></i> Buy</button>
            <button class="btn-sell" type="button"><i class="bi bi-shield"></i> Sell</button>
          </div>
          <a href="{{ route('stocks.company', $topStock->slug) }}" class="btn-whatsapp" style="text-decoration:none;">
            View Full Details
          </a>
        </div>
        @endif
        <div class="stock-list-card">
          @forelse($topStocks->skip(1)->take(4) as $s)
            <a href="{{ route('stocks.company', $s->slug) }}" class="stock-row" style="text-decoration:none;">
              <div class="slogo">
                @if($s->logo)
                  <img src="{{ asset($s->logo) }}" alt="{{ $s->name }}" class="img-fluid"/>
                @else
                  <div style="width:36px;height:36px;border-radius:8px;background:var(--green-light);color:var(--green-dark);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">
                    {{ mb_strtoupper(mb_substr($s->name, 0, 1)) }}
                  </div>
                @endif
              </div>
              <div class="sinfo">
                <div class="sname">{{ $s->name }}</div>
                <div class="smeta">{{ $s->industry ?? '' }}</div>
              </div>
              <div class="sright">
                <span class="sup">{{ $s->price ? '₹' . number_format($s->price, 2) : '—' }}</span>
              </div>
            </a>
          @empty
            <p class="sub" style="margin:0;padding:8px;">More companies coming soon.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section class="about-section">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-8">
        <div class="label-tag">// ABOUT</div>
        <h2>About STOCKWITTY</h2>
        <p>The National Stock Exchange of India Ltd. (NSE) is the leading stock exchange in India and the 4th largest in the world by number of equity trades, according to the World Federation of Exchanges (WFE) report. NSE pioneered electronic screen-based trading in 1994 and was the first fully-dematerialized electronic exchange in India.</p>
        <p>NSE has a fully-integrated business model comprising exchange listings, trading services, clearing and settlement services, indices, market data feeds, technology solutions, and financial education offerings. NSE also oversees compliance by trading and clearing members and listed companies with the rules and regulations of the exchange.</p>
        <p>Incorporated in 1992, NSE was recognized as a stock exchange by SEBI in April 1993 and commenced operations in 1994 with the launch of the wholesale debt market. Today, NSE serves 11.3 crore unique investors and hosts 2,720 listed companies.</p>
      </div>
      <div class="col-lg-4">
        <img src="{{ asset('img/img-2.png') }}" class="img-fluid" alt="About StockWitty">
      </div>
    </div>
  </div>
</section>

<!-- PRICE MOVEMENT CHART -->
<section class="chart-section">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3>Price Movement</h3>
          <div class="period-tabs">
            <button class="btn">1M</button>
            <button class="btn active">6M</button>
            <button class="btn">1Y</button>
            <button class="btn">3Y</button>
            <button class="btn">5Y</button>
            <button class="btn">Max</button>
          </div>
        </div>
        <div class="chart-canvas-wrap">
          <canvas id="priceChart"></canvas>
        </div>
      </div>
      <div class="col-lg-4 d-flex align-items-center justify-content-center">
        <div style="width:100%;height:280px;border:5px solid var(--teal);border-radius:24px;"></div>
      </div>
    </div>
  </div>
</section>

<!-- SMART INVESTMENT / FEATURES -->
<section class="features-section">
  <div class="container">
    <h2>Smart Investment Online</h2>
    <p class="sub">Investing Isn't Easy. Reading About It Should Be</p>
    <div class="row">
      <div class="col-lg-8">
        <div class="row g-0 align-items-start">
          <div class="col-lg-6 col-md-6 pe-lg-4">
            <div class="feature-card mb-2">
              <div class="d-flex align-items-start gap-2">
                <div class="icon-wrap flex-shrink-0"><i class="bi bi-funnel"></i></div>
                <div><h6>Income and expenses tracker</h6><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>
              </div>
            </div>
            <div class="feature-card mb-2">
              <div class="d-flex align-items-start gap-2">
                <div class="icon-wrap flex-shrink-0"><i class="bi bi-stars"></i></div>
                <div><h6>Income and expenses tracker</h6><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>
              </div>
            </div>
            <div class="feature-card mb-2">
              <div class="d-flex align-items-start gap-2">
                <div class="icon-wrap flex-shrink-0"><i class="bi bi-lightning-charge"></i></div>
                <div><h6>Income and expenses tracker</h6><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>
              </div>
            </div>
            <div class="feature-card mb-2">
              <div class="d-flex align-items-start gap-2">
                <div class="icon-wrap flex-shrink-0"><i class="bi bi-exclamation-triangle"></i></div>
                <div><h6>Income and expenses tracker</h6><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>
              </div>
            </div>
            <div class="feature-card mb-2">
              <div class="d-flex align-items-start gap-2">
                <div class="icon-wrap flex-shrink-0"><i class="bi bi-bar-chart-line"></i></div>
                <div><h6>Income and expenses tracker</h6><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 ps-lg-2">
            <div class="feature-card mb-2">
              <div class="d-flex align-items-start gap-2">
                <div class="icon-wrap flex-shrink-0"><i class="bi bi-building"></i></div>
                <div><h6>Income and expenses tracker</h6><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>
              </div>
            </div>
            <div class="feature-card mb-2">
              <div class="d-flex align-items-start gap-2">
                <div class="icon-wrap flex-shrink-0"><i class="bi bi-person-check"></i></div>
                <div><h6>Income and expenses tracker</h6><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>
              </div>
            </div>
            <div class="feature-card mb-2">
              <div class="d-flex align-items-start gap-2">
                <div class="icon-wrap flex-shrink-0"><i class="bi bi-grid-3x3-gap"></i></div>
                <div><h6>Income and expenses tracker</h6><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>
              </div>
            </div>
            <div class="text-center mt-4 pt-2">
              <button class="btn-load-more">Load More</button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div style="width:100%;height:480px;border:5px solid var(--teal);border-radius:24px;"></div>
      </div>
    </div>
  </div>
</section>

<!-- MEDIA SPLIT BANNER -->
<section>
  <div class="container-fluid px-0">
    <div class="media-split">
      <div class="panel">
        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&q=80" alt="DRHP"/>
        <div class="overlay-text overlay-bottom">
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut</p>
          <span class="chip mt-2 d-inline-block">DRHP Filed</span>
        </div>
      </div>
      <div class="panel">
        <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&q=80" alt="Media Coverage"/>
        <div class="overlay-text overlay-top">
          <span class="bolt d-block mb-2">⚡</span>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut</p>
          <span class="chip media d-inline-block">Media Coverage</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FINANCIALS -->
<section class="financials-section">
  <div class="container">
    <div class="label-tag">// FINANCIALS</div>
    <h2>Financial Performance in CR</h2>
    <p class="sub">Three statements that matters : Income statement , Balance sheet and cash flow.</p>
    <div class="fin-period-wrap">
      <button class="fin-period-btn active" data-period="yearly">Yearly</button>
      <button class="fin-period-btn" data-period="quarterly">Quarterly</button>
      <div class="fin-period-line"></div>
    </div>
    <div class="fin-card">
      <div class="fin-subtabs">
        <button class="fin-sub-btn active" data-table="income">Income Statement</button>
        <button class="fin-sub-btn" data-table="balance">Balance Sheet</button>
        <button class="fin-sub-btn" data-table="cashflow">Cash Flow</button>
      </div>
      <div class="fin-table-wrap active" id="tbl-income">
        <div class="table-responsive">
          <table class="fin-table">
            <thead><tr><th width="40%">Particulars (CR)</th><th width="15%">FY22</th><th width="15%">FY23</th><th width="15%">FY24</th><th width="15%">FY25</th></tr></thead>
            <tbody>
              <tr class="section-head"><td colspan="5">Revenue</td></tr>
              <tr><td>Revenue from operations</td><td>6,202</td><td>8,926</td><td>12,285</td><td>15,491</td></tr>
              <tr><td>Other Income</td><td>650</td><td>674</td><td>1,654</td><td>2,036</td></tr>
              <tr class="total-row highlight"><td>Total Revenue</td><td>6,852</td><td>11,856</td><td>16,434</td><td>19,177</td></tr>
              <tr class="section-head highlight"><td colspan="5">Expenses</td></tr>
              <tr><td>Employee Benefits Expenses</td><td>352</td><td>482</td><td>632</td><td>815</td></tr>
              <tr><td>Other Expenses</td><td>650</td><td>674</td><td>1,654</td><td>2,036</td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="fin-table-wrap" id="tbl-balance">
        <div class="table-responsive">
          <table class="fin-table">
            <thead><tr><th width="40%">Particulars (CR)</th><th width="15%">Q1 FY23</th><th width="15%">Q2 FY23</th><th width="15%">Q3 FY23</th><th width="15%">Q4 FY23</th></tr></thead>
            <tbody>
              <tr class="section-head"><td colspan="5">Assets</td></tr>
              <tr><td>Total Fixed Assets</td><td>4,100</td><td>4,250</td><td>4,380</td><td>4,500</td></tr>
              <tr><td>Current Assets</td><td>2,300</td><td>2,450</td><td>2,600</td><td>2,750</td></tr>
              <tr class="total-row highlight"><td>Total Assets</td><td>6,400</td><td>6,700</td><td>6,980</td><td>7,250</td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="fin-table-wrap" id="tbl-cashflow">
        <div class="table-responsive">
          <table class="fin-table">
            <thead><tr><th width="40%">Particulars (CR)</th><th width="15%">Q1 FY23</th><th width="15%">Q2 FY23</th><th width="15%">Q3 FY23</th><th width="15%">Q4 FY23</th></tr></thead>
            <tbody>
              <tr class="section-head"><td colspan="5">Operating Activities</td></tr>
              <tr><td>Net Profit After Tax</td><td>980</td><td>1,100</td><td>1,250</td><td>1,400</td></tr>
              <tr><td>Depreciation</td><td>120</td><td>125</td><td>130</td><td>135</td></tr>
              <tr class="total-row highlight"><td>Net Cash from Operations</td><td>1,100</td><td>1,225</td><td>1,380</td><td>1,535</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- NEW ARRIVALS -->
<section class="arrivals-section">
  <div class="container">
    <div class="label-tag text-center mb-2">NEW</div>
    <h2 class="text-center mb-2">New Arrivals</h2>
    <p class="sub text-center mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed</p>
    <div class="filter-tags">
      <button class="tag active">All</button>
      <button class="tag">Pre-IPO</button>
      <button class="tag">DHRP</button>
      <button class="tag">Unicorn</button>
      <button class="tag">Trending</button>
    </div>
    <div class="row g-3">
      @forelse($topStocks as $s)
      <div class="col-md-4">
        <a href="{{ route('stocks.company', $s->slug) }}" class="stock-card" style="text-decoration:none;">
          <div class="logo">
            @if($s->logo)
              <img src="{{ asset($s->logo) }}" class="img-fluid" alt="{{ $s->name }}">
            @else
              <div style="width:42px;height:42px;border-radius:8px;background:var(--green-light);color:var(--green-dark);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;">
                {{ mb_strtoupper(mb_substr($s->name, 0, 1)) }}
              </div>
            @endif
          </div>
          <div class="sinfo">
            <h6>{{ $s->name }}</h6>
            <div class="price">{{ $s->price ? '₹ ' . number_format($s->price, 2) : 'Price N/A' }}</div>
            @if($s->mcap)
              <div class="up"><i class="activity-icon bi bi-activity" style="color:var(--green-btn);"></i> Mcap ₹{{ $s->mcap }} Cr</div>
            @endif
          </div>
        </a>
      </div>
      @empty
        <p class="text-center sub">New listings will appear here soon.</p>
      @endforelse
    </div>
  </div>
</section>

<!-- HOW TO BUY -->
<section class="howtobuy-section">
  <div class="container">
    <h5>How to buy</h5>
    <h2>unlisted shares</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="step-num">01</div>
        <div class="step-title">Submit KYC</div>
        <p class="step-desc">Share your CML copy + PAN + Cancelled Cheque + Aadhar for verification. Takes 30 minutes.</p>
      </div>
      <div class="col-md-4">
        <div class="step-num">02</div>
        <div class="step-title">Transfer Payment</div>
        <p class="step-desc">Transfer to verified StocksWitty company account (never personal). UPI, NEFT, RTGS accepted.</p>
      </div>
      <div class="col-md-4">
        <div class="step-num">03</div>
        <div class="step-title">Receive Shares</div>
        <p class="step-desc">Shares credited to your demat (CDSL or NSDL) on the same day. Independent ISIN verification available.</p>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="faq-section">
  <div class="container">
    <div class="faq-heading-row">
      <div class="faq-big-wrap"><div class="faq-big">COMMON<br/>QUESTIONS</div></div>
      <div class="faq-label-wrap"><p class="faq-sub">SOME QUESTIONS<br/>PEOPLE USUALLY ASK</p></div>
    </div>
    <div class="row">
      <div class="col-md-10 mx-auto">
        <div class="faq-list">
          @foreach(['What are unlisted shares?','How do I buy unlisted shares?','Is it safe to invest in unlisted shares?','What is the minimum investment?','How long does delivery take?'] as $i => $q)
          <div class="faq-item">
            <button class="faq-btn" data-target="faq{{ $i+1 }}">
              <span>{{ strtoupper($q) }}</span>
              <span class="faq-icon">+</span>
            </button>
            <div class="faq-body" id="faq{{ $i+1 }}">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="testimonials-section">
  <div class="container">
    <div id="testiCarousel" class="owl-carousel">
      @foreach([['Sarah Johnson','Marketing Director, TechNova Inc.'],['Michael Chen','CEO, Horizon Startups'],['Emily Rodriguez','Product Manager, Global Brands Ltd.'],['Raj Mehta','Founder, FinTech Ventures']] as [$name, $role])
      <div class="testi-card">
        <div class="quote"><img src="{{ asset('img/quotes-icon.png') }}" class="img-fluid" alt="quotes"></div>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
        <div class="author">
          <div class="author-avatar"><img src="{{ asset('img/testi-writer-img.png') }}" class="img-fluid" alt="{{ $name }}"></div>
          <div><div class="name">{{ $name }}</div><div class="role">{{ $role }}</div></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
$(document).ready(function(){
  const ctx = document.getElementById('priceChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Dec','Jan','Feb','Mar','Apr','May'],
      datasets: [{
        label: 'Price (₹)',
        data: [2180, 2360, 2490, 2080, 1960, 1990],
        borderColor: '#1a5c40',
        backgroundColor: 'rgba(26,92,64,.08)',
        borderWidth: 2.5,
        tension: .4,
        fill: true,
        pointRadius: 5,
        pointBackgroundColor: '#1a5c40'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: {
          grid: { color: '#f0f0f0' },
          ticks: { font: { family: 'Open Sans', size: 11 }, callback: v => '₹' + v.toLocaleString() }
        },
        x: {
          grid: { display: false },
          ticks: { font: { family: 'Open Sans', size: 11 } }
        }
      }
    }
  });
});
</script>
@endpush
