@extends('layouts.sw')

@section('title', 'ETFs in India — Low-Cost Index & Thematic ETFs | StockWitty')
@section('description', 'Explore index, gold, sectoral, international and debt ETFs in India — expense ratios, 1-year returns and AUM, plus a plain-English explainer on how ETFs work.')

@php
$cats = ['All', 'Index', 'Gold', 'Sectoral', 'International', 'Debt'];

$explainers = [
    ['t' => 'A fund that trades like a share', 'd' => "An ETF holds a basket — say all 50 Nifty companies — and its units trade on the exchange through the day at a live price, unlike a mutual fund's single end-of-day NAV."],
    ['t' => 'Why the cost matters so much', 'd' => "Most ETFs simply track an index, so fees are a fraction of an active fund's. Over 15 years, a 1% annual saving compounds into a materially different outcome."],
    ['t' => 'What to actually check', 'd' => 'Expense ratio, tracking error, and traded volume. A cheap ETF nobody trades can cost you more on the spread than it saves you on fees.'],
];
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'ETFs']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Exchange traded funds" title="Low-cost index &amp; thematic ETFs."
                        subtitle="If you want market returns without paying for a manager's opinion, this is the cheapest honest way to get them." />

        <section class="py-14 sm:py-20" x-data="{ cat: 'All' }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.chips :options="$cats" model="cat" />

                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach (config('sw.etfs') as $e)
                        <div x-show="cat === 'All' || cat === '{{ $e['type'] }}'" style="display: block;"
                             class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-6 shadow-soft">
                            <span class="grid size-11 place-items-center rounded-xl bg-muted text-primary">
                                <x-sw.icon name="layers" class="size-5" />
                            </span>
                            <span class="mt-4 w-fit rounded-full bg-green-50 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-primary uppercase">{{ $e['type'] }}</span>
                            <h2 class="mt-3 text-base font-bold text-foreground">{{ $e['name'] }}</h2>
                            <dl class="mt-4 grid flex-1 grid-cols-2 gap-3 text-sm">
                                <div>
                                    <dt class="text-xs text-muted-foreground">Expense ratio</dt>
                                    <dd class="font-bold text-foreground">{{ $e['expense'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-muted-foreground">1Y return</dt>
                                    <dd class="font-bold text-foreground">{{ $e['r1y'] }}</dd>
                                </div>
                                <div class="col-span-2">
                                    <dt class="text-xs text-muted-foreground">AUM</dt>
                                    <dd class="font-bold text-foreground">{{ $e['aum'] }}</dd>
                                </div>
                            </dl>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Explainer" title="What is an ETF?" subtitle="Two minutes, no jargon." />
                <div class="mt-8 grid gap-5 lg:grid-cols-3">
                    @foreach ($explainers as $i => $c)
                        <x-sw.reveal :delay="$i * 0.07" class="h-full rounded-2xl border border-border bg-card p-6 shadow-soft">
                            <h3 class="text-lg font-bold text-foreground">{{ $c['t'] }}</h3>
                            <p class="mt-2 text-sm text-muted-foreground">{{ $c['d'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>
                <x-sw.illustrative-note>
                    Expense ratios, returns and AUM shown here are illustrative demo data. ETFs are
                    market-linked — read the scheme documents before investing.
                </x-sw.illustrative-note>
            </div>
        </section>
    </main>
</div>
@endsection
