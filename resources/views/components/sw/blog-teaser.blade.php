@php
$posts = [
    ['cat' => 'Guide', 'title' => 'How to Buy Unlisted Shares in India (Step-by-Step)', 'excerpt' => 'KYC, CML copy, payment to a verified account and off-market demat delivery — in order.', 'read' => '8 min read', 'href' => '/blog/how-to-buy-unlisted-shares/'],
    ['cat' => 'Tax', 'title' => 'Tax on Unlisted Shares in India (2026 Guide)', 'excerpt' => 'Holding period, LTCG vs STCG treatment, and what changes after the company lists.', 'read' => '7 min read', 'href' => '/blog/tax-on-unlisted-shares/'],
    ['cat' => 'Basics', 'title' => 'Unlisted vs Listed Shares: Key Differences', 'excerpt' => 'Liquidity, price discovery, disclosure and settlement — where the two really diverge.', 'read' => '6 min read', 'href' => '/blog/unlisted-shares-vs-listed-shares/'],
];
@endphp

<section id="blog" class="py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <x-sw.section-heading eyebrow="From the blog" title="Research you can read in one coffee"
                                   subtitle="Written for retail investors, not for compliance files." />
            <x-sw.reveal :delay="0.08">
                <a href="/blog/" class="inline-flex items-center gap-1.5 rounded-xl border border-primary/40 px-4 py-2.5 text-sm font-bold text-primary transition-all hover:border-primary hover:bg-muted">
                    All articles <x-sw.icon name="arrow-right" class="size-4" />
                </a>
            </x-sw.reveal>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
            @foreach ($posts as $i => $p)
                <x-sw.reveal :delay="$i * 0.08">
                    <a href="{{ $p['href'] }}" class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-6 shadow-soft">
                        <span class="w-fit rounded-full bg-mint/15 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-primary uppercase">
                            {{ $p['cat'] }}
                        </span>
                        <h3 class="mt-4 text-lg font-bold text-foreground">{{ $p['title'] }}</h3>
                        <p class="mt-2 flex-1 text-sm text-muted-foreground">{{ $p['excerpt'] }}</p>
                        <p class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground">
                            <x-sw.icon name="clock" class="size-3.5" />
                            {{ $p['read'] }}
                        </p>
                    </a>
                </x-sw.reveal>
            @endforeach
        </div>
    </div>
</section>
