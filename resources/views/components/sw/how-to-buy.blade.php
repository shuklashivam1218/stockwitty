@php
$steps = [
    ['n' => '01', 'icon' => 'file-check-2', 'title' => 'Submit KYC', 'body' => 'Send your Client Master List (CML) from your broker, PAN, Aadhaar and a cancelled cheque. Takes about five minutes and is a one-time step.'],
    ['n' => '02', 'icon' => 'landmark', 'title' => 'Transfer Payment', 'body' => 'We share a GST invoice and our verified company current account details. Payments go only to that account — never to a personal UPI, wallet or individual name.'],
    ['n' => '03', 'icon' => 'wallet', 'title' => 'Receive Shares', 'body' => 'Shares are transferred off-market to your CDSL or NSDL demat, usually the same working day. Verify the ISIN independently on the depository before and after credit.'],
];
@endphp

<section id="how-to-buy" class="bg-green-50/60 py-20 sm:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.section-heading eyebrow="Process — How To Buy" title="Three steps, no mystery"
                               subtitle="The same process we follow for a ₹25,000 lot and a ₹25 lakh block." align="center" />

        <div class="relative mt-14 grid gap-6 lg:grid-cols-3">
            <span aria-hidden class="absolute top-14 left-[16%] hidden h-0.5 w-[68%] origin-left rounded bg-gradient-to-r from-primary via-mint to-primary lg:block opacity-50"></span>

            @foreach ($steps as $i => $s)
                <x-sw.reveal :delay="$i * 0.14" class="card-lift relative rounded-2xl border border-border bg-card p-7 text-center shadow-soft">
                    <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-primary text-lg font-bold text-primary-foreground">
                        {{ $s['n'] }}
                    </span>
                    <x-sw.icon :name="$s['icon']" class="mx-auto mt-5 size-6 text-mint" />
                    <h3 class="mt-3 text-lg font-bold text-foreground">{{ $s['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $s['body'] }}</p>
                </x-sw.reveal>
            @endforeach
        </div>
    </div>
</section>
