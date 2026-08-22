@props(['study', 'related', 'disclaimer'])

@php
$toc = [
    ['id' => 'overview', 'label' => 'Overview'],
    ['id' => 'situation', 'label' => 'The situation'],
    ['id' => 'approach', 'label' => 'Approach'],
    ['id' => 'journey', 'label' => 'The journey'],
    ['id' => 'outcome', 'label' => 'Outcome'],
    ['id' => 'at-a-glance', 'label' => 'At a glance'],
    ['id' => 'in-their-words', 'label' => 'In their words'],
    ['id' => 'related', 'label' => 'Related case studies'],
    ['id' => 'callback', 'label' => 'Request a callback'],
];
$approachSteps = array_map(fn ($a) => ['t' => $a['title'], 'd' => $a['body']], $study['approach']);
$tableRows = array_map(fn ($r) => [$r['row'], $r['before'], $r['after']], $study['table']);
@endphp

<div class="pt-16">
    <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Case Studies', 'href' => '/case-studies/'], ['label' => $study['title']]]" />
</div>

<main class="pb-24">
    <section class="bg-price-card relative overflow-hidden">
        <div class="mx-auto w-full max-w-[1160px] px-4 py-12 sm:px-6 sm:py-16">
            <x-sw.reveal>
                <div class="flex flex-wrap gap-2">
                    @foreach (explode(' · ', $study['tag']) as $t)
                        <span class="rounded-full bg-white/12 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-mint uppercase">{{ $t }}</span>
                    @endforeach
                    <span class="rounded-full bg-white/12 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-mint uppercase">{{ $study['statChip'] }}</span>
                </div>
                <h1 class="mt-4 max-w-3xl text-3xl font-bold text-white sm:text-5xl">{{ $study['title'] }}</h1>
                <p class="mt-4 max-w-2xl text-base text-white/75 sm:text-lg">{{ $study['summary'] }}</p>
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <x-sw.download-gate :study="array_merge($study, ['disclaimer' => $disclaimer])">
                        <button type="button" class="bg-cta inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-bold text-white">
                            <x-sw.icon name="download" class="size-4" /> Download Full Case Study (PDF)
                        </button>
                    </x-sw.download-gate>
                    <a href="#callback" class="rounded-xl border border-white/25 px-5 py-3 text-sm font-bold text-white hover:bg-white/10">
                        Request a callback
                    </a>
                </div>
            </x-sw.reveal>
        </div>
    </section>

    <div class="mx-auto w-full max-w-[1160px] px-4 pt-8 sm:px-6">
        <div class="flex flex-wrap items-center justify-between gap-4" x-data="{ copied: false }">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold tracking-wide text-muted-foreground uppercase">Share</span>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2 text-sm font-semibold text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary">
                    LinkedIn
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($study['title']) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2 text-sm font-semibold text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary">
                    <span class="text-base font-bold leading-none">X</span> Post
                </a>
                <button type="button" @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                        class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2 text-sm font-semibold text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary">
                    <x-sw.icon name="copy" class="size-4" />
                    <span x-text="copied ? 'Copied!' : 'Copy link'"></span>
                </button>
            </div>
        </div>

        <dl class="mt-6 grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-border bg-border lg:grid-cols-4">
            @foreach ([
                ['k' => 'Investor type', 'v' => $study['meta']['investor']],
                ['k' => 'Holding period', 'v' => $study['meta']['holding']],
                ['k' => 'Products used', 'v' => $study['meta']['products']],
                ['k' => 'Region', 'v' => $study['meta']['region']],
            ] as $c)
                <div class="bg-green-50 p-4">
                    <dt class="text-[0.68rem] font-bold tracking-[0.12em] text-primary uppercase">{{ $c['k'] }}</dt>
                    <dd class="mt-1 text-sm font-bold text-foreground">{{ $c['v'] }}</dd>
                </div>
            @endforeach
        </dl>

        <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ($study['stats'] as $i => $s)
                <x-sw.reveal :delay="$i * 0.06" class="card-lift rounded-2xl border border-border bg-card p-5 shadow-soft">
                    <p class="text-3xl font-bold text-primary">
                        <x-sw.count-up :to="$s['count']" />
                    </p>
                    <p class="mt-1 text-xs font-semibold text-muted-foreground">{{ $s['label'] }}</p>
                </x-sw.reveal>
            @endforeach
        </div>

        <p class="mt-6 flex items-start gap-2 rounded-xl border border-amber-300 border-dashed bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-900">
            <x-sw.icon name="info" class="mt-0.5 size-4 shrink-0" />
            <span>{{ $disclaimer }}</span>
        </p>
    </div>

    <x-sw.toc-layout :items="$toc">
        <article class="pb-4">
            <x-sw.article-h2 id="overview">Overview</x-sw.article-h2>
            @foreach ($study['overview'] as $p)
                <p class="mt-4 text-base leading-relaxed text-muted-foreground">{{ $p }}</p>
            @endforeach

            <x-sw.article-h2 id="situation">The situation</x-sw.article-h2>
            @foreach ($study['situation'] as $p)
                <p class="mt-4 text-base leading-relaxed text-muted-foreground">{{ $p }}</p>
            @endforeach

            <x-sw.article-h2 id="approach">Approach</x-sw.article-h2>
            <p class="mt-3 text-base text-muted-foreground">The steps taken, in the order they happened.</p>
            <x-sw.numbered-steps :steps="$approachSteps" />

            <x-sw.article-h2 id="journey">The journey</x-sw.article-h2>
            <p class="mt-3 text-sm text-muted-foreground">{{ $study['chartNote'] }}</p>
            <div class="mt-5 h-64 rounded-2xl border border-border bg-card p-4 shadow-soft"
                 x-data="caseStudyChart()" data-chart="{{ json_encode($study['chart']) }}">
                <canvas x-ref="chart"></canvas>
            </div>

            <x-sw.article-h2 id="outcome">Outcome</x-sw.article-h2>
            <ul class="mt-4 space-y-3">
                @foreach ($study['outcome'] as $o)
                    <li class="flex gap-3 text-base leading-relaxed text-muted-foreground">
                        <span aria-hidden class="mt-2 size-1.5 shrink-0 rounded-full bg-primary"></span>
                        <span>{{ $o }}</span>
                    </li>
                @endforeach
            </ul>

            <x-sw.article-h2 id="at-a-glance">At a glance</x-sw.article-h2>
            <p class="mt-3 text-sm text-muted-foreground">Before and after, on process rather than profit. Illustrative.</p>
            <x-sw.comparison-table :head="['Measure', 'Before', 'After']" :rows="$tableRows" />

            <x-sw.article-h2 id="in-their-words">In their words</x-sw.article-h2>
            <blockquote class="mt-5 rounded-2xl border border-border bg-green-50 p-6">
                <x-sw.icon name="quote" class="size-6 text-primary" />
                <p class="mt-3 text-lg leading-relaxed font-semibold text-foreground italic">{{ $study['quote']['text'] }}</p>
                <footer class="mt-3 text-sm font-bold text-primary">— {{ $study['quote']['author'] }}</footer>
            </blockquote>

            <x-sw.article-h2 id="related">Related case studies</x-sw.article-h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                @foreach ($related as $r)
                    <a href="/case-studies/{{ $r['slug'] }}/" class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-5 shadow-soft">
                        <span class="w-fit rounded-full bg-beige px-3 py-1 text-[0.68rem] font-bold tracking-wide text-green-950 uppercase">{{ $r['statChip'] }}</span>
                        <h3 class="mt-3 text-base font-bold text-foreground">{{ $r['title'] }}</h3>
                        <p class="mt-2 flex-1 text-sm text-muted-foreground">{{ $r['summary'] }}</p>
                        <span class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-primary">
                            Read case study <x-sw.icon name="arrow-right" class="size-4" />
                        </span>
                    </a>
                @endforeach
            </div>

            <div id="callback" class="scroll-mt-28">
                <form class="mt-6 rounded-3xl border border-border bg-green-50 p-6 sm:p-8" x-data="{
                        done: false, errors: {},
                        submit(e) {
                            const fd = new FormData(e.target);
                            const next = {};
                            const name = String(fd.get('name') ?? '').trim();
                            const mobile = String(fd.get('mobile') ?? '').trim();
                            if (name.length < 2 || name.length > 100) next.name = 'Enter your full name.';
                            if (!/^[6-9]\d{9}$/.test(mobile.replace(/\D/g, '').slice(-10))) next.mobile = 'Enter a valid 10-digit Indian mobile number.';
                            this.errors = next;
                            if (Object.keys(next).length === 0) this.done = true;
                        }
                     }" @submit.prevent="submit($event)">
                    <h3 class="text-xl font-bold text-foreground">Request a callback</h3>
                    <p class="mt-2 max-w-lg text-sm text-muted-foreground">
                        Have a question about this journey or about unlisted shares in general? Leave your number and
                        a StockWitty specialist will call you back.
                    </p>

                    <p x-show="done" x-cloak class="mt-5 rounded-xl border border-primary/30 bg-card px-4 py-3 text-sm font-bold text-primary">
                        Thanks — we've got your request and will be in touch.
                    </p>

                    <div x-show="!done">
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <label class="block text-sm">
                                <span class="font-bold tracking-wide text-foreground uppercase text-xs">Name*</span>
                                <input name="name" type="text" placeholder="Your full name"
                                       class="mt-1.5 h-11 w-full rounded-xl border border-border bg-card px-3.5 text-sm text-foreground placeholder:text-muted-foreground focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/30 focus-visible:outline-none" />
                                <span x-show="errors.name" x-text="errors.name" x-cloak class="mt-1 block text-xs font-semibold text-destructive"></span>
                            </label>
                            <label class="block text-sm">
                                <span class="font-bold tracking-wide text-foreground uppercase text-xs">Mobile*</span>
                                <input name="mobile" type="tel" inputmode="numeric" placeholder="10-digit number"
                                       class="mt-1.5 h-11 w-full rounded-xl border border-border bg-card px-3.5 text-sm text-foreground placeholder:text-muted-foreground focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/30 focus-visible:outline-none" />
                                <span x-show="errors.mobile" x-text="errors.mobile" x-cloak class="mt-1 block text-xs font-semibold text-destructive"></span>
                            </label>
                        </div>
                        <label class="mt-4 block text-sm">
                            <span class="font-bold tracking-wide text-foreground uppercase text-xs">Your question (optional)</span>
                            <textarea name="note" rows="3" placeholder="What would you like to discuss?"
                                      class="mt-1.5 w-full rounded-xl border border-border bg-card px-3.5 py-3 text-sm text-foreground placeholder:text-muted-foreground focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/30 focus-visible:outline-none"></textarea>
                        </label>
                        <button type="submit" class="bg-cta mt-5 inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3.5 text-sm font-bold text-white">
                            <x-sw.icon name="arrow-right" class="size-4" /> Request a callback
                        </button>
                    </div>
                    <p class="mt-4 text-[0.7rem] text-muted-foreground">
                        StockWitty is a distributor of unlisted shares, not a SEBI-registered investment adviser.
                    </p>
                </form>
            </div>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <x-sw.download-gate :study="array_merge($study, ['disclaimer' => $disclaimer])">
                    <button type="button" class="bg-cta inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-bold text-white">
                        <x-sw.icon name="download" class="size-4" /> Download Full Case Study (PDF)
                    </button>
                </x-sw.download-gate>
                <a href="/case-studies/" class="rounded-xl border border-primary/40 px-5 py-3 text-sm font-bold text-primary transition-colors hover:bg-muted">
                    All case studies
                </a>
            </div>

            <p class="mt-8 flex items-start gap-2 rounded-xl border border-amber-300 border-dashed bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-900">
                <x-sw.icon name="info" class="mt-0.5 size-4 shrink-0" />
                <span>{{ $disclaimer }}</span>
            </p>
        </article>
    </x-sw.toc-layout>
</main>
