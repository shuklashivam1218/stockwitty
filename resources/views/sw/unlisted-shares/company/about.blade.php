@extends('layouts.sw')

@section('title', $stock->UL_STOCKS_COMPNAME . ': Company Profile | StockWitty')
@section('description', 'A company profile of ' . $stock->UL_STOCKS_COMPNAME . ' — business, verticals, revenue, history and FAQs.')

@php
$toc = array_values(array_filter([
    $overview ? ['id' => 'overview', 'label' => 'Overview of the company'] : null,
    count($verticals) ? ['id' => 'verticals', 'label' => 'Business verticals'] : null,
    $operations ? ['id' => 'operations', 'label' => 'What it does & how it operates'] : null,
    count($revenue) ? ['id' => 'revenue', 'label' => 'Revenue segments'] : null,
    $geography ? ['id' => 'geography', 'label' => 'Geographical presence'] : null,
    count($history) ? ['id' => 'history', 'label' => 'History & evolution'] : null,
    $industryPos ? ['id' => 'industry', 'label' => 'Industry position'] : null,
    $shareholding ? ['id' => 'shareholding', 'label' => 'Shareholding framework'] : null,
    $investorInterest ? ['id' => 'interest', 'label' => 'Why it draws investor interest'] : null,
    (count($strengths) || count($weaknesses) || count($opportunities) || count($threats)) ? ['id' => 'swot', 'label' => 'SWOT analysis'] : null,
    $marketLandscape ? ['id' => 'landscape', 'label' => 'Market landscape & reach'] : null,
    count($products) ? ['id' => 'products', 'label' => 'Products & services'] : null,
    $competitive ? ['id' => 'competitive', 'label' => 'Competitive strength'] : null,
    count($sources) ? ['id' => 'sources', 'label' => 'Sources & references'] : null,
    $aboutFaqs->isNotEmpty() ? ['id' => 'faq', 'label' => 'FAQ'] : null,
]));

$faqTabs = $aboutFaqs->keys()->reject(fn ($t) => $t === 'General')->values()->all();
$faqs = $aboutFaqs->flatMap(fn ($group, $tab) => $group->map(fn ($f) => ['tab' => $tab, 'q' => $f->UL_FAQ_QUESTION, 'a' => $f->UL_FAQ_ANSWER]))->values()->all();
@endphp

@section('content')
<x-sw.blog-post-layout
    :crumbs="[['label' => 'Home', 'href' => '/'], ['label' => 'Unlisted Shares', 'href' => '/unlisted-shares/'], ['label' => $stock->UL_STOCKS_COMPNAME, 'href' => '/unlisted-shares/' . $stock->UL_STOCKS_SLUG . '/'], ['label' => 'About']]"
    :chips="array_filter([$stock->UL_STOCKS_COMPNAME, 'Company Profile', 'Unlisted'])"
    heroIcon="landmark"
    :title="$stock->UL_STOCKS_COMPNAME . ': Company Profile'"
    :description="'A profile of ' . $stock->UL_STOCKS_COMPNAME . ' — business, verticals, revenue and history.'"
    authorLine="SW · StockWitty Research"
    dateLabel="Updated August 2026"
    readLabel="8 min read"
    :toc="$toc"
    :takeaways="array_values(array_filter(array_slice($strengths, 0, 5)))"
    :video="['caption' => $stock->UL_STOCKS_COMPNAME . ' explained — StockWitty', 'note' => 'Replace with your StockWitty YouTube video']"
    :faqTabs="$faqTabs"
    :faqs="$faqs"
    :sources="count($sources) ? array_map(fn ($s) => ['label' => $s['label'], 'href' => $s['href']], $sources) : [['label' => $stock->UL_STOCKS_COMPNAME . ' — official website', 'href' => $stock->UL_STOCKS_WEBSITE ?: '#']]"
    :related="[
        ['title' => $stock->UL_STOCKS_COMPNAME . ' — live price & how to buy', 'href' => '/unlisted-shares/' . $stock->UL_STOCKS_SLUG . '/', 'category' => 'Price', 'read' => 'Live data'],
        ['title' => $stock->UL_STOCKS_COMPNAME . ' — our thesis & WittyScore', 'href' => '/unlisted-shares/' . $stock->UL_STOCKS_SLUG . '/thesis/', 'category' => 'Analysis', 'read' => 'Read more'],
        ['title' => 'What Are Unlisted Shares?', 'href' => '/blog/what-are-unlisted-shares/', 'category' => 'Basics', 'read' => '7 min read'],
    ]"
    :leadForm="['heading' => 'Interested in ' . $stock->UL_STOCKS_COMPNAME . ' unlisted shares?', 'subtext' => 'Leave your details and a StockWitty specialist will call you back to explain current availability, pricing and the end-to-end process — with no obligation to buy.']"
