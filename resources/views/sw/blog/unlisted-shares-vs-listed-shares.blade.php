@extends('layouts.sw')

@section('title', 'Unlisted Shares vs Listed Shares: Key Differences Explained (2026) | StockWitty')
@section('description', 'Unlisted vs listed shares compared — trading venue, liquidity, pricing, disclosure, minimum investment, risk and tax holding periods, plus which one suits which investor.')

@php
$toc = [
    ['id' => 'quick-answer', 'label' => 'Quick answer & table'],
    ['id' => 'listed', 'label' => 'Listed shares — pros & cons'],
    ['id' => 'unlisted', 'label' => 'Unlisted shares — pros & cons'],
    ['id' => 'liquidity', 'label' => 'Liquidity: the biggest difference'],
    ['id' => 'pricing', 'label' => 'Pricing & transparency'],
    ['id' => 'tax', 'label' => 'Taxation difference'],
    ['id' => 'which', 'label' => 'Which one is right for you?'],
    ['id' => 'sources', 'label' => 'Sources & references'],
    ['id' => 'faq', 'label' => 'FAQ'],
];

$faqs = [
    ['tab' => 'Basics', 'q' => "What's the main difference between listed and unlisted shares?", 'a' => 'The trading venue. Listed shares change hands on the NSE or BSE at a continuously discovered market price, with high liquidity. Unlisted shares are transferred privately at a negotiated price and delivered as an off-market transfer to your demat account.'],
    ['tab' => 'Differences', 'q' => 'Are unlisted shares more profitable than listed?', 'a' => 'Not inherently. Unlisted shares can offer pre-IPO entry into a business before public demand affects the price, which is where the upside argument comes from. They can equally stagnate for years or fall, and you may not be able to exit while that happens. Neither category comes with a return you can count on.'],
    ['tab' => 'Differences', 'q' => 'Which is riskier?', 'a' => 'Unlisted shares, on almost every measure — lower liquidity, thinner disclosure, no continuous price discovery, wider spreads, and no certainty that a listing ever happens. Listed equity carries market risk, but you can usually exit at a visible price on any trading day.'],
    ['tab' => 'Tax', 'q' => 'Is the tax different for listed and unlisted shares?', 'a' => 'Yes, most visibly in the holding period: 24 months for the long-term threshold while a share is unlisted, versus 12 months for listed equity. Rate treatment also differs, partly because no STT is paid on an off-market unlisted transfer. Confirm the current rules with a CA.'],
];
@endphp

@section('content')
<x-sw.blog-post-layout
    :crumbs="[['label' => 'Home', 'href' => '/'], ['label' => 'Blog', 'href' => '/blog/'], ['label' => 'Basics', 'href' => '/blog/'], ['label' => 'Unlisted vs Listed Shares']]"
    :chips="['Unlisted Shares', 'Comparison', '2026']"
    heroIcon="scale"
    title="Unlisted Shares vs Listed Shares: Key Differences Explained (2026)"
    description="Unlisted vs listed shares compared — trading venue, liquidity, pricing, disclosure, minimum investment, risk and tax holding periods, plus which one suits which investor."
    authorLine="StockWitty Research · CA-reviewed"
    dateLabel="August 2026"
    readLabel="7 min read"
    :toc="$toc"
    :takeaways="[
        'Listed shares trade on the NSE/BSE with live prices and high liquidity; unlisted shares trade privately.',
        'Unlisted pricing is negotiated between buyer and seller, with no ticker and wider spreads.',
        'Unlisted companies disclose far less than listed ones, so research depends on annual reports and filings.',
        'Tax holding periods differ: 24 months for unlisted vs 12 months for listed equity.',
        'Unlisted shares can offer pre-IPO entry, but with materially higher risk and no guaranteed exit.',
    ]"
    :video="['caption' => 'Watch: Unlisted vs listed shares — what actually changes', 'note' => 'Replace with your StockWitty YouTube video']"
    :faqTabs="['Basics', 'Differences', 'Tax']"
    :faqs="$faqs"
    :sources="[
        ['label' => 'SEBI — Securities and Exchange Board of India', 'href' => 'https://www.sebi.gov.in/'],
        ['label' => 'NSE India', 'href' => 'https://www.nseindia.com/'],
        ['label' => 'BSE India', 'href' => 'https://www.bseindia.com/'],
        ['label' => 'CDSL — Central Depository Services', 'href' => 'https://www.cdslindia.com/'],
        ['label' => 'NSDL — National Securities Depository', 'href' => 'https://nsdl.co.in/'],
        ['label' => 'Income Tax Department, India', 'href' => 'https://www.incometax.gov.in/'],
    ]"
    :related="[
        ['title' => 'What Are Unlisted Shares?', 'href' => '/blog/what-are-unlisted-shares/', 'category' => 'Basics', 'read' => '7 min read'],
        ['title' => 'How to Buy Unlisted Shares in India', 'href' => '/blog/how-to-buy-unlisted-shares/', 'category' => 'Buying Guide', 'read' => '10 min read'],
        ['title' => 'Tax on Unlisted Shares in India', 'href' => '/blog/tax-on-unlisted-shares/', 'category' => 'Tax', 'read' => '8 min read'],
    ]"
    :leadForm="['heading' => 'Deciding between listed and unlisted? Ask an expert.', 'subtext' => 'A StockWitty specialist can walk you through how an unlisted position would sit alongside your listed portfolio — including the parts we\'d talk you out of. No obligation to transact.']"
