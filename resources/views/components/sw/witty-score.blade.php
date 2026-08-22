@php
$pillars = [
    ['label' => 'Financial Health', 'value' => 8.8, 'weight' => 30],
    ['label' => 'Valuation', 'value' => 7.6, 'weight' => 20],
    ['label' => 'Growth Potential', 'value' => 9.1, 'weight' => 20],
    ['label' => 'IPO Probability', 'value' => 8.9, 'weight' => 15],
    ['label' => 'Liquidity & Safety', 'value' => 8.1, 'weight' => 15],
];
$score = 8.5;
$r = 70;
$circumference = 2 * M_PI * $r;
$filled = ($score / 10) * $circumference;
$targetOffset = $circumference - $filled;
@endphp

<section id="wittyscore" class="bg-background py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 rounded-3xl border border-border bg-green-50/70 p-6 shadow-soft sm:p-10 lg:grid-cols-2 lg:gap-14">
            <x-sw.reveal>
                <p class="eyebrow">WittyScore — our differentiator</p>
                <h2 class="mt-3 text-3xl font-bold text-foreground sm:text-4xl">
                    One honest number for every unlisted share
                </h2>
                <p class="mt-4 text-base text-muted-foreground">
                    WittyScore rates every share on our platform from 0 to 10 using five weighted
                    pillars: Financial Health (30%), Valuation (20%), Growth Potential (20%), IPO
                    Probability (15%) and Liquidity &amp; Safety (15%). A low score stays low — we publish
                    it either way.
                </p>
                <ul class="mt-6 space-y-2 text-sm font-semibold text-muted-foreground">
                    @foreach ($pillars as $p)
                        <li class="flex items-center gap-2">
                            <x-sw.icon name="gauge" class="size-4 text-mint" />
                            {{ $p['label'] }} <span class="text-primary">· {{ $p['weight'] }}%</span>
                        </li>
                    @endforeach
                </ul>
                <a href="/wittyscore/" class="mt-7 inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-bold text-primary-foreground transition-transform hover:-translate-y-0.5">
                    See how we score
                    <x-sw.icon name="arrow-right" class="size-4" />
                </a>
            </x-sw.reveal>

            <x-sw.reveal :delay="0.12">
                <div class="rounded-3xl border border-border bg-card p-6 shadow-soft sm:p-8">
                    <div class="flex flex-col items-center">
                        <div class="relative grid size-[180px] place-items-center"
                             style="--circumference: {{ $circumference }}; --target-offset: {{ $targetOffset }}">
                            <svg viewBox="0 0 160 160" class="absolute inset-0 -rotate-90">
                                <circle cx="80" cy="80" r="{{ $r }}" fill="none" stroke-width="12" class="stroke-green-100" />
                                <circle cx="80" cy="80" r="{{ $r }}" fill="none" stroke-width="12" stroke-linecap="round"
                                        class="stroke-primary sw-score-fill" stroke-dasharray="{{ $circumference }}" />
                            </svg>
                            <div class="text-center">
                                <p class="text-4xl font-bold text-primary">
                                    <x-sw.count-up :to="$score" :decimals="1" :duration="1400" />
                                </p>
                                <p class="text-xs font-bold text-muted-foreground">out of 10</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm font-bold text-foreground">NSE India Limited</p>
                    </div>

                    <div class="mt-7 space-y-4">
                        @foreach ($pillars as $p)
                            <div>
                                <div class="flex items-center justify-between text-xs font-bold">
                                    <span class="text-muted-foreground">{{ $p['label'] }}</span>
                                    <span class="text-primary tabular-nums">{{ number_format($p['value'], 1) }}</span>
                                </div>
                                <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-green-100">
                                    <div class="sw-pillar-bar h-full rounded-full bg-gradient-to-r from-primary to-mint"
                                         style="--target-width: {{ $p['value'] * 10 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-sw.reveal>
        </div>
    </div>
</section>
