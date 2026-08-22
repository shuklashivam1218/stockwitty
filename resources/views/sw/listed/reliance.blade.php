@extends('layouts.sw')

@section('title', 'Reliance Industries Share Price, Fundamentals & Analysis | StockWitty')
@section('description', 'Reliance Industries share analysis — price trend, fundamentals, quarterly results, shareholding pattern, peer comparison and upcoming events, explained honestly.')

@php
$series = [
    ['m' => 'Mar', 'p' => 2410], ['m' => 'Apr', 'p' => 2515], ['m' => 'May', 'p' => 2602],
    ['m' => 'Jun', 'p' => 2488], ['m' => 'Jul', 'p' => 2740], ['m' => 'Aug', 'p' => 2847],
];

$quarters = [
    ['q' => 'Q1 FY27', 'rev' => '2,68,402', 'ebitda' => '47,760', 'pat' => '19,407', 'eps' => '28.7'],
    ['q' => 'Q4 FY26', 'rev' => '2,41,600', 'ebitda' => '43,100', 'pat' => '18,950', 'eps' => '28.0'],
    ['q' => 'Q3 FY26', 'rev' => '2,35,800', 'ebitda' => '41,700', 'pat' => '18,120', 'eps' => '26.8'],
    ['q' => 'Q2 FY26', 'rev' => '2,29,400', 'ebitda' => '40,300', 'pat' => '17,480', 'eps' => '25.8'],
];

$holding = [
    ['name' => 'Promoters', 'value' => 50.30],
    ['name' => 'FII', 'value' => 21.45],
    ['name' => 'DII', 'value' => 15.20],
    ['name' => 'Public & others', 'value' => 7.85],
];
$holdingColors = ['var(--brand)', 'var(--mint)', 'var(--green-200)', 'var(--beige)'];

$events = [
    ['d' => '24 Sep 2026', 'e' => 'Annual General Meeting'],
    ['d' => '17 Oct 2026', 'e' => 'Q2 FY27 results'],
    ['d' => '05 Nov 2026', 'e' => 'Record date — interim dividend (indicative)'],
];

$news = [
    ['t' => 'Retail arm adds stores across tier-2 cities', 'd' => '14 Aug 2026'],
    ['t' => 'New energy capex guidance held steady', 'd' => '09 Aug 2026'],
    ['t' => 'Telecom ARPU improves for the third quarter', 'd' => '01 Aug 2026'],
];

