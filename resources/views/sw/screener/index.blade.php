@extends('layouts.sw')

@section('title', 'AI-Powered Unlisted Share Screener — Filter 245+ Stocks | StockWitty')
@section('description', 'Screen unlisted and pre-IPO shares in plain English — filter by sector, price, WittyScore, tag and IPO probability across 245+ companies.')

@section('content')
<div class="min-h-screen bg-background" x-data="screener()" data-companies="{{ json_encode(config('sw.unlisted_companies')) }}">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Screener']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="245+ unlisted stocks" title="AI-powered unlisted screener."
                        subtitle="Describe what you're looking for in plain English, or use the filters. Both drive the same result set.">
            <div class="mt-6 max-w-2xl">
                <label class="relative block">
                    <x-sw.icon name="search" class="pointer-events-none absolute top-1/2 left-4 size-5 -translate-y-1/2 text-muted-foreground" />
                    <input type="text" x-model="nl" aria-label="Describe what you are screening for"
                           placeholder="e.g. profitable fintechs under ₹500 with an IPO likely"
                           class="w-full rounded-2xl border border-border bg-card py-4 pr-4 pl-12 text-sm shadow-soft outline-none focus:border-primary" />
                </label>
                <span class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-mint/15 px-3 py-1 text-xs font-bold text-primary">
                    <x-sw.icon name="sparkles" class="size-3.5" /> Powered by AI
                </span>
            </div>
        </x-sw.page-hero>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-[17rem_1fr]">
                    <aside class="h-fit rounded-2xl border border-border bg-card p-5 shadow-soft">
                        <h2 class="text-sm font-bold tracking-widest text-primary uppercase">Filters</h2>

                        <div class="mt-4 space-y-5 text-sm">
                            <label class="block">
                                <span class="font-semibold text-foreground">Sector</span>
                                <select x-model="sector" class="mt-2 w-full rounded-xl border border-border bg-background px-3 py-2.5 font-semibold outline-none focus:border-primary">
                                    <option>All sectors</option>
                                    @foreach (config('sw.sectors') as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="block">
                                <span class="font-semibold text-foreground">Max price · ₹<span x-text="maxPrice.toLocaleString('en-IN')"></span></span>
                                <input type="range" min="50" max="15000" step="50" x-model.number="maxPrice" class="mt-3 w-full accent-[var(--brand)]" />
                            </label>

                            <label class="block">
                                <span class="font-semibold text-foreground">Min WittyScore · <span x-text="minScore.toFixed(1)"></span></span>
                                <input type="range" min="0" max="10" step="0.1" x-model.number="minScore" class="mt-3 w-full accent-[var(--brand)]" />
                            </label>

                            <fieldset>
                                <legend class="font-semibold text-foreground">Tag</legend>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach (['Any', 'Pre-IPO', 'Unicorn', 'Trending'] as $t)
                                        <button type="button" @click="tag = '{{ $t }}'" :aria-pressed="tag === '{{ $t }}'"
                                                :class="tag === '{{ $t }}' ? 'border-primary bg-primary text-primary-foreground' : 'border-border text-muted-foreground hover:text-primary'"
                                                class="rounded-full border px-3 py-1.5 text-xs font-bold">
                                            {{ $t }}
                                        </button>
                                    @endforeach
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend class="font-semibold text-foreground">IPO probability</legend>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach (['Any', 'High', 'Medium'] as $t)
                                        <button type="button" @click="ipo = '{{ $t }}'" :aria-pressed="ipo === '{{ $t }}'"
                                                :class="ipo === '{{ $t }}' ? 'border-primary bg-primary text-primary-foreground' : 'border-border text-muted-foreground hover:text-primary'"
                                                class="rounded-full border px-3 py-1.5 text-xs font-bold">
                                            {{ $t }}
                                        </button>
                                    @endforeach
                                </div>
                            </fieldset>

                            <button type="button" @click="reset()" class="w-full rounded-xl border border-primary/40 px-4 py-2.5 font-bold text-primary hover:bg-muted">
                                Reset filters
                            </button>
                        </div>
                    </aside>

                    <div>
                        <p class="text-sm text-muted-foreground">
                            <span class="font-bold text-foreground" x-text="rows.length"></span> matches from 245+ screened unlisted stocks
                        </p>
                        <x-sw.reveal>
                            <div class="mt-3 overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
                                <table class="w-full min-w-[40rem] text-sm">
                                    <caption class="sr-only">Screener results</caption>
                                    <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                                        <tr>
                                            <th scope="col" class="px-4 py-3">Company</th>
                                            <th scope="col" class="px-4 py-3">Sector</th>
                                            <th scope="col" class="px-4 py-3">Tag</th>
                                            <th scope="col" class="px-4 py-3">IPO prob.</th>
                                            <th scope="col" class="px-4 py-3 text-right">Price</th>
                                            <th scope="col" class="px-4 py-3 text-right">WittyScore</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-border">
                                        <template x-for="c in rows" :key="c.slug">
                                            <tr class="hover:bg-green-50/60">
                                                <th scope="row" class="px-4 py-3 text-left">
                                                    <a :href="'/unlisted-shares/' + c.slug + '/'" class="font-bold text-foreground hover:text-primary" x-text="c.name"></a>
                                                </th>
                                                <td class="px-4 py-3 text-muted-foreground" x-text="c.sector"></td>
                                                <td class="px-4 py-3 text-muted-foreground" x-text="c.tag"></td>
                                                <td class="px-4 py-3 text-muted-foreground" x-text="ipoProbability(c)"></td>
                                                <td class="px-4 py-3 text-right font-bold text-foreground" x-text="'₹' + c.price.toLocaleString('en-IN')"></td>
                                                <td class="px-4 py-3 text-right font-bold text-primary" x-text="c.wittyScore.toFixed(1)"></td>
                                            </tr>
                                        </template>
                                        <tr x-show="rows.length === 0" style="display: none;">
                                            <td colspan="6" class="px-4 py-10 text-center text-muted-foreground">
                                                Nothing matches that screen. Loosen a filter or clear the search.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </x-sw.reveal>
                        <x-sw.illustrative-note />
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
