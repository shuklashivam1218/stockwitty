@extends('layouts.sw')

@section('title', "Aditya Birla Sun Life PMS — India's #3 PMS by AUM | StockWitty")
@section('description', "Aditya Birla Sun Life PMS explained: ₹32,200+ Cr AUM, 29 strategies, ₹50 lakh SEBI minimum, fee structure and flagship Select Sector Portfolio with 27.27% 3Y return.")

@php
$stats = [
    ['₹32.2K Cr', 'Total PMS AUM (₹32,200+ Cr)'], ['29', 'Strategies'],
    ['₹50 Lakh', 'Minimum investment (SEBI rule)'], ['↑22.4%', 'YoY AUM growth'],
];

$explainers = [
    ['t' => 'What PMS actually is', 'd' => "A SEBI-registered portfolio manager buys and sells securities in your own demat account under a discretionary mandate. You see every holding and every trade — unlike a mutual fund's pooled units."],
    ['t' => 'The ₹50 lakh minimum', 'd' => 'SEBI mandates a minimum investment of ₹50 lakh per PMS account. That makes it a product for concentrated, long-horizon capital — not a first equity allocation.'],
    ['t' => 'The fee structure', 'd' => 'A fixed management fee of 1.5–2.5% p.a., plus a performance fee of 10–20% on returns above the agreed hurdle rate, along with brokerage and custody costs. Ask for the full fee illustration in writing.'],
];

$flagshipStats = [
    ['27.27%', '3Y return (illustrative)'], ['₹29.5K Cr', 'Largest strategy AUM'],
    ['₹50L', 'Minimum investment'], ['#3', 'PMS in India by AUM'],
];

$strategies = [
    ['name' => 'Select Sector Portfolio (flagship)', 'style' => 'Concentrated sector rotation', 'aum' => '₹535.34 Cr', 'since' => '2015', 'r1y' => '+18.99%', 'r3y' => '+27.27%', 'r5y' => '+22.94%'],
    ['name' => 'Core Equity Portfolio', 'style' => 'Quality growth, multi-cap', 'aum' => '₹86.20 Cr', 'since' => '2012', 'r1y' => '+17.15%', 'r3y' => '+15.79%', 'r5y' => '+14.76%'],
    ['name' => 'India Special Opportunities', 'style' => 'Event-driven multi-cap', 'aum' => '₹32.86 Cr', 'since' => '2017', 'r1y' => '+14.08%', 'r3y' => '+14.76%', 'r5y' => '+13.92%'],
    ['name' => 'Emerging Leaders Portfolio', 'style' => 'Small & mid-cap', 'aum' => '₹41.60 Cr', 'since' => '2019', 'r1y' => '-3.22%', 'r3y' => '+11.40%', 'r5y' => '+12.85%'],
];

$compare = [
    ['Minimum investment', '₹50 lakh', '₹500 (SIP)', 'No minimum'],
    ['Who decides trades', 'Portfolio manager (discretionary)', 'Fund manager, pooled', 'You'],
    ['Holdings visibility', 'Every stock, in your own demat', 'Pooled units, monthly disclosure', 'Full'],
    ['Typical cost', '1.5–2.5% fixed + 10–20% performance fee', '0.5–2.0% expense ratio', 'Brokerage only'],
    ['Taxation', 'You are taxed on each underlying trade', 'Taxed on unit redemption', 'Taxed on each trade'],
];

$taxItems = [
    ['t' => 'Taxed at your level', 'd' => 'A PMS is not a pooled vehicle. Every buy and sell in your account is your own capital-gains event.'],
    ['t' => 'Short vs long term', 'd' => 'Listed equity held under 12 months: 20%. Over 12 months: 12.5% on gains above ₹1.25 lakh a year.'],
    ['t' => 'Fees and reporting', 'd' => 'Management and performance fees are not capital-gains deductions. Your manager provides an annual gains statement.'],
];

