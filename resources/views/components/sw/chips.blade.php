@props(['options', 'model'])

{{-- Options can be admin-entered (blog categories), so they go into the Alpine
     expressions through @js() — a quoted '{{ }}' would let a ' break out. --}}
<div class="flex flex-wrap gap-2">
    @foreach ($options as $o)
        <button type="button" @click="{{ $model }} = @js($o)"
                :aria-pressed="{{ $model }} === @js($o)"
                :class="{{ $model }} === @js($o) ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-card text-muted-foreground hover:border-primary/50 hover:text-primary'"
                class="rounded-full border px-4 py-2 text-sm font-semibold transition-all">
            {{ $o }}
        </button>
    @endforeach
</div>
