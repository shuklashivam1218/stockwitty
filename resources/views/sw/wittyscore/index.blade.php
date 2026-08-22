@extends('layouts.sw')

@section('title', 'WittyScore Methodology — How We Score Unlisted Shares | StockWitty')
@section('description', 'WittyScore is our 0–10 score for unlisted companies, built from five weighted pillars: Financial Health 30%, Valuation 20%, Growth Potential 20%, IPO Probability 15% and Liquidity & Safety 15%.')

@php
$pillars = [
    ['icon' => 'bar-chart-3', 'name' => 'Financial Health', 'key' => 'F', 'weight' => 30, 'what' => 'Revenue quality, margins, return on equity, cash generation and debt across the last three reported years.', 'penalise' => 'One-off gains dressed up as operating profit, and rising debt with flat cash flow.'],
    ['icon' => 'building-2', 'name' => 'Valuation', 'key' => 'V', 'weight' => 20, 'what' => 'The current unlisted price against listed peers on P/E and P/B, adjusted for an illiquidity discount.', 'penalise' => 'Paying a premium to listed peers for the privilege of being illiquid.'],
    ['icon' => 'trending-up', 'name' => 'Growth Potential', 'key' => 'G', 'weight' => 20, 'what' => "Sector growth runway, market position, and whether the company's own growth engine is structural or cyclical.", 'penalise' => 'A single blockbuster year after four flat ones.'],
    ['icon' => 'shield-check', 'name' => 'IPO Probability', 'key' => 'I', 'weight' => 15, 'what' => 'DRHP status, regulatory approvals, promoter intent and how credible the stated listing path actually looks.', 'penalise' => 'Years of IPO talk with no filing, and repeated timeline slippage.'],
    ['icon' => 'coins', 'name' => 'Liquidity & Safety', 'key' => 'L', 'weight' => 15, 'what' => 'How often lots actually change hands, the visible bid-ask spread, plus governance, auditor history and disclosure hygiene.', 'penalise' => 'Wide spreads, thin volumes, auditor churn and companies that go quiet for two quarters.'],
];

$nseContext = [
    ['Financial Health', 'Strong — roughly 63% net margin, consistently profitable, revenue growing around 17%.'],
    ['Valuation', 'The weakest pillar — at roughly 40× earnings, quality is largely in the price.'],
    ['Growth Potential', 'Sector compounding near 18% CAGR, and NSE is the clear market leader.'],
    ['IPO Probability', 'SEBI NOC progress makes a listing more likely than at any point since 2016.'],
    ['Liquidity & Safety', 'High — the most actively traded unlisted share in India.'],
];

$scaleBands = [
    ['8.0 – 10', 'Strong fundamentals and a credible, visible exit path.'],
    ['6.5 – 7.9', 'Solid business, but one pillar — usually valuation or liquidity — is stretched.'],
    ['5.0 – 6.4', 'Real question marks. Fine as a small position, not as a core holding.'],
    ['Below 5.0', 'We would not put our own money in at the current price.'],
];

$board = collect(config('sw.unlisted_companies'))->sortByDesc('wittyScore')->take(8)->values()->all();

