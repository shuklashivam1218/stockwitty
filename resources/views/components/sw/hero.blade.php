@php
$words = ['Invest', 'Smart,', 'Stay', 'Witty.'];
$badges = [
    ['label' => 'Pre-IPO 2026', 'class' => 'left-2 top-8 sm:left-6', 'delay' => 0],
    ['label' => '$58B Valuation', 'class' => 'right-2 top-24 sm:right-8', 'delay' => 1.1],
    ['label' => 'Profitable', 'class' => 'right-10 bottom-10 sm:right-24', 'delay' => 2.1],
];
@endphp

<section id="home" class="relative isolate overflow-hidden pt-28 pb-16 sm:pt-36 sm:pb-24">
    <div aria-hidden class="bg-mesh animate-mesh-pulse pointer-events-none absolute -top-40 left-1/2 -z-10 size-[900px] -translate-x-1/2 opacity-70 blur-3xl"></div>

    <svg aria-hidden viewBox="0 0 800 300" preserveAspectRatio="none"
         class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-64 w-full opacity-[0.18]">
        <defs>
            <linearGradient id="heroFill" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="var(--mint)" stop-opacity="0.7" />
                <stop offset="100%" stop-color="var(--mint)" stop-opacity="0" />
            </linearGradient>
        </defs>
        <path d="M0 260 L90 232 L180 244 L270 196 L360 210 L450 152 L540 168 L630 104 L720 118 L800 48"
              fill="none" stroke="var(--brand)" stroke-width="3" stroke-linecap="round" />
        <path d="M0 260 L90 232 L180 244 L270 196 L360 210 L450 152 L540 168 L630 104 L720 118 L800 48 L800 300 L0 300 Z"
              fill="url(#heroFill)" />
    </svg>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @foreach ($badges as $b)
            <div class="absolute hidden rounded-full border border-mint/40 bg-background/70 px-4 py-2 text-xs font-bold text-primary shadow-soft backdrop-blur-md md:block animate-badge-float {{ $b['class'] }}"
                 style="--float-delay: {{ $b['delay'] }}s">
                {{ $b['label'] }}
            </div>
        @endforeach

        <div class="mx-auto max-w-3xl text-center">
            <p class="animate-fade-up-in mx-auto inline-flex items-center gap-2 rounded-full border border-border bg-muted px-4 py-1.5 text-xs font-semibold text-green-700">
                <x-sw.icon name="sparkles" class="size-3.5 text-mint" />
                Unlisted &amp; Pre-IPO shares, explained without the jargon
            </p>

            <h1 class="mt-6 text-[2.6rem] leading-[1.05] font-bold text-foreground sm:text-6xl lg:text-7xl">
                @foreach ($words as $i => $w)
                    <span class="animate-fade-up-in mr-3 inline-block {{ $i === 3 ? 'text-gradient' : '' }}"
                          style="--fade-delay: {{ 0.15 + $i * 0.12 }}s">{{ $w }}</span>
                @endforeach
            </h1>

            <p class="animate-fade-up-in mx-auto mt-6 max-w-2xl text-base text-muted-foreground sm:text-lg" style="--fade-delay: 0.7s">
                We break down IPOs, DRHP filings and unlisted share prices with a witty take and real
                numbers — so retail investors in India can research pre-IPO companies properly, then buy
                them at a transparent, all-inclusive price.
            </p>

            <div class="animate-fade-up-in mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row" style="--fade-delay: 0.85s">
                <a href="#new-arrivals" class="bg-cta group inline-flex w-full items-center justify-center gap-2 rounded-xl px-7 py-3.5 text-sm font-bold text-white shadow-glow hover:scale-[1.03] sm:w-auto">
                    Explore Unlisted Shares
                    <x-sw.icon name="arrow-right" class="size-4 transition-transform group-hover:translate-x-1" />
                </a>
                <a href="#how-to-buy" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-beige px-7 py-3.5 text-sm font-bold text-green-950 transition-all hover:scale-[1.02] hover:bg-green-100 sm:w-auto">
                    <x-sw.icon name="shield-check" class="size-4 text-primary" />
                    How buying works
                </a>
            </div>
        </div>
    </div>
</section>
