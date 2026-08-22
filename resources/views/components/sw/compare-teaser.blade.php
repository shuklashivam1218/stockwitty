@php
$pairs = [
    ['a' => 'NSE India', 'b' => 'BSE', 'href' => '/compare/nse-india-vs-bse/'],
    ['a' => 'Tata Capital', 'b' => 'NSDL', 'href' => '/compare/tata-capital-vs-nsdl/'],
    ['a' => 'Swiggy', 'b' => 'Zepto', 'href' => '/compare/swiggy-vs-zepto/'],
];
@endphp

<section id="compare" class="py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.reveal>
            <div class="bg-price-card relative overflow-hidden rounded-3xl border border-green-800/50 p-7 text-white sm:p-10">
                <div aria-hidden class="pointer-events-none absolute -bottom-20 -left-16 size-64 rounded-full bg-mint/20 blur-3xl"></div>
                <div class="relative grid items-center gap-8 lg:grid-cols-2">
                    <div>
                        <p class="text-[0.7rem] font-bold tracking-[0.18em] text-mint-bright uppercase">
                            Compare unlisted shares
                        </p>
                        <h2 class="mt-3 text-3xl font-bold sm:text-4xl">
                            Two companies. One honest side-by-side.
                        </h2>
                        <p class="mt-3 text-sm text-white/70">
                            Price, lot size, P/E, DRHP status and liquidity — lined up so you can judge, not
                            guess.
                        </p>
                    </div>
                    <div class="space-y-3">
                        @foreach ($pairs as $p)
                            <a href="{{ $p['href'] }}" class="flex items-center justify-between gap-3 rounded-2xl border border-white/10 bg-white/[0.06] p-4 backdrop-blur-sm transition-colors hover:border-mint/50 hover:bg-white/10">
                                <span class="flex flex-wrap items-center gap-2 text-sm font-bold">
                                    <span class="rounded-full bg-mint/15 px-3 py-1 text-mint-bright">{{ $p['a'] }}</span>
                                    <x-sw.icon name="git-compare-arrows" class="size-4 text-white/60" />
                                    <span class="rounded-full bg-white/10 px-3 py-1">{{ $p['b'] }}</span>
                                </span>
                                <span class="inline-flex shrink-0 items-center gap-1 text-xs font-bold text-mint-bright">
                                    Compare <x-sw.icon name="arrow-right" class="size-3.5" />
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-sw.reveal>
    </div>
</section>
