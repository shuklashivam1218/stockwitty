@php
$videos = [
    ['initials' => 'NSE', 'title' => 'NSE India Unlisted — Should You Buy? | StockWitty', 'len' => '11:24'],
    ['initials' => 'TA', 'title' => 'Tata Capital Pre-IPO: The Honest Breakdown | StockWitty', 'len' => '9:02'],
    ['initials' => 'SB', 'title' => 'SBI Mutual Fund Unlisted Shares Explained | StockWitty', 'len' => '8:15'],
    ['initials' => 'OY', 'title' => 'OYO Unlisted: Risk, Lot Size & Reality Check | StockWitty', 'len' => '12:38'],
    ['initials' => 'SW', 'title' => 'Swiggy Unlisted vs Listed Peers | StockWitty', 'len' => '7:46'],
    ['initials' => 'PH', 'title' => 'PhonePe Pre-IPO — What Retail Should Know | StockWitty', 'len' => '10:31'],
];
$loop = array_merge($videos, $videos);
@endphp

<section id="watch" class="py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.section-heading eyebrow="Watch &amp; Learn — YouTube" title="Company breakdowns, in plain Hinglish"
                               subtitle="No hype reels. Numbers, lot sizes, risks and what the DRHP actually says." />
    </div>
    <div class="group mt-8 overflow-hidden [mask-image:linear-gradient(90deg,transparent,#000_4%,#000_96%,transparent)]">
        <div class="marquee-track flex w-max gap-4 px-4">
            @foreach ($loop as $v)
                <a href="/blog/" class="card-lift w-[19rem] shrink-0 overflow-hidden rounded-2xl border border-border bg-card shadow-soft">
                    <div class="bg-price-card relative flex h-40 items-end justify-between p-4">
                        <span class="grid size-10 place-items-center rounded-xl bg-white/10 text-xs font-bold text-mint-bright">
                            {{ $v['initials'] }}
                        </span>
                        <span class="rounded-md bg-black/40 px-2 py-1 text-[0.7rem] font-bold text-white">
                            {{ $v['len'] }}
                        </span>
                        <span class="absolute inset-0 grid place-items-center">
                            <span class="grid size-14 place-items-center rounded-full bg-mint/25 text-white ring-1 ring-mint/50 transition-transform duration-300 group-hover:scale-105">
                                <x-sw.icon name="play" class="size-6 fill-current" />
                            </span>
                        </span>
                    </div>
                    <p class="p-4 text-sm font-bold text-foreground">{{ $v['title'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
