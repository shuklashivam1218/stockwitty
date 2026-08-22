@extends('layouts.sw')

@section('title', '41 Investment Calculators — SIP, FD, Tax & Unlisted | StockWitty')
@section('description', 'Free, honest investment calculators for Indian investors — a fully working SIP calculator plus 40 more tools across unlisted shares, mutual funds, fixed income, retirement, gold and tax.')

@php
$calcCategories = ['All', 'Unlisted', 'Mutual Funds', 'Fixed Income', 'Retirement', 'Gold & ETF', 'General'];

$calcGroups = [
    ['category' => 'Unlisted', 'heading' => 'Unlisted & pre-IPO', 'blurb' => 'Ticket sizes, listing gains and the 24-month tax rule for unlisted shares.', 'items' => [
        ['name' => 'Unlisted Share Investment Calculator', 'href' => '/calculators/unlisted-investment/', 'desc' => 'Price × lot to your all-in ticket size, before you commit.', 'icon' => 'calculator', 'popular' => true],
        ['name' => 'Unlisted Shares Profit / Return Calculator', 'href' => '/calculators/unlisted-returns/', 'desc' => 'Absolute profit and CAGR between your buy and exit price.', 'icon' => 'trending-up', 'popular' => true],
        ['name' => 'Pre-IPO to Listing Gain Estimator', 'href' => '/calculators/pre-ipo-gain/', 'desc' => 'Model listing-day scenarios against your pre-IPO entry.', 'icon' => 'sparkles', 'popular' => true],
        ['name' => 'Unlisted Shares Tax Calculator', 'href' => '/calculators/unlisted-tax/', 'desc' => 'LTCG vs STCG using the 24-month holding rule for unlisted equity.', 'icon' => 'receipt', 'popular' => true],
        ['name' => 'Minimum Investment / Lot Size Calculator', 'href' => '/calculators/lot-size/', 'desc' => 'Smallest tradeable quantity and what it costs today.', 'icon' => 'scale'],
        ['name' => 'WittyScore Calculator', 'href' => '/wittyscore/', 'desc' => 'Score a company across our five pillars, 0 to 10.', 'icon' => 'shield'],
        ['name' => 'Brokerage & Charges Calculator', 'href' => '/calculators/brokerage/', 'desc' => 'Brokerage, STT, GST and stamp duty on a listed trade.', 'icon' => 'credit-card'],
        ['name' => 'Pre-money / Post-money Dilution Calculator', 'href' => '/calculators/dilution/', 'desc' => 'See how a new funding round dilutes your stake.', 'icon' => 'split'],
    ]],
    ['category' => 'Mutual Funds', 'heading' => 'Mutual funds', 'blurb' => 'SIPs, lumpsums, withdrawals and goal maths.', 'items' => [
        ['name' => 'SIP Calculator', 'href' => '/calculators/sip/', 'desc' => 'Project a monthly SIP with the standard future-value formula.', 'icon' => 'repeat', 'popular' => true, 'live' => true],
        ['name' => 'Lumpsum Calculator', 'href' => '/calculators/lumpsum/', 'desc' => 'One-time investment compounded over your horizon.', 'icon' => 'piggy-bank', 'popular' => true],
        ['name' => 'Step-up SIP Calculator', 'href' => '/calculators/step-up-sip/', 'desc' => 'Raise your SIP each year and see the difference.', 'icon' => 'bar-chart-3'],
        ['name' => 'SWP Calculator', 'href' => '/calculators/swp/', 'desc' => 'How long a corpus lasts with regular withdrawals.', 'icon' => 'wallet'],
        ['name' => 'STP Calculator', 'href' => '/calculators/stp/', 'desc' => 'Transfer from debt to equity in planned tranches.', 'icon' => 'split'],
        ['name' => 'MF Returns (CAGR / XIRR) Calculator', 'href' => '/calculators/mf-returns/', 'desc' => 'Annualised return on lumpsum or irregular cashflows.', 'icon' => 'line-chart'],
        ['name' => 'Goal-based SIP Calculator', 'href' => '/calculators/goal-sip/', 'desc' => 'Start from the target, work back to the monthly amount.', 'icon' => 'target'],
        ['name' => 'ELSS Tax-Saving Calculator', 'href' => '/calculators/elss/', 'desc' => '80C savings plus growth over the 3-year lock-in.', 'icon' => 'receipt'],
        ['name' => 'SIP vs Lumpsum Comparison', 'href' => '/calculators/sip-vs-lumpsum/', 'desc' => 'Same money, two routes, side-by-side outcomes.', 'icon' => 'scale'],
    ]],
    ['category' => 'Fixed Income', 'heading' => 'Fixed income', 'blurb' => 'Deposits, recurring deposits and bond yields.', 'items' => [
        ['name' => 'FD Calculator', 'href' => '/calculators/fd/', 'desc' => 'Maturity value with quarterly compounding.', 'icon' => 'landmark', 'popular' => true],
        ['name' => 'RD Calculator', 'href' => '/calculators/rd/', 'desc' => 'Monthly deposits into a recurring deposit.', 'icon' => 'banknote'],
        ['name' => 'FD with TDS Calculator', 'href' => '/calculators/fd-tds/', 'desc' => 'Post-tax FD returns after TDS at your slab.', 'icon' => 'receipt'],
        ['name' => 'NCD / Bond Yield Calculator', 'href' => '/calculators/bond-yield/', 'desc' => 'Current yield and yield to maturity on a bond.', 'icon' => 'percent'],
    ]],
    ['category' => 'Retirement', 'heading' => 'Retirement & goals', 'blurb' => 'Long-horizon planning for the things that matter.', 'items' => [
        ['name' => 'Retirement Corpus Calculator', 'href' => '/calculators/retirement/', 'desc' => 'The corpus you need, adjusted for inflation.', 'icon' => 'umbrella', 'popular' => true],
        ['name' => 'NPS Calculator', 'href' => '/calculators/nps/', 'desc' => 'NPS corpus, annuity split and monthly pension.', 'icon' => 'building-2'],
        ['name' => 'PPF Calculator', 'href' => '/calculators/ppf/', 'desc' => '15-year PPF maturity at the current rate.', 'icon' => 'hand-coins'],
        ['name' => 'EPF Calculator', 'href' => '/calculators/epf/', 'desc' => 'Employee and employer contributions till retirement.', 'icon' => 'landmark'],
        ['name' => "Children's Education Planner", 'href' => '/calculators/education/', 'desc' => 'Future fee cost and the SIP that funds it.', 'icon' => 'graduation-cap'],
        ['name' => 'Emergency Fund Calculator', 'href' => '/calculators/emergency-fund/', 'desc' => 'How many months of expenses you should hold.', 'icon' => 'shield'],
        ['name' => 'Marriage / Goal Planner', 'href' => '/calculators/goal-planner/', 'desc' => "Any dated goal, worked back to today's savings.", 'icon' => 'target'],
    ]],
    ['category' => 'Gold & ETF', 'heading' => 'Gold, silver & ETF', 'blurb' => 'Metals and index products, in rupee terms.', 'items' => [
        ['name' => 'Digital Gold Investment Calculator', 'href' => '/calculators/gold/', 'desc' => 'Grams bought and value at a target gold price.', 'icon' => 'coins'],
        ['name' => 'Gold SIP Calculator', 'href' => '/calculators/gold-sip/', 'desc' => 'Accumulate grams monthly at changing prices.', 'icon' => 'repeat'],
        ['name' => 'ETF Returns Calculator', 'href' => '/calculators/etf-returns/', 'desc' => 'Returns net of expense ratio and tracking drag.', 'icon' => 'line-chart'],
    ]],
    ['category' => 'General', 'heading' => 'General finance', 'blurb' => 'Tax, loans, interest and the everyday maths.', 'items' => [
        ['name' => 'Income Tax Calculator (Old vs New)', 'href' => '/calculators/income-tax/', 'desc' => 'Compare both regimes on the same income.', 'icon' => 'receipt', 'popular' => true],
        ['name' => 'Capital Gains Tax Calculator', 'href' => '/calculators/capital-gains/', 'desc' => 'Short and long-term gains across asset classes.', 'icon' => 'indian-rupee', 'popular' => true],
        ['name' => 'Compound Interest Calculator', 'href' => '/calculators/compound-interest/', 'desc' => 'Any principal, rate and compounding frequency.', 'icon' => 'trending-up'],
        ['name' => 'Simple Interest Calculator', 'href' => '/calculators/simple-interest/', 'desc' => 'Flat interest on a principal over time.', 'icon' => 'percent'],
        ['name' => 'CAGR Calculator', 'href' => '/calculators/cagr/', 'desc' => 'Annual growth rate between two values.', 'icon' => 'bar-chart-3'],
        ['name' => 'Inflation Calculator', 'href' => '/calculators/inflation/', 'desc' => "What today's money is worth years from now.", 'icon' => 'trending-down'],
        ['name' => 'Home Loan EMI Calculator', 'href' => '/calculators/home-loan-emi/', 'desc' => 'EMI, total interest and an amortisation view.', 'icon' => 'home', 'popular' => true],
        ['name' => 'Personal / Car Loan EMI Calculator', 'href' => '/calculators/loan-emi/', 'desc' => 'EMI on shorter-tenure consumer loans.', 'icon' => 'car'],
        ['name' => 'Net Worth Calculator', 'href' => '/calculators/net-worth/', 'desc' => 'Assets minus liabilities, in one place.', 'icon' => 'wallet'],
        ['name' => 'Rule of 72 Calculator', 'href' => '/calculators/rule-of-72/', 'desc' => 'Years to double your money at a given rate.', 'icon' => 'hourglass'],
    ]],
];

