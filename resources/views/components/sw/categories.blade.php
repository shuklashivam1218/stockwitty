@php
$tiles = [
    ['label' => 'Pre-IPO', 'href' => '/unlisted-shares/', 'icon' => 'rocket', 'note' => 'IPO-bound names'],
    ['label' => 'DRHP-Filed', 'href' => '/drhp/', 'icon' => 'file-search', 'note' => 'Filed with SEBI'],
    ['label' => 'Unicorns', 'href' => '/unlisted-shares/', 'icon' => 'sparkles', 'note' => '$1B+ startups'],
    ['label' => 'Trending', 'href' => '/unlisted-shares/', 'icon' => 'flame', 'note' => 'Most asked this week'],
    ['label' => 'Screener', 'href' => '/screener/', 'icon' => 'bar-chart-3', 'note' => 'Filter 250+ names'],
    ['label' => 'All Unlisted', 'href' => '/unlisted-shares/', 'icon' => 'layers', 'note' => 'Full listing'],
];
@endphp

<section id="categories" class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.section-heading eyebrow="Explore by category" title="Find your kind of unlisted share"
                               subtitle="Six doors into the same 250+ company database." />

        <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($tiles as $i => $t)
                <x-sw.reveal :delay="$i * 0.06">
                    <a href="{{ $t['href'] }}" class="card-lift group flex h-full flex-col gap-3 rounded-2xl border border-border bg-card p-5 shadow-soft">
                        <span class="grid size-11 place-items-center rounded-xl bg-muted text-primary transition-colors group-hover:bg-mint/20">
                            <x-sw.icon :name="$t['icon']" class="size-5" />
                        </span>
                        <span class="text-sm font-bold text-foreground">{{ $t['label'] }}</span>
                        <span class="text-xs font-semibold text-muted-foreground">{{ $t['note'] }}</span>
                    </a>
                </x-sw.reveal>
            @endforeach
        </div>
    </div>
</section>
