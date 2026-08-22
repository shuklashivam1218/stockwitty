@extends('layouts.sw')

@section('title', 'Buy 999 Pure Digital Silver Online from ₹10 | StockWitty')
@section('description', "Buy and sell 999 pure digital silver from ₹10 — live-style per-gram price, insured vault storage and an instant rupees-to-grams calculator.")

@php
$RATE = 92.4;

$features = [
    ['icon' => 'badge-check', 't' => '999 fine silver, assayed on receipt'],
    ['icon' => 'vault', 't' => 'Insured, audited vault storage'],
    ['icon' => 'lock', 't' => 'Held in your name by the custodian'],
];

$whySilver = [
    ['t' => 'Industrial demand', 'd' => 'Solar, electronics and EV wiring consume real tonnage every year.'],
    ['t' => 'Investment demand', 'd' => 'Bars and coins still act as a store of value when rates fall.'],
    ['t' => 'Honest caveat', 'd' => 'Silver draws down harder than gold. Size it as a satellite holding.'],
];

$faqs = [
    ['q' => 'How pure is the silver?', 'a' => '999 fine silver — 99.9% pure — held in bar form by the custodian and assayed on receipt.'],
    ['q' => 'Why is silver more volatile than gold?', 'a' => 'Silver has a large industrial demand component, so it reacts to manufacturing cycles as well as to investment demand. Expect bigger swings both ways.'],
    ['q' => 'Can I convert my holding into coins?', 'a' => 'Yes, once your holding crosses the minimum delivery weight. Making and delivery charges apply.'],
    ['q' => 'What are the charges?', 'a' => 'The buy and sell prices carry a spread and GST applies on purchase. No annual storage fee within the standard vault period.'],
];
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Digital Silver']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Digital silver" title="999 pure digital silver, from ₹10."
                        subtitle="The industrial metal with an investment case. Buy in rupees, own in grams, stored insured — and sell whenever you like." />

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-6 lg:grid-cols-[1fr_1.05fr]">
                    <x-sw.reveal class="bg-price-card h-full rounded-3xl p-7 text-white">
                        <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">Live-style price · 999 fine silver</p>
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
                                <dt class="text-muted-foreground"><span x-text="mode === 'Buy' ? 'Silver you get' : 'Silver you sell'"></span></dt>
                                <dd class="font-bold text-foreground"><span x-text="grams.toFixed(3)"></span> g</dd>
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
                            <span x-text="mode"></span> silver worth ₹<span x-text="amount.toLocaleString('en-IN')"></span>
                        </button>
                        <p class="mt-3 text-xs text-muted-foreground">Minimum ₹10. KYC is completed once, before your first order.</p>
                    </x-sw.reveal>
                </div>
            </div>
        </section>

        <section class="bg-green-50 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Why silver" title="Two demand engines, one metal"
                                       subtitle="Useful diversification — with more volatility than gold." />
                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    @foreach ($whySilver as $i => $s)
                        <x-sw.reveal :delay="$i * 0.07" class="card-lift h-full rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <p class="font-bold text-foreground">{{ $s['t'] }}</p>
                            <p class="mt-1.5 text-sm text-muted-foreground">{{ $s['d'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="FAQ" title="Digital silver questions, answered" />
                <div class="mt-6 divide-y divide-border rounded-2xl border border-border bg-card">
                    @foreach ($faqs as $f)
                        <div class="p-5">
                            <h3 class="font-bold text-foreground">{{ $f['q'] }}</h3>
                            <p class="mt-1.5 text-sm text-muted-foreground">{{ $f['a'] }}</p>
                        </div>
                    @endforeach
                </div>
                <x-sw.illustrative-note>
                    The per-gram price shown is illustrative demo data. Live rates are confirmed at order time.
                    Silver is market-linked and can fall sharply as well as rise.
                </x-sw.illustrative-note>
            </div>
        </section>
    </main>
</div>
@endsection
