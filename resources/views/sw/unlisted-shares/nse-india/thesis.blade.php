@extends('layouts.sw')

@section('title', 'NSE India Unlisted Shares: Should You Buy? Analysis & WittyScore 8.5 | StockWitty')
@section('description', 'Our honest investment thesis on NSE India unlisted shares — WittyScore 8.5/10, the bull and bear case, valuation view, key risks and a clear verdict. Not investment advice.')

@php
$toc = [
    ['id' => 'verdict', 'label' => 'The verdict'],
    ['id' => 'tldr', 'label' => 'TL;DR summary'],
    ['id' => 'wittyscore', 'label' => 'WittyScore breakdown'],
    ['id' => 'thesis', 'label' => 'The thesis, told straight'],
    ['id' => 'bull-bear', 'label' => 'Bull vs bear case'],
    ['id' => 'valuation', 'label' => 'Valuation view'],
    ['id' => 'who-for', 'label' => 'Who this is for'],
    ['id' => 'risks', 'label' => 'Key risks'],
    ['id' => 'voices', 'label' => 'What investors say'],
    ['id' => 'next-steps', 'label' => 'Next steps'],
    ['id' => 'author', 'label' => 'Author, sources & disclaimer'],
];

$pillars = [
    ['label' => 'Financial Health', 'value' => 8.8, 'note' => 'A ~63% net margin, consistently profitable, debt-free and cash-generative.'],
    ['label' => 'Valuation', 'value' => 7.6, 'note' => 'The weakest pillar. At ~40× earnings the quality is priced in.'],
    ['label' => 'Growth Potential', 'value' => 9.1, 'note' => 'Market leader in a sector compounding at roughly 18% a year; revenue growth around 17%.'],
    ['label' => 'IPO Probability', 'value' => 8.9, 'note' => "SEBI NOC and DRHP progress make a listing more likely than in years — but 'likely' is not 'dated'."],
    ['label' => 'Liquidity & Safety', 'value' => 8.1, 'note' => 'Among the most actively traded unlisted names in India, so exit is easier than most.'],
];

$bull = [
    'Monopoly-like economics: a moat competitors have not dented in two decades.',
    'Trading volumes in India are structurally rising as participation broadens.',
    'A listing could re-rate the business and unlock real liquidity for holders.',
    'Debt-free balance sheet with very high operating margins.',
];

$bear = [
    'At roughly 40× earnings, a lot of the good news is already in the price.',
    'The IPO timeline has slipped repeatedly and can slip again.',
    'Regulation on transaction fees, structure or governance could dent profits.',
    'A slowdown in volumes hits a transaction-linked revenue model directly.',
];

$stats = [
    ['label' => 'Indicative price', 'value' => '₹1,960'],
    ['label' => 'Approx P/E', 'value' => '~40×'],
    ['label' => 'Net margin', 'value' => '~60%'],
    ['label' => 'Debt', 'value' => 'Nil'],
];

$suits = [
    'You invest for years, not months.',
    'You want a wide-moat business before it lists.',
    'You treat the IPO as upside, not a guarantee.',
    'Unlisted is a small, considered slice of your portfolio.',
];

$notSuits = [
    'You may need to exit quickly or at a fixed date.',
    'You are uncomfortable paying a premium for quality.',
    'You are relying on a specific IPO timeline.',
    'This would be a large, concentrated position.',
];

$risks = [
    ['label' => 'Timeline risk', 'body' => 'The biggest one. Assume a listing takes longer than the current chatter suggests, and size your position so a two- or three-year delay changes nothing for you.'],
    ['label' => 'Liquidity risk', 'body' => 'Exit is a negotiated transaction with a buyer, not a click on a live exchange. Never commit money you may need soon.'],
    ['label' => 'Regulatory risk', 'body' => 'Market infrastructure is closely supervised. Changes to fees, market structure or governance requirements can move the story quickly.'],
    ['label' => 'Valuation risk', 'body' => 'At roughly 40× earnings, the business must keep delivering. Any stumble in volumes or margins is felt in the price you paid.'],
];

