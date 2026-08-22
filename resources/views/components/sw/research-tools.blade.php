<section id="research-tools" class="py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.section-heading eyebrow="Research &amp; tools" title="Do the homework before the money moves"
                               subtitle="Screeners, comparisons, calculators and writing that respects your time." />
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (config('sw.tools') as $i => $t)
                <x-sw.item-card :item="$t" :delay="($i % 3) * 0.06" />
            @endforeach
        </div>
    </div>
</section>
