@php
$sectors = [
    ['name' => 'Financial Services', 'slug' => 'financial-services', 'count' => 48, 'icon' => 'landmark'],
    ['name' => 'Fintech', 'slug' => 'fintech', 'count' => 32, 'icon' => 'smartphone'],
    ['name' => 'Technology', 'slug' => 'technology', 'count' => 29, 'icon' => 'cpu'],
    ['name' => 'Consumer & Retail', 'slug' => 'consumer-retail', 'count' => 26, 'icon' => 'shopping-bag'],
    ['name' => 'Energy', 'slug' => 'energy', 'count' => 18, 'icon' => 'zap'],
    ['name' => 'Banking', 'slug' => 'banking', 'count' => 16, 'icon' => 'building-2'],
    ['name' => 'FMCG', 'slug' => 'fmcg', 'count' => 14, 'icon' => 'package'],
    ['name' => 'Capital Markets', 'slug' => 'capital-markets', 'count' => 12, 'icon' => 'line-chart'],
    ['name' => 'Auto & Mobility', 'slug' => 'auto-mobility', 'count' => 11, 'icon' => 'car'],
    ['name' => 'Healthcare', 'slug' => 'healthcare', 'count' => 10, 'icon' => 'heart-pulse'],
    ['name' => 'Real Estate', 'slug' => 'real-estate', 'count' => 9, 'icon' => 'home'],
    ['name' => 'Aviation', 'slug' => 'aviation', 'count' => 6, 'icon' => 'plane'],
];
@endphp

<section id="sectors" class="bg-green-50/60 py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.section-heading eyebrow="Sectors — Browse the market" title="Explore the unlisted market by sector"
                               subtitle="From capital markets to quick commerce — start where your conviction already is." />

        <div class="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-4">
            @foreach ($sectors as $i => $s)
                <x-sw.reveal :delay="$i * 0.06">
                    <a href="/unlisted-shares/?sector={{ $s['slug'] }}" class="card-lift group flex items-center gap-3 rounded-2xl border border-border bg-card p-4 shadow-soft sm:p-5">
                        <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-green-50 text-primary transition-colors group-hover:bg-mint/20">
                            <x-sw.icon :name="$s['icon']" class="size-5" />
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-bold text-foreground">{{ $s['name'] }}</span>
                            <span class="block text-xs font-semibold text-muted-foreground">{{ $s['count'] }} companies</span>
                        </span>
                    </a>
                </x-sw.reveal>
            @endforeach
        </div>
    </div>
</section>
