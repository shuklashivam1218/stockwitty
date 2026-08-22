@php
$panels = [
    ['id' => 'drhp', 'chip' => 'DRHP Filed', 'title' => 'Track DRHP-filed companies before they IPO', 'body' => "38 companies have filed with SEBI. We log filing dates, offer size, promoter dilution and expected listing windows — so you know what's actually close to an IPO and what isn't.", 'cta' => 'Open DRHP tracker', 'img' => 'drhp.jpg'],
    ['id' => 'media', 'chip' => 'Media Coverage', 'title' => 'Featured research & market takes', 'body' => 'Weekly notes on unlisted price moves, valuation resets and what the tape is telling us — plus our takes quoted across Indian business media. Sharp, short, no fluff.', 'cta' => 'Read the blog', 'img' => 'media.jpg'],
];
@endphp

<section class="py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-5 lg:grid-cols-2">
            @foreach ($panels as $i => $p)
                <x-sw.reveal :delay="$i * 0.12">
                    <article id="{{ $p['id'] }}" class="card-lift group relative isolate h-[400px] overflow-hidden rounded-3xl border border-green-900/40 sm:h-[440px]">
                        <img src="{{ asset('images/sw/' . $p['img']) }}" alt="" aria-hidden loading="lazy" width="1024" height="768"
                             class="ken-burns absolute inset-0 -z-10 size-full object-cover" />
                        <div aria-hidden class="bg-price-card absolute inset-0 -z-10 opacity-80 mix-blend-multiply"></div>
                        <div aria-hidden class="absolute inset-0 -z-10 bg-gradient-to-t from-green-990/95 via-green-950/60 to-transparent"></div>
                        <div class="flex h-full flex-col justify-end p-7 text-white sm:p-9">
                            <span class="w-fit rounded-full border border-mint/40 bg-white/10 px-3 py-1 text-[0.7rem] font-bold tracking-widest text-mint-bright uppercase backdrop-blur-sm">
                                {{ $p['chip'] }}
                            </span>
                            <h3 class="mt-4 max-w-md text-2xl font-bold sm:text-3xl">{{ $p['title'] }}</h3>
                            <p class="mt-3 max-w-md text-sm text-white/75">{{ $p['body'] }}</p>
                            <span class="mt-6 inline-flex w-fit items-center gap-2 text-sm font-bold text-mint-bright">
                                {{ $p['cta'] }}
                                <x-sw.icon name="arrow-up-right" class="size-4 transition-transform group-hover:translate-x-1 group-hover:-translate-y-1" />
                            </span>
                        </div>
                    </article>
                </x-sw.reveal>
            @endforeach
        </div>
    </div>
</section>
