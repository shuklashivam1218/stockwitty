@extends('layouts.sw')

@section('title', 'Suryoday Small Finance Bank FD — 9.10% / 9.60% Rates | StockWitty')
@section('description', 'Suryoday Small Finance Bank fixed deposit rates — 9.10% general, 9.60% for senior citizens, ₹1,000 minimum, DICGC insured up to ₹5 lakh — with an FD maturity calculator.')

@php
$tenures = [
    ['t' => 'Short tenure (7 days – 6 months)', 'general' => '6.25%', 'senior' => '6.75%'],
    ['t' => 'Mid tenure (12 – 18 months)', 'general' => '8.25%', 'senior' => '8.75%'],
    ['t' => 'Best mid-term slab (2 – 3 years)', 'general' => '8.60%', 'senior' => '9.10%'],
    ['t' => 'Highest slab (5 years)', 'general' => '9.10%', 'senior' => '9.60%'],
];

$pros = [
    'Among the highest FD rates in India today — up to 9.60% p.a.',
    'Bank-grade safety: deposits insured by DICGC up to ₹5 lakh per depositor.',
    'Low entry barrier — you can open an FD with just ₹1,000.',
    'Senior citizens get an extra 0.50% across tenures.',
];

$cons = [
    'It is a smaller bank, so keep your total deposit within the ₹5 lakh insured limit per depositor.',
    'Premature withdrawal usually costs about 1% off the applicable rate.',
    'Interest is fully taxable at your slab rate, with TDS above the threshold.',
];

$peers = [
    ['North East Small Finance Bank', '9.40%', '9.90%', 'Yes'],
    ['Unity Small Finance Bank', '9.00%', '9.50%', 'Yes'],
    ['Utkarsh Small Finance Bank', '8.85%', '9.35%', 'Yes'],
];

$taxItems = [
    ['t' => 'Taxed at your slab', 'd' => 'FD interest is added to your total income and taxed at your applicable slab rate — there is no special lower rate.'],
    ['t' => 'TDS thresholds', 'd' => 'Banks deduct 10% TDS once annual interest crosses ₹50,000 (₹1,00,000 for senior citizens). Submit Form 15G/15H if eligible.'],
    ['t' => 'Declare it yourself', 'd' => 'Report interest as accrued each year, even for cumulative FDs where cash only arrives at maturity.'],
];

$steps = [
    ['t' => 'Complete KYC', 'd' => 'PAN, Aadhaar and a bank account. Usually approved the same day.'],
    ['t' => 'Choose tenure and payout', 'd' => 'Pick the slab that matches your horizon, and cumulative or periodic payout.'],
    ['t' => 'Fund and confirm', 'd' => 'Transfer from ₹1,000 upwards; the deposit receipt lands by email.'],
];