$tabs = ['Performance', 'Fundamentals', 'Quarterly results', 'Shareholding'];
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Listed Stocks', 'href' => '/listed/'], ['label' => 'Reliance Industries']]" />
    </div>

    <main>
        <section class="bg-price-card text-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <div>
                        <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">NSE: RELIANCE · Energy &amp; Retail</p>
                        <h1 class="mt-3 text-3xl font-bold sm:text-4xl">Reliance Industries Limited</h1>
                        <p class="mt-4 text-4xl font-bold">₹2,847.50</p>
                        <p class="mt-1 inline-flex items-center gap-1.5 text-sm font-bold text-mint-bright">
                            <x-sw.icon name="trending-up" class="size-4" /> +₹38.45 (+1.37%) today
                        </p>
                        <dl class="mt-5 grid grid-cols-2 gap-x-6 gap-y-2 text-xs sm:text-sm">
                            @foreach ([['Open', '₹2,815.00'], ['Prev close', '₹2,809.05'], ['Day high', '₹2,862.40'], ['Day low', '₹2,795.20']] as [$k, $v])
                                <div>
                                    <dt class="text-white/60">{{ $k }}</dt>
                                    <dd class="font-bold">{{ $v }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                    <dl class="grid grid-cols-2 gap-x-8 gap-y-3 text-sm sm:grid-cols-4">
                        @foreach ([['Market cap', '₹19.26 L Cr'], ['EPS (TTM)', '₹119.45'], ['Day H / L', '₹2,862.40 / ₹2,795.20'], ['52W H / L', '₹3,217.90 / ₹2,420.10']] as [$k, $v])
                            <div>
                                <dt class="text-white/60">{{ $k }}</dt>
                                <dd class="font-bold">{{ $v }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>
        </section>

        <section class="py-14 sm:py-20" x-data="reliancePage()">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap gap-2">
                    @foreach ($tabs as $t)
                        <button type="button" @click="selectTab('{{ $t }}')" :aria-pressed="tab === '{{ $t }}'"
                                :class="tab === '{{ $t }}' ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-card text-muted-foreground hover:text-primary'"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition-all">
                            {{ $t }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-6 rounded-3xl border border-border bg-card p-5 shadow-soft sm:p-7">
                    <div x-show="tab === 'Performance'" style="display: block;">
                        <div class="h-72 w-full">
                            <div x-ref="perfData" data-series="{{ json_encode($series) }}"></div>
                            <canvas x-ref="perfChart"></canvas>
                        </div>
                    </div>

                    <dl x-show="tab === 'Fundamentals'" style="display: none;" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ([['Market cap', '₹19.26 L Cr'], ['EPS (TTM)', '₹119.45'], ['ROE', '9.85%'], ['Book value', '₹1,323'], ['Dividend yield', '0.35%'], ['Face value', '₹10'], ['3Y CAGR', '+12.8%'], ['5Y CAGR', '+14.2%']] as [$k, $v])
                            <div class="rounded-2xl bg-green-50 p-4">
                                <dt class="text-xs font-semibold text-muted-foreground">{{ $k }}</dt>
                                <dd class="mt-1 text-lg font-bold text-foreground">{{ $v }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    <div x-show="tab === 'Quarterly results'" style="display: none;">
                        <div class="mb-5 grid gap-4 sm:grid-cols-3">
                            @foreach ([['Revenue (latest quarter)', '₹2,68,402 Cr', '+11.2% YoY'], ['Net profit', '₹19,407 Cr', '+8.5% YoY'], ['Operating margin', '17.8%', 'Latest quarter']] as [$k, $v, $n])
                                <div class="rounded-2xl bg-green-50 p-4">
                                    <p class="text-xs font-semibold text-muted-foreground">{{ $k }}</p>
                                    <p class="mt-1 text-xl font-bold text-foreground">{{ $v }}</p>
                                    <p class="text-xs font-bold text-primary">{{ $n }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[36rem] text-sm">
                                <caption class="sr-only">Quarterly results, ₹ crore</caption>
                                <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Quarter</th>
                                        <th scope="col" class="px-4 py-3 text-right">Revenue (₹ Cr)</th>
                                        <th scope="col" class="px-4 py-3 text-right">EBITDA (₹ Cr)</th>
                                        <th scope="col" class="px-4 py-3 text-right">PAT (₹ Cr)</th>
                                        <th scope="col" class="px-4 py-3 text-right">EPS (₹)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    @foreach ($quarters as $r)
                                        <tr>
                                            <th scope="row" class="px-4 py-3 text-left font-bold text-foreground">{{ $r['q'] }}</th>
                                            <td class="px-4 py-3 text-right text-muted-foreground">{{ $r['rev'] }}</td>
                                            <td class="px-4 py-3 text-right text-muted-foreground">{{ $r['ebitda'] }}</td>
                                            <td class="px-4 py-3 text-right text-muted-foreground">{{ $r['pat'] }}</td>
                                            <td class="px-4 py-3 text-right text-muted-foreground">{{ $r['eps'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div x-show="tab === 'Shareholding'" style="display: none;" class="grid items-center gap-6 lg:grid-cols-2">
                        <div class="h-64">
                            <div x-ref="pieData" data-holding="{{ json_encode($holding) }}"></div>
                            <canvas x-ref="pieChart"></canvas>
                        </div>
                        <ul class="space-y-2 text-sm">
                            @foreach ($holding as $i => $h)
                                <li class="flex items-center justify-between gap-3 border-b border-border pb-2">
                                    <span class="flex items-center gap-2 font-semibold text-foreground">
                                        <span class="size-3 rounded-full" style="background: {{ $holdingColors[$i % count($holdingColors)] }}"></span>
                                        {{ $h['name'] }}
                                    </span>
                                    <span class="font-bold text-muted-foreground">{{ number_format($h['value'], 2) }}%</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Peer comparison" title="How it stacks up against its peers"
                                       subtitle="Same sector lens, no cherry-picked window." />
                <div class="mt-8 overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
                    <table class="w-full min-w-[36rem] text-sm">
                        <caption class="sr-only">Peer comparison table</caption>
                        <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                            <tr>
                                <th scope="col" class="px-4 py-3">Company</th>
                                <th scope="col" class="px-4 py-3 text-right">Price</th>
                                <th scope="col" class="px-4 py-3 text-right">P/E</th>
                                <th scope="col" class="px-4 py-3 text-right">Market cap</th>
                                <th scope="col" class="px-4 py-3 text-right">WittyScore</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach (config('sw.listed_stocks') as $s)
                                <tr>
                                    <th scope="row" class="px-4 py-3 text-left font-bold text-foreground">{{ $s['name'] }}</th>
                                    <td class="px-4 py-3 text-right text-muted-foreground">₹{{ number_format($s['price'], 2) }}</td>
                                    <td class="px-4 py-3 text-right text-muted-foreground">{{ $s['pe'] }}</td>
                                    <td class="px-4 py-3 text-right text-muted-foreground">{{ $s['mktCap'] }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-primary">{{ number_format($s['wittyScore'], 1) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-sw.reveal class="h-full rounded-3xl border border-border bg-card p-6 shadow-soft">
                        <h2 class="text-xl font-bold text-foreground">Recent news</h2>
                        <ul class="mt-4 space-y-3">
                            @foreach ($news as $n)
                                <li class="border-b border-border pb-3 last:border-0">
                                    <p class="text-sm font-semibold text-foreground">{{ $n['t'] }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $n['d'] }}</p>
                                </li>
                            @endforeach
                        </ul>
                        <a href="/news/" class="mt-4 inline-block text-sm font-bold text-primary">All market news →</a>
                    </x-sw.reveal>
                    <x-sw.reveal :delay="0.08" class="h-full rounded-3xl border border-border bg-card p-6 shadow-soft">
                        <h2 class="text-xl font-bold text-foreground">Events calendar</h2>
                        <ul class="mt-4 space-y-3">
                            @foreach ($events as $e)
                                <li class="flex items-start gap-3 border-b border-border pb-3 last:border-0">
                                    <x-sw.icon name="calendar-clock" class="mt-0.5 size-4 text-primary" />
                                    <span>
                                        <span class="block text-sm font-semibold text-foreground">{{ $e['e'] }}</span>
                                        <span class="text-xs text-muted-foreground">{{ $e['d'] }}</span>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </x-sw.reveal>
                </div>
                <x-sw.illustrative-note>
                    All figures on this page — price, fundamentals, quarterly numbers, shareholding and events —
                    are illustrative demo data. Verify with the company's filings and your broker before acting.
                </x-sw.illustrative-note>
            </div>
        </section>
    </main>
</div>
@endsection
