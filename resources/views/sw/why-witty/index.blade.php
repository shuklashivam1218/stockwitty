@extends('layouts.sw')

@section('title', 'Why Witty — Smart, Honest Investing in Unlisted Shares | StockWitty')
@section('description', 'Why we\'re called StockWitty: Wise, Insightful, Transparent, Trusted and Yours. Research-backed unlisted share investing with risks shown as clearly as rewards.')

@php
$acronym = [
    ['letter' => 'W', 'word' => 'Wise', 'icon' => 'brain', 'd' => 'Research-backed, data-first decisions. Every company we list is read through the same five-pillar lens before it reaches you — never the other way round.'],
    ['letter' => 'I', 'word' => 'Insightful', 'icon' => 'lightbulb', 'd' => 'We explain the why, not just the what. A price without context is trivia; our job is to tell you what moved it and whether that matters.'],
    ['letter' => 'T', 'word' => 'Transparent', 'icon' => 'eye', 'd' => "Risks shown as clearly as rewards. We're a distributor, not a hidden-fee shop — pricing, spreads and our role are stated up front."],
    ['letter' => 'T', 'word' => 'Trusted', 'icon' => 'shield-check', 'd' => 'CA-reviewed data, verified accounts, shares delivered to your own CDSL/NSDL demat, and real humans you can reach on WhatsApp.'],
    ['letter' => 'Y', 'word' => 'Yours', 'icon' => 'hand-heart', 'd' => "Your money, your call. We inform, you decide — we don't chase you with hot tips or pretend a score is a promise."],
];

$values = [
    ['icon' => 'gauge', 't' => 'Smart', 'd' => 'WittyScore rates every unlisted share 0–10 on five weighted pillars. Low scores stay published.'],
    ['icon' => 'shield-check', 't' => 'Honest', 'd' => 'No guaranteed-return hype, no fake urgency. If we\'d skip a name, we say so and explain why.'],
    ['icon' => 'book-open', 't' => 'Clear', 'd' => 'Plain-English research. Jargon gets translated or dropped — DRHP, ISIN, illiquidity discount and all.'],
    ['icon' => 'zap', 't' => 'Quick', 'd' => "Fast KYC and same-day demat delivery once the trade settles, so paperwork isn't the hard part."],
];

