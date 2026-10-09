{{-- One blog post, rendered from a BlogPost (see Sw\BlogController::page()).
     Everything here is escaped plain text except $body, which is stored
     purified (clean($html, 'blog')) and only gains TOC ids/classes since. --}}
@props([
    'post', 'crumbs', 'chips', 'authorLine', 'author' => null, 'dateLabel', 'hero' => null,
    'introParas' => [], 'toc', 'body', 'related' => [], 'leadForm', 'preview' => false,
    'takeawaysTitle' => 'Key takeaways',
])

@php
    $title     = $post->title;
    $takeaways = $post->takeaways ?? [];
    $faqs      = $post->faqs ?? [];
    $faqTabs   = $post->faqTabs();
    $sources   = $post->sources ?? [];
    $youtubeId = $post->video['youtube_id'] ?? null;
    $socials   = $author ? array_filter([
        'LinkedIn'  => $author->author_linkedin,
        'X'         => $author->author_twitter,
        'Facebook'  => $author->author_facebook,
        'Instagram' => $author->author_instagram,
        'Website'   => $author->author_website,
    ]) : [];
    $authorSchema = $author ? array_filter([
        '@type'    => 'Person',
        'name'     => $author->name,
        'jobTitle' => $author->author_designation,
        'sameAs'   => array_values($socials) ?: null,
    ]) : null;
@endphp

@unless ($preview)
    @push('jsonld')
        {!! \App\Support\Seo\JsonLd::script(\App\Support\Seo\JsonLd::article(
            $title, (string) $post->summary, \App\Support\Seo\JsonLd::currentUrl(), $hero['src'] ?? null,
            array_filter([
                'datePublished' => $post->published_at?->toIso8601String(),
                'dateModified'  => $post->updated_at?->toIso8601String(),
                'author'        => $authorSchema,
            ])
        )) !!}
    @endpush
    <x-sw.faq-schema :faqs="$faqs" />
@endunless

@if ($preview)
    <div class="fixed inset-x-0 top-16 z-40 bg-amber-100 px-4 py-2 text-center text-sm font-bold text-amber-900">
        Preview · {{ $post->trashed() ? 'in trash' : ($post->isPublished() ? 'published' : 'draft — not visible to the public') }}
    </div>
@endif

<div class="pt-16">
    <x-sw.breadcrumb :items="$crumbs" />
</div>

