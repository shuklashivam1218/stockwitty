@props(['delay' => 0])

<div data-reveal style="--reveal-delay: {{ $delay }}s" {{ $attributes->merge(['class' => 'reveal']) }}>
    {{ $slot }}
</div>
