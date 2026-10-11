{{-- Shown at /unlisted-shares/{slug}/about|thesis|wittyscore/ until that page is
     published in the CMS. Sent with X-Robots-Tag: noindex and left out of the sitemap. --}}
@extends('layouts.sw')

@section('title', $title . ' | StocksWitty')
@section('description', $title . ' — our research desk is writing this page. Meanwhile see the live price and overview of ' . $stock->UL_STOCKS_COMPNAME . '.')

@section('content')
<div class="pt-16">
    <x-sw.breadcrumb :items="$crumbs" />
</div>

<main class="mx-auto w-full max-w-3xl px-4 py-16 text-center sm:px-6 sm:py-24">
    <x-sw.reveal>
        <span class="mx-auto grid size-16 place-items-center rounded-2xl bg-green-50 text-primary">
            <x-sw.icon :name="\App\Models\StockArticle::TYPES[$type]['icon']" class="size-8" />
        </span>
        <h1 class="mt-6 text-3xl font-bold text-foreground sm:text-4xl">{{ $title }}</h1>
        <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-muted-foreground">
            Our research desk is still writing this page. Until it's ready, the company page has
            {{ $stock->UL_STOCKS_COMPNAME }}'s live price, fundamentals and financials.
        </p>

        <div class="mt-8 flex flex-wrap justify-center gap-3">
            @foreach ($siblings as $i => $card)
                <a href="{{ $card['href'] }}"
                   class="{{ $i === 0 ? 'bg-primary text-primary-foreground' : 'border border-border bg-card text-foreground' }} inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-bold transition-transform hover:-translate-y-0.5">
                    {{ $i === 0 ? 'See ' . $stock->UL_STOCKS_COMPNAME . ' on StocksWitty' : $card['category'] . ': ' . $card['title'] }}
                    <x-sw.icon name="arrow-right" class="size-4" />
                </a>
            @endforeach
        </div>
    </x-sw.reveal>
</main>
@endsection
