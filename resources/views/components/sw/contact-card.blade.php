{{-- One contact detail card on /contact/. With an href the whole card is a
     link (external links open in a new tab); without one it is plain text. --}}
@props(['icon', 'label', 'value', 'href' => null, 'cta' => null, 'note' => null])

@php
    $external = $href && str_starts_with($href, 'http');
    $classes  = 'flex items-start gap-4 rounded-2xl border border-border bg-card p-5 shadow-soft' . ($href ? ' card-lift' : '');
@endphp

@if ($href)
<a href="{{ $href }}" @if ($external) target="_blank" rel="noopener noreferrer" @endif class="{{ $classes }}">
@else
<div class="{{ $classes }}">
@endif
    <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-muted text-primary">
        <x-sw.icon :name="$icon" class="size-5" />
    </span>
    <span>
        <span class="block text-xs font-bold tracking-widest text-mint uppercase">{{ $label }}</span>
        <span class="mt-1 block text-sm font-semibold text-foreground">{{ $value }}</span>
        @if ($note)
            <span class="block text-sm text-muted-foreground">{{ $note }}</span>
        @endif
        @if ($cta)
            <span class="mt-2 inline-flex items-center gap-1 text-sm font-bold text-primary">
                {{ $cta }} <x-sw.icon name="external-link" class="size-3.5" />
            </span>
        @endif
    </span>
@if ($href)
</a>
@else
</div>
@endif
