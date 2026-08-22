@php
$cases = [
    ['tag' => 'Tracking', 'title' => 'How an investor tracked NSE India from ₹1,700 to its IPO filing', 'body' => 'Three years of dealer quotes, two lot purchases and one long wait — including the months the price went nowhere.'],
    ['tag' => 'Diligence', 'title' => "A CA's checklist before her first unlisted purchase", 'body' => 'ISIN verified on CDSL, payment only to a company account, invoice matched to the delivery instruction.'],
    ['tag' => 'Reality check', 'title' => 'When an IPO slipped by two years: what the holder did next', 'body' => 'Illiquidity is the real risk in this market. This is how one investor sized the position for it.'],
];
@endphp

<section id="case-studies" class="py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.section-heading eyebrow="Case studies" title="Real journeys, no guaranteed returns"
                               subtitle="What actually happened — including the slow and awkward parts." />
        <div class="mt-8 grid gap-4 md:grid-cols-3">
            @foreach ($cases as $i => $c)
                <x-sw.reveal :delay="$i * 0.08">
                    <a href="/case-studies/" class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-6 shadow-soft">
                        <span class="w-fit rounded-full bg-beige px-3 py-1 text-[0.7rem] font-bold tracking-wide text-green-950 uppercase">
                            {{ $c['tag'] }}
                        </span>
                        <h3 class="mt-4 text-lg font-bold text-foreground">{{ $c['title'] }}</h3>
                        <p class="mt-2 flex-1 text-sm text-muted-foreground">{{ $c['body'] }}</p>
                        <span class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-primary">
                            Read case study <x-sw.icon name="arrow-up-right" class="size-4" />
                        </span>
                    </a>
                </x-sw.reveal>
            @endforeach
        </div>
    </div>
</section>
