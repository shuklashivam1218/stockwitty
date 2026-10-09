@php
// Latest three published posts; the section disappears rather than show an empty grid.
$posts = \App\Models\BlogPost::published()->with('category')->orderByDesc('published_at')->limit(3)->get()
    ->map(fn ($p) => \App\Http\Controllers\Sw\BlogController::card($p));
@endphp

@if ($posts->isNotEmpty())

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
                            {{ $p['category'] }}
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
@endif
