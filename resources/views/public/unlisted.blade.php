@extends('layouts.app')

@section('title', 'NSE India Unlisted Shares – StockWitty')

@section('styles')
<style>
  /* ── HERO ───────────────────────────────────────────────── */
  .hero-section {
    position: relative;
    min-height: 580px;
    display: flex;
    align-items: center;
    overflow: hidden;
    background-color: #c8e8de;
  }
  .hero-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(rgba(80,130,110,0.18) 1px, transparent 1px),
      linear-gradient(90deg, rgba(80,130,110,0.18) 1px, transparent 1px);
    background-size: 52px 52px;
    z-index: 1;
  }
  .hero-section::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(195,232,218,0.55);
    z-index: 1;
  }
  .hero-inner { position: relative; z-index: 2; width: 100%; }
  .float-card { position: absolute; background: #fff; border-radius: 14px; box-shadow: 0 8px 28px rgba(0,0,0,0.13); padding: 14px 18px 12px; min-width: 200px; z-index: 4; }
  .fc-tl { top: 233px; left: 12%; transform: rotate(8deg); }
  .fc-tr { top: 126px; right: 11%; transform: rotate(-12deg); }
  .fc-br { bottom: 91px; right: 17%; transform: rotate(10deg); }
  .float-card .fc-row1 { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
  .float-card .fc-company { font-size: 13.5px; font-weight: 700; color: #1a1a1a; }
  .badge-preipo { font-size: 9px; font-weight: 700; border-radius: 4px; padding: 2px 7px; text-transform: uppercase; letter-spacing: 0.03em; }
  .badge-preipo.orange { background: #fff4e0; color: #c07800; border: 1px solid #f0c96a; }
  .float-card .fc-price { font-size: 26px; font-weight: 800; color: #1a1a1a; line-height: 1.1; }
  .float-card .fc-change { font-weight: 700; font-size: 12px; color: #1a7a5e; margin-top: 3px; }
  .hero-text { text-align: center; padding: 25px 15px; background: rgba(255,255,255,.56); backdrop-filter: blur(55px); }
  .hero-text h1 { font-size: clamp(28px, 4.5vw, 40px); font-weight: 700; color: #1a1a1a; line-height: 1; letter-spacing: -0.01em; margin-bottom: 18px; }
  .hero-text p { font-size: 13.5px; color: #1a1a1a; line-height: 1.35; max-width: 520px; margin: 0 auto 20px; }
  .btn-explore { background: #064F3E; color: #fff; border: none; padding: 13px 24px; border-radius: 10px; font-size: 13px; font-weight: 600; font-family: 'Open Sans', sans-serif; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; transition: background 0.2s, transform 0.15s; }
  .btn-explore:hover { background: #112820; color: #fff; transform: translateY(-1px); }
  @media (max-width: 768px) { .float-card { display: none; } }

  /* ── STATS BAR ──────────────────────────────────────────── */
  .stats-bar { background: #fff; padding: 52px 0 44px; }
  .stat-item { text-align: center; }
  .stat-num { font-size: clamp(36px, 5vw, 48px); font-weight: 400; color: #1a1a1a; line-height: 1; }
  .stat-label { font-size: 12px; font-weight: 500; text-transform: uppercase; color: #58595D; margin-top: 7px; }

  /* ── WHY STOCKSWITTY ────────────────────────────────────── */
  .why-section { position: relative; overflow: hidden; padding: 90px 0 100px; }
  .why-bg { position: absolute; inset: 0; background-image: url('{{ asset('img/why-stockwitty-bg.png') }}'); background-size: cover; background-position: center; z-index: 0; }
  .why-bg::after { content: ''; position: absolute; inset: 0; background: rgba(5,10,30,0.55); }
  .why-inner { position: relative; z-index: 2; }
  .why-eyebrow { font-size: 14px; font-weight: 400; letter-spacing: 0.12em; color: #E7F1EB; text-transform: uppercase; margin-bottom: 0; }
  .why-title { font-size: clamp(26px, 4vw, 30px); font-weight: 400; color: #E7F1EB; line-height: 1.2; margin-bottom: 8px; }
  .why-sub { font-size: 10px; color: #fff; margin: 0 auto; }
  .why-card { background: #fff; border-radius: 16px; padding: 20px; height: 100%; }
  .why-icon { width: 40px; height: 40px; background: rgba(7,101,80,.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
  .why-icon i { font-size: 18px; color: #1a7a5e; }
  .why-card h3 { font-size: 15px; font-weight: 700; color: #1a1a1a; margin-bottom: 5px; line-height: 1.3; }
  .why-card p { font-size: 13px; color: #111827; line-height: 1.75; margin: 0; }

  /* ── HOT SECTORS ────────────────────────────────────────── */
  .sectors-section { background: #fff; padding: 80px 0 90px; }
  .sectors-title { font-size: clamp(24px, 3.5vw, 35px); font-weight: 400; color: #1a1a1a; line-height: 1.2; margin-bottom: 10px; font-style: italic; }
  .sectors-sub { font-size: 12px; color: rgba(0,0,0,.6); line-height: 1.7; margin: 0 auto; }
  .sectors-grid { display: grid; grid-template-columns: repeat(4, 1fr); max-width: 850px; margin: 0 auto; }
  .sg-cell { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 44px 20px; text-align: center; position: relative; cursor: pointer; transition: background 0.2s; }
  .sg-cell:nth-child(-n+4) { border-bottom: 1.5px solid #c8c4be; }
  .sg-cell:not(:nth-child(4n)) { border-right: 1.5px solid #c8c4be; }
  .sg-num { font-size: 25px; font-weight: 600; color: #1a1a1a; line-height: 1; margin-bottom: 10px; }
  .sg-label { font-size: 14px; font-weight: 400; color: #000; }
  .sg-highlight { background: linear-gradient(135deg, #064F3E 0%, #115E12 67%, #D2AB67 100%); border-color: transparent !important; }
  .sg-highlight .sg-num { color: #fff; font-weight: 500; }
  .sg-highlight .sg-label { color: rgba(255,255,255,.88); }
  .sg-empty { cursor: default; }

  /* ── SC STAT CARDS ──────────────────────────────────────── */
  .sc-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
  .sc-card { border-radius: 20px; padding: 22px 20px 20px; display: flex; flex-direction: column; min-height: 260px; position: relative; overflow: hidden; }
  .sc-yellow { background: #d4f000; }
  .sc-red    { background: #ff6b5b; }
  .sc-blue   { background: #6bc5f8; }
  .sc-purple { background: #7c5cbf; }
  .sc-icon { width: 34px; height: 34px; background: rgba(255,255,255,.35); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; }
  .sc-icon-green { background: #3cb878 !important; }
  .sc-label { font-size: 11px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: #000; line-height: 1.4; margin-bottom: 10px; }
  .sc-sparkline { flex: 1; display: flex; align-items: center; margin: 4px 0 8px; }
  .sc-sparkline svg { width: 100%; height: 44px; }
  .sc-red .sc-sparkline polyline, .sc-purple .sc-sparkline polyline { stroke: rgba(255,255,255,.6); }
  .sc-blue .sc-sparkline polyline { stroke: rgba(0,50,100,.5); }
  .sc-yellow .sc-sparkline polyline { stroke: rgba(0,0,0,.3); }
  .sc-value { font-size: 25px; font-weight: 500; color: #1a1a1a; line-height: 1.1; margin-bottom: 10px; }
  .sc-footer { font-size: 12.5px; font-weight: 500; color: #064F3E; }
  .sc-red .sc-footer, .sc-purple .sc-footer { color: #064F3E; }
  .sc-up { color: #1a6b00; font-weight: 700; }

  /* ── WHO OWNS ───────────────────────────────────────────── */
  .owns-section { background: #fff; padding: 80px 0 90px; }
  .owns-title { font-size: clamp(24px, 3.5vw, 38px); font-weight: 700; color: #1a3d2e; margin-bottom: 14px; line-height: 1.2; }
  .owns-desc { font-size: 14.5px; color: #444; line-height: 1.75; max-width: 680px; margin-bottom: 0; }
  .owns-chart-wrap { background: #f5f0e8; border-radius: 16px; padding: 32px; display: flex; align-items: center; justify-content: center; min-height: 340px; }
  .owns-chart-wrap canvas { max-width: 280px; max-height: 280px; }
  .owns-legend { display: flex; flex-direction: column; gap: 0; max-height: 380px; overflow-y: auto; padding-right: 4px; }
  .owns-legend::-webkit-scrollbar { width: 4px; }
  .owns-legend::-webkit-scrollbar-thumb { background: #c8c4be; border-radius: 4px; }
  .ol-item { display: flex; align-items: center; gap: 12px; background: #f5f0e8; border-radius: 10px; padding: 13px 16px; margin-bottom: 8px; }
  .ol-dot { width: 14px; height: 14px; border-radius: 3px; flex-shrink: 0; }
  .ol-name { font-size: 13.5px; font-weight: 500; color: #2a2a2a; flex: 1; }
  .ol-pct { font-size: 13.5px; font-weight: 700; color: #1a3d2e; }

  /* ── FUNDAMENTALS ───────────────────────────────────────── */
  .fund-section { position: relative; overflow: hidden; padding: 80px 0 90px; }
  .fund-bg { position: absolute; inset: 0; background-color: #3d5a3e; background-image: url('{{ asset('img/fundament-bg.png') }}'); background-size: cover; background-position: center; z-index: 0; }
  .fund-bg::after { content: ''; position: absolute; inset: 0; background: rgba(40,65,42,.72); }
  .fund-inner { position: relative; z-index: 2; }
  .fund-card { background: #fff; border-radius: 20px; padding: 36px 36px 12px; }
  .fund-title { font-size: clamp(20px, 2.5vw, 28px); font-weight: 700; color: #1a3d2e; margin-bottom: 6px; }
  .fund-sub { font-size: 13.5px; color: rgba(0,0,0,.6); margin-bottom: 28px; }
  .fund-grid { display: grid; grid-template-columns: repeat(4, 1fr); border: 1px solid #e8e4de; border-radius: 17px; }
  .fg-cell { padding: 18px 16px 16px; border-right: 1px solid #e8e4de; border-bottom: 1px solid #e8e4de; }
  .fg-cell:nth-child(4n) { border-right: none; }
  .fg-no-border-b { border-bottom: none; }
  .fg-label { font-size: 10px; font-weight: 600; text-transform: uppercase; color: rgba(0,0,0,.6); margin-bottom: 8px; }
  .fg-value { font-size: 16px; font-weight: 600; color: #064f3e; line-height: 1.2; }
  .fg-green { color: #1a6b4a; }
  .fg-small { font-size: 13px; word-break: break-all; }

  /* ── ABOUT LIST ─────────────────────────────────────────── */
  ul { padding-left: 20px; }
  ul li { color: rgba(0,0,0,.6); margin-bottom: 10px; font-size: 15px; }

  /* ── RESPONSIVE ─────────────────────────────────────────── */
  @media (max-width: 991px) {
    .sc-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 767px) {
    .why-section { padding: 60px 0 70px; }
    .sectors-section { padding: 56px 0 64px; }
    .sectors-grid { grid-template-columns: repeat(2, 1fr); max-width: 100%; }
    .sg-cell { border-right: none !important; border-bottom: none !important; border: 1px solid #c8c4be; margin: -0.5px; padding: 32px 16px; }
    .sg-highlight { border: none !important; margin: 0; }
    .sg-empty { display: none; }
    .owns-section { padding: 56px 0 64px; }
    .owns-chart-wrap { min-height: 260px; padding: 20px; }
    .owns-legend { max-height: none; }
    .fund-section { padding: 56px 0 64px; }
    .fund-card { padding: 24px 16px 4px; border-radius: 14px; }
    .fund-grid { grid-template-columns: repeat(2, 1fr); }
    .fg-cell:nth-child(4n) { border-right: 1px solid #e8e4de; }
    .fg-cell:nth-child(2n) { border-right: none; }
    .fg-no-border-b { border-bottom: 1px solid #e8e4de; }
    .fg-cell:nth-last-child(-n+2) { border-bottom: none; }
    .sc-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .sc-card { min-height: 200px; padding: 16px 14px; }
  }
  @media (max-width: 768px) {
    .price-card .pc-top { flex-direction: column; gap: 20px; width: 100%; }
  }
</style>
@endsection

@section('content')

<!-- HERO -->
<section class="hero-section">
  <div class="float-card fc-tl">
    <div class="fc-row1">
      <div class="fc-company">Tata Capital</div>
      <span class="badge-preipo orange">PRE-IPO</span>
    </div>
    <div class="fc-price">₹1,075</div>
    <div class="fc-change"><i class="bi bi-arrow-up-short"></i>₹28 (+2.67%) today</div>
  </div>
  <div class="float-card fc-tr">
    <div class="fc-row1">
      <div class="fc-company">NSE India</div>
      <span class="badge-preipo orange">PRE-IPO</span>
    </div>
    <div class="fc-price">₹1,960</div>
    <div class="fc-change"><i class="bi bi-arrow-up-short"></i>₹40 (+2.08%) today</div>
  </div>
  <div class="float-card fc-br">
    <div class="fc-row1">
      <div class="fc-company">OYO Rooms</div>
      <span class="badge-preipo orange">PRE-IPO</span>
    </div>
    <div class="fc-price">₹52</div>
    <div class="fc-change"><i class="bi bi-arrow-up-short"></i>₹3 (+6.1%) today</div>
  </div>
  <div class="hero-inner">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="hero-text">
            <h1>Invest Smart, Stay Witty</h1>
            <p>We break down IPOs, unlisted shares, market trends, and everything in between with a fresh, witty take. Our content is sharp, fun, and built for retail investors, market geeks, and anyone tired of bland financial chatter.</p>
            <button class="btn-explore">Explore More <span><i class="bi bi-arrow-down"></i></span></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS BAR -->
<section class="stats-bar">
  <div class="container">
    <div class="row text-center g-0">
      <div class="col-4 stat-item">
        <div class="stat-num">110+</div>
        <div class="stat-label">Companies Tracked</div>
      </div>
      <div class="col-4 stat-item">
        <div class="stat-num">50L+</div>
        <div class="stat-label">Volume Facilitated</div>
      </div>
      <div class="col-4 stat-item">
        <div class="stat-num">24hr</div>
        <div class="stat-label">Demat Delivery</div>
      </div>
    </div>
  </div>
</section>

<!-- WHY STOCKSWITTY -->
<section class="why-section">
  <div class="why-bg"></div>
  <div class="container why-inner">
    <div class="row justify-content-center text-center mb-4">
      <div class="col-12 col-lg-8">
        <div class="why-eyebrow">// WHY STOCKSWITTY</div>
        <h2 class="why-title">Investing isn't easy. Reading about it should be.</h2>
        <p class="why-sub">We cut through the financial fog with insights that make you think — and smile.</p>
      </div>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-12 col-md-4">
        <div class="why-card">
          <div class="why-icon"><i class="bi bi-envelope"></i></div>
          <h3>Unlisted, but not unseen.</h3>
          <p>From OYO to NSE India, we bring shadow markets into the spotlight — with live prices, real financials, and unfiltered analysis you won't find on Moneycontrol.</p>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="why-card">
          <div class="why-icon"><i class="bi bi-grid"></i></div>
          <h3>Trends, traps, and truth bombs.</h3>
          <p>No-fluff commentary that keeps you two steps ahead. We call out red flags in DRHPs, question hype, and tell you when valuations smell funny.</p>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="why-card">
          <div class="why-icon"><i class="bi bi-record-circle"></i></div>
          <h3>Beyond numbers. Into narratives.</h3>
          <p>Because data without context is just noise. We tell you why a company matters, not just its share price — so you invest with conviction, not FOMO.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- HOT SECTORS -->
<section class="sectors-section">
  <div class="container">
    <div class="row justify-content-center text-center mb-4">
      <div class="col-12 col-lg-8">
        <h2 class="sectors-title">Hot sectors you can't trade on Zerodha (yet)</h2>
        <p class="sectors-sub">Before the IPO buzz, here's where smart money is already moving. Browse pre-IPO opportunities by sector.</p>
      </div>
    </div>
    <div class="sectors-grid">
      <div class="sg-cell"><div class="sg-num">18</div><div class="sg-label">NBFC &amp; Finance</div></div>
      <div class="sg-cell sg-highlight"><div class="sg-num">07</div><div class="sg-label">Real Estate</div></div>
      <div class="sg-cell"><div class="sg-num">12</div><div class="sg-label">Tech &amp; SaaS</div></div>
      <div class="sg-cell"><div class="sg-num">09</div><div class="sg-label">Renewables</div></div>
      <div class="sg-cell sg-empty"></div>
      <div class="sg-cell"><div class="sg-num">11</div><div class="sg-label">Health Care</div></div>
      <div class="sg-cell"><div class="sg-num">09</div><div class="sg-label">Consumers</div></div>
      <div class="sg-cell sg-empty"></div>
    </div>
  </div>
</section>

<!-- ABOUT / FOUNDER'S TAKE -->
<section class="about-section">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-8">
        <div class="label-tag">// Founder's take</div>
        <h2>The honest story of NSE India</h2>
        <p>NSE is the stock market exchange of India. It's where Reliance, TCS, HDFC Bank and 2,720 other companies trade. It processes more derivative contracts than any exchange on Earth. It has been "about to IPO" since 2016 — longer than most Silicon Valley unicorns have existed.</p>
        <p>NSE has a fully-integrated business model comprising exchange listings, trading services, clearing and settlement services, indices, market data feeds, technology solutions, and financial education offerings.</p>
        <p>FY25 revenue: ₹19,177 crore. FY25 profit: ₹12,188 crore. That's a 63% net margin. Software companies wish they had these economics.</p>
        <p>Our verdict? At ₹1,960 per share, you're buying into a 99.9% market share business at roughly 40x earnings. Expensive on paper. Cheap if the IPO actually happens by Diwali 2026.</p>

        <div class="sc-grid mt-4">
          <div class="sc-card sc-yellow">
            <div class="sc-icon">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <rect x="1" y="10" width="4" height="9" rx="1" fill="#3a3a00"/>
                <rect x="8" y="5" width="4" height="14" rx="1" fill="#3a3a00"/>
                <rect x="15" y="1" width="4" height="18" rx="1" fill="#3a3a00"/>
              </svg>
            </div>
            <div class="sc-label">TOTAL ANNUM</div>
            <div class="sc-sparkline">
              <svg viewBox="0 0 160 40" preserveAspectRatio="none">
                <polyline points="0,28 20,24 40,30 60,18 80,22 100,16 120,20 140,12 160,8" fill="none" stroke="#3a3a00" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="sc-value">₹12.55L Cr</div>
            <div class="sc-footer"><span class="sc-up">↑</span> 19.4% YoY</div>
          </div>
          <div class="sc-card sc-red">
            <div class="sc-icon">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <polyline points="2,15 6,8 10,12 14,5 18,9" fill="none" stroke="#5a0a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="sc-label">AVG 5-YR RETURN</div>
            <div class="sc-sparkline">
              <svg viewBox="0 0 160 40" preserveAspectRatio="none">
                <polyline points="0,10 20,16 40,12 60,22 80,18 100,26 120,20 140,28 160,24" fill="none" stroke="#5a0a00" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="sc-value">18.7%</div>
            <div class="sc-footer">Beats Nifty by 2.1%</div>
          </div>
          <div class="sc-card sc-blue">
            <div class="sc-icon"><span style="font-size:18px;">⚡</span></div>
            <div class="sc-label">FUNDS OUTPERFORMING BENCHMARK</div>
            <div class="sc-sparkline">
              <svg viewBox="0 0 160 40" preserveAspectRatio="none">
                <polyline points="0,22 20,26 40,18 60,28 80,20 100,24 120,16 140,22 160,14" fill="none" stroke="#003a5a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="sc-value">68/88</div>
            <div class="sc-footer">77% Success Rate</div>
          </div>
          <div class="sc-card sc-purple">
            <div class="sc-icon sc-icon-green"><span style="font-size:16px; color:#fff;">✳</span></div>
            <div class="sc-label">5 ⭐ Rated Funds (CRISIL)</div>
            <div class="sc-sparkline">
              <svg viewBox="0 0 160 40" preserveAspectRatio="none">
                <polyline points="0,30 20,22 40,28 60,16 80,24 100,18 120,26 140,14 160,20" fill="none" stroke="#1a003a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="sc-value">14</div>
            <div class="sc-footer">Highest in industry</div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="buy-sell-card">
          <div class="live-label">LIVE UNLISTED SHARE PRICE</div>
          <div class="price-display">₹1,960</div>
          <div class="change-sm">↑ &nbsp;₹40 (+2.08%) this week</div>
          <div class="bs-btn-row">
            <button class="btn-buy"><i class="bi bi-bag-fill"></i> Buy</button>
            <button class="btn-sell"><i class="bi bi-shield"></i> Sell</button>
          </div>
          <div class="qty-control">
            <button class="qty-btn">−</button>
            <span>250</span>
            <button class="qty-btn">+</button>
          </div>
          <p class="qty-min-note">Min lot: 250 shares . Total: ₹ 4,90,000</p>
          <button class="btn-whatsapp">
            Get Quote on Whatsapp
            <span class="wa-icon"><i class="bi bi-whatsapp"></i></span>
          </button>
          <button class="btn-inquiry">Fill inquiry form instead</button>
        </div>
        <div class="stock-list-card">
          <div id="stockListCarousel" class="owl-carousel">
            <div>
              <div class="stock-row">
                <div class="slogo"><img src="{{ asset('img/sbi.png') }}" alt="SBI" class="img-fluid"/></div>
                <div class="sinfo"><div class="sname">SBI</div><div class="smeta">Today &nbsp; 600+</div></div>
                <div class="sright"><i class="activity-icon bi bi-activity" style="color:var(--green-btn);"></i><span class="sup">40%</span></div>
              </div>
              <div class="stock-row">
                <div class="slogo"><img src="{{ asset('img/groww.png') }}" alt="Groww" class="img-fluid"/></div>
                <div class="sinfo"><div class="sname">Global Health</div><div class="smeta">Today &nbsp; 600+</div></div>
                <div class="sright"><i class="activity-icon bi bi-activity" style="color:#FF3B30;"></i><span class="sdown">02%</span></div>
              </div>
              <div class="stock-row">
                <div class="slogo"><img src="{{ asset('img/img-1.png') }}" alt="ZAK" class="img-fluid"/></div>
                <div class="sinfo"><div class="sname">ZAK Ventures</div><div class="smeta">Today &nbsp; 600+</div></div>
                <div class="sright"><i class="activity-icon bi bi-activity" style="color:var(--green-btn);"></i><span class="sup">40%</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PRICE MOVEMENT -->
<section class="chart-section">
  <div class="container">
    <div class="row g-5 align-items-start">
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
        <div class="label-tag mt-4">// IPO roadmap</div>
        <h2>The honest story of NSE India</h2>
        <p><strong>Where things stand today (May 2026)</strong></p>
        <ul>
          <li>January 30, 2026: SEBI grants long-awaited No Objection Certificate (NOC) after NSE pays ₹1,600 Cr settlement</li>
          <li>February 6, 2026: NSE Board formally approves IPO plan</li>
          <li>March 2026: Writ petition filed in Delhi High Court challenging NOC validity</li>
          <li>April 2026: 20+ investment banks shortlisted (Kotak, Citi, JM Financial, JP Morgan, HSBC, Morgan Stanley)</li>
          <li>Expected June 2026: DRHP filing with SEBI</li>
          <li>Expected Q3/Q4 2026: Listing on NSE and BSE</li>
        </ul>
      </div>
      <div class="col-lg-4 d-flex align-items-center justify-content-center">
        <div style="width:100%;height:280px;border:5px solid var(--teal);border-radius:24px;"></div>
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
      @foreach(['Lenskart','TATA Shares','Physicswallah','Global Health Insure','ZAK ventures ATC.','PolicyX','Groww','OYO','Reliance Retail','NSE India','Boat Lifestyle','Swiggy Unlisted'] as $stock)
      <div class="col-md-4">
        <div class="stock-card">
          <div class="logo"><img src="{{ asset('img/sbi.png') }}" class="img-fluid" alt="{{ $stock }}"></div>
          <div class="sinfo">
            <h6>{{ $stock }}</h6>
            <div class="price">₹ 909</div>
            <div class="up"><i class="activity-icon bi bi-activity" style="color:var(--green-btn);"></i> (+800) 40%</div>
          </div>
        </div>
      </div>
      @endforeach
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

<!-- FUNDAMENTALS -->
<section class="fund-section">
  <div class="fund-bg"></div>
  <div class="container fund-inner">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-9">
        <div class="fund-card">
          <h2 class="fund-title">Fundamentals</h2>
          <p class="fund-sub">Key metrics and identifiers for NSE India Limited Unlisted Shares</p>
          <div class="fund-grid">
            <div class="fg-cell"><div class="fg-label">SHARE PRICE</div><div class="fg-value fg-green">₹1,960</div></div>
            <div class="fg-cell"><div class="fg-label">LOT SIZE</div><div class="fg-value">250 shares</div></div>
            <div class="fg-cell"><div class="fg-label">52W HIGH</div><div class="fg-value">₹2,470</div></div>
            <div class="fg-cell"><div class="fg-label">52W LOW</div><div class="fg-value">₹1,705</div></div>
            <div class="fg-cell"><div class="fg-label">MARKET CAP</div><div class="fg-value">₹4.85 L Cr</div></div>
            <div class="fg-cell"><div class="fg-label">P/E RATIO</div><div class="fg-value">40.0x</div></div>
            <div class="fg-cell"><div class="fg-label">P/B RATIO</div><div class="fg-value">15.76x</div></div>
            <div class="fg-cell"><div class="fg-label">DEBT TO EQUITY</div><div class="fg-value">0.00</div></div>
            <div class="fg-cell"><div class="fg-label">ROE</div><div class="fg-value">31.7%</div></div>
            <div class="fg-cell"><div class="fg-label">BOOK VALUE</div><div class="fg-value">₹129.79</div></div>
            <div class="fg-cell"><div class="fg-label">FACE VALUE</div><div class="fg-value">₹1</div></div>
            <div class="fg-cell"><div class="fg-label">EPS (FY25)</div><div class="fg-value">₹49</div></div>
            <div class="fg-cell"><div class="fg-label">TOTAL SHARES</div><div class="fg-value">247.5 Cr</div></div>
            <div class="fg-cell"><div class="fg-label">DEPOSITORY</div><div class="fg-value">NSDL &amp; CDSL</div></div>
            <div class="fg-cell"><div class="fg-label">ISIN</div><div class="fg-value">INE721I01024</div></div>
            <div class="fg-cell"><div class="fg-label">PAN</div><div class="fg-value">AAACN1797L</div></div>
            <div class="fg-cell fg-no-border-b"><div class="fg-label">CIN</div><div class="fg-value fg-small">U67120MH1992PLC069769</div></div>
            <div class="fg-cell fg-no-border-b"><div class="fg-label">RTA</div><div class="fg-value">Link Intime</div></div>
            <div class="fg-cell fg-no-border-b"><div class="fg-label">FOUNDED</div><div class="fg-value">1992</div></div>
            <div class="fg-cell fg-no-border-b"><div class="fg-label">HEADQUARTERS</div><div class="fg-value fg-green">Mumbai</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WHO OWNS NSE -->
<section class="owns-section">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="owns-title">Who owns NSE India?</h2>
        <p class="owns-desc">NSE has a diverse shareholder base of <strong>financial institutions, foreign investors, and domestic mutual funds</strong>. No single entity owns more than 7.5%. As of March 31, 2025:</p>
      </div>
    </div>
    <div class="row align-items-center g-4 mt-2">
      <div class="col-12 col-md-6">
        <div class="owns-chart-wrap">
          <canvas id="nseDonut"></canvas>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="owns-legend">
          <div class="ol-item"><span class="ol-dot" style="background:#1a3d2e;"></span><span class="ol-name">LIC of India</span><span class="ol-pct">10.72%</span></div>
          <div class="ol-item"><span class="ol-dot" style="background:#2d6a45;"></span><span class="ol-name">SBI Group</span><span class="ol-pct">7.50%</span></div>
          <div class="ol-item"><span class="ol-dot" style="background:#3d8c5a;"></span><span class="ol-name">Stock Holding Corporation</span><span class="ol-pct">4.44%</span></div>
          <div class="ol-item"><span class="ol-dot" style="background:#5aad78;"></span><span class="ol-name">Veracity Investments (Temasek)</span><span class="ol-pct">3.18%</span></div>
          <div class="ol-item"><span class="ol-dot" style="background:#7ecfa0;"></span><span class="ol-name">SAIF II SE Investments</span><span class="ol-pct">3.00%</span></div>
          <div class="ol-item"><span class="ol-dot" style="background:#b8952a;"></span><span class="ol-name">Aranda Investments (Mauritius)</span><span class="ol-pct">2.95%</span></div>
          <div class="ol-item"><span class="ol-dot" style="background:#d4aa3a;"></span><span class="ol-name">PI Opportunities Fund</span><span class="ol-pct">2.82%</span></div>
          <div class="ol-item"><span class="ol-dot" style="background:#e8c860;"></span><span class="ol-name">Others</span><span class="ol-pct">65.39%</span></div>
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
    <p class="sub">Three statements that matters : Income statement , Balance sheet and cash flow. All numbers sourced from NSE's annual reports.</p>
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
          @foreach(['What are unlisted shares?','How do I buy NSE India unlisted shares?','Is the NSE IPO confirmed?','What is the minimum investment amount?','How are shares delivered to my demat?'] as $i => $q)
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

  // Price movement chart
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
        y: { grid: { color: '#f0f0f0' }, ticks: { font: { family: 'Open Sans', size: 11 }, callback: v => '₹' + v.toLocaleString() } },
        x: { grid: { display: false }, ticks: { font: { family: 'Open Sans', size: 11 } } }
      }
    }
  });

  // NSE ownership donut chart
  const donutCtx = document.getElementById('nseDonut').getContext('2d');
  new Chart(donutCtx, {
    type: 'doughnut',
    data: {
      labels: ['LIC of India','SBI Group','Stock Holding Corp','Veracity (Temasek)','SAIF II SE','Aranda (Mauritius)','PI Opportunities','Others'],
      datasets: [{
        data: [10.72, 7.50, 4.44, 3.18, 3.00, 2.95, 2.82, 65.39],
        backgroundColor: ['#1a3d2e','#2d6a45','#3d8c5a','#5aad78','#7ecfa0','#b8952a','#d4aa3a','#e8c860'],
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      cutout: '65%',
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed + '%' } } }
    }
  });

});
</script>
@endpush