$faqs = [
    ['q' => 'Is a high WittyScore a buy recommendation?', 'a' => 'No. StockWitty is a distributor, not a SEBI-registered investment adviser. WittyScore is a research summary of what the numbers and disclosures look like — it is not advice and does not predict returns.'],
    ['q' => 'How often is the score refreshed?', 'a' => 'After every set of audited annual results, and sooner if there is a material event — a DRHP filing, an auditor change, or a large funding round.'],
    ['q' => 'Why does a well-known unicorn score below a boring finance company?', 'a' => 'Because profitability and governance carry half the weight between them. Brand recognition carries none.'],
];
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'WittyScore']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Methodology" title="WittyScore: how we grade an unlisted company."
                        subtitle="One number out of ten, built from five pillars we can show you. No black box, no paid placement — and no promises about price." />

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="The formula" title="Five pillars, fixed weights, one number"
                                       subtitle="Every pillar is scored 0–10, then weighted. Nothing is fudged after the fact." />

                <x-sw.reveal>
                    <div class="bg-price-card mt-6 overflow-x-auto rounded-3xl p-6 text-white sm:p-8">
                        <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">WittyScore formula</p>
                        <p class="mt-3 font-mono text-base leading-relaxed font-bold sm:text-xl">
                            WittyScore = F×0.30 + V×0.20 + G×0.20 + I×0.15 + L×0.15
                        </p>
                        <p class="mt-3 text-sm text-white/70">
                            F = Financial Health · V = Valuation · G = Growth Potential · I = IPO Probability ·
                            L = Liquidity &amp; Safety
                        </p>
                    </div>
                </x-sw.reveal>

                <div class="mt-6 overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
                    <table class="w-full min-w-[34rem] text-sm">
                        <caption class="sr-only">WittyScore pillar weights</caption>
                        <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                            <tr>
                                <th scope="col" class="px-5 py-3">Pillar</th>
                                <th scope="col" class="px-5 py-3">Symbol</th>
                                <th scope="col" class="px-5 py-3 text-right">Weight</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($pillars as $p)
                                <tr>
                                    <th scope="row" class="px-5 py-3.5 text-left font-bold text-foreground">{{ $p['name'] }}</th>
                                    <td class="px-5 py-3.5 font-mono text-muted-foreground">{{ $p['key'] }}</td>
                                    <td class="px-5 py-3.5 text-right font-bold text-primary">{{ $p['weight'] }}%</td>
                                </tr>
                            @endforeach
                            <tr class="bg-green-50">
                                <th scope="row" class="px-5 py-3.5 text-left font-bold text-foreground">Total</th>
                                <td class="px-5 py-3.5"></td>
                                <td class="px-5 py-3.5 text-right font-bold text-primary">100%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <x-sw.reveal :delay="0.08">
                    <div class="mt-6 rounded-3xl border border-border bg-card p-6 shadow-soft">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h3 class="text-lg font-bold text-foreground">Worked example — NSE India Limited</h3>
                            <span class="rounded-full bg-mint/15 px-4 py-1.5 text-sm font-bold text-primary">WittyScore 8.5 / 10 · Strong</span>
                        </div>
                        <ul class="mt-4 space-y-2.5 text-sm">
                            @foreach ($nseContext as [$k, $v])
                                <li class="border-b border-border pb-2.5 last:border-0">
                                    <span class="font-bold text-foreground">{{ $k }}: </span>
                                    <span class="text-muted-foreground">{{ $v }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="/unlisted-shares/nse-india/thesis/" class="mt-5 inline-block text-sm font-bold text-primary hover:underline">
                            Read the full NSE India thesis →
                        </a>
                    </div>
                </x-sw.reveal>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-[1fr_1.2fr]">
                    <x-sw.reveal class="bg-price-card h-full rounded-3xl p-8 text-white">
                        <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">Scale</p>
                        <p class="mt-4 text-6xl font-bold">
                            <x-sw.count-up :to="10" />
                            <span class="text-2xl text-white/60">/10</span>
                        </p>
                        <ul class="mt-8 space-y-3 text-sm text-white/80">
                            @foreach ($scaleBands as [$band, $note])
                                <li class="rounded-xl bg-white/[0.06] p-3.5">
                                    <span class="font-bold text-mint-bright">{{ $band }}</span>
                                    <span class="mt-1 block text-white/70">{{ $note }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </x-sw.reveal>

                    <div class="grid gap-4">
                        @foreach ($pillars as $i => $p)
                            <x-sw.reveal :delay="$i * 0.06" class="rounded-2xl border border-border bg-card p-6 shadow-soft">
                                <div class="flex items-start gap-4">
                                    <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-muted text-primary">
                                        <x-sw.icon :name="$p['icon']" class="size-5" />
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-baseline justify-between gap-3">
                                            <h2 class="font-bold text-foreground">{{ $p['name'] }}</h2>
                                            <span class="text-sm font-bold text-primary">{{ $p['weight'] }}%</span>
                                        </div>
                                        <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-green-50" role="img" aria-label="{{ $p['name'] }} weight {{ $p['weight'] }} percent">
                                            <div class="bg-cta h-full rounded-full transition-[width] duration-700" style="width: {{ $p['weight'] * 3.2 }}%"></div>
                                        </div>
                                        <p class="mt-3 text-sm text-muted-foreground">{{ $p['what'] }}</p>
                                        <p class="mt-2 text-sm text-foreground">
                                            <span class="font-semibold">We mark down for:</span> {{ $p['penalise'] }}
                                        </p>
                                    </div>
                                </div>
                            </x-sw.reveal>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Leaderboard" title="Highest WittyScores right now"
                                       subtitle="Ranked by score, not by how much we'd like to sell you." />
                <div class="mt-6 overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
                    <table class="w-full min-w-[36rem] text-sm">
                        <thead class="bg-green-50 text-left">
                            <tr class="text-xs tracking-wide text-muted-foreground uppercase">
                                <th class="px-5 py-3 font-bold">Company</th>
                                <th class="px-5 py-3 font-bold">Sector</th>
                                <th class="px-5 py-3 font-bold">Price</th>
                                <th class="px-5 py-3 font-bold">WittyScore</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($board as $c)
                                <tr>
                                    <td class="px-5 py-3.5 font-bold text-foreground">{{ $c['name'] }}</td>
                                    <td class="px-5 py-3.5 text-muted-foreground">{{ $c['sector'] }}</td>
                                    <td class="px-5 py-3.5 font-semibold text-foreground">₹{{ number_format($c['price']) }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="rounded-full bg-mint/15 px-3 py-1 font-bold text-primary">{{ number_format($c['wittyScore'], 1) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="FAQ" title="What WittyScore is — and isn't" />
                <div class="mt-6 divide-y divide-border rounded-2xl border border-border bg-card">
                    @foreach ($faqs as $f)
                        <div class="p-5">
                            <h3 class="font-bold text-foreground">{{ $f['q'] }}</h3>
                            <p class="mt-1.5 text-sm text-muted-foreground">{{ $f['a'] }}</p>
                        </div>
                    @endforeach
                </div>
                <x-sw.illustrative-note>
                    Scores and prices on this page are illustrative demo data. WittyScore is research, not
                    investment advice — unlisted shares are illiquid and high-risk.
                </x-sw.illustrative-note>
            </div>
        </section>
    </main>
</div>
@endsection
