@php $chips = ['All', 'Pre-IPO', 'DRHP', 'Unicorn', 'Trending']; @endphp

<section id="new-arrivals" class="py-20 sm:py-28" x-data="{ filter: 'All' }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.section-heading eyebrow="Unlisted Shares — New Arrivals" title="New Arrivals"
                               subtitle="Freshly added unlisted and pre-IPO names, with live indicative prices." />

        <x-sw.reveal :delay="0.08">
            <div class="mt-7 flex flex-wrap gap-2">
                @foreach ($chips as $c)
                    <button type="button" @click="filter = '{{ $c }}'"
                            :class="filter === '{{ $c }}' ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-background text-muted-foreground hover:border-mint hover:text-primary'"
                            class="rounded-full border px-4 py-2 text-sm font-bold transition-all">
                        {{ $c }}
                    </button>
                @endforeach
            </div>
        </x-sw.reveal>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (config('sw.new_arrival_companies') as $c)
                <article x-show="filter === 'All' || {!! \Illuminate\Support\Js::from($c['tags']) !!}.includes(filter)"
                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95"
                         class="card-lift rounded-2xl border border-border bg-card p-5 shadow-soft">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="grid size-11 place-items-center rounded-xl bg-muted text-sm font-bold text-primary">
                                {{ $c['short'] }}
                            </span>
                            <div>
                                <h3 class="text-base font-bold text-foreground">{{ $c['name'] }}</h3>
                                <p class="mt-0.5 text-xs font-semibold text-muted-foreground">{{ implode(' · ', $c['tags']) }}</p>
                            </div>
                        </div>
                        <span class="inline-flex shrink-0 items-center gap-0.5 rounded-full px-2 py-1 text-xs font-bold {{ $c['change'] >= 0 ? 'bg-mint/15 text-green-500' : 'bg-destructive/10 text-destructive' }}">
                            <x-sw.icon :name="$c['change'] >= 0 ? 'arrow-up-right' : 'arrow-down-right'" class="size-3.5" />
                            {{ number_format(abs($c['change']), 2) }}%
                        </span>
                    </div>
                    <div class="mt-5 flex items-end justify-between">
                        <p class="text-2xl font-bold text-foreground">₹{{ number_format($c['price']) }}</p>
                        <a href="/unlisted-shares/{{ $c['slug'] }}/" class="rounded-lg bg-beige px-3.5 py-2 text-xs font-bold text-green-950 transition-colors hover:bg-green-100">
                            View company
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
