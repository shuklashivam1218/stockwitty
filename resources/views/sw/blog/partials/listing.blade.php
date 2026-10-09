{{-- The swappable part of /blog/: filters, featured card, grid, pager.
     Rendered into the full page and returned alone for the in-page (AJAX)
     page/filter changes. No x-sw.reveal here — its IntersectionObserver
     only sees nodes present at page load, so swapped-in cards would stay
     invisible; animate-fade-up-in plays on insertion instead.
     Every link keeps a real href (data-blog-nav) as the no-JS fallback. --}}
@php
    $chip   = 'inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold transition-all';
    $chipOn = 'border-primary bg-primary text-primary-foreground shadow-soft';
    $chipOff = 'border-border bg-card text-muted-foreground hover:border-primary/50 hover:text-primary';
@endphp

<div data-blog-status="{{ $posts->lastPage() > 1 ? 'Page ' . $posts->currentPage() . ' of ' . $posts->lastPage() : '' }}{{ $active ? ' · ' . $active->name : '' }}">
    <div class="flex flex-wrap items-center justify-between gap-4">
        @if ($categories->count() > 1)
            <nav aria-label="Filter guides by category" class="flex flex-wrap gap-2">
                <a href="/blog/#posts" data-blog-nav aria-current="{{ $active ? 'false' : 'true' }}"
                   class="{{ $chip }} {{ $active ? $chipOff : $chipOn }}">All</a>
                @foreach ($categories as $cat)
                    <a href="/blog/?category={{ urlencode($cat->slug) }}#posts" data-blog-nav aria-current="{{ $active?->is($cat) ? 'true' : 'false' }}"
                       class="{{ $chip }} {{ $active?->is($cat) ? $chipOn : $chipOff }}">{{ $cat->name }}</a>
                @endforeach
            </nav>
        @endif

        @if ($total)
            <p class="text-sm font-semibold text-muted-foreground">
                <span class="font-bold text-foreground">{{ $total }}</span> {{ \Illuminate\Support\Str::plural('guide', $total) }}{{ $active ? ' in ' . $active->name : '' }}
            </p>
        @endif
    </div>

    @if ($featured)
        <a href="{{ $featured['href'] }}" class="animate-fade-up-in card-lift mt-8 grid gap-6 overflow-hidden rounded-3xl border border-border bg-card p-6 shadow-soft sm:p-8 lg:grid-cols-[1.2fr_1fr]">
            <div>
                <span class="rounded-full bg-mint/15 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-primary uppercase">
                    Featured · {{ $featured['category'] }}
                </span>
                <h2 class="mt-4 text-2xl font-bold text-foreground sm:text-3xl">{{ $featured['title'] }}</h2>
                <p class="mt-3 text-sm text-muted-foreground sm:text-base">{{ $featured['excerpt'] }}</p>
                <p class="mt-5 flex flex-wrap items-center gap-4 text-xs font-bold text-muted-foreground">
                    <span class="inline-flex items-center gap-1.5">
                        <x-sw.icon name="clock" class="size-3.5" /> {{ $featured['read'] }}
                    </span>
                    <span>{{ $featured['date'] }}</span>
                    <span class="inline-flex items-center gap-1 text-primary">
                        Read the guide <x-sw.icon name="arrow-right" class="size-3.5" />
                    </span>
                </p>
            </div>
            @if ($featured['image'])
                <img src="{{ $featured['image'] }}" alt="{{ $featured['imageAlt'] }}" loading="lazy"
                     class="h-full min-h-40 w-full rounded-2xl object-cover" />
            @else
                <div class="bg-price-card grid min-h-40 place-items-center rounded-2xl p-6 text-center">
                    @if ($featured['heroIcon'])
                        <span class="grid size-14 place-items-center rounded-2xl bg-white/10 text-mint-bright ring-1 ring-white/20">
                            <x-sw.icon :name="$featured['heroIcon']" class="size-7" />
                        </span>
                    @endif
                    <p class="text-lg font-bold text-white">{{ $featured['title'] }}</p>
                </div>
            @endif
        </a>
    @endif

    @if ($posts->isNotEmpty())
        <ul class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $i => $p)
                <li class="animate-fade-up-in" style="--fade-delay: {{ number_format(0.04 * $i, 2) }}s">
                    <a href="{{ $p['href'] }}" class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-6 shadow-soft">
                        <span class="w-fit rounded-full bg-green-50 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-primary uppercase">{{ $p['category'] }}</span>
                        <h3 class="mt-4 text-lg font-bold text-foreground">{{ $p['title'] }}</h3>
                        <p class="mt-2 flex-1 text-sm text-muted-foreground">{{ $p['excerpt'] }}</p>
                        <p class="mt-4 flex items-center justify-between text-xs font-bold text-muted-foreground">
                            <span class="inline-flex items-center gap-1.5">
                                <x-sw.icon name="clock" class="size-3.5" /> {{ $p['read'] }}
                            </span>
                            <span>{{ $p['date'] }}</span>
                        </p>
                    </a>
                </li>
            @endforeach
        </ul>
    @elseif (!$featured)
        <p class="mt-8 rounded-2xl border border-dashed border-border bg-card p-10 text-center text-sm font-semibold text-muted-foreground">
            New guides are on their way — check back soon.
        </p>
    @endif

    {{ $posts->links('partials.sw.pagination') }}
</div>