>
    <x-slot:intro>
        @if ($overview)
            <p>{{ $overview }}</p>
        @else
            <p>A structured profile for {{ $stock->UL_STOCKS_COMPNAME }} — business, verticals, revenue and history are added by our research desk as they become available.</p>
        @endif
    </x-slot:intro>

    @if ($overview)
        <x-sw.article-h2 id="overview">Overview of the company</x-sw.article-h2>
        <x-sw.prose><p>{{ $overview }}</p></x-sw.prose>
    @endif

    @if (count($verticals))
        <x-sw.article-h2 id="verticals">Business verticals</x-sw.article-h2>
        <x-sw.checklist-card :items="array_map(fn ($v) => $v['text'] ? $v['title'] . ' — ' . $v['text'] : $v['title'], $verticals)" />
    @endif

    @if ($operations)
        <x-sw.article-h2 id="operations">What it does &amp; how it operates</x-sw.article-h2>
        <x-sw.prose><p>{{ $operations }}</p></x-sw.prose>
    @endif

    @if (count($revenue))
        <x-sw.article-h2 id="revenue">Revenue segments</x-sw.article-h2>
        <x-sw.checklist-card :items="array_map(fn ($v) => $v['text'] ? $v['title'] . ' — ' . $v['text'] : $v['title'], $revenue)" />
    @endif

    @if ($geography)
        <x-sw.article-h2 id="geography">Geographical presence</x-sw.article-h2>
        <x-sw.prose><p>{{ $geography }}</p></x-sw.prose>
    @endif

    @if (count($history))
        <x-sw.article-h2 id="history">History &amp; evolution</x-sw.article-h2>
        <ol class="mt-6 space-y-3 border-l-2 border-mint/40 pl-5">
            @foreach ($history as $i => $t)
                <li>
                    <x-sw.reveal :delay="$i * 0.04">
                        <div class="relative rounded-xl border border-border bg-card px-4 py-3 text-sm shadow-soft">
                            <span class="absolute top-1/2 -left-[27px] size-3 -translate-y-1/2 rounded-full border-2 border-background bg-mint"></span>
                            <span class="font-bold text-foreground">{{ $t['year'] }}</span>
                            <span class="ml-2 text-muted-foreground">{{ $t['text'] }}</span>
                        </div>
                    </x-sw.reveal>
                </li>
            @endforeach
        </ol>
    @endif

    @if ($industryPos)
        <x-sw.article-h2 id="industry">Industry position</x-sw.article-h2>
        <x-sw.prose><p>{{ $industryPos }}</p></x-sw.prose>
    @endif

    @if ($shareholding)
        <x-sw.article-h2 id="shareholding">Shareholding framework</x-sw.article-h2>
        <x-sw.prose><p>{{ $shareholding }}</p></x-sw.prose>
    @endif

    @if ($investorInterest)
        <x-sw.article-h2 id="interest">Why {{ $stock->UL_STOCKS_COMPNAME }} draws investor interest</x-sw.article-h2>
        <x-sw.prose><p>{{ $investorInterest }}</p></x-sw.prose>
        <x-sw.prose>
            <p>For live price, valuation and financials, see the
                <a href="/unlisted-shares/{{ $stock->UL_STOCKS_SLUG }}/" class="inline-flex items-center gap-1 font-semibold text-primary underline-offset-4 hover:underline">
                    {{ $stock->UL_STOCKS_COMPNAME }} price page <x-sw.icon name="arrow-right" class="size-4" />
                </a>
            </p>
        </x-sw.prose>
    @endif

    @if (count($strengths) || count($weaknesses) || count($opportunities) || count($threats))
        <x-sw.article-h2 id="swot">SWOT analysis</x-sw.article-h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            @foreach ([['label' => 'Strengths', 'points' => $strengths], ['label' => 'Weaknesses', 'points' => $weaknesses], ['label' => 'Opportunities', 'points' => $opportunities], ['label' => 'Threats', 'points' => $threats]] as $i => $s)
                @if (count($s['points']))
                    <x-sw.reveal :delay="$i * 0.05">
                        <div class="card-lift h-full rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <p class="text-xs font-bold tracking-widest text-primary uppercase">{{ $s['label'] }}</p>
                            <ul class="mt-3 space-y-2 text-sm leading-relaxed text-muted-foreground">
                                @foreach ($s['points'] as $p)
                                    <li class="flex gap-2">
                                        <span class="mt-2 size-1.5 shrink-0 rounded-full bg-mint"></span>
                                        <span>{{ $p }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </x-sw.reveal>
                @endif
            @endforeach
        </div>
    @endif

    @if ($marketLandscape)
        <x-sw.article-h2 id="landscape">Market landscape &amp; reach</x-sw.article-h2>
        <x-sw.prose><p>{{ $marketLandscape }}</p></x-sw.prose>
    @endif

    @if (count($products))
        <x-sw.article-h2 id="products">Products &amp; services</x-sw.article-h2>
        <x-sw.checklist-card :items="array_map(fn ($v) => $v['text'] ? $v['title'] . ' — ' . $v['text'] : $v['title'], $products)" />
    @endif

    @if ($competitive)
        <x-sw.article-h2 id="competitive">Competitive strength</x-sw.article-h2>
        <x-sw.prose>
            <p>{{ $competitive }}</p>
            <p class="rounded-2xl border border-border bg-green-50 px-4 py-3 text-sm">
                This page is company information, not investment advice. Business details, figures and
                shareholding change over time — verify current data from official filings before making any
                decision.
            </p>
        </x-sw.prose>
    @endif
</x-sw.blog-post-layout>
@endsection
