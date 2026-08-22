@extends('layouts.sw')

@section('title', 'Pre-IPO, IPO & Unlisted Market News | StockWitty')
@section('description', "News that moves unlisted and pre-IPO prices in India — SEBI approvals, DRHP filings, startup funding rounds and mutual fund flows, with StockWitty's honest take.")

@php
$cats = ['All', 'IPO', 'Unlisted', 'Startup Funding', 'Mutual Funds'];
$catLinks = [
    ['label' => 'IPO news', 'href' => '/news/ipo/'],
    ['label' => 'Unlisted shares', 'href' => '/news/unlisted-shares/'],
    ['label' => 'Startup funding', 'href' => '/news/startup-funding/'],
    ['label' => 'Mutual funds', 'href' => '/news/mutual-funds/'],
];

$newsItems = [
    ['slug' => 'nse-ipo-sebi-noc-2026', 'title' => 'NSE India Inches Closer to IPO as SEBI Grants No-Objection', 'category' => 'IPO', 'date' => '15 Aug 2026', 'excerpt' => "The exchange's long-delayed listing takes a concrete step forward. What it means for unlisted holders — and what still has to happen."],
    ['slug' => 'tata-capital-drhp-update', 'title' => 'Tata Capital DRHP Update: Timelines and the Fine Print', 'category' => 'IPO', 'date' => '11 Aug 2026', 'excerpt' => 'The filing sets out an issue structure worth reading closely before you add at current unlisted prices.'],
    ['slug' => 'unlisted-volumes-cool-off', 'title' => 'Unlisted Deal Volumes Cool Off After a Hot Quarter', 'category' => 'Unlisted', 'date' => '08 Aug 2026', 'excerpt' => 'Spreads widened across pre-IPO names this month. A calmer market is usually a better entry.'],
    ['slug' => 'zepto-late-stage-round', 'title' => 'Zepto Closes a Late-Stage Round at a Higher Mark', 'category' => 'Startup Funding', 'date' => '05 Aug 2026', 'excerpt' => 'A fresh primary round resets the reference price that unlisted dealers quote from.'],
    ['slug' => 'sip-flows-record', 'title' => 'Monthly SIP Flows Hit Another Record', 'category' => 'Mutual Funds', 'date' => '02 Aug 2026', 'excerpt' => 'Retail discipline is holding up even through a choppy quarter for mid-caps.'],
    ['slug' => 'nsdl-listing-chatter', 'title' => 'NSDL Listing Chatter Returns to the Unlisted Desk', 'category' => 'Unlisted', 'date' => '29 Jul 2026', 'excerpt' => 'Depository names are back in demand. Our honest read on valuation at these levels.'],
];

$featured = $newsItems[0];
$rest = array_slice($newsItems, 1);
@endphp

@section('content')
<div class="min-h-screen bg-background" x-data="{ cat: 'All' }">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'News']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Market news" title="Pre-IPO, IPO &amp; unlisted market news."
                        subtitle="What actually changed today, why it matters for the price you're being quoted, and what we'd wait to confirm.">
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach ($catLinks as $c)
                    <a href="{{ $c['href'] }}" class="rounded-full border border-primary/30 bg-green-50 px-4 py-2 text-sm font-bold text-primary transition-colors hover:bg-mint/20">
                        {{ $c['label'] }}
                    </a>
                @endforeach
            </div>
        </x-sw.page-hero>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.reveal>
                    <a href="/news/{{ $featured['slug'] }}/" class="card-lift grid gap-6 rounded-3xl border border-border bg-card p-6 shadow-soft sm:p-8 lg:grid-cols-[1.3fr_1fr]">
                        <div>
                            <span class="rounded-full bg-mint/15 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-primary uppercase">
                                Top story · {{ $featured['category'] }}
                            </span>
                            <h2 class="mt-4 text-2xl font-bold text-foreground sm:text-3xl">{{ $featured['title'] }}</h2>
                            <p class="mt-3 text-sm text-muted-foreground sm:text-base">{{ $featured['excerpt'] }}</p>
                            <p class="mt-5 inline-flex items-center gap-2 text-xs font-bold text-muted-foreground">
                                {{ $featured['date'] }}
                                <span class="inline-flex items-center gap-1 text-primary">
                                    Read <x-sw.icon name="arrow-right" class="size-3.5" />
                                </span>
                            </p>
                        </div>
                        <div class="bg-price-card grid min-h-36 place-items-center rounded-2xl p-6 text-center">
                            <p class="text-lg font-bold text-white">
                                NSE India: <span class="text-mint-bright">SEBI no-objection granted</span>
                            </p>
                        </div>
                    </a>
                </x-sw.reveal>

                <div class="mt-10">
                    <x-sw.chips :options="$cats" model="cat" />
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($rest as $n)
                        <div x-show="cat === 'All' || cat === '{{ $n['category'] }}'" style="display: block;">
                            <a href="/news/{{ $n['slug'] }}/" class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-6 shadow-soft">
                                <span class="w-fit rounded-full bg-green-50 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-primary uppercase">{{ $n['category'] }}</span>
                                <h3 class="mt-4 text-lg font-bold text-foreground">{{ $n['title'] }}</h3>
                                <p class="mt-2 flex-1 text-sm text-muted-foreground">{{ $n['excerpt'] }}</p>
                                <p class="mt-4 text-xs font-bold text-muted-foreground">{{ $n['date'] }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
