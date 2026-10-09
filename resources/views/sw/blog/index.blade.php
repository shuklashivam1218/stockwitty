@extends('layouts.sw')

@section('title', 'Unlisted Shares Blog — Honest Guides & Tax Explainers | StocksWitty')
@section('description', 'Plain-English guides on unlisted and pre-IPO shares in India: how to buy, tax treatment, DRHP, ISIN and CML basics, and what the risks really are.')

@section('content')
<div class="min-h-screen bg-background" x-data="{ cat: 'All' }">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Blog']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="StocksWitty research" title="Unlisted shares, explained — honestly."
                        subtitle="No jargon walls, no sales pitch dressed up as research. Just what we'd tell a friend before they wired money for pre-IPO shares." />

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @if (count($cats) > 2)
                    <x-sw.chips :options="$cats" model="cat" />
                @endif

                @if ($featured)
                <x-sw.reveal x-show="cat === 'All' || cat === @js($featured['category'])">
                    <a href="{{ $featured['href'] }}" class="card-lift mt-8 grid gap-6 overflow-hidden rounded-3xl border border-border bg-card p-6 shadow-soft sm:p-8 lg:grid-cols-[1.2fr_1fr]">
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
                                <span>{{ $featured['date'] }}</span>
                                <span class="inline-flex items-center gap-1 text-primary">
                                    Read the guide <x-sw.icon name="arrow-right" class="size-3.5" />
                                </span>
                            </p>
                        </div>
                        @if ($featured['image'])
                            <img src="{{ $featured['image'] }}" alt="{{ $featured['imageAlt'] }}" loading="lazy"
                                 class="h-full min-h-40 w-full rounded-2xl object-cover" />
                        @else
                            <div class="bg-price-card grid min-h-40 place-items-center rounded-2xl p-6 text-center">
                                @if ($featured['heroIcon'])
                                    <span class="grid size-14 place-items-center rounded-2xl bg-white/10 text-mint-bright ring-1 ring-white/20">
                                        <x-sw.icon :name="$featured['heroIcon']" class="size-7" />
                                    </span>
                                @endif
                                <p class="text-lg font-bold text-white">{{ $featured['title'] }}</p>
                            </div>
                        @endif
                    </a>
                </x-sw.reveal>
                @else
                    <p class="mt-8 rounded-2xl border border-dashed border-border bg-card p-10 text-center text-sm font-semibold text-muted-foreground">New guides are on their way — check back soon.</p>
                @endif

                <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($rest as $p)
                        <div x-show="cat === 'All' || cat === @js($p['category'])">
                            <a href="{{ $p['href'] }}" class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-6 shadow-soft">
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
