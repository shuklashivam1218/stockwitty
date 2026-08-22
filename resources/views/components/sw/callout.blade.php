@props(['title'])

<aside class="mt-6 flex gap-4 rounded-2xl border border-mint/50 bg-green-50 p-5">
    <x-sw.icon name="info" class="mt-0.5 size-5 shrink-0 text-primary" />
    <div>
        <p class="text-sm font-bold text-foreground">{{ $title }}</p>
        <div class="mt-1.5 text-sm leading-relaxed text-muted-foreground">{{ $slot }}</div>
    </div>
</aside>
