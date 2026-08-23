@extends('layouts.sw')

@section('title', $stock->UL_STOCKS_COMPNAME . ' Unlisted Share Price ₹' . number_format($company['price']) . ' | StockWitty')
@section('description', $stock->UL_STOCKS_COMPNAME . ' unlisted share price, fundamentals, financials and how to buy. WittyScore ' . number_format($company['wittyScore'], 1) . '/10. Distributor, not investment advice.')

@php
$sections = [
    ['id' => 'price-chart', 'label' => 'Price Chart'],
    ['id' => 'about', 'label' => 'About'],
    ['id' => 'fundamentals', 'label' => 'Fundamentals'],
];
if ($insight?->UL_CI_FOUNDERS_VERDICT) { $sections[] = ['id' => 'founders-take', 'label' => "Founder's Take"]; }
$sections[] = ['id' => 'how-to-buy', 'label' => 'How to Buy'];
$sections[] = ['id' => 'financials', 'label' => 'Financials'];
if ($insight?->UL_CI_IPO_TIMELINE) { $sections[] = ['id' => 'ipo-roadmap', 'label' => 'IPO Roadmap']; }
if ($overviewFaqs->isNotEmpty()) { $sections[] = ['id' => 'faq', 'label' => 'FAQ']; }

$fundamentalCells = array_values(array_filter([
    ['Share Price', '₹' . number_format($company['price'], 2)],
    ['Lot Size', number_format($company['lot']) . ' shares'],
    $high52w ? ['52W High', '₹' . number_format($high52w, 2)] : null,
    $low52w ? ['52W Low', '₹' . number_format($low52w, 2)] : null,
    $marketCap ? ['Market Cap', '₹' . number_format($marketCap, 1) . ' Cr'] : null,
    $peRatio ? ['P/E', number_format($peRatio, 1) . 'x'] : null,
    $pbRatio ? ['P/B', number_format($pbRatio, 2) . 'x'] : null,
    $debtToEquity !== null ? ['Debt-to-Equity', number_format($debtToEquity, 2)] : null,
    $roe !== null ? ['ROE', number_format($roe, 1) . '%'] : null,
    $bookValue ? ['Book Value', '₹' . number_format($bookValue, 2)] : null,
    $eps !== null ? ['EPS', '₹' . number_format($eps, 2)] : null,
    $numShares ? ['Total Shares', number_format($numShares / 10000000, 1) . ' Cr'] : null,
    $stock->UL_STOCKS_ISIN ? ['ISIN', $stock->UL_STOCKS_ISIN] : null,
    $stock->UL_STOCKS_DEMAT_ACCOUNT_REQ ? ['Depository', $stock->UL_STOCKS_DEMAT_ACCOUNT_REQ] : null,
    ($stock->UL_STOCKS_INC_MONTH || $stock->UL_STOCKS_INC_YEAR) ? ['Founded', trim(($stock->UL_STOCKS_INC_MONTH ?? '') . ' ' . ($stock->UL_STOCKS_INC_YEAR ?? ''))] : null,
    $stock->UL_STOCKS_CITY_NAME ? ['HQ', $stock->UL_STOCKS_CITY_NAME] : null,
]));

$steps = [
    ['n' => '01', 'icon' => 'file-check-2', 'title' => 'Submit KYC', 'body' => 'Share your CML copy + PAN + Cancelled Cheque + Aadhaar for verification. Takes ~30 minutes.'],
    ['n' => '02', 'icon' => 'landmark', 'title' => 'Transfer Payment', 'body' => 'Transfer to the verified StockWitty company account (never personal). UPI, NEFT, RTGS accepted.'],
    ['n' => '03', 'icon' => 'wallet', 'title' => 'Receive Shares', 'body' => 'Shares credited to your demat (CDSL or NSDL) the same day. Independent ISIN verification available.'],
];

$badges = [
    ['icon' => 'star', 'title' => 'Trustpilot', 'sub' => 'Read our reviews'],
    ['icon' => 'badge-check', 'title' => 'Google Reviews', 'sub' => 'Rated by investors'],
    ['icon' => 'shield-check', 'title' => 'ISIN Verified', 'sub' => 'CDSL / NSDL demat'],
    ['icon' => 'check-circle-2', 'title' => 'CA Reviewed', 'sub' => 'Data checked for accuracy'],
    ['icon' => 'message-circle', 'title' => 'Human Support', 'sub' => 'Real people, no bots'],
];

