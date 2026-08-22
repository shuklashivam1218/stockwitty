@extends('layouts.sw')

@section('title', 'Buy 24K Digital Gold Online from ₹10 | StockWitty')
@section('description', "Buy and sell 99.99% pure 24K digital gold from ₹10 — live-style per-gram price, insured vault storage and an instant amount-to-grams calculator.")

@php
$RATE = 7248.5;

$features = [
    ['icon' => 'badge-check', 't' => '99.99% pure 24K gold, assayed'],
    ['icon' => 'vault', 't' => 'Stored in an insured, audited vault'],
    ['icon' => 'lock', 't' => 'Fully insured while in custody'],
];

$howItWorks = [
    ['t' => 'Enter an amount', 'd' => 'Start at ₹10. You buy grams, not fixed coin sizes.'],
    ['t' => 'Pay securely', 'd' => 'UPI, netbanking or card. The purchase is confirmed at the live rate.'],
    ['t' => 'Gold moves to the vault', 'd' => 'Your grams sit in an insured, audited vault in your name.'],
    ['t' => 'Sell or take delivery', 'd' => 'Sell any time at the live rate, or convert to coins and bars.'],
];

$faqs = [
    ['q' => 'Is digital gold actually backed by physical gold?', 'a' => 'Yes. Every purchase is backed by 24K, 99.99% pure gold held in an insured vault by the custodian on your behalf.'],
    ['q' => 'Can I take physical delivery?', 'a' => 'Once your holding crosses the minimum delivery weight, you can request coins or bars delivered to your address. Making and delivery charges apply.'],
    ['q' => 'What charges apply?', 'a' => 'The buy and sell prices carry a spread, and GST applies on purchase. There are no annual storage fees for the standard vault period.'],
    ['q' => 'How fast can I sell?', 'a' => 'You can sell any time at the live sell price, with proceeds credited to your registered bank account, usually within one to two working days.'],
];
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Digital Gold']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Digital gold" title="24K digital gold, from ₹10."
                        subtitle="Same metal, none of the locker. Buy in rupees, own in grams, sell whenever — 99.99% pure and vault-stored." />

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-[1fr_1.05fr]">
                    <x-sw.reveal class="bg-price-card h-full rounded-3xl p-7 text-white">
                        <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">Live-style price · 24K, 99.99% pure</p>
                        <p class="mt-4 text-4xl font-bold">₹{{ number_format($RATE, 2) }}</p>
                        <p class="text-sm text-white/70">per gram (inclusive of applicable spread)</p>
                        <span class="mt-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-bold">
                            <span class="size-2 animate-pulse rounded-full bg-mint-bright"></span>
                            Price refreshes through market hours
                        </span>
                        <ul class="mt-8 space-y-3 text-sm text-white/80">
                            @foreach ($features as $f)
                                <li class="flex items-center gap-3">
                                    <x-sw.icon :name="$f['icon']" class="size-4 text-mint-bright" />
                                    {{ $f['t'] }}
                                </li>
                            @endforeach
                        </ul>
                    </x-sw.reveal>

                    <x-sw.reveal :delay="0.08" class="h-full rounded-3xl border border-border bg-card p-7 shadow-soft"
                                 x-data="{
                                     mode: 'Buy', amount: 1000, rate: {{ $RATE }},
                                     get grams() { return this.amount / this.rate; },
                                     get gst() { return this.amount * 0.03; },
                                 }">
                        <div class="flex gap-2 rounded-xl bg-green-50 p-1.5">
                            <button type="button" @click="mode = 'Buy'" :aria-pressed="mode === 'Buy'"
                                    :class="mode === 'Buy' ? 'bg-primary text-primary-foreground' : 'text-primary'"
                                    class="flex-1 rounded-lg py-2.5 text-sm font-bold transition-all">Buy</button>
                            <button type="button" @click="mode = 'Sell'" :aria-pressed="mode === 'Sell'"
                                    :class="mode === 'Sell' ? 'bg-primary text-primary-foreground' : 'text-primary'"
                                    class="flex-1 rounded-lg py-2.5 text-sm font-bold transition-all">Sell</button>
                        </div>

                        <label class="mt-6 block">
                            <span class="text-sm font-bold text-foreground">Amount (₹)</span>
                            <input type="number" min="10" step="10" x-model.number="amount"
                                   class="mt-2 w-full rounded-xl border border-border bg-background px-4 py-3 text-lg font-bold outline-none focus:border-primary" />
                        </label>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ([500, 1000, 5000, 10000] as $a)
                                <button type="button" @click="amount = {{ $a }}"
                                        class="rounded-full border border-border px-3 py-1.5 text-xs font-bold text-muted-foreground hover:border-primary hover:text-primary">
                                    ₹{{ number_format($a) }}
                                </button>
                            @endforeach
                        </div>

                        <dl class="mt-6 space-y-2.5 border-t border-border pt-5 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground"><span x-text="mode === 'Buy' ? 'Gold you get' : 'Gold you sell'"></span></dt>
                                <dd class="font-bold text-foreground"><span x-text="grams.toFixed(4)"></span> g</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">Rate per gram</dt>
                                <dd class="font-bold text-foreground">₹{{ number_format($RATE, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted-foreground">GST (3%, on purchase)</dt>
                                <dd class="font-bold text-foreground">
                                    <span x-text="mode === 'Buy' ? ('₹' + gst.toFixed(2)) : '—'"></span>
                                </dd>
                            </div>
                        </dl>

                        <button type="button" class="bg-cta mt-6 w-full rounded-xl px-5 py-3.5 text-sm font-bold text-white">
                            <span x-text="mode"></span> gold worth ₹<span x-text="amount.toLocaleString('en-IN')"></span>
                        </button>
                        <p class="mt-3 text-xs text-muted-foreground">Minimum ₹10. You'll complete KYC once before your first order.</p>
                    </x-sw.reveal>
                </div>
            </div>
        </section>

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="How it works" title="Four steps, no locker keys"
                                       subtitle="From rupees to grams and back again." />
                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($howItWorks as $i => $s)
                        <x-sw.reveal :delay="$i * 0.06" class="card-lift h-full rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <span class="grid size-10 place-items-center rounded-xl bg-price-card text-sm font-bold text-white">0{{ $i + 1 }}</span>
                            <p class="mt-4 font-bold text-foreground">{{ $s['t'] }}</p>
                            <p class="mt-1.5 text-sm text-muted-foreground">{{ $s['d'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="FAQ" title="Digital gold questions, answered" />
                <div class="mt-6 divide-y divide-border rounded-2xl border border-border bg-card">
                    @foreach ($faqs as $f)
                        <div class="p-5">
                            <h3 class="font-bold text-foreground">{{ $f['q'] }}</h3>
                            <p class="mt-1.5 text-sm text-muted-foreground">{{ $f['a'] }}</p>
                        </div>
                    @endforeach
                </div>
                <x-sw.illustrative-note>
                    The per-gram price shown is illustrative demo data. Live rates are confirmed at the time of
                    your order. Gold prices are market-linked and can fall as well as rise.
                </x-sw.illustrative-note>
            </div>
        </section>
    </main>
</div>
@endsection
