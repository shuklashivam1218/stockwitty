@props(['steps'])

<ol class="mt-6 space-y-4">
    @foreach ($steps as $i => $s)
        <li>
            <x-sw.reveal :delay="$i * 0.04">
                <div class="card-lift flex gap-5 rounded-2xl border border-border bg-card p-5 shadow-soft">
                    <span class="text-4xl font-bold leading-none text-mint/70 sm:text-5xl">{{ $i + 1 }}</span>
                    <span>
                        <span class="block font-bold text-foreground">{{ $s['t'] }}</span>
                        <span class="mt-1 block text-sm leading-relaxed text-muted-foreground">{{ $s['d'] }}</span>
                    </span>
                </div>
            </x-sw.reveal>
        </li>
    @endforeach
</ol>
