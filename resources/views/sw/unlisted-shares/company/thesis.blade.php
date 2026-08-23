@extends('layouts.sw')

@section('title', $stock->UL_STOCKS_COMPNAME . ' Unlisted Shares: Should You Buy? | StockWitty')
@section('description', 'Our take on ' . $stock->UL_STOCKS_COMPNAME . ' unlisted shares — WittyScore, bull/bear case, key risks and a clear verdict. Not investment advice.')

@php
$overall = $wittyScore?->overall();

$pillars = $wittyScore ? array_values(array_filter([
    $wittyScore->UL_WS_FINANCIAL_HEALTH !== null ? ['label' => 'Financial Health', 'value' => (float) $wittyScore->UL_WS_FINANCIAL_HEALTH] : null,
    $wittyScore->UL_WS_VALUATION !== null ? ['label' => 'Valuation', 'value' => (float) $wittyScore->UL_WS_VALUATION] : null,
    $wittyScore->UL_WS_GROWTH_POTENTIAL !== null ? ['label' => 'Growth Potential', 'value' => (float) $wittyScore->UL_WS_GROWTH_POTENTIAL] : null,
    $wittyScore->UL_WS_IPO_PROBABILITY !== null ? ['label' => 'IPO Probability', 'value' => (float) $wittyScore->UL_WS_IPO_PROBABILITY] : null,
    $wittyScore->UL_WS_LIQUIDITY_SAFETY !== null ? ['label' => 'Liquidity & Safety', 'value' => (float) $wittyScore->UL_WS_LIQUIDITY_SAFETY] : null,
])) : [];

