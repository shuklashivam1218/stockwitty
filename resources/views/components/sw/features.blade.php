@php
$features = [
    ['icon' => 'gauge', 'title' => 'Honest WittyScore ratings', 'body' => 'Every company gets a 0–100 score built from growth, profitability, governance and IPO visibility — with the reasoning shown, including the parts that look weak.'],
    ['icon' => 'badge-check', 'title' => 'Verified company-account payments', 'body' => "Funds move only to StockWitty's registered current account against a GST invoice. No personal UPI, no intermediary wallet, ever."],
    ['icon' => 'zap', 'title' => 'Same-day demat credit', 'body' => 'Off-market transfers executed before the 2 PM cut-off usually land in your CDSL or NSDL demat the same working day, with the DIS reference shared to you.'],
    ['icon' => 'indian-rupee', 'title' => 'Transparent all-inclusive pricing', 'body' => 'The price you see already includes our margin. No brokerage surprise, no last-minute revision between quote and payment.'],
    ['icon' => 'book-open-check', 'title' => 'Deep research & DRHP tracking', 'body' => "We read the prospectus so you don't have to: revenue mix, promoter dilution, offer-for-sale size and realistic listing timelines for 38 filed companies."],
    ['icon' => 'message-circle', 'title' => 'Human support on WhatsApp', 'body' => 'A real analyst answers price, lot size and settlement questions — typically within minutes during market hours. No chatbot loops.'],
];
@endphp

<section id="features" class="bg-green-50/60 py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.section-heading eyebrow="Why StockWitty" title="Smart Investing, Simplified"
                               subtitle="Investing isn't easy. Researching it should be." align="center" />

        <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($features as $i => $f)
                <x-sw.reveal :delay="$i * 0.09" class="card-lift group relative overflow-hidden rounded-2xl border border-border bg-card p-6 pl-7 shadow-soft">
                    <span aria-hidden class="absolute inset-y-0 left-0 w-1.5 bg-primary transition-colors group-hover:bg-mint"></span>
                    <span class="grid size-12 place-items-center rounded-xl bg-muted text-primary transition-colors group-hover:bg-primary group-hover:text-primary-foreground">
                        <x-sw.icon :name="$f['icon']" class="size-6" />
                    </span>
                    <h3 class="mt-5 text-lg font-bold text-foreground">{{ $f['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $f['body'] }}</p>
                </x-sw.reveal>
            @endforeach
        </div>
    </div>
</section>