$ipoTimeline = \App\Models\UnlistedCompanyInsight::parsePairs($insight?->UL_CI_IPO_TIMELINE);
$ipoFacts    = \App\Models\UnlistedCompanyInsight::parsePairs($insight?->UL_CI_IPO_FACTS);
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Unlisted Shares', 'href' => '/unlisted-shares/'], ['label' => $stock->UL_STOCKS_COMPNAME]]" />
    </div>

    <main>
        <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
            <div class="flex items-start gap-3 rounded-2xl border border-dashed border-amber-500/60 bg-amber-50 p-4 text-sm text-amber-900">
                <x-sw.icon name="alert-triangle" class="mt-0.5 size-4 shrink-0" />
                <p><strong>Not investment advice —</strong> figures on this page should be verified against official filings before you invest.</p>
            </div>
        </div>

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[1.35fr_1fr]">
                <div>
                    <x-sw.reveal>
                        <div class="flex items-start gap-4">
                            <span class="grid size-16 shrink-0 place-items-center rounded-2xl bg-primary text-xl font-bold text-primary-foreground shadow-soft">{{ $company['initials'] }}</span>
                            <div>
                                <h1 class="text-2xl leading-tight font-bold text-foreground sm:text-4xl">{{ $stock->UL_STOCKS_COMPNAME }}</h1>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach (array_filter([$company['tag'], $stock->UL_STOCKS_INDUSTRY, 'Unlisted']) as $c)
                                        <span class="rounded-full border border-border bg-green-50 px-3 py-1 text-xs font-semibold text-primary">{{ $c }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2">
                            @if ($stock->UL_STOCKS_ISIN)
                                <span class="rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground">
                                    ISIN: <span class="text-foreground">{{ $stock->UL_STOCKS_ISIN }}</span>
                                </span>
                            @endif
                            <span class="rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground">
                                WittyScore: <span class="text-foreground">{{ number_format($company['wittyScore'], 1) }}/10</span>
                            </span>
                            <span class="rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground">
                                Min lot: <span class="text-foreground">{{ number_format($company['lot']) }} sh</span>
                            </span>
                            @if ($stock->UL_STOCKS_WEBSITE)
                                <a href="{{ $stock->UL_STOCKS_WEBSITE }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-primary hover:bg-green-50">
                                    Website <x-sw.icon name="external-link" class="size-3.5" />
                                </a>
                            @endif
                        </div>
                    </x-sw.reveal>

                    @if ($insight?->UL_CI_AI_SUMMARY)
                        <x-sw.reveal :delay="0.08">
                            <div class="mt-7 rounded-3xl border border-border bg-beige p-6 shadow-soft sm:p-7">
                                <span class="rounded-full bg-primary px-3 py-1 text-[11px] font-bold tracking-widest text-primary-foreground uppercase">AI Summary</span>
                                <h2 class="mt-4 text-lg font-bold text-foreground sm:text-xl">{{ $stock->UL_STOCKS_COMPNAME }} unlisted shares, in 30 seconds</h2>
                                <p class="mt-3 text-sm leading-relaxed text-muted-foreground sm:text-base">{{ $insight->UL_CI_AI_SUMMARY }}</p>
                            </div>
                        </x-sw.reveal>
                    @endif
                </div>

                <div id="trade" class="scroll-mt-24 lg:sticky lg:top-24">
                    <div class="space-y-5">
                        <div class="bg-price-card relative overflow-hidden rounded-3xl p-6 shadow-soft sm:p-8">
                            <div class="pointer-events-none absolute -top-24 -right-16 size-72 rounded-full bg-mint/20 blur-3xl"></div>
                            <div class="relative flex items-center justify-between text-[11px] font-bold tracking-widest text-mint-bright uppercase">
                                <span>Current Price</span>
                                @if ($priceAsOf)
                                    <span class="inline-flex items-center gap-1.5 text-white/70">
                                        <x-sw.icon name="clock" class="size-3.5" /> {{ \Illuminate\Support\Carbon::parse($priceAsOf)->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                            <p class="relative mt-4 text-5xl font-bold text-white sm:text-6xl">
                                <x-sw.count-up :to="$company['price']" prefix="₹" />
                            </p>
                            @if ($weekChangePct !== null)
                                <p class="relative mt-2 text-sm font-semibold text-mint-bright">
                                    {{ $weekChangePct >= 0 ? '▲' : '▼' }} ₹{{ number_format(abs($weekChange), 2) }}
                                    ({{ $weekChangePct >= 0 ? '+' : '−' }}{{ number_format(abs($weekChangePct), 2) }}%) this week
                                </p>
                            @endif
                            <p class="relative mt-4 text-xs font-semibold text-white/75">
                                Min lot: {{ number_format($company['lot']) }} shares · Total: ₹{{ number_format($company['lot'] * $company['price']) }}
                            </p>
                            <a href="https://wa.me/919999999999" target="_blank" rel="noopener noreferrer"
                               class="relative mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-mint to-mint-bright px-4 py-3 text-sm font-bold text-[#052a14] transition-transform hover:-translate-y-0.5">
                                <x-sw.icon name="message-circle" class="size-4" />
                                Get Quote on WhatsApp
                            </a>
                            <a href="#lead" class="relative mt-3 block text-center text-xs font-semibold text-mint-bright underline-offset-4 hover:underline">
                                Fill inquiry form instead →
                            </a>
                        </div>

                        <div x-data="{ side: 'Buy', qty: {{ $company['lot'] }}, price: {{ $company['price'] }}, submitted: false }" class="rounded-3xl border border-border bg-card p-6 shadow-soft">
                            <h2 class="text-lg font-bold text-foreground">Trade</h2>
                            <div role="group" aria-label="Order side" class="mt-4 grid grid-cols-2 gap-1 rounded-xl border border-border bg-green-50 p-1">
                                <button type="button" @click="side = 'Buy'" :aria-pressed="side === 'Buy'"
                                        :class="side === 'Buy' ? 'bg-primary text-primary-foreground shadow-soft' : 'text-muted-foreground hover:text-primary'"
                                        class="rounded-lg px-4 py-2 text-sm font-bold transition-colors">Buy</button>
                                <button type="button" @click="side = 'Sell'" :aria-pressed="side === 'Sell'"
                                        :class="side === 'Sell' ? 'bg-primary text-primary-foreground shadow-soft' : 'text-muted-foreground hover:text-primary'"
                                        class="rounded-lg px-4 py-2 text-sm font-bold transition-colors">Sell</button>
                            </div>

                            <label class="mt-5 block text-[11px] font-bold tracking-widest text-muted-foreground uppercase">Quantity</label>
                            <div class="mt-2 flex items-center gap-2">
                                <button type="button" aria-label="Decrease quantity" @click="qty = Math.max(1, qty - 1)"
                                        class="grid size-10 shrink-0 place-items-center rounded-xl border border-border bg-green-50 text-primary transition-colors hover:border-primary/50">
                                    <x-sw.icon name="minus" class="size-4" />
                                </button>
                                <input type="number" inputmode="numeric" min="1" x-model.number="qty"
                                       class="h-10 min-w-0 flex-1 rounded-xl border border-border bg-background px-3 text-center text-base font-bold text-foreground outline-none" />
                                <button type="button" aria-label="Increase quantity" @click="qty = qty + 1"
                                        class="grid size-10 shrink-0 place-items-center rounded-xl border border-border bg-green-50 text-primary transition-colors hover:border-primary/50">
                                    <x-sw.icon name="plus" class="size-4" />
                                </button>
                            </div>

                            <div class="mt-5 flex items-baseline justify-between rounded-xl border border-border bg-green-50 px-4 py-3">
                                <span class="text-[11px] font-bold tracking-widest text-muted-foreground uppercase">Total Amount</span>
                                <span class="text-xl font-bold text-primary sm:text-2xl" x-text="'₹' + (Math.max(qty, 0) * price).toLocaleString('en-IN')"></span>
                            </div>
                            <p class="mt-2 text-xs font-semibold text-muted-foreground">
                                Min lot: {{ number_format($company['lot']) }} shares · ₹{{ number_format($company['price'], 2) }} per share
                            </p>

                            <button type="button" @click="submitted = true"
                                    class="mt-5 w-full rounded-xl bg-gradient-to-r from-[#0C7031] to-[#11F1C4] px-4 py-3 text-sm font-bold text-[#052a14] transition-transform hover:-translate-y-0.5">
                                Confirm <span x-text="side"></span> Order
                            </button>
                            <div x-show="submitted" style="display: none;" class="mt-4 space-y-3 rounded-xl border border-border bg-beige px-4 py-3">
                                <p class="text-xs font-semibold text-foreground">We'll confirm live availability &amp; price and get back to you.</p>
                                <a :href="'https://wa.me/919999999999?text=' + encodeURIComponent('Hi StockWitty, I would like to ' + side.toLowerCase() + ' ' + Math.max(qty,0) + ' shares of {{ $stock->UL_STOCKS_COMPNAME }}.')"
                                   target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold text-primary-foreground transition-transform hover:-translate-y-0.5">
                                    <x-sw.icon name="message-circle" class="size-4" />
                                    Continue on WhatsApp
                                </a>
                                <p class="text-[11px] text-muted-foreground">StockWitty is a distributor — this is a quote/lead request, not instant execution.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-y border-border bg-green-50 py-6">
            <div class="mx-auto grid max-w-7xl grid-cols-2 gap-3 px-4 sm:grid-cols-4 sm:px-6 lg:grid-cols-6 lg:px-8">
                @foreach (array_filter([
                    ['label' => 'Price', 'value' => '₹' . number_format($company['price'], 0)],
                    ['label' => 'Market Cap', 'value' => $company['mktCap']],
                    ['label' => 'P/E', 'value' => $company['pe']],
                    ['label' => 'WittyScore', 'value' => number_format($company['wittyScore'], 1) . '/10'],
                    $high52w ? ['label' => '52W High', 'value' => '₹' . number_format($high52w, 0)] : null,
                    $low52w ? ['label' => '52W Low', 'value' => '₹' . number_format($low52w, 0)] : null,
                ]) as $s)
                    <div class="rounded-xl border border-border bg-card px-3 py-3 text-center shadow-soft">
                        <p class="text-sm font-bold text-primary sm:text-base">{{ $s['value'] }}</p>
                        <p class="mt-1 text-[11px] font-semibold text-muted-foreground">{{ $s['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <div x-data="tocSpy({{ Illuminate\Support\Js::from(array_column($sections, 'id')) }})" class="sticky top-16 z-30 border-y border-border bg-background/85 backdrop-blur">
            <div class="mx-auto max-w-7xl overflow-x-auto px-4 sm:px-6 lg:px-8">
                <nav aria-label="Page sections" class="flex gap-1 py-2 whitespace-nowrap">
                    @foreach ($sections as $s)
                        <a href="#{{ $s['id'] }}" @click.prevent="scrollToSection('{{ $s['id'] }}')"
                           :class="active === '{{ $s['id'] }}' ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-green-50 hover:text-primary'"
                           class="rounded-full px-3.5 py-2 text-xs font-bold transition-colors sm:text-sm">
                            {{ $s['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <section id="price-chart" class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Price movement" title="{{ $stock->UL_STOCKS_COMPNAME }} unlisted price history"
                                       subtitle="Indicative levels from dealer bids and asks." />
                <div class="mt-8 rounded-3xl border border-border bg-card p-5 shadow-soft sm:p-7" x-data="nsePriceChart()" data-series="{{ json_encode($series) }}">
                    <x-sw.chips :options="['1M', '6M', '1Y', '3Y', '5Y', 'Max']" model="period" />
                    <div class="mt-6 h-[280px] w-full sm:h-[360px]" x-effect="period; updateChart()">
                        <canvas x-ref="chart"></canvas>
                    </div>
                    <p class="mt-4 text-xs text-muted-foreground">
                        Indicative dealer levels. Unlisted prices are negotiated, not exchange-quoted.
                    </p>
                </div>
            </div>
        </section>

        <section id="about" class="bg-green-50/60 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="About the company" title="About {{ $stock->UL_STOCKS_COMPNAME }}" />
                <div class="mt-6 max-w-3xl space-y-4 text-base leading-relaxed text-muted-foreground">
                    {!! $stock->UL_STOCKS_ABOUT ?: '<p>Company profile coming soon.</p>' !!}
                </div>
                <a href="/unlisted-shares/{{ $stock->UL_STOCKS_SLUG }}/about/" class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-primary underline-offset-4 hover:underline">
                    Read the full company profile <x-sw.icon name="arrow-right" class="size-4" />
                </a>
            </div>
        </section>

        <section id="fundamentals" class="bg-mesh py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Fundamentals" title="Key metrics and identifiers" />
                <div class="mt-8 rounded-3xl border border-border bg-card p-5 shadow-soft sm:p-7">
                    <dl class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach ($fundamentalCells as [$k, $v])
                            <div class="rounded-xl border border-border bg-green-50/70 px-4 py-3">
                                <dt class="text-[11px] font-semibold tracking-wide text-muted-foreground uppercase">{{ $k }}</dt>
                                <dd class="mt-1 text-sm font-bold break-words text-foreground">{{ $v }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
                <p class="mt-3 text-xs text-muted-foreground">Verify identifiers and figures against official filings.</p>
            </div>
        </section>

        @if ($insight?->UL_CI_FOUNDERS_VERDICT)
            <section id="founders-take" class="py-14 sm:py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <x-sw.section-heading eyebrow="Founder's Take" title="The honest story of {{ $stock->UL_STOCKS_COMPNAME }}" />
                    @if ($insight->UL_CI_FOUNDERS_INTRO)
                        <p class="mt-5 max-w-2xl text-base text-muted-foreground">{{ $insight->UL_CI_FOUNDERS_INTRO }}</p>
                    @endif
                    @if ($insight->UL_CI_FOUNDERS_QUOTE)
                        <x-sw.reveal>
                            <blockquote class="mt-6 max-w-3xl border-l-4 border-amber-400 bg-beige px-6 py-5 text-lg font-semibold text-foreground italic">
                                {{ $insight->UL_CI_FOUNDERS_QUOTE }}
                            </blockquote>
                        </x-sw.reveal>
                    @endif
                    <x-sw.reveal :delay="0.08">
                        <div class="bg-price-card mt-6 max-w-3xl rounded-2xl p-6 text-sm leading-relaxed text-white shadow-soft sm:text-base">
                            <span class="text-[11px] font-bold tracking-widest text-mint-bright uppercase">Our verdict</span>
                            <p class="mt-2">{{ $insight->UL_CI_FOUNDERS_VERDICT }}</p>
                        </div>
                    </x-sw.reveal>
                    <a href="/unlisted-shares/{{ $stock->UL_STOCKS_SLUG }}/thesis/" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-bold text-primary-foreground transition-transform hover:-translate-y-0.5">
                        Read our full thesis &amp; WittyScore breakdown <x-sw.icon name="arrow-right" class="size-4" />
                    </a>
                </div>
            </section>
        @endif

        <section id="financials" class="bg-green-50/60 py-14 sm:py-20" x-data="{ range: 'Yearly', tab: 'Income Statement' }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Financials" title="Financial Performance (₹ Cr)"
                                       subtitle="From reported financials — verify against audited annual reports." />
                <div class="mt-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <x-sw.chips :options="['Yearly', 'Quarterly']" model="range" />
                        <x-sw.chips :options="['Income Statement', 'Balance Sheet', 'Cash Flow']" model="tab" />
                    </div>
                    <div class="mt-6 overflow-x-auto rounded-2xl border border-border shadow-soft">
                        @foreach ($financialTables as $rangeKey => $tabs)
                            @foreach ($tabs as $tabKey => $table)
                                @if (count($table['cols']))
                                    <table x-show="range === '{{ $rangeKey }}' && tab === '{{ $tabKey }}'" style="display: none;"
                                           class="w-full min-w-[640px] border-collapse text-left text-sm">
                                        <thead>
                                            <tr class="bg-green-50">
                                                <th class="px-4 py-3 text-xs font-bold tracking-wide text-primary uppercase">Particulars</th>
                                                @foreach ($table['cols'] as $c)
                                                    <th class="px-4 py-3 text-right text-xs font-bold tracking-wide text-primary uppercase">{{ $c }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($table['rows'] as $r)
                                                <tr class="border-t border-border bg-card">
                                                    <td class="px-4 py-3 {{ !empty($r['strong']) ? 'font-bold text-primary' : 'font-semibold text-foreground' }}">{{ $r['label'] }}</td>
                                                    @foreach ($r['values'] as $v)
                                                        <td class="px-4 py-3 text-right tabular-nums {{ !empty($r['strong']) ? 'font-bold text-primary' : 'text-muted-foreground' }}">{{ $v }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            @endforeach
                        @endforeach
                        @if (!count($financialTables['Yearly']['Income Statement']['cols']))
                            <p class="p-6 text-center text-sm text-muted-foreground">Financial data not yet published for this company.</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section id="how-to-buy" class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Process" title="How to buy unlisted shares" align="center" />
                <div class="mt-10 grid gap-6 lg:grid-cols-3">
                    @foreach ($steps as $i => $s)
                        <x-sw.reveal :delay="$i * 0.12" class="card-lift rounded-2xl border border-border bg-card p-7 text-center shadow-soft">
                            <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-primary text-lg font-bold text-primary-foreground">{{ $s['n'] }}</span>
                            <x-sw.icon :name="$s['icon']" class="mx-auto mt-5 size-6 text-mint" />
                            <h3 class="mt-3 text-lg font-bold text-foreground">{{ $s['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $s['body'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>
            </div>
        </section>

        @if (count($ipoTimeline) || count($ipoFacts))
            <section id="ipo-roadmap" class="bg-green-50/60 py-14 sm:py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <x-sw.section-heading eyebrow="IPO Roadmap" title="Where things stand" />
                    @if (count($ipoTimeline))
                        <ol class="mt-8 max-w-3xl border-l-2 border-green-100 pl-6">
                            @foreach ($ipoTimeline as $t)
                                <li class="relative pb-7 last:pb-0">
                                    <span class="absolute -left-[31px] mt-1 grid size-4 place-items-center rounded-full border-2 border-background bg-primary"></span>
                                    <p class="inline-flex items-center gap-2 text-sm font-bold text-primary">
                                        <x-sw.icon name="calendar-clock" class="size-4" />
                                        {{ $t['label'] }}
                                    </p>
                                    <p class="mt-1.5 text-sm leading-relaxed text-muted-foreground">{{ $t['value'] }}</p>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                    @if (count($ipoFacts))
                        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($ipoFacts as $f)
                                <div class="rounded-2xl border border-border bg-card p-5 shadow-soft">
                                    <p class="text-[11px] font-semibold tracking-wide text-muted-foreground uppercase">{{ $f['label'] }}</p>
                                    <p class="mt-1.5 text-sm font-bold text-foreground">{{ $f['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <p class="mt-3 text-xs text-muted-foreground">IPO timeline and sizing figures are indicative and subject to change.</p>
                </div>
            </section>
        @endif

        @if ($overviewFaqs->isNotEmpty())
            <section id="faq" class="py-14 sm:py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <x-sw.section-heading eyebrow="FAQ" title="Buying {{ $stock->UL_STOCKS_COMPNAME }} unlisted shares" align="center" />
                    <div class="mx-auto mt-8 max-w-3xl">
                        @foreach ($overviewFaqs as $f)
                            <details class="sw-faq-item mb-3 overflow-hidden rounded-2xl border border-border bg-card px-5 shadow-soft transition-colors">
                                <summary class="flex cursor-pointer items-center justify-between gap-3 py-5 text-left text-base font-bold text-foreground">
                                    {{ $f->UL_FAQ_QUESTION }}
                                    <x-sw.icon name="chevron-down" class="sw-faq-chevron size-4 shrink-0 text-muted-foreground" />
                                </summary>
                                <p class="pb-5 text-sm leading-relaxed text-muted-foreground">{{ $f->UL_FAQ_ANSWER }}</p>
                            </details>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="border-y border-border bg-green-50 py-8">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-center gap-3 px-4 sm:px-6 lg:px-8">
                @foreach ($badges as $b)
                    <div class="inline-flex items-center gap-2.5 rounded-2xl border border-border bg-card px-4 py-3 shadow-soft">
                        <x-sw.icon :name="$b['icon']" class="size-5 shrink-0 text-primary" />
                        <span>
                            <span class="block text-sm font-bold text-foreground">{{ $b['title'] }}</span>
                            <span class="block text-xs text-muted-foreground">{{ $b['sub'] }}</span>
                        </span>
                    </div>
                @endforeach
            </div>
        </section>

        <div id="lead" class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <p class="rounded-2xl border border-border bg-green-50 px-5 py-4 text-xs leading-relaxed text-muted-foreground">
                <strong class="text-foreground">Disclaimer:</strong> StockWitty is a distributor of
                unlisted and pre-IPO shares, not a SEBI-registered investment adviser. Unlisted shares are
                illiquid and high-risk, prices are negotiated and an IPO may be delayed or never happen.
                Nothing on this page is investment advice.
            </p>
        </div>
    </main>
</div>
@endsection
