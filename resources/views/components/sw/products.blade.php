<section id="products" class="py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.section-heading eyebrow="Every product, one login" title="One platform. Every way to invest."
                               subtitle="Unlisted shares are our hero product — but your portfolio doesn't stop there." />
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (config('sw.products') as $i => $p)
                <x-sw.item-card :item="$p" :delay="($i % 4) * 0.06" />
            @endforeach
        </div>
    </div>
</section>
