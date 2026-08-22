@props(['options', 'model'])

<div class="flex flex-wrap gap-2">
    @foreach ($options as $o)
        <button type="button" @click="{{ $model }} = '{{ $o }}'"
                :aria-pressed="{{ $model }} === '{{ $o }}'"
                :class="{{ $model }} === '{{ $o }}' ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-card text-muted-foreground hover:border-primary/50 hover:text-primary'"
                class="rounded-full border px-4 py-2 text-sm font-semibold transition-all">
            {{ $o }}
        </button>
    @endforeach
</div>
