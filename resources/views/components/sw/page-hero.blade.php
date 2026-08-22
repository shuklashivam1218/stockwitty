@props(['eyebrow', 'title', 'subtitle' => null])

<section class="relative overflow-hidden border-b border-border">
    <div class="bg-mesh pointer-events-none absolute inset-0 opacity-50"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <x-sw.reveal>
            <p class="eyebrow">{!! $eyebrow !!}</p>
            <h1 class="mt-3 max-w-3xl text-3xl font-bold text-foreground sm:text-5xl">{!! $title !!}</h1>
            @if ($subtitle)
                <p class="mt-4 max-w-2xl text-base text-muted-foreground sm:text-lg">{!! $subtitle !!}</p>
            @endif
        </x-sw.reveal>
        @isset($slot)
            @if (trim($slot))
                <x-sw.reveal :delay="0.1">
                    {{ $slot }}
                </x-sw.reveal>
            @endif
        @endisset
    </div>
</section>
