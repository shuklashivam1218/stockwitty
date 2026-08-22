@extends('layouts.app')

@section('title', ($page->CMS_PAGE_TITLE ?? 'Disclaimer') . ' – StockWitty')

@section('styles')
<style>
/* Uses the site-wide palette from css/app.css (--green-dark, --teal, --beige, --text-dark, --text-muted, --border) */
html{scroll-behavior:smooth;scroll-padding-top:90px;}
.doc-hero{background:#fff;padding:clamp(32px,5vw,56px) 0 clamp(20px,3vw,32px);border-bottom:1px solid var(--border);}
.doc-eyebrow{display:inline-flex;align-items:center;gap:7px;padding:5px 14px;background:var(--green-light);border-radius:20px;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--green-dark);margin-bottom:16px;}
.doc-hero h1{font-size:clamp(28px,4vw,40px);font-weight:600;line-height:1.15;color:var(--text-dark);margin-bottom:12px;}
.doc-hero .lede{font-size:clamp(14px,1.2vw,16px);color:var(--text-muted);max-width:720px;line-height:1.6;}
.doc-meta{display:flex;gap:24px;flex-wrap:wrap;margin-top:20px;font-size:13px;color:var(--text-muted);}
.doc-meta strong{color:var(--green-dark);font-weight:600;}
.doc-layout{background:#fff;display:grid;grid-template-columns:240px 1fr;gap:48px;padding:40px 0 72px;color:var(--text-dark);line-height:1.7;}
.doc-toc{position:sticky;top:96px;align-self:start;max-height:calc(100vh - 110px);overflow-y:auto;}
.doc-toc h4{font-size:11px;font-weight:700;letter-spacing:0.8px;text-transform:uppercase;color:var(--text-muted);margin-bottom:12px;}
.doc-toc ul{list-style:none;margin:0;padding:0;}
.doc-toc li{margin-bottom:2px;}
.doc-toc a{display:block;padding:7px 12px;font-size:13px;color:var(--text-dark);text-decoration:none;border-left:2px solid transparent;border-radius:0 6px 6px 0;transition:all 0.15s;}
.doc-toc a:hover{background:var(--green-light);color:var(--green-dark);border-left-color:var(--green-dark);}
.doc-body{max-width:760px;}
.doc-body img{display:block;max-width:100%;height:auto;border-radius:8px;}
.doc-body h2{font-size:clamp(20px,2.2vw,25px);font-weight:600;color:var(--green-dark);margin:36px 0 12px;scroll-margin-top:90px;}
.doc-body h2:first-child{margin-top:0;}
.doc-body h3{font-size:clamp(15px,1.5vw,17px);font-weight:600;color:var(--text-dark);margin:22px 0 10px;}
.doc-body p{margin-bottom:15px;font-size:15px;color:var(--text-dark);}
.doc-body ul,.doc-body ol{margin:0 0 16px 22px;}
.doc-body li{margin-bottom:8px;font-size:15px;}
.doc-body ul li::marker,.doc-body ol li::marker{color:var(--green-dark);}
.doc-body strong{color:var(--text-dark);font-weight:700;}
.doc-body a{color:var(--green-dark);text-decoration:underline;text-underline-offset:2px;font-weight:500;}
.doc-body a:hover{color:var(--green-btn);}
.callout{border-radius:10px;padding:16px 20px;margin:22px 0;font-size:14px;line-height:1.6;}
.callout-label{font-size:11px;font-weight:700;letter-spacing:0.5px;text-transform:uppercase;margin-bottom:6px;display:flex;align-items:center;gap:8px;}
.callout.warning{background:#fdecea;border-left:4px solid #dc3545;color:var(--text-dark);}
.callout.warning .callout-label{color:#dc3545;}
.callout.info{background:#e7f0fb;border-left:4px solid #1565c0;color:var(--text-dark);}
.callout.info .callout-label{color:#1565c0;}
.callout.success{background:var(--green-light);border-left:4px solid var(--green-dark);color:var(--text-dark);}
.callout.success .callout-label{color:var(--green-dark);}
.callout.gold{background:var(--beige);border-left:4px solid #a47f25;color:var(--text-dark);}
.callout.gold .callout-label{color:#8a6818;}
.doc-table{width:100%;border-collapse:collapse;margin:20px 0;font-size:14px;border-radius:10px;overflow:hidden;border:1px solid var(--border);}
.doc-table thead{background:var(--green-dark);color:#fff;}
.doc-table th{padding:12px 16px;text-align:left;font-size:12px;font-weight:600;letter-spacing:0.4px;text-transform:uppercase;}
.doc-table td{padding:12px 16px;border-bottom:1px solid var(--border);background:#fff;vertical-align:top;}
.doc-table tr:last-child td{border-bottom:none;}
.contact-block{background:#fff;border:1px solid var(--border);border-radius:16px;padding:22px;margin:24px 0;}
.contact-block h4{font-size:16px;font-weight:600;color:var(--text-dark);margin-bottom:12px;}
.contact-row{display:flex;gap:10px;margin-bottom:8px;font-size:14px;}
.contact-row strong{min-width:140px;color:var(--green-dark);}
@media(max-width:900px){.doc-layout{grid-template-columns:1fr;gap:24px;}.doc-toc{position:relative;top:0;max-height:none;background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px;}}
</style>
@endsection

@section('content')

<section class="doc-hero">
  <div class="container">
    <span class="doc-eyebrow">⚠️ Disclaimer</span>
    <h1>{{ $page->CMS_PAGE_TITLE }}</h1>
    <p class="lede">{{ $page->CMS_PAGE_DESCRIPTION }}</p>
    <div class="doc-meta">
      <span><strong>Last updated:</strong> June 2026</span>
      <span><strong>Applies to:</strong> StocksWitty.com &amp; all sub-domains</span>
    </div>
  </div>
</section>

<div class="container">
  <div class="doc-layout">
    <aside class="doc-toc">
      <h4>On this page</h4>
      <ul id="docTocList"></ul>
    </aside>
    <main class="doc-body" id="docBody">

<div class="callout warning">
<div class="callout-label">⚠️ Read this before you invest</div>
Investments in securities markets are subject to market risks. Read all the related documents carefully before investing. The value of investments can go down as well as up, and you may get back less than you invested. StocksWitty does not guarantee any returns.
</div>

{!! $page->CMS_PAGE_CONTENT !!}

    </main>
  </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
  var body    = document.getElementById('docBody');
  var tocList = document.getElementById('docTocList');
  if (!body || !tocList) return;

  var headings = body.querySelectorAll('h2');
  var seen = {};
  tocList.innerHTML = '';

  headings.forEach(function (h2, i) {
    var label = h2.textContent.trim();
    var base  = label.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-+|-+$)/g, '') || ('section-' + (i + 1));
    var id    = h2.id || base;
    var n     = 1;
    while (seen[id] || (document.getElementById(id) && document.getElementById(id) !== h2)) {
      id = base + '-' + (n++);
    }
    seen[id] = true;
    h2.id = id;

    var li = document.createElement('li');
    var a  = document.createElement('a');
    a.href = '#' + id;
    a.textContent = (i + 1) + '. ' + label;
    li.appendChild(a);
    tocList.appendChild(li);
  });

  // Explicit smooth-scroll on click — don't rely solely on CSS scroll-behavior,
  // which some browsers/OS settings (reduced-motion) silently ignore.
  tocList.addEventListener('click', function (e) {
    var link = e.target.closest('a[href^="#"]');
    if (!link) return;
    var target = document.getElementById(link.getAttribute('href').slice(1));
    if (!target) return;
    e.preventDefault();
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    history.pushState(null, '', link.getAttribute('href'));
  });
})();
</script>
@endpush
