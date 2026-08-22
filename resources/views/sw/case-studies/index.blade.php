@extends('layouts.sw')

@section('title', 'Case Studies — Real Unlisted Investor Journeys | StockWitty')
@section('description', 'Honest case studies from unlisted share investors — the wins, the waits and the passes. Illustrative journeys, not investment advice.')

@php
$disclaimer = config('sw.case_studies_disclaimer');
@endphp

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Case Studies']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Case studies" title="Real stories. Honest outcomes."
                        subtitle="How real investors research, buy and hold unlisted shares — the wins, the waits and the passes." />

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-5 lg:grid-cols-3">
                    @foreach (config('sw.case_studies') as $i => $c)
                        <x-sw.reveal :delay="$i * 0.08" class="card-lift flex h-full flex-col rounded-2xl border border-border bg-card p-6 shadow-soft">
                            <div class="flex items-start justify-between gap-3">
                                <span class="grid size-11 place-items-center rounded-xl bg-muted text-primary">
                                    <x-sw.icon name="file-text" class="size-5" />
                                </span>
                                <span class="rounded-full bg-beige px-3 py-1 text-[0.7rem] font-bold tracking-wide text-green-950 uppercase">{{ $c['statChip'] }}</span>
                            </div>
                            <p class="mt-4 text-[0.7rem] font-bold tracking-[0.12em] text-primary uppercase">{{ $c['tag'] }}</p>
                            <h2 class="mt-2 text-lg font-bold text-foreground">
                                <a href="/case-studies/{{ $c['slug'] }}/" class="hover:text-primary">{{ $c['title'] }}</a>
                            </h2>
                            <p class="mt-2 flex-1 text-sm text-muted-foreground">{{ $c['summary'] }}</p>
                            <div class="mt-5 flex flex-wrap items-center gap-3">
                                <a href="/case-studies/{{ $c['slug'] }}/" class="inline-flex items-center gap-1.5 text-sm font-bold text-primary hover:underline">
                                    Read case study <x-sw.icon name="arrow-right" class="size-4" />
                                </a>
                                <x-sw.download-gate :study="array_merge($c, ['disclaimer' => $disclaimer])">
                                    <button type="button" class="inline-flex items-center gap-1.5 rounded-xl border border-primary/40 px-3.5 py-2 text-xs font-bold text-primary transition-colors hover:bg-muted">
                                        <x-sw.icon name="download" class="size-3.5" /> Download PDF
                                    </button>
                                </x-sw.download-gate>
                            </div>
                        </x-sw.reveal>
                    @endforeach
                </div>

                <p class="mt-8 flex items-start gap-2 rounded-xl border border-amber-300 border-dashed bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-900">
                    <x-sw.icon name="info" class="mt-0.5 size-4 shrink-0" />
                    <span>{{ $disclaimer }}</span>
                </p>

                <p class="mt-4 text-xs text-muted-foreground">
                    Case studies describe individual experiences, are framed around process rather than
                    returns, and are not indicative of future outcomes. StockWitty is a distributor of unlisted
                    shares, not a SEBI-registered investment adviser.
                </p>
            </div>
        </section>
    </main>
</div>
@endsection