>
    <x-slot:intro>
        <p>
            Listed and unlisted shares are the same legal animal — equity in a company. Everything
            that feels different comes from one structural fact: one has an exchange standing between
            buyer and seller, and the other does not.
        </p>
        <p>
            That single difference cascades into liquidity, pricing, disclosure, minimum ticket size,
            risk and even how long you must hold before the tax treatment improves.
        </p>
        <p>
            Here is the comparison side by side, followed by an honest read on which category suits
            which kind of investor. Figures used are illustrative — verify against official filings.
        </p>
    </x-slot:intro>

    <x-sw.article-h2 id="quick-answer">Quick answer</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Listed shares are exchange-traded, continuously priced and easy to exit. Unlisted shares are
            privately transferred, negotiated in price and hard to exit — in exchange for the chance to
            own a business before it lists.
        </p>
    </x-sw.prose>
    <x-sw.comparison-table
        :head="['Factor', 'Listed shares', 'Unlisted shares']"
        :rows="[
            ['Trading venue', 'NSE / BSE order book', 'Private off-market transfer via CDSL / NSDL'],
            ['Liquidity', 'High — usually exit same day', 'Low — depends on buyer interest in that name'],
            ['Pricing', 'Live, continuously discovered', 'Negotiated; quotes differ between dealers'],
            ['Regulation & disclosure', 'SEBI listing obligations, quarterly results, continuous disclosures', 'Companies Act filings; annual report, far less frequent'],
            ['Minimum investment', 'One share', 'Minimum lot set by the seller — often ₹50,000–₹1,50,000 (illustrative)'],
            ['Risk', 'Market risk, but visible price and exit', 'Market + liquidity + valuation + no-IPO risk'],
            ['Tax holding period (long-term)', '12 months', '24 months while unlisted'],
        ]"
    />

    <x-sw.article-h2 id="listed">Listed shares — pros &amp; cons</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Pro: you can buy or sell almost any quantity on any trading day at a visible price.',
        'Pro: quarterly results, disclosures and analyst coverage make research far easier.',
        'Pro: no minimum ticket — you can start with a single share and scale gradually.',
        'Con: the price already reflects public expectations, so obvious stories are rarely cheap.',
        'Con: daily volatility tempts a lot of investors into selling good businesses badly.',
    ]" />

    <x-sw.article-h2 id="unlisted">Unlisted shares — pros &amp; cons</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Pro: potential entry before listing demand affects the price.',
        'Pro: access to businesses a listed-only portfolio simply cannot own yet.',
        'Pro: no screen to react to, which suits investors who genuinely think in years.',
        'Con: you may not be able to sell when you want, at any sensible price.',
        'Con: thinner disclosure means more of your view rests on fewer facts.',
        'Con: no guarantee an IPO happens — timelines slip by years and sometimes disappear.',
    ]" />

    <x-sw.article-h2 id="liquidity">Liquidity: the biggest difference</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Everything else on the table is a detail compared with this. On the exchange, a buyer exists
            for a liquid stock at all times during market hours. In the unlisted market, a buyer for
            your specific company at your specific size may not exist this week.
        </p>
        <p>
            Practically, that means an unlisted position should be money you can leave alone. If your
            plan requires selling by a particular date, the asset is working against you before the
            business has done anything wrong.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="pricing">Pricing &amp; transparency</x-sw.article-h2>
    <x-sw.prose>
        <p>
            A listed price is the outcome of thousands of competing orders. An unlisted quote is one
            party's view, anchored to the last few transactions they have seen and the valuation of the
            most recent primary round. Two dealers can be a few percentage points apart on the same day,
            and the gap widens on thin names.
        </p>
        <p>
            The defence is simple: compare at least two quotes, and ask which recent transaction a quote
            references. A counterparty who answers that plainly is usually the one worth dealing with.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="tax">Taxation difference</x-sw.article-h2>
    <x-sw.prose>
        <p>
            The headline gap is the holding period — 24 months for a long-term gain while a share is
            unlisted, versus 12 months for listed equity. Rate treatment also differs, partly because no
            STT is paid on an off-market unlisted transfer, and the rules change with each Finance Act.
        </p>
        <p>
            Our
            <a href="/blog/tax-on-unlisted-shares/" class="font-semibold text-primary underline underline-offset-4">
                complete guide to tax on unlisted shares
            </a>
            covers the detail, including what changes the day the company lists. Verify your own position
            with a CA.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="which">Which one is right for you?</x-sw.article-h2>
    <x-sw.prose>
        <p>
            For almost every investor the answer is listed first: it is liquid, well-disclosed and can be
            built with small amounts. Unlisted shares make sense as a satellite allocation once that core
            exists — a slice you can size, forget and hold through years of nothing happening.
        </p>
        <p>
            If you would be checking a non-existent price weekly, or if the money has a deadline attached
            to it, the honest answer is that unlisted shares are not the right fit. That is not a
            judgement on the asset; it is a judgement on the match.
        </p>
    </x-sw.prose>
</x-sw.blog-post-layout>
@endsection
