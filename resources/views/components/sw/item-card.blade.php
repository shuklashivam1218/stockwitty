@props(['item', 'delay' => 0])

<x-sw.reveal :delay="$delay">
    <a href="{{ $item['href'] }}" class="card-lift group flex h-full flex-col rounded-2xl border border-border border-l-4 border-l-primary bg-card p-6 shadow-soft hover:bg-green-50">
        <span class="grid size-11 place-items-center rounded-xl bg-muted text-primary transition-colors group-hover:bg-mint/20">
            <x-sw.icon :name="$item['icon']" class="size-5" />
        </span>
        <h3 class="mt-4 text-base font-bold text-foreground">{{ $item['title'] }}</h3>
        <p class="mt-2 flex-1 text-sm text-muted-foreground">{{ $item['desc'] }}</p>
        <span class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-primary">
            Explore
            <x-sw.icon name="arrow-right" class="size-4 transition-transform group-hover:translate-x-1" />
        </span>
    </a>
</x-sw.reveal>
