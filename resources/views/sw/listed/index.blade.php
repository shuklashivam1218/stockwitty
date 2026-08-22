@extends('layouts.sw')

@section('title', 'Listed Stocks — Live Prices & Honest Analysis | StockWitty')
@section('description', 'Track listed Indian stocks with live-style prices, fundamentals and an honest WittyScore — Reliance, TCS, HDFC Bank, Infosys and more.')

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Listed Stocks']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Listed markets" title="Listed stocks — live prices &amp; honest analysis."
                        subtitle="The same research discipline we apply to unlisted names, pointed at the companies you can already buy on the exchange." />

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
                    <table class="w-full min-w-[46rem] text-sm">
                        <caption class="sr-only">Listed stocks with price, day change and WittyScore</caption>
                        <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                            <tr>
                                <th scope="col" class="px-5 py-3">Company</th>
                                <th scope="col" class="px-5 py-3">Sector</th>
                                <th scope="col" class="px-5 py-3 text-right">Price</th>
                                <th scope="col" class="px-5 py-3 text-right">Day change</th>
                                <th scope="col" class="px-5 py-3 text-right">Market cap</th>
                                <th scope="col" class="px-5 py-3 text-right">P/E</th>
                                <th scope="col" class="px-5 py-3 text-right">WittyScore</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach (config('sw.listed_stocks') as $s)
                                @php $up = $s['changePct'] >= 0; @endphp
                                <tr class="transition-colors hover:bg-green-50/60">
                                    <th scope="row" class="px-5 py-4 text-left">
                                        <a href="/listed/{{ $s['slug'] }}/" class="flex items-center gap-3">
                                            <span class="grid size-9 place-items-center rounded-lg bg-price-card text-[0.65rem] font-bold text-white">{{ $s['initials'] }}</span>
                                            <span class="font-bold text-foreground hover:text-primary">{{ $s['name'] }}</span>
                                        </a>
                                    </th>
                                    <td class="px-5 py-4 text-muted-foreground">{{ $s['sector'] }}</td>
                                    <td class="px-5 py-4 text-right font-bold text-foreground">₹{{ number_format($s['price'], 2) }}</td>
                                    <td class="px-5 py-4 text-right font-bold {{ $up ? 'text-primary' : 'text-destructive' }}">
                                        <span class="inline-flex items-center gap-1">
                                            <x-sw.icon :name="$up ? 'trending-up' : 'trending-down'" class="size-4" />
                                            {{ $up ? '+' : '' }}{{ $s['changePct'] }}%
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right text-muted-foreground">{{ $s['mktCap'] }}</td>
                                    <td class="px-5 py-4 text-right text-muted-foreground">{{ $s['pe'] }}</td>
                                    <td class="px-5 py-4 text-right">
                                        <span class="rounded-lg bg-green-50 px-2.5 py-1 text-xs font-bold text-primary">{{ number_format($s['wittyScore'], 1) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach (config('sw.listed_stocks') as $i => $s)
                        <x-sw.reveal :delay="$i * 0.06">
                            <a href="/listed/{{ $s['slug'] }}/" class="card-lift block h-full rounded-2xl border border-border bg-card p-5 shadow-soft">
                                <p class="text-sm font-bold text-foreground">{{ $s['name'] }}</p>
                                <p class="mt-3 text-2xl font-bold text-foreground">₹{{ number_format($s['price'], 2) }}</p>
                                <p class="mt-1 text-sm font-bold {{ $s['changePct'] >= 0 ? 'text-primary' : 'text-destructive' }}">
                                    {{ $s['changePct'] >= 0 ? '+' : '' }}{{ $s['changeAbs'] }} ({{ $s['changePct'] }}%)
                                </p>
                                <p class="mt-4 text-xs font-bold text-primary">View analysis →</p>
                            </a>
                        </x-sw.reveal>
                    @endforeach
                </div>

                <x-sw.illustrative-note />
            </div>
        </section>
    </main>
</div>
@endsection