$calcCount = array_sum(array_map(fn($g) => count($g['items']), $calcGroups));
@endphp

@section('content')
<div class="min-h-screen bg-background" x-data="{ filter: 'All' }">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Calculators']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Calculators" title="Investment calculators"
                        subtitle="Free, honest tools to plan every rupee — from SIPs and FDs to unlisted-share taxes.">
            <div class="mt-8">
                <x-sw.chips :options="$calcCategories" model="filter" />
                <p class="mt-3 text-xs text-muted-foreground">{{ $calcCount }} calculators · 1 live today, the rest rolling out.</p>
            </div>
        </x-sw.page-hero>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.reveal>
                    <div class="overflow-hidden rounded-3xl border border-border bg-card shadow-soft" x-data="sipCalculator()">
                        <div class="flex flex-wrap items-center gap-3 border-b border-border bg-green-50 px-6 py-5">
                            <span class="grid size-11 place-items-center rounded-xl bg-primary text-primary-foreground">
                                <x-sw.icon name="repeat" class="size-5" />
                            </span>
                            <div>
                                <h2 class="text-xl font-bold text-foreground">SIP calculator</h2>
                                <p class="text-xs text-muted-foreground">Standard future-value formula, updated as you type.</p>
                            </div>
                            <span class="ml-auto rounded-full bg-primary px-3 py-1 text-[11px] font-bold tracking-wider text-primary-foreground uppercase">Live</span>
                        </div>

                        <div class="grid gap-8 p-6 sm:p-7 lg:grid-cols-[minmax(0,1fr)_22rem]">
                            <div class="space-y-7">
                                <div>
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <label for="sip-monthly" class="text-sm font-semibold text-foreground">Monthly investment (₹)</label>
                                        <div class="flex items-center gap-1 rounded-xl border border-border bg-card px-3 py-1.5">
                                            <input id="sip-monthly" type="number" inputmode="decimal" min="500" max="200000" step="500"
                                                   x-model.number="monthly" class="w-24 bg-transparent text-right text-sm font-bold text-primary outline-none" />
                                        </div>
                                    </div>
                                    <input type="range" aria-label="Monthly investment" min="500" max="200000" step="500" x-model.number="monthly" class="mt-3 w-full accent-[var(--brand)]" />
                                    <div class="mt-1 flex justify-between text-[11px] text-muted-foreground"><span>500</span><span>2,00,000</span></div>
                                </div>

                                <div>
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <label for="sip-rate" class="text-sm font-semibold text-foreground">Expected return rate (% p.a.)</label>
                                        <div class="flex items-center gap-1 rounded-xl border border-border bg-card px-3 py-1.5">
                                            <input id="sip-rate" type="number" inputmode="decimal" min="1" max="30" step="0.5"
                                                   x-model.number="rate" class="w-24 bg-transparent text-right text-sm font-bold text-primary outline-none" />
                                            <span class="text-xs font-semibold text-muted-foreground">%</span>
                                        </div>
                                    </div>
                                    <input type="range" aria-label="Expected return rate" min="1" max="30" step="0.5" x-model.number="rate" class="mt-3 w-full accent-[var(--brand)]" />
                                    <div class="mt-1 flex justify-between text-[11px] text-muted-foreground"><span>1</span><span>30</span></div>
                                </div>

                                <div>
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <label for="sip-years" class="text-sm font-semibold text-foreground">Time period (years)</label>
                                        <div class="flex items-center gap-1 rounded-xl border border-border bg-card px-3 py-1.5">
                                            <input id="sip-years" type="number" inputmode="decimal" min="1" max="40" step="1"
                                                   x-model.number="years" class="w-24 bg-transparent text-right text-sm font-bold text-primary outline-none" />
                                            <span class="text-xs font-semibold text-muted-foreground">yr</span>
                                        </div>
                                    </div>
                                    <input type="range" aria-label="Time period" min="1" max="40" step="1" x-model.number="years" class="mt-3 w-full accent-[var(--brand)]" />
                                    <div class="mt-1 flex justify-between text-[11px] text-muted-foreground"><span>1</span><span>40</span></div>
                                </div>

                                <div class="h-52 w-full">
                                    <canvas x-ref="growthChart"></canvas>
                                </div>
                            </div>

                            <div class="space-y-5">
                                <div class="h-48 w-full">
                                    <canvas x-ref="donutChart"></canvas>
                                </div>

                                <dl class="bg-price-card rounded-2xl p-5 text-white">
                                    <dt class="text-xs text-white/70">Invested amount</dt>
                                    <dd class="text-xl font-bold" x-text="fmt(invested)"></dd>
                                    <dt class="mt-3 text-xs text-white/70">Est. returns</dt>
                                    <dd class="text-xl font-bold text-mint-bright" x-text="fmt(returns)"></dd>
                                    <dt class="mt-3 text-xs text-white/70">Total value</dt>
                                    <dd class="text-3xl font-bold" x-text="fmt(total)"></dd>
                                </dl>

                                <a href="/mutual-funds/" class="bg-cta flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-bold text-white transition-transform hover:scale-[1.02]">
                                    Explore mutual funds <x-sw.icon name="arrow-right" class="size-4" />
                                </a>
                                <p class="text-xs text-muted-foreground">Estimates only. Returns are not guaranteed.</p>
                            </div>
                        </div>
                    </div>
                </x-sw.reveal>
            </div>
        </section>

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @foreach ($calcGroups as $gi => $g)
                    <div x-show="filter === 'All' || filter === '{{ $g['category'] }}'" style="display: block;" class="{{ $gi === 0 ? '' : 'mt-14' }}">
                        <x-sw.reveal>
                            <h2 class="text-2xl font-bold text-foreground sm:text-3xl">{{ $g['heading'] }}</h2>
                            <p class="mt-2 max-w-2xl text-sm text-muted-foreground">{{ $g['blurb'] }}</p>
                        </x-sw.reveal>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            @foreach ($g['items'] as $i => $item)
                                <x-sw.reveal :delay="min($i, 6) * 0.04">
                                    <a href="{{ $item['href'] }}" class="card-lift group flex h-full flex-col rounded-2xl border border-border bg-card p-5 shadow-soft transition-shadow hover:border-primary/40">
                                        <div class="flex items-start justify-between gap-3">
                                            <span class="grid size-11 place-items-center rounded-xl bg-green-50 text-primary transition-colors group-hover:bg-primary group-hover:text-primary-foreground">
                                                <x-sw.icon :name="$item['icon']" class="size-5" />
                                            </span>
                                            <span class="flex items-center gap-1.5">
                                                @if (!empty($item['popular']))
                                                    <span title="Popular" class="inline-flex items-center gap-1 rounded-full bg-mint/15 px-2 py-0.5 text-[10px] font-bold text-primary">
                                                        <x-sw.icon name="star" class="size-3 fill-current" /> Popular
                                                    </span>
                                                @endif
                                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold tracking-wide uppercase {{ !empty($item['live']) ? 'bg-primary text-primary-foreground' : 'border border-border text-muted-foreground' }}">
                                                    {{ !empty($item['live']) ? 'Live' : 'Soon' }}
                                                </span>
                                            </span>
                                        </div>
                                        <h3 class="mt-4 text-sm font-bold text-foreground">{{ $item['name'] }}</h3>
                                        <p class="mt-1 text-xs leading-relaxed text-muted-foreground">{{ $item['desc'] }}</p>
                                        <span class="mt-3 text-xs font-semibold text-primary">Open →</span>
                                    </a>
                                </x-sw.reveal>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <x-sw.illustrative-note>
                    Calculator outputs are estimates based on the assumptions you enter. They are not
                    projections of actual returns, and StockWitty does not give investment advice.
                </x-sw.illustrative-note>
            </div>
        </section>
    </main>
</div>
@endsection
