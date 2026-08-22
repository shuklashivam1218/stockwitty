@php
$reviews = [
    ['name' => 'Rohan Sharma', 'role' => 'Software Engineer, Bengaluru', 'quote' => 'Asked about NSE India lot sizes on WhatsApp, got a straight answer with no sales push. Paid to the company account in the morning, shares were in my demat the same evening.'],
    ['name' => 'Meera Nair', 'role' => 'Chartered Accountant, Kochi', 'quote' => 'I checked the GST invoice, the bank details and the ISIN on CDSL before paying — everything matched. The pricing quoted was the pricing charged, nothing added later.'],
    ['name' => 'Arjun Patel', 'role' => 'Business Owner, Ahmedabad', 'quote' => 'They told me upfront that unlisted shares are illiquid and an IPO may take years. That honesty is why I came back for a second lot.'],
];
@endphp

<section id="reviews" class="bg-green-50/60 py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.reveal class="mx-auto max-w-2xl text-center">
            <p class="eyebrow">Reviews — investor feedback</p>
            <h2 class="mt-3 text-3xl font-bold text-foreground sm:text-4xl">
                Rated by investors who've actually dealt with us
            </h2>
            <div class="mt-5 inline-flex items-center gap-3 rounded-full border border-border bg-card px-5 py-2.5 shadow-soft">
                <span class="text-2xl font-bold text-primary">
                    <x-sw.count-up :to="4.8" :decimals="1" :duration="1200" />
                </span>
                <span class="flex gap-0.5">
                    @for ($i = 0; $i < 5; $i++)
                        <x-sw.icon name="star" class="size-4 fill-mint text-mint" />
                    @endfor
                </span>
                <span class="text-xs font-semibold text-muted-foreground">
                    average across 340+ verified deals
                </span>
            </div>
            <p class="mt-4 text-sm text-muted-foreground">
                Feedback on process, pricing and speed. We never publish return or profit claims.
            </p>
        </x-sw.reveal>

        <div class="mt-12 grid gap-5 lg:grid-cols-3">
            @foreach ($reviews as $i => $r)
                <x-sw.reveal :delay="$i * 0.12" class="card-lift rounded-2xl border border-border bg-card p-6 shadow-soft">
                    <x-sw.icon name="quote" class="size-7 text-mint" />
                    <blockquote class="mt-4 text-sm leading-relaxed text-muted-foreground">
                        &ldquo;{{ $r['quote'] }}&rdquo;
                    </blockquote>
                    <figcaption class="mt-5 flex items-center gap-3 border-t border-border pt-4">
                        <span class="grid size-10 place-items-center rounded-full bg-primary text-sm font-bold text-primary-foreground">
                            {{ collect(explode(' ', $r['name']))->map(fn($n) => $n[0])->implode('') }}
                        </span>
                        <span>
                            <span class="block text-sm font-bold text-foreground">{{ $r['name'] }}</span>
                            <span class="block text-xs font-semibold text-muted-foreground">{{ $r['role'] }}</span>
                        </span>
                    </figcaption>
                </x-sw.reveal>
            @endforeach
        </div>
    </div>
</section>