$toc = array_values(array_filter([
    $verdictLong || $overall ? ['id' => 'verdict', 'label' => 'The verdict'] : null,
    $tldr ? ['id' => 'tldr', 'label' => 'TL;DR summary'] : null,
    count($pillars) ? ['id' => 'wittyscore', 'label' => 'WittyScore breakdown'] : null,
    $thesisHtml ? ['id' => 'thesis', 'label' => 'The thesis, told straight'] : null,
    (count($bullCase) || count($bearCase)) ? ['id' => 'bull-bear', 'label' => 'Bull vs bear case'] : null,
    (count($suitsIf) || count($notSuitsIf)) ? ['id' => 'who-for', 'label' => 'Who this is for'] : null,
    count($risks) ? ['id' => 'risks', 'label' => 'Key risks'] : null,
    ['id' => 'author', 'label' => 'Author, sources & disclaimer'],
]));
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[
            ['label' => 'Home', 'href' => '/'],
            ['label' => 'Unlisted Shares', 'href' => '/unlisted-shares/'],
            ['label' => $stock->UL_STOCKS_COMPNAME, 'href' => '/unlisted-shares/' . $stock->UL_STOCKS_SLUG . '/'],
            ['label' => 'Thesis & Analysis'],
        ]" />
    </div>

    <main class="pb-24">
        <div class="mx-auto w-full max-w-[1160px] px-4 sm:px-6 lg:flex lg:justify-center lg:gap-12">
            <div class="min-w-0 lg:max-w-[780px] lg:flex-1">
                <header class="pt-10 sm:pt-14">
                    <x-sw.reveal>
                        <span class="inline-flex items-center rounded-full border border-brand/20 bg-green-50 px-3 py-1 text-xs font-bold tracking-widest text-primary uppercase">
                            Investment Thesis &amp; Analysis
                        </span>
                        <h1 class="mt-5 text-3xl leading-tight font-bold text-foreground sm:text-5xl">
                            {{ $stock->UL_STOCKS_COMPNAME }} Unlisted Shares: Should You Buy?
                        </h1>
                        <p class="mt-5 text-lg text-muted-foreground">
                            Our honest take on whether {{ $stock->UL_STOCKS_COMPNAME }}'s unlisted shares deserve a
                            place in your portfolio.
                        </p>
                        <div class="mt-7 flex flex-wrap items-center gap-x-4 gap-y-3 border-y border-border py-4 text-xs font-semibold text-muted-foreground">
                            <span class="flex items-center gap-2">
                                <span class="grid size-9 place-items-center rounded-full bg-primary text-xs font-bold text-primary-foreground">SW</span>
                                By StockWitty Research
                            </span>
                        </div>
                    </x-sw.reveal>
                </header>
            </div>
            <div class="hidden shrink-0 lg:block lg:w-[248px]"></div>
        </div>

        <x-sw.toc-layout :items="$toc">
            @if ($verdictLong || $overall)
                <x-sw.reveal :delay="0.05">
                    <section id="verdict" class="scroll-mt-28 bg-price-card mt-10 grid gap-6 rounded-3xl p-6 text-white shadow-soft sm:grid-cols-[auto_1fr] sm:items-center sm:p-8">
                        <div class="flex items-center gap-4 sm:flex-col sm:gap-1 sm:border-r sm:border-white/15 sm:pr-8">
                            <p class="text-4xl font-bold text-mint-bright">{{ number_format($overall ?? 0, 1) }}<span class="text-lg text-white/70"> / 10</span></p>
                            <p class="text-xs font-bold tracking-widest text-white/70 uppercase">WittyScore</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">Our verdict</p>
                            <p class="mt-2 text-base leading-relaxed text-white/90 sm:text-lg">
                                {{ $verdictLong ?: 'Full verdict coming soon — see the WittyScore breakdown below.' }}
                            </p>
                        </div>
                    </section>
                </x-sw.reveal>
            @endif

            @if ($tldr)
                <x-sw.reveal :delay="0.05">
                    <section id="tldr" class="scroll-mt-28 mt-12 rounded-3xl border border-border bg-beige p-6 shadow-soft sm:p-8">
                        <h2 class="text-xl font-bold text-foreground">The short answer</h2>
                        <p class="mt-4 text-base leading-relaxed text-ink/80">{{ $tldr }}</p>
                    </section>
                </x-sw.reveal>
            @endif

            @if (count($pillars))
                <section id="wittyscore" class="scroll-mt-28 mt-16">
                    <x-sw.reveal><h2 class="text-2xl font-bold text-foreground sm:text-3xl">How we scored {{ $stock->UL_STOCKS_COMPNAME }}: {{ number_format($overall, 1) }} / 10</h2></x-sw.reveal>
                    <div class="mt-7 grid gap-4 sm:grid-cols-2">
                        @foreach ($pillars as $i => $p)
                            <x-sw.reveal :delay="$i * 0.1" class="card-lift rounded-2xl border border-border bg-card p-5 shadow-soft">
                                <div class="flex items-center justify-between text-sm font-bold">
                                    <span class="flex items-center gap-2 text-foreground">
                                        <x-sw.icon name="gauge" class="size-4 text-mint" />
                                        {{ $p['label'] }}
                                    </span>
                                    <span class="text-primary tabular-nums">{{ number_format($p['value'], 1) }}</span>
                                </div>
                                <div class="mt-2 h-2 overflow-hidden rounded-full bg-green-100">
                                    <div class="sw-pillar-bar h-full rounded-full bg-gradient-to-r from-primary to-mint" style="--target-width: {{ $p['value'] * 10 }}%"></div>
                                </div>
                            </x-sw.reveal>
                        @endforeach
                    </div>
                    <p class="mt-5 text-sm text-muted-foreground">
                        Scores are computed from our methodology, not hand-picked.
                        <a href="/wittyscore/" class="font-bold text-primary hover:underline">WittyScore methodology →</a>
                    </p>
                </section>
            @endif

            @if ($thesisHtml)
                <section id="thesis" class="scroll-mt-28 mt-16">
                    <x-sw.reveal><h2 class="text-2xl font-bold text-foreground sm:text-3xl">The thesis, told straight</h2></x-sw.reveal>
                    <div class="mt-6 space-y-4 text-base leading-relaxed text-muted-foreground">{!! $thesisHtml !!}</div>
                </section>
            @endif

            @if (count($bullCase) || count($bearCase))
                <section id="bull-bear" class="scroll-mt-28 mt-16">
                    <x-sw.reveal>
                        <h2 class="text-2xl font-bold text-foreground sm:text-3xl">Bull case vs bear case</h2>
                        <p class="mt-2 text-base text-muted-foreground">Both columns are real. Which one you weight more heavily is the actual decision.</p>
                    </x-sw.reveal>
                    <div class="mt-7 grid gap-4 lg:grid-cols-2">
                        @if (count($bullCase))
                            <x-sw.reveal>
                                <div class="h-full rounded-2xl border border-brand/25 bg-green-50 p-6 shadow-soft">
                                    <h3 class="text-sm font-bold tracking-widest text-primary uppercase">Bull case</h3>
                                    <ul class="mt-4 space-y-3 text-sm leading-relaxed text-muted-foreground">
                                        @foreach ($bullCase as $b)
                                            <li class="flex gap-2"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-mint"></span>{{ $b }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </x-sw.reveal>
                        @endif
                        @if (count($bearCase))
                            <x-sw.reveal :delay="0.1">
                                <div class="h-full rounded-2xl border border-rose-300 bg-rose-50 p-6 shadow-soft">
                                    <h3 class="text-sm font-bold tracking-widest text-rose-800 uppercase">Bear case</h3>
                                    <ul class="mt-4 space-y-3 text-sm leading-relaxed text-rose-900/80">
                                        @foreach ($bearCase as $b)
                                            <li class="flex gap-2"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-rose-400"></span>{{ $b }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </x-sw.reveal>
                        @endif
                    </div>
                </section>
            @endif

            @if (count($suitsIf) || count($notSuitsIf))
                <section id="who-for" class="scroll-mt-28 mt-16">
                    <x-sw.reveal><h2 class="text-2xl font-bold text-foreground sm:text-3xl">Who this is — and isn't — for</h2></x-sw.reveal>
                    <div class="mt-7 grid gap-4 lg:grid-cols-2">
                        @if (count($suitsIf))
                            <x-sw.reveal>
                                <div class="h-full rounded-2xl border border-brand/25 bg-card p-6 shadow-soft">
                                    <h3 class="text-sm font-bold tracking-widest text-primary uppercase">Might suit you if…</h3>
                                    <ul class="mt-4 space-y-3 text-sm leading-relaxed text-muted-foreground">
                                        @foreach ($suitsIf as $s)
                                            <li class="flex gap-2"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-mint"></span>{{ $s }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </x-sw.reveal>
                        @endif
                        @if (count($notSuitsIf))
                            <x-sw.reveal :delay="0.1">
                                <div class="h-full rounded-2xl border border-border bg-beige p-6 shadow-soft">
                                    <h3 class="text-sm font-bold tracking-widest text-ink/70 uppercase">Probably not if…</h3>
                                    <ul class="mt-4 space-y-3 text-sm leading-relaxed text-ink/70">
                                        @foreach ($notSuitsIf as $s)
                                            <li class="flex gap-2"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-ink/30"></span>{{ $s }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </x-sw.reveal>
                        @endif
                    </div>
                </section>
            @endif

            @if (count($risks))
                <section id="risks" class="scroll-mt-28 mt-16">
                    <x-sw.reveal><h2 class="text-2xl font-bold text-foreground sm:text-3xl">The risks we'd want you to read twice</h2></x-sw.reveal>
                    <div class="mt-7 space-y-4">
                        @foreach ($risks as $i => $r)
                            <x-sw.reveal :delay="$i * 0.06" class="rounded-2xl border border-border bg-card p-5 shadow-soft">
                                <h3 class="text-sm font-bold text-foreground">{{ $r['label'] }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $r['value'] }}</p>
                            </x-sw.reveal>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (count($thesisFaqs))
                <section id="thesis-faq" class="scroll-mt-28 mt-16">
                    <x-sw.reveal><h2 class="text-2xl font-bold text-foreground sm:text-3xl">Frequently asked questions</h2></x-sw.reveal>
                    <div class="mt-7">
                        @foreach ($thesisFaqs as $f)
                            <details class="sw-faq-item mb-3 overflow-hidden rounded-2xl border border-border bg-card px-5 shadow-soft transition-colors">
                                <summary class="flex cursor-pointer items-center justify-between gap-3 py-4 text-left text-base font-bold text-foreground">
                                    {{ $f->UL_FAQ_QUESTION }}
                                    <x-sw.icon name="chevron-down" class="sw-faq-chevron size-4 shrink-0 text-muted-foreground" />
                                </summary>
                                <p class="pb-5 text-sm leading-relaxed text-muted-foreground">{{ $f->UL_FAQ_ANSWER }}</p>
                            </details>
                        @endforeach
                    </div>
                </section>
            @endif

            <x-sw.reveal>
                <section id="next-steps" class="scroll-mt-28 bg-price-card mt-16 rounded-3xl p-6 text-white shadow-soft sm:p-10">
                    <h2 class="text-2xl font-bold sm:text-3xl">Next steps</h2>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="/unlisted-shares/{{ $stock->UL_STOCKS_SLUG }}/" class="inline-flex items-center gap-2 rounded-xl bg-mint px-5 py-3 text-sm font-bold text-green-990 transition-transform hover:scale-[1.02]">
                            See live price &amp; how to buy
                            <x-sw.icon name="arrow-right" class="size-4" />
                        </a>
                        <a href="/unlisted-shares/{{ $stock->UL_STOCKS_SLUG }}/about/" class="inline-flex items-center gap-2 rounded-xl border border-white/25 px-5 py-3 text-sm font-bold text-white transition-colors hover:bg-white/10">
                            Read the company profile
                        </a>
                    </div>
                </section>
            </x-sw.reveal>

            <section id="author" class="scroll-mt-28 mt-16 space-y-4">
                <x-sw.reveal>
                    <div class="rounded-2xl border border-border bg-green-50 p-6 shadow-soft">
                        <div class="flex items-center gap-3">
                            <span class="grid size-11 place-items-center rounded-full bg-primary text-sm font-bold text-primary-foreground">SW</span>
                            <div>
                                <p class="text-sm font-bold text-foreground">StockWitty Research</p>
                            </div>
                        </div>
                        <p class="mt-4 text-sm leading-relaxed text-muted-foreground">
                            We write honest theses as a distributor of unlisted shares, not as a SEBI-registered
                            adviser — which means we show the risk as clearly as the reward, even when it costs us a sale.
                        </p>
                    </div>
                </x-sw.reveal>

                <x-sw.reveal :delay="0.1">
                    <div class="rounded-2xl border border-border bg-beige p-6">
                        <h3 class="text-xs font-bold tracking-widest text-ink/70 uppercase">Disclaimer</h3>
                        <p class="mt-3 text-xs leading-relaxed text-ink/70">
                            This page is for information only and is not investment advice. StockWitty is a
                            distributor of unlisted shares and is not a SEBI-registered investment adviser.
                            Unlisted shares are illiquid and high-risk; there is no guarantee of an IPO, a listing
                            date or an exit at any price. WittyScore is our proprietary, opinion-based score and
                            may change without notice. Do your own due diligence and consult a Chartered Accountant
                            for tax treatment.
                        </p>
                    </div>
                </x-sw.reveal>
            </section>
        </x-sw.toc-layout>
    </main>
</div>
@endsection
