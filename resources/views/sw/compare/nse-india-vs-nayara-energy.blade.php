@extends('layouts.sw')

@section('title', 'NSE India vs Nayara Energy — Unlisted Share Comparison | StockWitty')
@section('description', 'NSE India vs Nayara Energy unlisted shares compared: price, WittyScore, sector, business model, financial snapshot, IPO status, SWOT and our verdict.')

@php
$left = [
    'name' => 'NSE India Limited', 'initials' => 'NSE', 'price' => '₹1,960', 'score' => '8.5',
    'sector' => 'Capital Markets', 'model' => 'Transaction fees on exchange volumes, plus data, listing and index licensing revenue.',
    'fin' => 'High-margin, asset-light. Revenue and profit have compounded strongly with market volumes.',
    'ipo' => 'Regulatory no-objection received; DRHP path is the operative next step.',
    'strength' => 'Structural moat, near-monopoly derivatives franchise, exceptional margins.',
    'weak' => 'Regulatory and fee-cap risk. Earnings are levered to market activity.',
    'chip' => 'Pre-IPO',
    'opportunity' => 'Volume growth, data monetisation and eventual listing-led re-rating.',
];

$right = [
    'name' => 'Nayara Energy', 'initials' => 'NY', 'price' => '₹935', 'score' => '6.9',
    'sector' => 'Energy & Refining', 'model' => 'Refining throughput and a growing retail fuel network — a spread business, not a fee business.',
    'fin' => 'Large revenue base with cyclical, refining-margin-driven profitability.',
    'ipo' => 'No filing in the public domain. Any listing is speculative at this stage.',
    'strength' => 'Scale refinery assets and an expanding retail footprint.',
    'weak' => 'Cyclicality, crude and crack-spread exposure, and ownership-related complexity.',
    'chip' => 'Trending',
    'opportunity' => 'Retail network expansion and any structural improvement in refining spreads.',
];

$rows = [
    ['Indicative price', 'price'], ['WittyScore (0–10)', 'score'], ['Sector', 'sector'],
    ['Business model', 'model'], ['Financial snapshot', 'fin'], ['IPO status', 'ipo'],
    ['Key strength', 'strength'], ['Key risk', 'weak'],
];
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Compare', 'href' => '/compare/'], ['label' => 'NSE India vs Nayara Energy']]" />
    </div>

    <main>
        <section class="bg-price-card text-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold sm:text-4xl">NSE India vs Nayara Energy</h1>
                <p class="mt-3 max-w-2xl text-sm text-white/75">
                    A fee-based market infrastructure business against a cyclical refining business. Both are
                    unlisted; they are not remotely the same kind of risk.
                </p>
                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    @foreach ([$left, $right] as $c)
                        <div class="rounded-2xl border border-white/15 bg-white/[0.06] p-5">
                            <div class="flex items-center gap-3">
                                <span class="grid size-11 place-items-center rounded-xl bg-white/10 text-xs font-bold">{{ $c['initials'] }}</span>
                                <div>
                                    <p class="font-bold">{{ $c['name'] }}</p>
                                    <p class="text-xs text-mint-bright">{{ $c['chip'] }}</p>
                                </div>
                            </div>
                            <p class="mt-4 text-3xl font-bold">{{ $c['price'] }}</p>
                            <p class="text-xs text-white/70">WittyScore {{ $c['score'] }}/10</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.reveal>
                    <div class="overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
                        <table class="w-full min-w-[42rem] text-sm">
                            <caption class="sr-only">NSE India versus Nayara Energy comparison</caption>
                            <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                                <tr>
                                    <th scope="col" class="px-5 py-3">Metric</th>
                                    <th scope="col" class="px-5 py-3">NSE India</th>
                                    <th scope="col" class="px-5 py-3">Nayara Energy</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach ($rows as [$label, $key])
                                    <tr class="align-top">
                                        <th scope="row" class="px-5 py-4 text-left font-bold text-foreground">{{ $label }}</th>
                                        <td class="px-5 py-4 text-muted-foreground">{{ $left[$key] }}</td>
                                        <td class="px-5 py-4 text-muted-foreground">{{ $right[$key] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-sw.reveal>

                <div class="mt-8 grid gap-5 lg:grid-cols-2">
                    @foreach ([$left, $right] as $i => $c)
                        <x-sw.reveal :delay="$i * 0.08" class="h-full rounded-2xl border border-border bg-card p-6 shadow-soft">
                            <h2 class="text-lg font-bold text-foreground">{{ $c['name'] }} — SWOT in short</h2>
                            <dl class="mt-4 space-y-3 text-sm">
                                <div>
                                    <dt class="font-bold text-primary">Strengths</dt>
                                    <dd class="text-muted-foreground">{{ $c['strength'] }}</dd>
                                </div>
                                <div>
                                    <dt class="font-bold text-primary">Weaknesses / risks</dt>
                                    <dd class="text-muted-foreground">{{ $c['weak'] }}</dd>
                                </div>
                                <div>
                                    <dt class="font-bold text-primary">Opportunity</dt>
                                    <dd class="text-muted-foreground">{{ $c['opportunity'] }}</dd>
                                </div>
                            </dl>
                        </x-sw.reveal>
                    @endforeach
                </div>

                <x-sw.reveal>
                    <div class="bg-price-card mt-8 rounded-3xl p-7 text-white">
                        <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">Where we land</p>
                        <h2 class="mt-2 text-2xl font-bold">NSE India is the higher-quality business; neither is cheap</h2>
                        <p class="mt-3 max-w-3xl text-sm text-white/80">
                            On business quality, predictability and IPO visibility, NSE India wins clearly — hence
                            8.5 against 6.9. That does not make it the better buy at every price: much of the
                            listing optimism is already in the quote. Nayara is a cyclical you'd want to buy in a
                            weak spread environment, not a strong one. If you can only hold one and you're buying to
                            own a business for years, NSE India is the more defensible choice.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="/unlisted-shares/nse-india/thesis/" class="bg-cta rounded-xl px-5 py-3 text-sm font-bold text-white">
                                Read the full NSE India thesis
                            </a>
                            <a href="/compare/" class="rounded-xl border border-white/25 px-5 py-3 text-sm font-bold text-white hover:bg-white/10">
                                Compare other names
                            </a>
                        </div>
                    </div>
                </x-sw.reveal>

                <x-sw.illustrative-note />
            </div>
        </section>
    </main>
</div>
@endsection