<main>
    <div class="mx-auto w-full max-w-[1160px] px-4 pt-10 sm:px-6 sm:pt-14 lg:flex lg:justify-center lg:gap-12">
        <div class="min-w-0 lg:max-w-[780px] lg:flex-1">
            <x-sw.reveal>
                <div class="flex flex-wrap gap-2">
                    @foreach ($chips as $c)
                        <span class="rounded-full bg-mint/15 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-primary uppercase">{{ $c }}</span>
                    @endforeach
                </div>
                <h1 class="mt-5 text-3xl font-bold leading-tight text-foreground sm:text-[2.75rem]">{{ $title }}</h1>
                <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs font-semibold text-muted-foreground">
                    <span class="inline-flex items-center gap-2">
                        <span aria-hidden="true" class="grid size-9 place-items-center rounded-full bg-primary text-[0.7rem] font-bold text-primary-foreground">{{ $author ? strtoupper(mb_substr($author->name, 0, 1)) : 'SW' }}</span>
                        {{ $authorLine }}
                    </span>
                    <span>{{ $dateLabel }}</span>
                    <span class="inline-flex items-center gap-1.5">
                        <x-sw.icon name="clock" class="size-3.5" />
                        {{ $post->readLabel() }}
                    </span>
                </div>
            </x-sw.reveal>
            @isset($topSlot)
                <div class="mt-7">{{ $topSlot }}</div>
            @endisset
        </div>
        <div class="hidden shrink-0 lg:block lg:w-[248px]"></div>
    </div>

    <x-sw.reveal :delay="0.05">
        <figure class="mx-auto mt-8 max-w-6xl px-4 sm:px-6">
            @if ($hero)
                <img src="{{ $hero['src'] }}" alt="{{ $hero['alt'] }}" width="{{ $hero['width'] ?? 1600 }}" height="{{ $hero['height'] ?? 900 }}"
                     class="w-full rounded-3xl border border-border object-cover shadow-soft" />
            @else
            <div data-agent-skip class="bg-price-card relative flex aspect-[16/7] w-full flex-col items-center justify-center gap-5 overflow-hidden rounded-3xl px-6 text-center shadow-soft">
                <div class="pointer-events-none absolute -top-24 -right-16 size-72 rounded-full bg-mint/20 blur-3xl"></div>
                @if ($post->hero_icon)
                    <span class="relative grid size-16 place-items-center rounded-2xl bg-white/10 text-mint-bright ring-1 ring-white/20 backdrop-blur sm:size-20">
                        <x-sw.icon :name="$post->hero_icon" class="size-8 sm:size-10" />
                    </span>
                @endif
                <p class="relative max-w-2xl text-lg font-bold leading-snug text-white sm:text-2xl">{{ $title }}</p>
            </div>
            @endif
        </figure>
    </x-sw.reveal>

    <x-sw.toc-layout :items="$toc">
        <article class="pb-4">
            @if ($introParas)
                <div class="mt-8 space-y-4 text-base leading-relaxed text-muted-foreground">
                    @foreach ($introParas as $para)
                        <p>{{ $para }}</p>
                    @endforeach
                </div>
            @endif

            @if ($takeaways)
            <x-sw.reveal>
                <aside class="mt-10 rounded-3xl border border-mint/40 bg-green-50 p-6 sm:p-7">
                    <h2 class="text-lg font-bold text-foreground">{{ $takeawaysTitle }}</h2>
                    <ul class="mt-4 space-y-3">
                        @foreach ($takeaways as $t)
                            <li class="flex gap-3 text-sm leading-relaxed text-muted-foreground">
                                <x-sw.icon name="check" class="mt-0.5 size-4 shrink-0 text-primary" />
                                <span>{{ $t }}</span>
                            </li>
                        @endforeach
                    </ul>
                </aside>
            </x-sw.reveal>
            @endif

            @if ($youtubeId)
            {{-- Click-to-load: no YouTube request (or cookie) until the reader asks for it. --}}
            <x-sw.reveal>
                <figure data-agent-skip class="mt-10" x-data="{ playing: false }">
                    <div class="bg-price-card relative grid aspect-video w-full place-items-center overflow-hidden rounded-3xl">
                        <template x-if="!playing">
                            <button type="button" @click="playing = true" class="group absolute inset-0 grid place-items-center" aria-label="Play video: {{ $post->video['caption'] ?? $title }}">
                                <img src="https://i.ytimg.com/vi/{{ $youtubeId }}/hqdefault.jpg" alt="" loading="lazy" class="absolute inset-0 size-full object-cover opacity-70" />
                                <span class="relative grid size-16 place-items-center rounded-full bg-white/15 text-white ring-1 ring-white/30 backdrop-blur transition-transform group-hover:scale-105">
                                    <x-sw.icon name="play" class="size-7 fill-white" />
                                </span>
                            </button>
                        </template>
                        <template x-if="playing">
                            <iframe class="absolute inset-0 size-full" src="https://www.youtube-nocookie.com/embed/{{ $youtubeId }}?autoplay=1&rel=0"
                                    title="{{ $post->video['caption'] ?? $title }}" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen
                                    referrerpolicy="strict-origin-when-cross-origin"></iframe>
                        </template>
                    </div>
                    @if (!empty($post->video['caption']))
                        <figcaption class="mt-3 text-center text-xs font-semibold text-muted-foreground">{{ $post->video['caption'] }}</figcaption>
                    @endif
                </figure>
            </x-sw.reveal>
            @endif

            <div class="sw-article">{!! $body !!}</div>

            @if ($sources)
            <h2 id="sources" class="mt-14 scroll-mt-28 text-2xl font-bold text-foreground sm:text-3xl">Sources &amp; references</h2>
            <p class="mt-3 text-sm text-muted-foreground">Verify every figure against official filings.</p>
            <ul class="mt-4 grid gap-2 sm:grid-cols-2">
                @foreach ($sources as $s)
                    <li>
                        <a href="{{ $s['href'] }}" target="_blank" rel="noopener noreferrer nofollow"
                           class="flex items-center justify-between gap-3 rounded-xl border border-border bg-card px-4 py-3 text-sm font-semibold text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary">
                            {{ $s['label'] }}
                            <x-sw.icon name="external-link" class="size-4 shrink-0" />
                        </a>
                    </li>
                @endforeach
            </ul>
            @endif

            @if ($faqs)
            <h2 id="faq" class="mt-14 scroll-mt-28 text-2xl font-bold text-foreground sm:text-3xl">Frequently asked questions</h2>
            {{-- Tab names are admin text inside Alpine expressions: always @js(), never quoted {{ }}. --}}
            <div x-data="{ active: 'All' }">
                @if (count($faqTabs) > 1)
                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach (array_merge(['All'], $faqTabs) as $t)
                        <button type="button" @click="active = @js($t)" :aria-pressed="active === @js($t)"
                                :class="active === @js($t) ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-card text-muted-foreground hover:border-primary/50 hover:text-primary'"
                                class="rounded-full border px-4 py-1.5 text-sm font-semibold transition-all">{{ $t }}</button>
                    @endforeach
                </div>
                @endif
                <div class="mt-5">
                    @foreach ($faqs as $f)
                        <details x-show="active === 'All' || active === @js($f['tab'])"
                                 class="sw-faq-item mb-3 overflow-hidden rounded-2xl border border-border bg-card px-5 shadow-soft transition-colors">
                            <summary class="flex cursor-pointer items-center justify-between gap-3 py-4 text-left text-base font-bold text-foreground">
                                {{ $f['q'] }}
                                <x-sw.icon name="chevron-down" class="sw-faq-chevron size-4 shrink-0 text-muted-foreground" />
                            </summary>
                            <p class="pb-5 text-sm leading-relaxed text-muted-foreground">{{ $f['a'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
            @endif

            <div data-agent-skip class="mt-12 flex flex-wrap items-center gap-3 border-y border-border py-5" x-data="{ copied: false }">
                <span class="inline-flex items-center gap-2 text-sm font-bold text-foreground">
                    <x-sw.icon name="share-2" class="size-4 text-primary" /> Share this guide
                </span>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2 text-sm font-semibold text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary">
                    LinkedIn
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($title) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2 text-sm font-semibold text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary">
                    <span class="text-base font-bold leading-none">X</span> Post
                </a>
                <button type="button" @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                        class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2 text-sm font-semibold text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary">
                    <x-sw.icon name="copy" class="size-4" />
                    <span x-text="copied ? 'Copied!' : 'Copy link'"></span>
                </button>
            </div>

            <x-sw.reveal>
                <div class="mt-10 flex flex-col gap-4 rounded-3xl border border-border bg-secondary p-6 sm:flex-row sm:items-start">
                    <span aria-hidden="true" class="grid size-14 shrink-0 place-items-center rounded-2xl bg-primary text-base font-bold text-primary-foreground">
                        {{ $author ? strtoupper(mb_substr($author->name, 0, 1)) : 'SW' }}
                    </span>
                    <div>
                        @if ($author)
                            <p class="text-base font-bold text-foreground">{{ $author->name }}</p>
                            @if ($author->author_designation)
                                <p class="text-xs font-bold tracking-wide text-primary uppercase">{{ $author->author_designation }}</p>
                            @endif
                            <p class="mt-1.5 text-sm leading-relaxed text-muted-foreground">
                                {{ $author->author_bio ?: 'Writes for StocksWitty on unlisted shares, pre-IPO investing and the Indian markets.' }}
                            </p>
                            @if ($socials)
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($socials as $label => $href)
                                        <a href="{{ $href }}" target="_blank" rel="noopener noreferrer nofollow me"
                                           class="rounded-full border border-border bg-card px-3 py-1 text-[0.7rem] font-bold text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary">{{ $label }}</a>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <p class="text-base font-bold text-foreground">StocksWitty Research</p>
                            <p class="mt-1.5 text-sm leading-relaxed text-muted-foreground">
                                We research unlisted shares the way we'd want them explained to us — the risks as clearly as the upside.
                            </p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach (['CA-reviewed', 'Unlisted-shares specialists', 'Distributor · not a SEBI adviser'] as $c)
                                    <span class="rounded-full border border-border bg-card px-3 py-1 text-[0.7rem] font-bold text-muted-foreground">{{ $c }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </x-sw.reveal>

            @if ($related)
            <h2 id="related" class="mt-14 scroll-mt-28 text-2xl font-bold text-foreground sm:text-3xl">Related reading</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-3">
                @foreach ($related as $i => $r)
                    <x-sw.reveal :delay="$i * 0.05">
                        <a href="{{ $r['href'] }}" class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <span class="text-[0.7rem] font-bold tracking-wide text-primary uppercase">{{ $r['category'] }}</span>
                            <span class="mt-2 flex-1 font-bold leading-snug text-foreground">{{ $r['title'] }}</span>
                            <span class="mt-3 text-xs font-semibold text-muted-foreground">{{ $r['read'] }}</span>
                        </a>
                    </x-sw.reveal>
                @endforeach
            </div>
            @endif

            <x-sw.illustrative-note>
                Any prices, lot sizes or return figures in this article are illustrative examples for explanation only. Confirm live quotes and charges before you transact.
            </x-sw.illustrative-note>
        </article>
    </x-sw.toc-layout>

    <section data-agent-skip class="bg-price-card mt-16 py-14" x-data="{
        done: false, errors: {},
        submit(e) {
            const fd = new FormData(e.target);
            const next = {};
            const name = String(fd.get('name') ?? '').trim();
            const email = String(fd.get('email') ?? '').trim();
            const mobile = String(fd.get('mobile') ?? '').trim();
            if (name.length < 2 || name.length > 100) next.name = 'Enter your full name.';
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email) || email.length > 255) next.email = 'Enter a valid email address.';
            if (!/^[6-9]\d{9}$/.test(mobile.replace(/\D/g, '').slice(-10))) next.mobile = 'Enter a valid 10-digit Indian mobile number.';
            if (!fd.get('consent')) next.consent = 'Please accept to be contacted.';
            this.errors = next;
            if (Object.keys(next).length === 0) this.done = true;
        }
    }">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">
            <x-sw.reveal>
                <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">Talk to a human</p>
                <h2 class="mt-3 text-2xl font-bold text-white sm:text-3xl">{{ $leadForm['heading'] }}</h2>
                <p class="mt-3 text-sm leading-relaxed text-white/75">{{ $leadForm['subtext'] }}</p>

                <div x-show="done" style="display: none;" class="mt-7 rounded-2xl border border-white/20 bg-white/10 p-6 text-white">
                    <p class="flex items-center gap-2 text-base font-bold">
                        <x-sw.icon name="check" class="size-5 text-mint-bright" /> Request received
                    </p>
                    <p class="mt-2 text-sm text-white/80">
                        Our team will call you back on a working day between 10am and 7pm IST. Nothing is bought or sold on your behalf without your written confirmation.
                    </p>
                </div>

                <form x-show="!done" style="display: block;" class="mt-7 grid gap-4 sm:grid-cols-2" @submit.prevent="submit($event)">
                    <label class="block text-sm">
                        <span class="font-semibold text-white/90">Name</span>
                        <input name="name" type="text" placeholder="Your full name" maxlength="255"
                               class="mt-1.5 w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:border-mint-bright focus:outline-none" />
                        <span x-show="errors.name" x-text="errors.name" class="mt-1 block text-xs font-semibold text-mint-bright" style="display: none;"></span>
                    </label>
                    <label class="block text-sm">
                        <span class="font-semibold text-white/90">Email</span>
                        <input name="email" type="email" placeholder="you@email.com" maxlength="255"
                               class="mt-1.5 w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:border-mint-bright focus:outline-none" />
                        <span x-show="errors.email" x-text="errors.email" class="mt-1 block text-xs font-semibold text-mint-bright" style="display: none;"></span>
                    </label>
                    <label class="block text-sm">
                        <span class="font-semibold text-white/90">Mobile</span>
                        <input name="mobile" type="tel" placeholder="10-digit mobile" maxlength="255"
                               class="mt-1.5 w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:border-mint-bright focus:outline-none" />
                        <span x-show="errors.mobile" x-text="errors.mobile" class="mt-1 block text-xs font-semibold text-mint-bright" style="display: none;"></span>
                    </label>
                    <label class="block text-sm">
                        <span class="font-semibold text-white/90">Which share are you interested in?</span>
                        <input name="share" type="text" placeholder="e.g. NSE India" maxlength="255"
                               class="mt-1.5 w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-white/40 focus:border-mint-bright focus:outline-none" />
                    </label>

                    <label class="flex items-start gap-3 text-xs text-white/75 sm:col-span-2">
                        <input type="checkbox" name="consent" class="mt-0.5 size-4 shrink-0 rounded border-white/30 bg-white/10" />
                        <span>
                            I agree to be contacted by StocksWitty about unlisted shares. I understand StocksWitty is a distributor, not a SEBI-registered investment adviser.
                            <span x-show="errors.consent" x-text="errors.consent" class="block font-semibold text-mint-bright" style="display: none;"></span>
                        </span>
                    </label>

                    <div class="sm:col-span-2">
                        <button type="submit" class="bg-cta inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-bold text-white">
                            Request a callback →
                        </button>
                    </div>
                </form>
            </x-sw.reveal>
        </div>
    </section>

    <div class="mx-auto max-w-[780px] px-4 py-10 sm:px-6">
        <p class="text-xs leading-relaxed text-muted-foreground">
            Disclaimer: StocksWitty is an information portal and a distributor of unlisted shares. It is
            not a SEBI-registered investment adviser and nothing here is investment advice. Unlisted
            shares are illiquid and high-risk, prices are negotiated, and an IPO may be delayed or may
            never happen. Do your own due diligence and consult your own adviser.
        </p>
    </div>
</main>
