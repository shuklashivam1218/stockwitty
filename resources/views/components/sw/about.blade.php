@php
$points = [
    'One published price — no hidden dealer markup',
    'Payments only to a verified company bank account',
    'Research notes written in plain English',
];
$aboutStats = [
    ['k' => 'Companies covered', 'v' => '120+'],
    ['k' => 'Avg. delivery', 'v' => 'Same day'],
    ['k' => 'DRHP tracked', 'v' => '38'],
];
@endphp

<section id="about" class="bg-green-50/60 py-20 sm:py-28">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div>
            <x-sw.reveal>
                <p class="eyebrow">About — About StockWitty</p>
                <h2 class="mt-3 text-3xl font-bold sm:text-4xl">
                    Unlisted investing, minus the mystique
                </h2>
            </x-sw.reveal>
            <x-sw.reveal :delay="0.1">
                <div class="mt-5 space-y-4 text-base text-muted-foreground">
                    <p>
                        StockWitty is a distributor of unlisted and pre-IPO shares built for Indian retail
                        investors who are tired of bland financial chatter. Most of this market still runs on
                        WhatsApp forwards, vague quotes and screenshots. We publish an actual price, explain
                        where it came from, and let you decide.
                    </p>
                    <p>
                        On every company we cover — NSE India, Tata Capital, OYO, Swiggy, PhonePe and dozens
                        more — you get lot size, minimum ticket, valuation multiples, a financial history and
                        DRHP status in one place. Buying is just as boring on purpose: KYC, payment to a
                        verified company account, and shares credited to your own demat.
                    </p>
                    <p>
                        We are an information portal and a distributor, not a SEBI-registered investment
                        adviser. So we will never tell you what to buy. We will tell you what a company earns,
                        what the shares cost, how illiquid they are, and what could go wrong.
                    </p>
                </div>
            </x-sw.reveal>
            <x-sw.reveal :delay="0.18">
                <ul class="mt-7 space-y-3">
                    @foreach ($points as $p)
                        <li class="flex items-start gap-3 text-sm font-semibold text-foreground">
                            <x-sw.icon name="check-circle-2" class="mt-0.5 size-5 shrink-0 text-mint" />
                            {{ $p }}
                        </li>
                    @endforeach
                </ul>
            </x-sw.reveal>
        </div>

        <x-sw.reveal :delay="0.15">
            <div class="relative">
                <div aria-hidden class="absolute -inset-6 -z-10 rounded-[2.5rem] bg-mint/15 blur-2xl"></div>
                <div class="card-lift overflow-hidden rounded-3xl border border-border bg-card p-4 shadow-soft">
                    <img src="{{ asset('images/sw/about-graphic.jpg') }}"
                         alt="Illustration of rising unlisted share valuations and coin stacks"
                         loading="lazy" width="1024" height="1024" class="w-full rounded-2xl" />
                    <div class="grid grid-cols-3 gap-3 p-3 pt-5">
                        @foreach ($aboutStats as $s)
                            <div class="rounded-xl bg-muted p-3 text-center">
                                <p class="text-lg font-bold text-primary">{{ $s['v'] }}</p>
                                <p class="mt-1 text-[0.7rem] font-semibold text-muted-foreground">{{ $s['k'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-sw.reveal>
    </div>
</section>
