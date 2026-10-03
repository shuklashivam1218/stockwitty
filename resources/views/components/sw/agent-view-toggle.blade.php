{{-- The "Human view / AI agent" pill. Stateless: it must sit inside
     <x-sw.agent-view>, whose Alpine scope provides `agent` and setAgent().
     variant="dark" for the footer, "light" for the agent panel header. --}}
@props(['variant' => 'light'])

@php
    $dark = $variant === 'dark';

    $group    = $dark ? 'border-white/25 bg-white/5' : 'border-border/70 bg-background';
    $active   = $dark ? 'bg-white text-primary' : 'bg-primary text-primary-foreground';
    $inactive = $dark ? 'text-white/75 hover:text-white' : 'text-muted-foreground hover:text-foreground';
@endphp

<div role="group" aria-label="Page view" class="inline-flex items-center gap-0.5 rounded-full border p-0.5 {{ $group }}">
    <button type="button" @click="setAgent(false)" :aria-pressed="!agent"
            :class="!agent ? '{{ $active }}' : '{{ $inactive }}'"
            class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold transition-colors">
        <x-sw.icon name="user" class="size-3.5" />
        Human view
    </button>
    <button type="button" @click="setAgent(true)" :aria-pressed="agent"
            :class="agent ? '{{ $active }}' : '{{ $inactive }}'"
            class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold transition-colors">
        <x-sw.icon name="bot" class="size-3.5" />
        AI agent
    </button>
</div>