$steps = [
    ['t' => 'Confirm eligibility', 'd' => 'You need ₹50 lakh of investible capital — the SEBI-mandated PMS minimum, per account.'],
    ['t' => 'Read the disclosure document', 'd' => 'We share the full strategy deck, fee illustration and SEBI disclosure document before anything is signed.'],
    ['t' => 'Sign, fund, and hold', 'd' => 'PMS agreement plus a power of attorney for the demat account. Then give the mandate years, not quarters.'],
];

$peers = [
    ['Aditya Birla Sun Life PMS', '₹32,200+ Cr', '29', '#3 by AUM'],
    ['ICICI Prudential PMS', '₹48,500 Cr', '~18', '#1 by AUM'],
    ['Motilal Oswal PMS', '₹38,900 Cr', '~14', '#2 by AUM'],
    ['Kotak PMS', '₹21,400 Cr', '~12', '#4 by AUM'],
];
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'PMS']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Portfolio Management Services" title="Aditya Birla Sun Life PMS — India's #3 PMS by AUM."
                        subtitle="A discretionary, professionally managed equity portfolio held in your own name — ₹32,200+ Cr across 29 strategies, with a ₹50 lakh regulatory minimum.">
            <div class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($stats as [$v, $k])
                    <div class="rounded-2xl border border-border bg-card p-5 shadow-soft">
                        <p class="text-2xl font-bold text-primary">{{ $v }}</p>
                        <p class="mt-1 text-xs font-semibold text-muted-foreground">{{ $k }}</p>
                    </div>
                @endforeach
            </div>
        </x-sw.page-hero>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-3">
                    @foreach ($explainers as $i => $c)
                        <x-sw.reveal :delay="$i * 0.07" class="card-lift h-full rounded-2xl border border-border bg-card p-6 shadow-soft">
                            <span class="grid size-11 place-items-center rounded-xl bg-muted text-primary">
                                <x-sw.icon name="briefcase" class="size-5" />
                            </span>
                            <h2 class="mt-4 text-lg font-bold text-foreground">{{ $c['t'] }}</h2>
                            <p class="mt-2 text-sm text-muted-foreground">{{ $c['d'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.reveal>
                    <div class="bg-price-card rounded-3xl p-7 text-white sm:p-9">
                        <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">Flagship strategy</p>
                        <h2 class="mt-3 text-2xl font-bold sm:text-3xl">Select Sector Portfolio</h2>
                        <p class="mt-3 max-w-2xl text-sm text-white/80">
                            The house's largest and best-known mandate, concentrated on high-conviction sector
                            calls. Aditya Birla Sun Life is India's third-largest PMS by assets, running 29
                            strategies across large-cap, multi-cap and small &amp; mid-cap mandates.
                        </p>
                        <div class="mt-6 grid gap-4 sm:grid-cols-4">
                            @foreach ($flagshipStats as [$v, $k])
                                <div class="rounded-2xl border border-white/15 bg-white/[0.06] p-4">
                                    <p class="text-2xl font-bold text-mint-bright">{{ $v }}</p>
                                    <p class="text-xs font-semibold text-white/70">{{ $k }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-sw.reveal>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Strategies" title="Four strategies, including the weak one"
                                       subtitle="Style, size and indicative returns. Request the disclosure document for verified numbers." />
                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    @foreach ($strategies as $i => $s)
                        <x-sw.reveal :delay="$i * 0.05" class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-6 shadow-soft">
                            <h3 class="text-lg font-bold text-foreground">{{ $s['name'] }}</h3>
                            <p class="mt-1 text-sm font-semibold text-primary">{{ $s['style'] }}</p>
                            <dl class="mt-4 grid flex-1 grid-cols-2 gap-3 text-sm sm:grid-cols-5">
                                @foreach ([['AUM', $s['aum']], ['Since', $s['since']], ['1Y', $s['r1y']], ['3Y', $s['r3y']], ['5Y', $s['r5y']]] as [$k, $v])
                                    <div>
                                        <dt class="text-xs text-muted-foreground">{{ $k }}</dt>
                                        <dd class="font-bold {{ str_starts_with($v, '-') ? 'text-destructive' : 'text-foreground' }}">{{ $v }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                            <a href="#footer" class="mt-5 inline-flex items-center gap-1 text-sm font-bold text-primary">
                                Request details <x-sw.icon name="arrow-right" class="size-4" />
                            </a>
                        </x-sw.reveal>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Comparison" title="PMS vs mutual funds vs direct equity"
                                       subtitle="Three ways to own Indian equities, with very different entry points and costs." />
                <div class="mt-6 overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
                    <table class="w-full min-w-[42rem] text-sm">
                        <caption class="sr-only">PMS versus mutual funds versus direct equity</caption>
                        <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                            <tr>
                                <th scope="col" class="px-5 py-3"></th>
                                <th scope="col" class="px-5 py-3">PMS</th>
                                <th scope="col" class="px-5 py-3">Mutual funds</th>
                                <th scope="col" class="px-5 py-3">Direct equity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($compare as [$label, $pms, $mf, $direct])
                                <tr>
                                    <th scope="row" class="px-5 py-3.5 text-left font-bold text-foreground">{{ $label }}</th>
                                    <td class="px-5 py-3.5 text-muted-foreground">{{ $pms }}</td>
                                    <td class="px-5 py-3.5 text-muted-foreground">{{ $mf }}</td>
                                    <td class="px-5 py-3.5 text-muted-foreground">{{ $direct }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Taxation" title="How PMS is taxed" />
                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    @foreach ($taxItems as $i => $t)
                        <x-sw.reveal :delay="$i * 0.05" class="h-full rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <p class="font-bold text-foreground">{{ $t['t'] }}</p>
                            <p class="mt-1.5 text-sm text-muted-foreground">{{ $t['d'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>

                <div class="mt-14">
                    <x-sw.section-heading eyebrow="How to invest" title="Three steps into a PMS mandate" />
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    @foreach ($steps as $i => $s)
                        <x-sw.reveal :delay="$i * 0.06" class="h-full rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <p class="text-sm font-bold text-primary">Step {{ $i + 1 }}</p>
                            <p class="mt-1 font-bold text-foreground">{{ $s['t'] }}</p>
                            <p class="mt-1.5 text-sm text-muted-foreground">{{ $s['d'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>

                <div class="mt-14">
                    <x-sw.section-heading eyebrow="Peer comparison" title="Where ABSL sits among Indian PMS providers"
                                           subtitle="Ranked by assets under management — figures illustrative." />
                </div>
                <div class="mt-6 overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
                    <table class="w-full min-w-[34rem] text-sm">
                        <caption class="sr-only">PMS provider comparison</caption>
                        <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                            <tr>
                                <th scope="col" class="px-5 py-3">Provider</th>
                                <th scope="col" class="px-5 py-3 text-right">PMS AUM</th>
                                <th scope="col" class="px-5 py-3 text-right">Strategies</th>
                                <th scope="col" class="px-5 py-3 text-right">Rank</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($peers as [$name, $aum, $strats, $rank])
                                <tr>
                                    <th scope="row" class="px-5 py-3.5 text-left font-bold text-foreground">{{ $name }}</th>
                                    <td class="px-5 py-3.5 text-right text-muted-foreground">{{ $aum }}</td>
                                    <td class="px-5 py-3.5 text-right text-muted-foreground">{{ $strats }}</td>
                                    <td class="px-5 py-3.5 text-right font-bold text-primary">{{ $rank }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 flex items-start gap-3 rounded-2xl border border-border bg-secondary p-6">
                    <x-sw.icon name="shield-alert" class="mt-0.5 size-5 shrink-0 text-primary" />
                    <p class="text-sm text-muted-foreground">
                        PMS is a high-ticket, market-linked product. Past performance is not indicative of future
                        results, returns are not guaranteed, and all strategy returns and AUM figures shown here
                        are illustrative. StockWitty is a distributor, not a SEBI-registered investment adviser.
                    </p>
                </div>

                <x-sw.illustrative-note />
            </div>
        </section>
    </main>
</div>
@endsection
