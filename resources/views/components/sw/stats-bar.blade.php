@php
$stats = [
    ['value' => '250+', 'count' => 250, 'suffix' => '+', 'label' => 'Unlisted companies tracked'],
    ['value' => '10,000+', 'count' => 10000, 'suffix' => '+', 'label' => 'Investors served'],
    ['value' => 'Same-day', 'label' => 'Demat credit (CDSL / NSDL)'],
    ['value' => '0–10', 'label' => 'Honest WittyScore on every share'],
];
@endphp

<section class="bg-background py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @foreach ($stats as $i => $s)
                <x-sw.reveal :delay="$i * 0.1" class="card-lift rounded-2xl border border-border bg-green-50 p-5 text-center shadow-soft sm:p-7">
                    <p class="text-3xl font-bold text-primary sm:text-4xl">
                        @if (isset($s['count']))
                            <x-sw.count-up :to="$s['count']" :suffix="$s['suffix'] ?? ''" :duration="1500" />
                        @else
                            {{ $s['value'] }}
                        @endif
                    </p>
                    <p class="mt-2 text-xs font-semibold text-muted-foreground sm:text-sm">{{ $s['label'] }}</p>
                </x-sw.reveal>
            @endforeach
        </div>
    </div>
</section>
