@extends('layouts.sw')

@section('title', 'Compare Unlisted Shares Side by Side | StockWitty')
@section('description', 'Pick any two unlisted or pre-IPO companies and compare price, WittyScore, sector, business model, financial snapshot and IPO status side by side.')

@php
$popular = [
    ['a' => 'NSE India', 'b' => 'Nayara Energy', 'href' => '/compare/nse-india-vs-nayara-energy/'],
    ['a' => 'NSE India', 'b' => 'BSE', 'href' => '/compare/nse-india-vs-bse/'],
    ['a' => 'PhonePe', 'b' => 'Razorpay', 'href' => '/compare/phonepe-vs-razorpay/'],
    ['a' => 'Swiggy', 'b' => 'Zepto', 'href' => '/compare/swiggy-vs-zepto/'],
];
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Compare']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Compare" title="Compare any two unlisted shares."
                        subtitle="Same metrics, same lens, no favourites. Pick two names and see where they actually differ." />

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.reveal x-data="{ a: 'nse-india', b: 'nsdl' }">
                    <div class="rounded-3xl border border-border bg-card p-6 shadow-soft sm:p-8">
                        <div class="grid items-end gap-4 lg:grid-cols-[1fr_auto_1fr_auto]">
                            <label class="block">
                                <span class="text-sm font-bold text-foreground">First company</span>
                                <select x-model="a" class="mt-2 w-full rounded-xl border border-border bg-background px-3 py-3 text-sm font-semibold outline-none focus:border-primary">
                                    @foreach (config('sw.unlisted_companies') as $c)
                                        <option value="{{ $c['slug'] }}">{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <span class="grid size-11 place-items-center justify-self-center rounded-xl bg-muted text-primary">
                                <x-sw.icon name="git-compare-arrows" class="size-5" />
                            </span>
                            <label class="block">
                                <span class="text-sm font-bold text-foreground">Second company</span>
                                <select x-model="b" class="mt-2 w-full rounded-xl border border-border bg-background px-3 py-3 text-sm font-semibold outline-none focus:border-primary">
                                    @foreach (config('sw.unlisted_companies') as $c)
                                        <option value="{{ $c['slug'] }}">{{ $c['name'] }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <a :href="'/compare/' + a + '-vs-' + b + '/'" class="bg-cta inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3.5 text-sm font-bold text-white">
                                Compare <x-sw.icon name="arrow-right" class="size-4" />
                            </a>
                        </div>
                    </div>
                </x-sw.reveal>

                <h2 class="mt-12 text-xl font-bold text-foreground">Popular comparisons</h2>
                <div class="mt-4 flex flex-wrap gap-3">
                    @foreach ($popular as $p)
                        <a href="{{ $p['href'] }}" class="card-lift rounded-full border border-border bg-card px-5 py-3 text-sm font-bold text-foreground shadow-soft">
                            {{ $p['a'] }} <span class="text-muted-foreground">vs</span> {{ $p['b'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