$reviews = [
    ['name' => 'Rohan S.', 'role' => 'Long-term investor, Bengaluru', 'stars' => 5, 'quote' => 'Held NSE unlisted two years purely on the moat. Not counting on a specific IPO date.'],
    ['name' => 'Meera N.', 'role' => 'CA & investor, Kochi', 'stars' => 4, 'quote' => "Love the business, less sure on price. Bought small, told myself I'd add only if it corrects."],
];

$TLDR = "NSE India runs the exchange where most of India's stock-market trading happens — a business with a genuine moat, very high margins and a near-100% share of equity-derivatives volume. That quality is real. The catch is price: at roughly 40× earnings you are paying up, and the long-awaited IPO has been 'almost here' since 2016. If you can hold for years and treat the IPO as an upside option rather than a promise, the thesis holds. If you need liquidity or a fixed timeline, this is not for you.";
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <nav aria-label="Breadcrumb" class="border-b border-border bg-green-50/60">
            <ol class="mx-auto flex max-w-4xl flex-wrap items-center gap-1 px-4 py-3 text-xs font-semibold text-muted-foreground sm:px-6">
                <li><a href="/" class="hover:text-primary">Home</a></li>
                <x-sw.icon name="chevron-right" class="size-3.5" />
                <li><a href="/unlisted-shares/" class="hover:text-primary">Unlisted Shares</a></li>
                <x-sw.icon name="chevron-right" class="size-3.5" />
                <li><a href="/unlisted-shares/nse-india/" class="hover:text-primary">NSE India</a></li>
                <x-sw.icon name="chevron-right" class="size-3.5" />
                <li aria-current="page" class="text-foreground">Thesis &amp; Analysis</li>
            </ol>
        </nav>
    </div>

    <main class="pb-24">
        <div class="mx-auto w-full max-w-[1160px] px-4 sm:px-6 lg:flex lg:justify-center lg:gap-12">
            <div class="min-w-0 lg:max-w-[780px] lg:flex-1">
                <div class="mt-6 flex items-start gap-3 rounded-2xl border border-dashed border-amber-500/60 bg-amber-50 p-4 text-sm text-amber-900">
                    <x-sw.icon name="alert-triangle" class="mt-0.5 size-4 shrink-0" />
                    <p><strong>Illustrative demo data —</strong> figures on this page are for layout purposes and should be verified against official filings before publishing.</p>
                </div>

                <header class="pt-10 sm:pt-14">
                    <x-sw.reveal>
                        <span class="inline-flex items-center rounded-full border border-brand/20 bg-green-50 px-3 py-1 text-xs font-bold tracking-widest text-primary uppercase">
                            Investment Thesis &amp; Analysis
                        </span>
                        <h1 class="mt-5 text-3xl leading-tight font-bold text-foreground sm:text-5xl">
                            NSE India Unlisted Shares: Should You Buy?
                        </h1>
                        <p class="mt-5 text-lg text-muted-foreground">
                            Our honest, opinionated take on whether NSE India's unlisted shares deserve a place in
                            your portfolio — the bull case, the bear case, the valuation, and where we land.
                        </p>
                        <div class="mt-7 flex flex-wrap items-center gap-x-4 gap-y-3 border-y border-border py-4 text-xs font-semibold text-muted-foreground">
                            <span class="flex items-center gap-2">
                                <span class="grid size-9 place-items-center rounded-full bg-primary text-xs font-bold text-primary-foreground">SW</span>
                                By StockWitty Research · Reviewed by a Chartered Accountant
                            </span>
                            <span>Updated 12 Aug 2026</span>
                            <span>9 min read</span>
                        </div>
                    </x-sw.reveal>
                </header>
            </div>
            <div class="hidden shrink-0 lg:block lg:w-[248px]"></div>
        </div>

        <x-sw.toc-layout :items="$toc">
            <x-sw.reveal :delay="0.05">
                <section id="verdict" class="scroll-mt-28 bg-price-card mt-10 grid gap-6 rounded-3xl p-6 text-white shadow-soft sm:grid-cols-[auto_1fr] sm:items-center sm:p-8">
                    <div class="flex items-center gap-4 sm:flex-col sm:gap-1 sm:border-r sm:border-white/15 sm:pr-8">
                        <p class="text-4xl font-bold text-mint-bright">8.5<span class="text-lg text-white/70"> / 10</span></p>
                        <p class="text-xs font-bold tracking-widest text-white/70 uppercase">WittyScore</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">Our verdict in one line</p>
                        <p class="mt-2 text-base leading-relaxed text-white/90 sm:text-lg">
                            A rare, near-monopoly business at a rich price — attractive for patient investors who
                            can stomach an IPO timeline that has slipped for years.
                        </p>
                        <span class="mt-4 inline-flex items-center rounded-full bg-mint/20 px-3 py-1 text-xs font-bold text-mint-bright">
                            Verdict: Strong business · Premium valuation
                        </span>
                    </div>
                </section>
            </x-sw.reveal>

            <x-sw.reveal :delay="0.05">
                <section id="tldr" class="scroll-mt-28 mt-12 rounded-3xl border border-border bg-beige p-6 shadow-soft sm:p-8" x-data="{ speak() { if (!window.speechSynthesis) return; const u = new SpeechSynthesisUtterance(document.getElementById('tldr-summary').textContent); u.lang = 'en-IN'; window.speechSynthesis.speak(u); } }">
                    <h2 class="text-xl font-bold text-foreground">The short answer</h2>
                    <p id="tldr-summary" class="mt-4 text-base leading-relaxed text-ink/80">{{ $TLDR }}</p>
                    <button type="button" @click="speak()" class="mt-5 inline-flex items-center gap-2 rounded-xl border border-brand/25 bg-card px-4 py-2.5 text-sm font-bold text-primary transition-colors hover:bg-green-50">
                        <x-sw.icon name="volume-2" class="size-4" />
                        Listen to this summary
                    </button>
                </section>
            </x-sw.reveal>

            <section id="wittyscore" class="scroll-mt-28 mt-16">
                <x-sw.reveal><h2 class="text-2xl font-bold text-foreground sm:text-3xl">How we scored NSE India: 8.5 / 10</h2></x-sw.reveal>
                <div class="mt-7 grid gap-4 sm:grid-cols-2">
                    @foreach ($pillars as $i => $p)
                        <x-sw.reveal :delay="$i * 0.1" class="card-lift rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <div class="flex items-center justify-between text-sm font-bold">
                                <span class="flex items-center gap-2 text-foreground">
                                    <x-sw.icon name="gauge" class="size-4 text-mint" />
                                    {{ $p['label'] }}
                                </span>
                                <span class="text-primary tabular-nums">{{ number_format($p['value'], 1) }}</span>
                            </div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-green-100">
                                <div class="sw-pillar-bar h-full rounded-full bg-gradient-to-r from-primary to-mint" style="--target-width: {{ $p['value'] * 10 }}%"></div>
                            </div>
                            <p class="mt-3 text-sm leading-relaxed text-muted-foreground">{{ $p['note'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>
                <p class="mt-5 text-sm text-muted-foreground">
                    Scores are computed from our methodology, not hand-picked.
                    <a href="/wittyscore/" class="font-bold text-primary hover:underline">WittyScore methodology →</a>
                </p>
            </section>

            <section id="thesis" class="scroll-mt-28 mt-16">
                <x-sw.reveal><h2 class="text-2xl font-bold text-foreground sm:text-3xl">The thesis, told straight</h2></x-sw.reveal>
                <div class="mt-6">
                    <x-sw.prose>
                        <p>
                            NSE is best understood as a toll booth on Indian capital markets. It does not take a
                            view on any stock and does not carry market risk — it charges for access. Every
                            trade, every data feed, every listing and every clearing leg passes through
                            infrastructure it owns, and the fee is collected whether the market rises or falls.
                        </p>
                    </x-sw.prose>

                    <x-sw.reveal :delay="0.05">
                        <blockquote class="my-8 border-l-4 border-amber-400 bg-beige/70 py-4 pl-5 text-lg leading-relaxed font-semibold text-foreground italic">
                            "NSE going public is like the friend who's been saying they'll start a business for a
                            decade. Eventually they will — just don't bet your rent on the timeline."
                        </blockquote>
                    </x-sw.reveal>

                    <x-sw.prose>
                        <p>
                            The company has been "about to IPO" since 2016. The co-location controversy and the
                            regulatory overhang that followed stretched a straightforward listing into a
                            multi-year saga, and several announced timelines came and went. What has changed is
                            that the pieces are finally moving: regulatory clearance has progressed, DRHP work is
                            further along than before, and long-standing holders are keen to sell. The door is
                            more open than it has been in years — open is not the same as guaranteed.
                        </p>
                        <p>
                            So the thesis is not "buy because the IPO is coming". It is "buy a franchise you
                            would be happy to own unlisted for another three years, and let the listing be the
                            bonus". That framing is the difference between a comfortable position and a
                            frustrating one.
                        </p>
                    </x-sw.prose>
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <x-sw.reveal class="rounded-2xl border border-brand/25 bg-green-50 p-6 shadow-soft">
                        <h3 class="flex items-center gap-2 text-sm font-bold tracking-widest text-primary uppercase">
                            <x-sw.icon name="thumbs-up" class="size-4" />
                            What we love
                        </h3>
                        <p class="mt-3 text-sm leading-relaxed text-muted-foreground">
                            A near-monopoly with ~99.9% share of equity-derivatives volume, extremely high
                            margins, no debt, structural tailwinds.
                        </p>
                    </x-sw.reveal>
                    <x-sw.reveal :delay="0.06" class="rounded-2xl border border-amber-300 bg-amber-50 p-6 shadow-soft">
                        <h3 class="flex items-center gap-2 text-sm font-bold tracking-widest text-amber-800 uppercase">
                            <x-sw.icon name="thumbs-down" class="size-4" />
                            What worries us
                        </h3>
                        <p class="mt-3 text-sm leading-relaxed text-amber-900/80">
                            The price leaves little margin for error, the IPO timeline has slipped repeatedly, and
                            market-infrastructure regulation can move suddenly.
                        </p>
                    </x-sw.reveal>
                </div>
            </section>

            <section id="bull-bear" class="scroll-mt-28 mt-16">
                <x-sw.reveal>
                    <h2 class="text-2xl font-bold text-foreground sm:text-3xl">Bull case vs bear case</h2>
                    <p class="mt-2 text-base text-muted-foreground">Both columns are real. Which one you weight more heavily is the actual decision.</p>
                </x-sw.reveal>
                <div class="mt-7 grid gap-4 lg:grid-cols-2">
                    <x-sw.reveal>
                        <div class="h-full rounded-2xl border border-brand/25 bg-green-50 p-6 shadow-soft">
                            <h3 class="text-sm font-bold tracking-widest text-primary uppercase">Bull case</h3>
                            <ul class="mt-4 space-y-3 text-sm leading-relaxed text-muted-foreground">
                                @foreach ($bull as $b)
                                    <li class="flex gap-2"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-mint"></span>{{ $b }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </x-sw.reveal>
                    <x-sw.reveal :delay="0.1">
                        <div class="h-full rounded-2xl border border-rose-300 bg-rose-50 p-6 shadow-soft">
                            <h3 class="text-sm font-bold tracking-widest text-rose-800 uppercase">Bear case</h3>
                            <ul class="mt-4 space-y-3 text-sm leading-relaxed text-rose-900/80">
                                @foreach ($bear as $b)
                                    <li class="flex gap-2"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-rose-400"></span>{{ $b }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </x-sw.reveal>
                </div>
            </section>

            <section id="valuation" class="scroll-mt-28 mt-16">
                <x-sw.reveal>
                    <h2 class="text-2xl font-bold text-foreground sm:text-3xl">Is it expensive?</h2>
                    <p class="mt-2 text-base text-muted-foreground">Yes — but 'expensive' and 'overvalued' are not the same for a franchise.</p>
                </x-sw.reveal>
                <div class="mt-7 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach ($stats as $i => $s)
                        <x-sw.reveal :delay="$i * 0.08" class="rounded-2xl border border-border bg-green-50 p-4 text-center shadow-soft">
                            <p class="text-xl font-bold text-primary sm:text-2xl">{{ $s['value'] }}</p>
                            <p class="mt-1 text-xs font-semibold text-muted-foreground">{{ $s['label'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>
                <div class="mt-6">
                    <x-sw.prose>
                        <p>
                            A premium is the price of admission for a near-monopoly with an IPO catalyst attached.
                            Businesses like this rarely go on sale — you are unlikely to ever get NSE cheap, and
                            waiting for a bargain has itself been a decade-long trade. What you can control is
                            timeframe and position size: at ~40× earnings the business has to keep compounding
                            for the price to look sensible in hindsight, and that argues for years, not quarters.
                        </p>
                        <p class="text-sm font-semibold">All figures are illustrative; verify against the company's audited filings.</p>
                    </x-sw.prose>
                </div>
            </section>

            <section id="who-for" class="scroll-mt-28 mt-16">
                <x-sw.reveal><h2 class="text-2xl font-bold text-foreground sm:text-3xl">Who this is — and isn't — for</h2></x-sw.reveal>
                <div class="mt-7 grid gap-4 lg:grid-cols-2">
                    <x-sw.reveal>
                        <div class="h-full rounded-2xl border border-brand/25 bg-card p-6 shadow-soft">
                            <h3 class="text-sm font-bold tracking-widest text-primary uppercase">Might suit you if…</h3>
                            <ul class="mt-4 space-y-3 text-sm leading-relaxed text-muted-foreground">
                                @foreach ($suits as $s)
                                    <li class="flex gap-2"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-mint"></span>{{ $s }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </x-sw.reveal>
                    <x-sw.reveal :delay="0.1">
                        <div class="h-full rounded-2xl border border-border bg-beige p-6 shadow-soft">
                            <h3 class="text-sm font-bold tracking-widest text-ink/70 uppercase">Probably not if…</h3>
                            <ul class="mt-4 space-y-3 text-sm leading-relaxed text-ink/70">
                                @foreach ($notSuits as $s)
                                    <li class="flex gap-2"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-ink/30"></span>{{ $s }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </x-sw.reveal>
                </div>
            </section>

            <section id="risks" class="scroll-mt-28 mt-16">
                <x-sw.reveal><h2 class="text-2xl font-bold text-foreground sm:text-3xl">The risks we'd want you to read twice</h2></x-sw.reveal>
                <div class="mt-7 space-y-4">
                    @foreach ($risks as $i => $r)
                        <x-sw.reveal :delay="$i * 0.06" class="rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <h3 class="text-sm font-bold text-foreground">{{ $r['label'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $r['body'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>
            </section>

            <section id="voices" class="scroll-mt-28 mt-16">
                <x-sw.reveal><h2 class="text-2xl font-bold text-foreground sm:text-3xl">What investors are saying</h2></x-sw.reveal>
                <div class="mt-7 grid gap-4 sm:grid-cols-2">
                    @foreach ($reviews as $i => $r)
                        <x-sw.reveal :delay="$i * 0.1">
                            <figure class="card-lift h-full rounded-2xl border border-border bg-card p-6 shadow-soft">
                                <span class="flex gap-0.5">
                                    @for ($s = 0; $s < 5; $s++)
                                        <x-sw.icon name="star" class="{{ $s < $r['stars'] ? 'size-4 fill-mint text-mint' : 'size-4 text-green-200' }}" />
                                    @endfor
                                </span>
                                <blockquote class="mt-4 text-sm leading-relaxed text-muted-foreground">&ldquo;{{ $r['quote'] }}&rdquo;</blockquote>
                                <figcaption class="mt-5 flex items-center gap-3 border-t border-border pt-4">
                                    <span class="grid size-10 place-items-center rounded-full bg-primary text-sm font-bold text-primary-foreground">{{ $r['name'][0] }}</span>
                                    <span>
                                        <span class="block text-sm font-bold text-foreground">{{ $r['name'] }}</span>
                                        <span class="block text-xs font-semibold text-muted-foreground">{{ $r['role'] }}</span>
                                    </span>
                                </figcaption>
                            </figure>
                        </x-sw.reveal>
                    @endforeach
                </div>
                <p class="mt-4 text-xs text-muted-foreground">
                    Sentiment reflects individual investor views on the business and our process. It is not a forecast and not a recommendation.
                </p>
            </section>

            <x-sw.reveal>
                <section id="next-steps" class="scroll-mt-28 bg-price-card mt-16 rounded-3xl p-6 text-white shadow-soft sm:p-10">
                    <h2 class="text-2xl font-bold sm:text-3xl">Where we land</h2>
                    <p class="mt-4 text-base leading-relaxed text-white/85">
                        We would own NSE India as a long-horizon holding, sized so that another delay is an
                        inconvenience rather than a problem. The business is about as good as Indian financial
                        infrastructure gets: a toll booth with pricing power, no debt and volumes that keep
                        growing. The price simply asks you to be patient and to accept that the listing is an
                        option, not a schedule. WittyScore 8.5/10 — strong business, premium price.
                    </p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="/unlisted-shares/nse-india/" class="inline-flex items-center gap-2 rounded-xl bg-mint px-5 py-3 text-sm font-bold text-green-990 transition-transform hover:scale-[1.02]">
                            See live price &amp; how to buy
                            <x-sw.icon name="arrow-right" class="size-4" />
                        </a>
                        <a href="/unlisted-shares/nse-india/about/" class="inline-flex items-center gap-2 rounded-xl border border-white/25 px-5 py-3 text-sm font-bold text-white transition-colors hover:bg-white/10">
                            Read the company profile
                        </a>
                    </div>
                </section>
            </x-sw.reveal>

            <section id="author" class="scroll-mt-28 mt-16 space-y-4">
                <x-sw.reveal>
                    <div class="rounded-2xl border border-border bg-green-50 p-6 shadow-soft">
                        <div class="flex items-center gap-3">
                            <span class="grid size-11 place-items-center rounded-full bg-primary text-sm font-bold text-primary-foreground">SW</span>
                            <div>
                                <p class="text-sm font-bold text-foreground">StockWitty Research · reviewed by a Chartered Accountant</p>
                                <p class="text-xs font-semibold text-muted-foreground">Placeholder author identity — replace before publishing.</p>
                            </div>
                        </div>
                        <p class="mt-4 text-sm leading-relaxed text-muted-foreground">
                            We write honest theses as a distributor of unlisted shares, not as a SEBI-registered
                            adviser — which means we show the risk as clearly as the reward, even when it costs us a sale.
                        </p>
                    </div>
                </x-sw.reveal>

                <x-sw.reveal :delay="0.05">
                    <div class="rounded-2xl border border-border bg-card p-6 shadow-soft">
                        <h3 class="text-xs font-bold tracking-widest text-primary uppercase">Sources</h3>
                        <ul class="mt-3 flex flex-wrap gap-4 text-sm font-semibold">
                            @foreach ([['label' => 'SEBI', 'href' => 'https://www.sebi.gov.in/'], ['label' => 'NSE India', 'href' => 'https://www.nseindia.com/'], ['label' => 'CDSL', 'href' => 'https://www.cdslindia.com/']] as $s)
                                <li>
                                    <a href="{{ $s['href'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-primary hover:underline">
                                        {{ $s['label'] }} <x-sw.icon name="external-link" class="size-3.5" />
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </x-sw.reveal>

                <x-sw.reveal :delay="0.1">
                    <div class="rounded-2xl border border-border bg-beige p-6">
                        <h3 class="text-xs font-bold tracking-widest text-ink/70 uppercase">Disclaimer</h3>
                        <p class="mt-3 text-xs leading-relaxed text-ink/70">
                            This page is for information only and is not investment advice. StockWitty is a
                            distributor of unlisted shares and is not a SEBI-registered investment adviser.
                            Unlisted shares are illiquid and high-risk; there is no guarantee of an IPO, a listing
                            date or an exit at any price. WittyScore is our proprietary, opinion-based score and
                            may change without notice. All figures on this page are illustrative. Do your own due
                            diligence and consult a Chartered Accountant for tax treatment.
                        </p>
                    </div>
                </x-sw.reveal>
            </section>
        </x-sw.toc-layout>
    </main>
</div>
@endsection