$brandBullets = [
    '10,000+ investors on the platform',
    'Shares delivered to your own CDSL / NSDL demat',
    'CA-verified financials on every company page',
    'Human support on WhatsApp, not a chatbot loop',
];
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Why Witty']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Our name, our promise"
                        title="Why &quot;Witty&quot;? Because investing should be smart AND honest."
                        subtitle="Wit isn't a joke — it's judgement. The unlisted market has plenty of noise and very little candour. We built StockWitty for investors who want both the numbers and the caveats.">
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="/unlisted-shares/" class="bg-cta inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-bold text-white">
                    Explore unlisted shares <x-sw.icon name="arrow-right" class="size-4" />
                </a>
                <a href="/wittyscore/" class="inline-flex items-center gap-2 rounded-xl border border-primary/40 px-5 py-3 text-sm font-bold text-primary transition-colors hover:bg-muted">
                    See how WittyScore works
                </a>
            </div>
        </x-sw.page-hero>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="W.I.T.T.Y." title="Five letters, five commitments"
                                       subtitle="Not a tagline exercise — this is the checklist we hold our own research to." />
                <div class="mt-8 space-y-4">
                    @foreach ($acronym as $i => $a)
                        <x-sw.reveal :delay="$i * 0.06" class="card-lift flex flex-col gap-5 rounded-3xl border border-border bg-card p-6 shadow-soft sm:flex-row sm:items-center sm:p-7">
                            <div class="flex items-center gap-4 sm:w-56 sm:shrink-0">
                                <span class="bg-price-card grid size-16 shrink-0 place-items-center rounded-2xl text-3xl font-bold text-mint-bright">{{ $a['letter'] }}</span>
                                <div>
                                    <p class="text-xl font-bold text-foreground">{{ $a['word'] }}</p>
                                    <x-sw.icon :name="$a['icon']" class="mt-1 size-4 text-primary" />
                                </div>
                            </div>
                            <p class="text-sm text-muted-foreground sm:text-base">{{ $a['d'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="What it means in practice" title="Smart. Honest. Clear. Quick."
                                       subtitle="Four things you should feel within five minutes of using StockWitty." />
                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($values as $i => $v)
                        <x-sw.reveal :delay="$i * 0.06" class="card-lift h-full rounded-2xl border border-border bg-card p-6 shadow-soft">
                            <span class="grid size-11 place-items-center rounded-xl bg-muted text-primary">
                                <x-sw.icon :name="$v['icon']" class="size-5" />
                            </span>
                            <h3 class="mt-4 text-lg font-bold text-foreground">{{ $v['t'] }}</h3>
                            <p class="mt-2 text-sm text-muted-foreground">{{ $v['d'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-[1.1fr_1fr]">
                    <x-sw.reveal>
                        <div>
                            <p class="eyebrow">Brand story</p>
                            <h2 class="mt-3 text-2xl font-bold text-foreground sm:text-3xl">
                                Built for investors tired of bland financial chatter
                            </h2>
                            <div class="mt-4 space-y-4 text-sm text-muted-foreground sm:text-base">
                                <p>
                                    The unlisted market in India grew up on WhatsApp forwards. A price, a promise of an
                                    IPO "next quarter", and a broker who goes quiet the moment you ask about the
                                    spread. Everything was either dry and corporate or breathless and unverifiable.
                                </p>
                                <p>
                                    We started StockWitty because there was an obvious gap: research that reads like a
                                    person wrote it, with the awkward parts left in. So every company page carries a
                                    WittyScore, a valuation view we're willing to defend, and a bear case sitting right
                                    next to the bull case.
                                </p>
                                <p>
                                    We're a distributor, not a SEBI-registered investment adviser — which means we can
                                    show you the work but the decision stays yours. That's not a limitation we
                                    tolerate; it's the relationship we actually want.
                                </p>
                            </div>
                        </div>
                    </x-sw.reveal>

                    <x-sw.reveal :delay="0.1" class="bg-price-card h-full rounded-3xl p-7 text-white">
                        <span class="grid size-11 place-items-center rounded-xl bg-white/10 text-mint-bright">
                            <x-sw.icon name="sparkles" class="size-5" />
                        </span>
                        <p class="mt-5 text-lg leading-relaxed font-semibold sm:text-xl">
                            "Invest Smart, Stay Witty" isn't about being clever. It's about being the kind of
                            investor who reads the risk section first — and having a platform that actually
                            writes one.
                        </p>
                        <ul class="mt-7 space-y-3 text-sm text-white/80">
                            @foreach ($brandBullets as $l)
                                <li class="rounded-xl bg-white/[0.06] p-3.5 font-semibold">{{ $l }}</li>
                            @endforeach
                        </ul>
                    </x-sw.reveal>
                </div>

                <div class="mt-10 flex flex-wrap items-center justify-between gap-5 rounded-3xl border border-border bg-green-50 p-7">
                    <div>
                        <h2 class="text-xl font-bold text-foreground sm:text-2xl">Ready to see the research?</h2>
                        <p class="mt-1.5 text-sm text-muted-foreground">
                            Start with the companies, or start with the method — either way, nothing is hidden.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="/unlisted-shares/" class="bg-cta inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-bold text-white">
                            Explore unlisted shares <x-sw.icon name="arrow-right" class="size-4" />
                        </a>
                        <a href="/wittyscore/" class="inline-flex items-center gap-2 rounded-xl border border-primary/40 bg-card px-5 py-3 text-sm font-bold text-primary transition-colors hover:bg-muted">
                            See how WittyScore works
                        </a>
                    </div>
                </div>

                <x-sw.illustrative-note>
                    StockWitty is an information portal and a distributor of unlisted shares — not a
                    SEBI-registered investment adviser. Nothing here is investment advice.
                </x-sw.illustrative-note>
            </div>
        </section>
    </main>
</div>
@endsection
