@props([
    'crumbs', 'chips', 'title', 'description', 'authorLine', 'dateLabel', 'readLabel',
    'heroIcon' => null, 'hero' => null, 'toc', 'takeaways', 'takeawaysTitle' => 'Key takeaways',
    'video', 'faqTabs', 'faqs', 'sources', 'related', 'leadForm',
])

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
                        <span class="grid size-9 place-items-center rounded-full bg-primary text-[0.7rem] font-bold text-primary-foreground">SW</span>
                        {{ $authorLine }}
                    </span>
                    <span>{{ $dateLabel }}</span>
                    <span class="inline-flex items-center gap-1.5">
                        <x-sw.icon name="clock" class="size-3.5" />
                        {{ $readLabel }}
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
            <div class="bg-price-card relative flex aspect-[16/7] w-full flex-col items-center justify-center gap-5 overflow-hidden rounded-3xl px-6 text-center shadow-soft">
                <div class="pointer-events-none absolute -top-24 -right-16 size-72 rounded-full bg-mint/20 blur-3xl"></div>
                @if ($heroIcon)
                    <span class="relative grid size-16 place-items-center rounded-2xl bg-white/10 text-mint-bright ring-1 ring-white/20 backdrop-blur sm:size-20">
                        <x-sw.icon :name="$heroIcon" class="size-8 sm:size-10" />
                    </span>
                @endif
                <p class="relative max-w-2xl text-lg font-bold leading-snug text-white sm:text-2xl">{{ $title }}</p>
            </div>
            @endif
        </figure>
    </x-sw.reveal>

    <x-sw.toc-layout :items="$toc">
        <article class="pb-4">
            <div class="mt-8 space-y-4 text-base leading-relaxed text-muted-foreground">
                {{ $intro }}
            </div>

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

            <x-sw.reveal>
                <figure class="mt-10" x-data="{ playing: false }">
                    <div class="bg-price-card relative grid aspect-video w-full place-items-center overflow-hidden rounded-3xl">
                        <button type="button" @click="playing = true"
                                class="grid size-16 place-items-center rounded-full bg-white/15 text-white ring-1 ring-white/30 backdrop-blur transition-transform hover:scale-105"
                                aria-label="Play video">
                            <x-sw.icon name="play" class="size-7 fill-white" />
                        </button>
                        <span class="absolute bottom-4 left-5 text-xs font-semibold text-white/60">Replace with your StockWitty YouTube video</span>
                    </div>
                    <figcaption class="mt-3 text-center text-xs font-semibold text-muted-foreground">{{ $video['caption'] }}</figcaption>
                </figure>
            </x-sw.reveal>

            {{ $slot }}

            <h2 id="sources" class="mt-14 scroll-mt-28 text-2xl font-bold text-foreground sm:text-3xl">Sources &amp; references</h2>
            <p class="mt-3 text-sm text-muted-foreground">Verify every figure against official filings.</p>
            <ul class="mt-4 grid gap-2 sm:grid-cols-2">
                @foreach ($sources as $s)
                    <li>
                        <a href="{{ $s['href'] }}" target="_blank" rel="noopener noreferrer"
                           class="flex items-center justify-between gap-3 rounded-xl border border-border bg-card px-4 py-3 text-sm font-semibold text-muted-foreground transition-colors hover:border-primary/50 hover:text-primary">
                            {{ $s['label'] }}
                            <x-sw.icon name="external-link" class="size-4 shrink-0" />
                        </a>
                    </li>
                @endforeach
            </ul>

            <h2 id="faq" class="mt-14 scroll-mt-28 text-2xl font-bold text-foreground sm:text-3xl">Frequently asked questions</h2>
            <div x-data="{ active: 'All' }">
                <div class="mt-5 flex flex-wrap gap-2">
                    <button type="button" @click="active = 'All'" :aria-pressed="active === 'All'"
                            :class="active === 'All' ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-card text-muted-foreground hover:border-primary/50 hover:text-primary'"
                            class="rounded-full border px-4 py-1.5 text-sm font-semibold transition-all">All</button>
                    @foreach ($faqTabs as $t)
                        <button type="button" @click="active = '{{ $t }}'" :aria-pressed="active === '{{ $t }}'"
                                :class="active === '{{ $t }}' ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-card text-muted-foreground hover:border-primary/50 hover:text-primary'"
                                class="rounded-full border px-4 py-1.5 text-sm font-semibold transition-all">{{ $t }}</button>
                    @endforeach
                </div>
                <div class="mt-5">
                    @foreach ($faqs as $f)
                        <details x-show="active === 'All' || active === '{{ $f['tab'] }}'"
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

            <div class="mt-12 flex flex-wrap items-center gap-3 border-y border-border py-5" x-data="{ copied: false }">
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
                    <span class="grid size-14 shrink-0 place-items-center rounded-2xl bg-primary text-base font-bold text-primary-foreground">SW</span>
                    <div>
                        <p class="text-base font-bold text-foreground">StockWitty Research</p>
                        <p class="mt-1.5 text-sm leading-relaxed text-muted-foreground">
                            We research unlisted shares the way we'd want them explained to us — the risks as clearly as the upside.
                        </p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach (['CA-reviewed', 'Unlisted-shares specialists', 'Distributor · not a SEBI adviser'] as $c)
                                <span class="rounded-full border border-border bg-card px-3 py-1 text-[0.7rem] font-bold text-muted-foreground">{{ $c }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-sw.reveal>

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

            <x-sw.illustrative-note>
                Any prices, lot sizes or return figures in this article are illustrative examples for explanation only. Confirm live quotes and charges before you transact.
            </x-sw.illustrative-note>
        </article>
    </x-sw.toc-layout>

    <section class="bg-price-card mt-16 py-14" x-data="{
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
                            I agree to be contacted by StockWitty about unlisted shares. I understand StockWitty is a distributor, not a SEBI-registered investment adviser.
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
            Disclaimer: StockWitty is an information portal and a distributor of unlisted shares. It is
            not a SEBI-registered investment adviser and nothing here is investment advice. Unlisted
            shares are illiquid and high-risk, prices are negotiated, and an IPO may be delayed or may
            never happen. Do your own due diligence and consult your own adviser.
        </p>
    </div>
</main>
