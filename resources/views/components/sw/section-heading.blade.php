@props(['eyebrow', 'title', 'subtitle' => null, 'align' => 'left'])

<x-sw.reveal :class="$align === 'center' ? 'mx-auto max-w-2xl text-center' : 'max-w-2xl'">
    <p class="eyebrow">{!! $eyebrow !!}</p>
    <h2 class="mt-3 text-3xl font-bold text-foreground sm:text-4xl">{!! $title !!}</h2>
    @if ($subtitle)
        <p class="mt-3 text-base text-muted-foreground">{!! $subtitle !!}</p>
    @endif
</x-sw.reveal>
