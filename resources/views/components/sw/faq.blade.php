<section id="faq" class="py-20 sm:py-28">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1fr_1.4fr] lg:px-8">
        <x-sw.section-heading eyebrow="FAQ — Questions, answered" title="The things everyone asks us first"
                               subtitle="Still unsure? Message the team on WhatsApp — a person replies, not a bot." />

        <x-sw.reveal :delay="0.1">
            <div class="w-full">
                @foreach (config('sw.faqs') as $f)
                    <details class="sw-faq-item mb-3 overflow-hidden rounded-2xl border border-border bg-card px-5 shadow-soft transition-colors">
                        <summary class="flex cursor-pointer items-center justify-between gap-3 py-5 text-left text-base font-bold text-foreground">
                            {{ $f['q'] }}
                            <x-sw.icon name="chevron-down" class="sw-faq-chevron size-4 shrink-0 text-muted-foreground" />
                        </summary>
                        <p class="pb-5 text-sm leading-relaxed text-muted-foreground">{{ $f['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </x-sw.reveal>
    </div>
</section>
