@extends('layouts.sw')

@section('title', 'Unlisted & Pre-IPO Shares in India — Live Prices | StockWitty')
@section('description', 'Browse unlisted and pre-IPO shares in India — NSE India, Tata Capital, Reliance Retail and more. Prices, WittyScore and lot sizes in one honest directory.')

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Unlisted Shares']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="{{ count($companies) }} companies tracked" title="Unlisted &amp; Pre-IPO Shares in India"
                        subtitle="Every name we deal in, with an honest WittyScore, current indicative price and the minimum lot you'd need. No hype, no hidden spread talk." />

        <section class="py-14 sm:py-20" x-data="unlistedShares()" data-companies="{{ json_encode($companies) }}">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <x-sw.chips :options="['All', 'Pre-IPO', 'Unicorn', 'DRHP-Filed', 'Trending']" model="filter" />
                    <div class="flex flex-wrap gap-3">
                        <label class="relative">
                            <x-sw.icon name="search" class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
                            <input x-model="q" type="text" placeholder="Search a company" aria-label="Search companies"
                                   class="w-56 rounded-xl border border-border bg-card py-2.5 pr-3 pl-9 text-sm outline-none focus:border-primary" />
                        </label>
                        <select x-model="sector" aria-label="Filter by sector"
                                class="rounded-xl border border-border bg-card px-3 py-2.5 text-sm font-semibold text-foreground outline-none focus:border-primary">
                            <option>All sectors</option>
                            @foreach ($sectors as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                        <select x-model="sort" aria-label="Sort companies"
                                class="rounded-xl border border-border bg-card px-3 py-2.5 text-sm font-semibold text-foreground outline-none focus:border-primary">
                            @foreach (['Trending', 'Price: high to low', 'Price: low to high', 'WittyScore'] as $s)
                                <option value="{{ $s }}">Sort: {{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <p class="mt-6 text-sm text-muted-foreground">
                    Showing <span class="font-bold text-foreground" x-text="list.length"></span> of {{ count($companies) }} companies
                </p>

                <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <template x-for="c in list" :key="c.slug">
                        <a :href="'/unlisted-shares/' + c.slug + '/'" class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <div class="flex items-start justify-between gap-3">
                                <span class="grid size-11 place-items-center rounded-xl bg-price-card text-xs font-bold text-white" x-text="c.initials"></span>
                                <span class="rounded-full bg-mint/15 px-2.5 py-1 text-[0.65rem] font-bold tracking-wide text-primary uppercase" x-text="c.tag"></span>
                            </div>
                            <h2 class="mt-4 text-base font-bold text-foreground" x-text="c.name"></h2>
                            <p class="text-xs font-semibold text-muted-foreground" x-text="c.sector"></p>
                            <div class="mt-4 flex items-end justify-between">
                                <p class="text-2xl font-bold text-foreground" x-text="'₹' + c.price.toLocaleString('en-IN')"></p>
                                <span class="inline-flex items-center gap-1 text-sm font-bold" :class="c.changePct >= 0 ? 'text-primary' : 'text-destructive'">
                                    <span x-text="c.changePct >= 0 ? '▲' : '▼'"></span>
                                    <span x-text="(c.changePct >= 0 ? '+' : '') + c.changePct + '%'"></span>
                                </span>
                            </div>
                            <dl class="mt-4 grid grid-cols-2 gap-2 border-t border-border pt-4 text-xs">
                                <div>
                                    <dt class="text-muted-foreground">Lot size</dt>
                                    <dd class="font-bold text-foreground" x-text="c.lot"></dd>
                                </div>
                                <div>
                                    <dt class="text-muted-foreground">Min investment</dt>
                                    <dd class="font-bold text-foreground" x-text="minInvestment(c.price, c.lot)"></dd>
                                </div>
                            </dl>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="rounded-lg bg-green-50 px-2.5 py-1 text-xs font-bold text-primary">
                                    WittyScore <span x-text="c.wittyScore.toFixed(1)"></span>
                                </span>
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-primary">View →</span>
                            </div>
                        </a>
                    </template>
                </div>

                <p x-show="list.length === 0" style="display: none;" class="mt-10 rounded-2xl border border-border bg-green-50 p-8 text-center text-sm text-muted-foreground">
                    No company matches those filters. Try clearing the search or picking another sector.
                </p>

                <x-sw.illustrative-note />
            </div>
        </section>
    </main>
</div>
@endsection
