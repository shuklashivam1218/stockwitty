@php
$trustSignals = [
    ['icon' => 'users', 'label' => '10,000+ investors'],
    ['icon' => 'shield-check', 'label' => 'Shares in your CDSL / NSDL demat'],
    ['icon' => 'file-check', 'label' => 'CA-verified financials'],
    ['icon' => 'scale', 'label' => 'SEBI-compliant distributor'],
    ['icon' => 'message-circle', 'label' => 'Human support on WhatsApp'],
];
@endphp

<section class="border-y border-border bg-green-50 py-6 sm:py-7">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.reveal>
            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4">
                @foreach ($trustSignals as $s)
                    <div class="inline-flex items-center gap-2 rounded-full border border-border bg-background/70 px-3.5 py-2 text-xs font-semibold text-muted-foreground shadow-soft backdrop-blur-sm sm:text-sm">
                        <x-sw.icon :name="$s['icon']" class="size-4 shrink-0 text-primary" />
                        <span>{{ $s['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </x-sw.reveal>
    </div>
</section>
