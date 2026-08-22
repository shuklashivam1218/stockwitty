@extends('layouts.sw')

@section('title', 'StockWitty — Invest Smart, Stay Witty')
@section('description', 'Research and buy unlisted & pre-IPO shares in India — live prices, DRHP tracking, honest research and same-day demat delivery. Invest Smart, Stay Witty.')

@section('content')
<div class="min-h-screen bg-background">
    <main>
        <x-sw.hero />
        <x-sw.trusted-strip />
        <x-sw.showcase />
        <x-sw.stats-bar />
        <x-sw.categories />
        <x-sw.products />
        <x-sw.sectors />
        <x-sw.witty-score />
        <x-sw.how-to-buy />
        <x-sw.about />
        <x-sw.features />
        <x-sw.split-banner />
        <x-sw.new-arrivals />
        <x-sw.compare-teaser />
        <x-sw.research-tools />
        <x-sw.blog-teaser />
        <x-sw.watch-learn />
        <x-sw.case-studies />
        <x-sw.reviews />
        <x-sw.faq />
    </main>
</div>
@endsection
