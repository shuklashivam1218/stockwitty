{{-- A stock's overall WittyScore and its five pillars, from unlisted_witty_scores.
     Shown above the body of the stock's WittyScore page. --}}
@props(['company', 'score'])

@php
    use App\Models\UnlistedWittyScore;

    $overall = $score->overall();
    $labels  = [
        'UL_WS_FINANCIAL_HEALTH' => 'Financial Health',
        'UL_WS_VALUATION'        => 'Valuation',
        'UL_WS_GROWTH_POTENTIAL' => 'Growth Potential',
        'UL_WS_IPO_PROBABILITY'  => 'IPO Probability',
        'UL_WS_LIQUIDITY_SAFETY' => 'Liquidity & Safety',
    ];
    $pillars = collect($labels)
        ->filter(fn ($label, $field) => $score->$field !== null)
        ->map(fn ($label, $field) => [
            'label'  => $label,
            'value'  => (float) $score->$field,
            'weight' => (int) round(UnlistedWittyScore::WEIGHTS[$field] * 100),
        ])
        ->values();
@endphp

<section id="score-breakdown" class="scroll-mt-28 mt-10">
    <x-sw.reveal :delay="0.05">
        <div class="bg-price-card flex items-center gap-5 rounded-3xl p-6 text-white shadow-soft sm:p-8">
            <p class="text-4xl font-bold text-mint-bright sm:text-5xl">{{ number_format($overall, 1) }}<span class="text-lg text-white/70"> / 10</span></p>
            <div>
                <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">WittyScore</p>
                <p class="mt-1 text-base text-white/90">How we scored {{ $company }} across five weighted pillars.</p>
            </div>
        </div>
    </x-sw.reveal>

    <div class="mt-5 grid gap-4 sm:grid-cols-2">
        @foreach ($pillars as $i => $p)
            <x-sw.reveal :delay="$i * 0.1" class="card-lift rounded-2xl border border-border bg-card p-5 shadow-soft">
                <div class="flex items-center justify-between text-sm font-bold">
                    <span class="flex items-center gap-2 text-foreground">
                        <x-sw.icon name="gauge" class="size-4 text-mint" />
                        {{ $p['label'] }} <span class="font-semibold text-muted-foreground">· {{ $p['weight'] }}%</span>
                    </span>
                    <span class="text-primary tabular-nums">{{ number_format($p['value'], 1) }}</span>
                </div>
                <div class="mt-2 h-2 overflow-hidden rounded-full bg-green-100">
                    <div class="sw-pillar-bar h-full rounded-full bg-gradient-to-r from-primary to-mint" style="--target-width: {{ $p['value'] * 10 }}%"></div>
                </div>
            </x-sw.reveal>
        @endforeach
    </div>
    <p class="mt-4 text-sm text-muted-foreground">
        Scores come from our methodology, not hand-picked.
        <a href="/wittyscore/" class="font-bold text-primary hover:underline">WittyScore methodology →</a>
    </p>
</section>
