@extends('layouts.sw')

@section('title', 'SBI Mutual Funds — AUM ₹12.55L Cr, Top Funds & 1 to Avoid | StockWitty')
@section('description', 'SBI Mutual Fund explained honestly — ₹12.55 lakh crore AUM, 8.7 crore+ investors, 88 schemes. Four SBI funds worth your money, one we\'d avoid, plus tax and how to invest.')

@php
$headerStats = [
    ['₹12.55L Cr', 'Total AUM'], ['8.7 Cr+', 'Investors'], ['21.4%', 'Avg return, top funds (illustrative)'],
    ['₹500', 'SIP starts from'], ['~0.85%', 'Average expense ratio'], ['#1', "India's largest fund house"],
];

$picks = [
    ['name' => 'SBI Contra Fund', 'category' => 'Equity', 'nav' => '₹385.42', 'aum' => '₹47,200 Cr', 'expense' => '0.68%', 'r1y' => '+28.1%', 'r3y' => '+26.4%', 'r5y' => '+25.7%', 'why' => 'A genuinely contrarian mandate with a long record — and one of the lowest expense ratios in its category.'],
    ['name' => 'SBI Long Term Equity Fund (ELSS)', 'category' => 'ELSS', 'nav' => '₹412.85', 'aum' => '₹3,890 Cr', 'expense' => '0.92%', 'r1y' => '+22.3%', 'r3y' => '+19.8%', 'r5y' => '+21.4%', 'why' => 'Tax deduction under the old regime plus a three-year lock-in that quietly enforces patience.'],
    ['name' => 'SBI Small Cap Fund', 'category' => 'Equity', 'nav' => '₹128.74', 'aum' => '₹1,420 Cr', 'expense' => '1.18%', 'r1y' => '+18.2%', 'r3y' => '+24.6%', 'r5y' => '+27.8%', 'why' => 'Strong long-term compounding, but the highest expense here and the sharpest drawdowns. Size the position accordingly.'],
    ['name' => 'SBI Bluechip Fund', 'category' => 'Equity', 'nav' => '₹196.84', 'aum' => '₹38,500 Cr', 'expense' => '0.84%', 'r1y' => '+16.4%', 'r3y' => '+15.1%', 'r5y' => '+15.8%', 'why' => 'The steady large-cap core. Not exciting, and that is precisely the point.'],
];

$avoid = ['name' => 'SBI Thematic Momentum-style Fund (illustrative)', 'nav' => '₹21.05', 'aum' => '₹2,310 Cr', 'expense' => '2.18%', 'r1y' => '+24.8%', 'r3y' => '+11.4%', 'r5y' => '+9.6%', 'why' => "A flattering one-year number sitting on weak three- and five-year risk-adjusted returns, with a 2.18% expense ratio doing the rest of the damage. This is a caution about cost and timing, not a judgement on the fund house."];

$cats = ['All', 'Equity', 'ELSS', 'Hybrid', 'Index', 'Debt'];

$taxRules = [
    ['Equity funds — short term', 'Units sold within 12 months: gains taxed at 20%.'],
    ['Equity funds — long term', 'Held over 12 months: gains above ₹1.25 lakh a year taxed at 12.5%.'],
    ['Debt funds', 'All gains taxed at your income-tax slab rate, regardless of holding period.'],
    ['ELSS', 'Deduction up to ₹1.5 lakh under Section 80C (old regime), with a three-year lock-in.'],
];

$steps = [
    ['Complete KYC', 'PAN, Aadhaar and a bank account. One-time, usually done the same day.'],
    ['Pick fund and mode', "Lumpsum or SIP from ₹500. Choose direct plans where you're doing your own research."],
    ['Automate and review', 'Set the SIP mandate, then review once or twice a year — not once a week.'],
];

