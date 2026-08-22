@extends('layouts.sw')

@section('title', 'Unlisted Shares Blog — Honest Guides & Tax Explainers | StockWitty')
@section('description', 'Plain-English guides on unlisted and pre-IPO shares in India: how to buy, tax treatment, DRHP, ISIN and CML basics, and what the risks really are.')

@php
$cats = ['All', 'Basics', 'Buying & Selling', 'Tax', 'Analysis', 'Glossary'];

$blogPosts = [
    ['slug' => 'how-to-buy-unlisted-shares', 'title' => 'How to Buy Unlisted Shares in India (2026)', 'category' => 'Buying & Selling', 'excerpt' => 'KYC, price confirmation, payment to a verified company account and off-market demat delivery — the whole process in order.', 'read' => '9 min read', 'date' => '12 Aug 2026'],
    ['slug' => 'what-are-unlisted-shares', 'title' => 'What Are Unlisted Shares?', 'category' => 'Basics', 'excerpt' => 'A plain-English definition, how the private market works in India, the types you\'ll meet, and who they actually suit.', 'read' => '7 min read', 'date' => '14 Aug 2026'],
    ['slug' => 'how-to-sell-unlisted-shares', 'title' => 'How to Sell Unlisted Shares in India', 'category' => 'Buying & Selling', 'excerpt' => 'Liquidity reality, getting a buy-side quote, the off-market transfer via DIS, lock-in and ROFR checks, and the documents you\'ll need.', 'read' => '8 min read', 'date' => '13 Aug 2026'],
    ['slug' => 'tax-on-unlisted-shares', 'title' => 'Tax on Unlisted Shares in India', 'category' => 'Tax', 'excerpt' => 'Holding period, LTCG vs STCG treatment, ITR reporting and what changes the day the company lists.', 'read' => '8 min read', 'date' => '04 Aug 2026'],
    ['slug' => 'unlisted-shares-vs-listed-shares', 'title' => 'Unlisted vs Listed Shares', 'category' => 'Basics', 'excerpt' => 'Liquidity, price discovery, disclosure and settlement — where the two really diverge.', 'read' => '7 min read', 'date' => '28 Jul 2026'],
    ['slug' => 'risks-of-investing-in-unlisted-shares', 'title' => 'Risks of Investing in Unlisted Shares', 'category' => 'Analysis', 'excerpt' => 'The honest list: illiquidity, valuation risk, no guaranteed IPO, thin disclosure, wide spreads — and how to reduce each.', 'read' => '8 min read', 'date' => '25 Jul 2026'],
    ['slug' => 'is-it-safe-to-buy-unlisted-shares', 'title' => 'Is It Safe to Buy Unlisted Shares?', 'category' => 'Analysis', 'excerpt' => "'Risky' and 'unsafe' aren't the same thing. The process checks that matter, and the red flags that should end a deal.", 'read' => '8 min read', 'date' => '21 Jul 2026'],
    ['slug' => 'what-is-drhp', 'title' => 'What is DRHP?', 'category' => 'Glossary', 'excerpt' => 'The draft red herring prospectus, why SEBI reviews it, and what a filing does (and doesn\'t) signal.', 'read' => '5 min read', 'date' => '14 Jul 2026'],
    ['slug' => 'what-is-isin-and-cml-copy', 'title' => 'What is ISIN & CML Copy?', 'category' => 'Glossary', 'excerpt' => 'Two documents every unlisted transaction depends on — and how to verify both before you pay.', 'read' => '4 min read', 'date' => '07 Jul 2026'],
];

$featured = $blogPosts[0];
$rest = array_slice($blogPosts, 1);
@endphp

@section('content')
<div class="min-h-screen bg-background" x-data="{ cat: 'All' }">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Blog']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="StockWitty research" title="Unlisted shares, explained — honestly."
                        subtitle="No jargon walls, no sales pitch dressed up as research. Just what we'd tell a friend before they wired money for pre-IPO shares." />

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.chips :options="$cats" model="cat" />

                <x-sw.reveal>
                    <a href="/blog/{{ $featured['slug'] }}/" class="card-lift mt-8 grid gap-6 overflow-hidden rounded-3xl border border-border bg-card p-6 shadow-soft sm:p-8 lg:grid-cols-[1.2fr_1fr]">
                        <div>
                            <span class="rounded-full bg-mint/15 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-primary uppercase">
                                Featured · {{ $featured['category'] }}
                            </span>
                            <h2 class="mt-4 text-2xl font-bold text-foreground sm:text-3xl">{{ $featured['title'] }}</h2>
                            <p class="mt-3 text-sm text-muted-foreground sm:text-base">{{ $featured['excerpt'] }}</p>
                            <p class="mt-5 flex flex-wrap items-center gap-4 text-xs font-bold text-muted-foreground">
                                <span class="inline-flex items-center gap-1.5">
                                    <x-sw.icon name="clock" class="size-3.5" /> {{ $featured['read'] }}
                                </span>
                                <span>Updated {{ $featured['date'] }}</span>
                                <span class="inline-flex items-center gap-1 text-primary">
                                    Read the guide <x-sw.icon name="arrow-right" class="size-3.5" />
                                </span>
                            </p>
                        </div>
                        <div class="bg-price-card grid min-h-40 place-items-center rounded-2xl p-6 text-center">
                            <p class="text-lg font-bold text-white">
                                The 5-step buying process,
                                <span class="text-mint-bright"> start to demat credit</span>
                            </p>
                        </div>
                    </a>
                </x-sw.reveal>

                <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($rest as $p)
                        <div x-show="cat === 'All' || cat === '{{ $p['category'] }}'" style="display: block;">
                            <a href="/blog/{{ $p['slug'] }}/" class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-6 shadow-soft">
                                <span class="w-fit rounded-full bg-green-50 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-primary uppercase">{{ $p['category'] }}</span>
                                <h3 class="mt-4 text-lg font-bold text-foreground">{{ $p['title'] }}</h3>
                                <p class="mt-2 flex-1 text-sm text-muted-foreground">{{ $p['excerpt'] }}</p>
                                <p class="mt-4 flex items-center justify-between text-xs font-bold text-muted-foreground">
                                    <span class="inline-flex items-center gap-1.5">
                                        <x-sw.icon name="clock" class="size-3.5" /> {{ $p['read'] }}
                                    </span>
                                    <span>{{ $p['date'] }}</span>
                                </p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