$faqs = [
    ['q' => 'Is a small finance bank FD safe?', 'a' => 'Deposits are insured by DICGC up to ₹5 lakh per depositor per bank, including principal and interest. Stay within that limit and your capital carries the same insurance as any scheduled bank.'],
    ['q' => 'What is the minimum deposit?', 'a' => '₹1,000, which makes it one of the most accessible high-rate FDs in India.'],
    ['q' => 'Do senior citizens really get more?', 'a' => 'Yes — an extra 0.50% across tenures, so the top slab moves from 9.10% to 9.60% p.a.'],
    ['q' => 'Can I withdraw early?', 'a' => 'Usually yes, with a penalty of about 1% off the applicable rate. Laddering tenures is a better plan than locking everything in one deposit.'],
];
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Fixed Deposits', 'href' => '/fixed-deposits/'], ['label' => 'Suryoday Small Finance Bank']]" />
    </div>

    <main>
        <section class="bg-price-card text-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">Small finance bank · DICGC insured up to ₹5 lakh</p>
                <h1 class="mt-3 text-3xl font-bold sm:text-4xl">Suryoday Small Finance Bank Fixed Deposit</h1>
                <div class="mt-6 grid gap-4 sm:grid-cols-4">
                    @foreach ([['9.10%', 'General rate (p.a.)'], ['9.60%', 'Senior citizens (+0.50%)'], ['₹1,000', 'Minimum deposit'], ['₹5 lakh', 'DICGC insured cover']] as [$v, $k])
                        <div class="rounded-2xl border border-white/15 bg-white/[0.06] p-5">
                            <p class="text-3xl font-bold text-mint-bright">{{ $v }}</p>
                            <p class="text-xs font-semibold text-white/70">{{ $k }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-[1.1fr_1fr]">
                    <x-sw.reveal class="rounded-3xl border border-border bg-card p-6 shadow-soft">
                        <h2 class="text-xl font-bold text-foreground">Rate slabs</h2>
                        <div class="mt-4 overflow-x-auto">
                            <table class="w-full text-sm">
                                <caption class="sr-only">Suryoday FD rate slabs</caption>
                                <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Tenure slab</th>
                                        <th scope="col" class="px-4 py-3 text-right">General</th>
                                        <th scope="col" class="px-4 py-3 text-right">Senior</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    @foreach ($tenures as $r)
                                        <tr>
                                            <th scope="row" class="px-4 py-3 text-left font-semibold text-foreground">{{ $r['t'] }}</th>
                                            <td class="px-4 py-3 text-right text-muted-foreground">{{ $r['general'] }}</td>
                                            <td class="px-4 py-3 text-right font-bold text-primary">{{ $r['senior'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 rounded-2xl bg-green-50 p-5">
                            <p class="text-xs font-bold tracking-widest text-primary uppercase">Worked example</p>
                            <p class="mt-2 text-sm text-muted-foreground">
                                A deposit of <span class="font-bold text-foreground">₹1,00,000</span> grows to
                                <span class="font-bold text-foreground">₹1,19,925</span> over the tenure shown
                                — <span class="font-bold text-primary">₹19,925</span> of interest, with quarterly compounding.
                            </p>
                        </div>

                        <h3 class="mt-8 text-lg font-bold text-foreground">Interest payout options</h3>
                        <ul class="mt-3 space-y-2 text-sm text-muted-foreground">
                            <li>• Cumulative — interest compounds quarterly and pays at maturity.</li>
                            <li>• Quarterly payout — regular income, slightly lower effective yield.</li>
                            <li>• Monthly payout — discounted rate, best for retirees needing cash flow.</li>
                        </ul>

                        <p class="mt-6 flex items-start gap-2 rounded-2xl bg-green-50 p-4 text-sm text-muted-foreground">
                            <x-sw.icon name="shield-check" class="mt-0.5 size-4 shrink-0 text-primary" />
                            Deposits are insured by DICGC up to ₹5 lakh per depositor per bank, including
                            principal and interest. Above that limit you carry bank credit risk — which is exactly
                            why small finance banks pay more.
                        </p>
                    </x-sw.reveal>

                    <x-sw.reveal :delay="0.08" class="rounded-3xl border border-border bg-card p-6 shadow-soft"
                                 x-data="{
                                     amount: 100000, years: 5, senior: false,
                                     get rate() { return this.senior ? 9.6 : 9.1; },
                                     get maturity() { const r = this.rate / 100 / 4; return Math.round(this.amount * Math.pow(1 + r, 4 * this.years)); },
                                 }">
                        <h2 class="text-xl font-bold text-foreground">FD calculator</h2>
                        <div class="mt-5 space-y-5 text-sm">
                            <label class="block">
                                <span class="font-semibold text-foreground">Deposit amount · ₹<span x-text="amount.toLocaleString('en-IN')"></span></span>
                                <input type="range" min="1000" max="2000000" step="1000" x-model.number="amount" class="mt-3 w-full accent-[var(--brand)]" />
                            </label>
                            <label class="block">
                                <span class="font-semibold text-foreground">Tenure · <span x-text="years"></span> years</span>
                                <input type="range" min="1" max="10" step="1" x-model.number="years" class="mt-3 w-full accent-[var(--brand)]" />
                            </label>
                            <label class="flex items-center gap-3 font-semibold text-foreground">
                                <input type="checkbox" x-model="senior" class="size-4 accent-[var(--brand)]" />
                                Senior citizen rate (<span x-text="rate"></span>% p.a.)
                            </label>
                        </div>

                        <div class="bg-price-card mt-6 rounded-2xl p-5 text-white">
                            <p class="text-xs font-semibold text-white/70">Maturity value</p>
                            <p class="mt-1 text-3xl font-bold">₹<span x-text="maturity.toLocaleString('en-IN')"></span></p>
                            <p class="mt-1 text-xs text-mint-bright">
                                Interest earned ₹<span x-text="(maturity - amount).toLocaleString('en-IN')"></span> · quarterly compounding at <span x-text="rate"></span>%
                            </p>
                        </div>

                        <a href="/signup/" class="bg-cta mt-5 block rounded-xl px-5 py-3.5 text-center text-sm font-bold text-white">
                            Open this FD
                        </a>
                    </x-sw.reveal>
                </div>
            </div>
        </section>

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="The honest view" title="What we like — and what to watch"
                                       subtitle="A high rate always has a reason. Here it is, stated plainly." />
                <div class="mt-6 grid gap-4 lg:grid-cols-2">
                    <x-sw.reveal class="h-full rounded-2xl border border-border bg-card p-6 shadow-soft">
                        <h3 class="font-bold text-foreground">Pros</h3>
                        <ul class="mt-3 space-y-2.5 text-sm text-muted-foreground">
                            @foreach ($pros as $p)
                                <li class="flex gap-2"><x-sw.icon name="check" class="mt-0.5 size-4 shrink-0 text-primary" />{{ $p }}</li>
                            @endforeach
                        </ul>
                    </x-sw.reveal>
                    <x-sw.reveal :delay="0.08" class="h-full rounded-2xl border border-border bg-card p-6 shadow-soft">
                        <h3 class="font-bold text-foreground">Cons</h3>
                        <ul class="mt-3 space-y-2.5 text-sm text-muted-foreground">
                            @foreach ($cons as $c)
                                <li class="flex gap-2"><x-sw.icon name="x" class="mt-0.5 size-4 shrink-0 text-destructive" />{{ $c }}</li>
                            @endforeach
                        </ul>
                    </x-sw.reveal>
                </div>

                <div class="mt-14">
                    <x-sw.section-heading eyebrow="Comparison" title="Against other high-rate FDs"
                                           subtitle="Small finance bank FDs, all DICGC insured up to ₹5 lakh." />
                </div>
                <div class="mt-6 overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
                    <table class="w-full min-w-[34rem] text-sm">
                        <caption class="sr-only">Comparison with other fixed deposits</caption>
                        <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                            <tr>
                                <th scope="col" class="px-5 py-3">Bank</th>
                                <th scope="col" class="px-5 py-3 text-right">General</th>
                                <th scope="col" class="px-5 py-3 text-right">Senior</th>
                                <th scope="col" class="px-5 py-3 text-right">DICGC</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr class="bg-green-50">
                                <th scope="row" class="px-5 py-3.5 text-left font-bold text-foreground">Suryoday Small Finance Bank</th>
                                <td class="px-5 py-3.5 text-right font-bold text-primary">9.10%</td>
                                <td class="px-5 py-3.5 text-right font-bold text-primary">9.60%</td>
                                <td class="px-5 py-3.5 text-right text-muted-foreground">Yes</td>
                            </tr>
                            @foreach ($peers as [$bank, $general, $senior, $dicgc])
                                <tr>
                                    <th scope="row" class="px-5 py-3.5 text-left font-bold text-foreground">{{ $bank }}</th>
                                    <td class="px-5 py-3.5 text-right text-muted-foreground">{{ $general }}</td>
                                    <td class="px-5 py-3.5 text-right text-muted-foreground">{{ $senior }}</td>
                                    <td class="px-5 py-3.5 text-right text-muted-foreground">{{ $dicgc }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Taxation" title="How FD interest is taxed" />
                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    @foreach ($taxItems as $i => $t)
                        <x-sw.reveal :delay="$i * 0.05" class="h-full rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <p class="font-bold text-foreground">{{ $t['t'] }}</p>
                            <p class="mt-1.5 text-sm text-muted-foreground">{{ $t['d'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>

                <div class="mt-14">
                    <x-sw.section-heading eyebrow="How to invest" title="Three steps to open the FD" />
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
                    <x-sw.section-heading eyebrow="FAQ" title="Suryoday FD, answered" />
                </div>
                <div class="mt-6 divide-y divide-border rounded-2xl border border-border bg-card">
                    @foreach ($faqs as $f)
                        <div class="p-5">
                            <h3 class="font-bold text-foreground">{{ $f['q'] }}</h3>
                            <p class="mt-1.5 text-sm text-muted-foreground">{{ $f['a'] }}</p>
                        </div>
                    @endforeach
                </div>

                <x-sw.illustrative-note>
                    Rates and the worked example on this page are indicative — verify Suryoday's current card
                    rates before investing. Calculator output is an estimate using quarterly compounding.
                </x-sw.illustrative-note>
            </div>
        </section>
    </main>
</div>
@endsection
