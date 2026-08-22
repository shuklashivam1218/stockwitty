@props(['items'])

<ul class="mt-5 grid gap-3">
    @foreach ($items as $t)
        <li class="flex gap-3 rounded-xl border border-border bg-card px-4 py-3 text-sm text-muted-foreground">
            <x-sw.icon name="check" class="mt-0.5 size-4 shrink-0 text-primary" />
            <span>{{ $t }}</span>
        </li>
    @endforeach
</ul>