$peers = [
    ['SBI Mutual Fund', '₹12.55L Cr', '88 schemes', '~0.85%'],
    ['ICICI Prudential MF', '₹8.90L Cr', '~120 schemes', '~0.95%'],
    ['HDFC Mutual Fund', '₹7.80L Cr', '~100 schemes', '~0.92%'],
    ['Nippon India MF', '₹6.20L Cr', '~95 schemes', '~0.88%'],
];
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Mutual Funds']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Mutual funds" title="SBI Mutual Fund, reviewed honestly."
                        subtitle="India's largest fund house runs 88 schemes. We'll tell you which four we'd own and which one we'd skip — no commission-led shortlists.">
            <div class="mt-8 grid gap-3 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($headerStats as [$v, $k])
                    <div class="rounded-2xl border border-border bg-card p-4 shadow-soft">
                        <p class="text-xl font-bold text-primary">{{ $v }}</p>
                        <p class="mt-1 text-xs font-semibold text-muted-foreground">{{ $k }}</p>
                    </div>
                @endforeach
            </div>
            <p class="mt-4 inline-flex items-center gap-2 rounded-full border border-border bg-green-50 px-4 py-2 text-xs font-semibold text-muted-foreground">
                <x-sw.icon name="clock" class="size-3.5 text-primary" /> NAV updates daily at 11:30 PM IST
            </p>
        </x-sw.page-hero>

        <section class="py-14 sm:py-20" x-data="{ cat: 'All' }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.reveal>
                    <div class="bg-price-card flex flex-wrap items-center justify-between gap-5 rounded-3xl p-7 text-white">
                        <div>
                            <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">This month's honest shortlist</p>
                            <h2 class="mt-2 text-2xl font-bold sm:text-3xl">4 SBI funds worth your money (and 1 to avoid)</h2>
                            <p class="mt-2 max-w-xl text-sm text-white/75">
                                Total AUM across the house is <x-sw.count-up :to="12.55" :decimals="2" /> lakh crore, held by
                                8.7 crore+ investors. Scale is not the same as suitability — the fund still has to fit your horizon.
                            </p>
                        </div>
                        <a href="/screener/" class="bg-cta inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-bold text-white">
                            <x-sw.icon name="sparkles" class="size-4" /> Screen funds with our tools
                        </a>
                    </div>
                </x-sw.reveal>

                <div class="mt-10">
                    <div class="flex flex-wrap gap-2">
                        @foreach ($cats as $c)
                            <button type="button" @click="cat = '{{ $c }}'" :aria-pressed="cat === '{{ $c }}'"
                                    :class="cat === '{{ $c }}' ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-card text-muted-foreground hover:border-primary/50 hover:text-primary'"
                                    class="rounded-full border px-4 py-2 text-sm font-semibold transition-all">
                                {{ $c }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-2">
                    @foreach ($picks as $f)
                        <div x-show="cat === 'All' || cat === '{{ $f['category'] }}'" style="display: block;"
                             class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-6 shadow-soft">
                            <div class="flex items-center justify-between gap-2">
                                <span class="rounded-full bg-green-50 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-primary uppercase">{{ $f['category'] }}</span>
                                <span class="text-xs font-semibold text-muted-foreground">Expense {{ $f['expense'] }}</span>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-foreground">{{ $f['name'] }}</h3>
                            <dl class="mt-4 grid grid-cols-2 gap-3 text-sm sm:grid-cols-4">
                                @foreach ([['NAV', $f['nav']], ['AUM', $f['aum']], ['1Y', $f['r1y']], ['3Y', $f['r3y']]] as [$k, $v])
                                    <div>
                                        <dt class="text-xs text-muted-foreground">{{ $k }}</dt>
                                        <dd class="font-bold text-foreground">{{ $v }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                            <p class="mt-3 text-xs font-semibold text-primary">5Y return {{ $f['r5y'] }}</p>
                            <p class="mt-3 flex-1 text-sm text-muted-foreground">{{ $f['why'] }}</p>
                            <a href="/calculators/sip/" class="mt-5 inline-flex items-center gap-1 text-sm font-bold text-primary">
                                Model a SIP in this fund <x-sw.icon name="arrow-right" class="size-4" />
                            </a>
                        </div>
                    @endforeach
                </div>

                <x-sw.reveal>
                    <div class="mt-6 rounded-2xl border border-destructive/40 bg-card p-6 shadow-soft">
                        <span class="inline-flex items-center gap-1 rounded-full bg-secondary px-3 py-1 text-[0.7rem] font-bold text-foreground uppercase">
                            <x-sw.icon name="thumbs-down" class="size-3" /> The 1 we'd avoid
                        </span>
                        <h3 class="mt-4 text-lg font-bold text-foreground">{{ $avoid['name'] }}</h3>
                        <dl class="mt-4 grid grid-cols-2 gap-3 text-sm sm:grid-cols-5">
                            @foreach ([['NAV', $avoid['nav']], ['AUM', $avoid['aum']], ['Expense', $avoid['expense']], ['1Y', $avoid['r1y']], ['3Y / 5Y', $avoid['r3y'] . ' / ' . $avoid['r5y']]] as [$k, $v])
                                <div>
                                    <dt class="text-xs text-muted-foreground">{{ $k }}</dt>
                                    <dd class="font-bold text-foreground">{{ $v }}</dd>
                                </div>
                            @endforeach
                        </dl>
                        <p class="mt-3 text-sm text-muted-foreground">{{ $avoid['why'] }}</p>
                    </div>
                </x-sw.reveal>
            </div>
        </section>

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-[1.2fr_1fr]">
                    <x-sw.reveal class="h-full rounded-3xl border border-border bg-card p-6 shadow-soft">
                        <h2 class="text-xl font-bold text-foreground">Browse all 88 SBI mutual funds</h2>
                        <p class="mt-2 text-sm text-muted-foreground">
                            Equity, debt, hybrid, index, ELSS and solution-oriented schemes — 88 in total across
                            the house. Filter by category, expense ratio and horizon rather than by last year's chart.
                        </p>
                        <div class="mt-5 grid gap-3 sm:grid-cols-3">
                            @foreach ([['36', 'Equity schemes'], ['28', 'Debt schemes'], ['24', 'Hybrid, index & other']] as [$v, $k])
                                <div class="rounded-2xl bg-green-50 p-4">
                                    <p class="text-2xl font-bold text-primary">{{ $v }}</p>
                                    <p class="text-xs font-semibold text-muted-foreground">{{ $k }}</p>
                                </div>
                            @endforeach
                        </div>
                        <a href="/screener/" class="mt-5 inline-flex items-center gap-1 text-sm font-bold text-primary">
                            Open the fund screener <x-sw.icon name="arrow-right" class="size-4" />
                        </a>
                    </x-sw.reveal>
                    <x-sw.reveal :delay="0.08" class="bg-price-card h-full rounded-3xl p-6 text-white">
                        <span class="grid size-11 place-items-center rounded-xl bg-white/10 text-mint-bright">
                            <x-sw.icon name="calculator" class="size-5" />
                        </span>
                        <h2 class="mt-4 text-xl font-bold">SIP calculator</h2>
                        <p class="mt-2 text-sm text-white/75">
                            See what ₹5,000 a month could become over 10 years at different return assumptions —
                            and how much of it is your own money versus growth.
                        </p>
                        <a href="/calculators/sip/" class="bg-cta mt-5 inline-block rounded-xl px-5 py-3 text-sm font-bold text-white">
                            Open SIP calculator →
                        </a>
                    </x-sw.reveal>
                </div>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Taxation" title="How mutual fund gains are taxed"
                                       subtitle="Current rules, stated plainly. Confirm with your CA for your own situation." />
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach ($taxRules as $i => [$t, $d])
                        <x-sw.reveal :delay="$i * 0.05" class="h-full rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <p class="font-bold text-foreground">{{ $t }}</p>
                            <p class="mt-1.5 text-sm text-muted-foreground">{{ $d }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>

                <div class="mt-14">
                    <x-sw.section-heading eyebrow="How to invest" title="Three steps, start to SIP" />
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    @foreach ($steps as $i => [$t, $d])
                        <x-sw.reveal :delay="$i * 0.06" class="h-full rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <p class="text-sm font-bold text-primary">Step {{ $i + 1 }}</p>
                            <p class="mt-1 font-bold text-foreground">{{ $t }}</p>
                            <p class="mt-1.5 text-sm text-muted-foreground">{{ $d }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>

                <div class="mt-14">
                    <x-sw.section-heading eyebrow="Peer comparison" title="SBI against the other large fund houses"
                                           subtitle="Scale, breadth and average cost — figures illustrative." />
                </div>
                <div class="mt-6 overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
                    <table class="w-full min-w-[34rem] text-sm">
                        <caption class="sr-only">Fund house comparison</caption>
                        <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                            <tr>
                                <th scope="col" class="px-5 py-3">Fund house</th>
                                <th scope="col" class="px-5 py-3 text-right">AUM</th>
                                <th scope="col" class="px-5 py-3 text-right">Schemes</th>
                                <th scope="col" class="px-5 py-3 text-right">Avg expense</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($peers as [$name, $aum, $schemes, $expense])
                                <tr>
                                    <th scope="row" class="px-5 py-3.5 text-left font-bold text-foreground">{{ $name }}</th>
                                    <td class="px-5 py-3.5 text-right text-muted-foreground">{{ $aum }}</td>
                                    <td class="px-5 py-3.5 text-right text-muted-foreground">{{ $schemes }}</td>
                                    <td class="px-5 py-3.5 text-right text-muted-foreground">{{ $expense }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <x-sw.illustrative-note>
                    NAVs, AUM figures, expense ratios and all returns on this page are illustrative. Past
                    performance is not indicative of future results. Mutual fund investments are subject to
                    market risk — read all scheme documents carefully. StockWitty is a distributor, not a
                    SEBI-registered investment adviser.
                </x-sw.illustrative-note>
            </div>
        </section>
    </main>
</div>
@endsection
