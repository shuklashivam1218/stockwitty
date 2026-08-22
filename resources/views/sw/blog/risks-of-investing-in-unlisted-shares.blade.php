@extends('layouts.sw')

@section('title', 'Risks of Investing in Unlisted Shares (The Honest View, 2026) | StockWitty')
@section('description', 'The real risks of unlisted shares in India — illiquidity, valuation risk, no guaranteed IPO, thin disclosure, wide spreads and lock-in — plus practical ways to reduce each one.')

@php
$toc = [
    ['id' => 'why-higher', 'label' => 'Why unlisted shares carry higher risk'],
    ['id' => 'main-risks', 'label' => 'The main risks explained'],
    ['id' => 'avoid', 'label' => 'Who should avoid unlisted shares'],
    ['id' => 'reduce', 'label' => 'How to reduce these risks'],
    ['id' => 'sources', 'label' => 'Sources & references'],
    ['id' => 'faq', 'label' => 'FAQ'],
];

$faqs = [
    ['tab' => 'Risk', 'q' => 'What is the biggest risk of unlisted shares?', 'a' => "Illiquidity. Everything else is survivable if you can wait, and almost nothing is if you can't. There may be no buyer for your specific company at your size on the day you decide to sell, which means the exit price and the exit date are both outside your control."],
    ['tab' => 'Basics', 'q' => 'Can I lose all my money in unlisted shares?', 'a' => 'Yes. Equity in any company can go to zero, and unlisted companies are typically earlier, less diversified and less scrutinised than listed ones. Invest only an amount you could write off entirely without it changing your financial plans.'],
    ['tab' => 'Risk', 'q' => 'What if the company never IPOs?', 'a' => "Then you continue to hold an unlisted share, and your exit depends on finding a private buyer, a strategic acquisition, or a company buyback. Some well-known names have been 'about to list' for years. Never buy assuming a listing date."],
    ['tab' => 'Process', 'q' => 'How can I reduce unlisted-share risk?', 'a' => 'Size the position so illiquidity can never force your hand, spread exposure across more than one name, verify every transaction (verified company account, ISIN check in your own depository statement), read the annual report rather than the rumour, and plan a holding period in years.'],
];
@endphp

@section('content')
<x-sw.blog-post-layout
    :crumbs="[['label' => 'Home', 'href' => '/'], ['label' => 'Blog', 'href' => '/blog/'], ['label' => 'Analysis', 'href' => '/blog/'], ['label' => 'Risks of Investing in Unlisted Shares']]"
    :chips="['Unlisted Shares', 'Risk', '2026']"
    heroIcon="alert-triangle"
    title="Risks of Investing in Unlisted Shares (The Honest View, 2026)"
    description="The real risks of unlisted shares in India — illiquidity, valuation risk, no guaranteed IPO, thin disclosure, wide spreads and lock-in — plus practical ways to reduce each one."
    authorLine="StockWitty Research · CA-reviewed"
    dateLabel="August 2026"
    readLabel="8 min read"
    :toc="$toc"
    :takeaways="[
        'Illiquidity means you may not be able to exit when you want, at any sensible price.',
        'There is no live market price, so valuation is negotiated and can be wrong in both directions.',
        'There is no guarantee an IPO ever happens — timelines slip by years and sometimes vanish.',
        'Disclosures are thinner than for listed companies, so you are deciding with less information.',
        'Bid-ask spreads are wide, and regulatory or lock-in restrictions can delay an exit further.',
    ]"
    :video="['caption' => 'Watch: The risks of unlisted shares, without the sales pitch', 'note' => 'Replace with your StockWitty YouTube video']"
    :faqTabs="['Basics', 'Risk', 'Process']"
    :faqs="$faqs"
    :sources="[
        ['label' => 'SEBI — investor cautions & regulations', 'href' => 'https://www.sebi.gov.in/'],
        ['label' => 'SEBI SCORES — investor complaints', 'href' => 'https://scores.sebi.gov.in/'],
        ['label' => 'CDSL — Central Depository Services', 'href' => 'https://www.cdslindia.com/'],
        ['label' => 'NSDL — National Securities Depository', 'href' => 'https://nsdl.co.in/'],
        ['label' => 'Income Tax Department, India', 'href' => 'https://www.incometax.gov.in/'],
    ]"
    :related="[
        ['title' => 'Is It Safe to Buy Unlisted Shares?', 'href' => '/blog/is-it-safe-to-buy-unlisted-shares/', 'category' => 'Safety', 'read' => '8 min read'],
        ['title' => 'How to Buy Unlisted Shares in India', 'href' => '/blog/how-to-buy-unlisted-shares/', 'category' => 'Buying Guide', 'read' => '10 min read'],
        ['title' => 'What Are Unlisted Shares?', 'href' => '/blog/what-are-unlisted-shares/', 'category' => 'Basics', 'read' => '7 min read'],
    ]"
    :leadForm="['heading' => 'Understand the risks before you invest — talk to us.', 'subtext' => 'A StockWitty specialist will go through the risks of a specific name with you before anything else — liquidity, disclosure and realistic timelines. No obligation to transact.']"
>
    <x-slot:intro>
        <p>
            Most unlisted-share content is written to make you buy. This one is written to make sure
            you know what you are buying, because the risks here are not footnotes — they are the
            defining features of the asset.
        </p>
        <p>
            We distribute unlisted shares. We would still rather you walked away from a transaction
            than entered one with money you cannot afford to lock up, or with an assumption about an
            IPO that nobody can promise you.
        </p>
        <p>
            Here is the honest list, in the order it tends to hurt people.
        </p>
    </x-slot:intro>

    <x-sw.article-h2 id="why-higher">Why unlisted shares carry higher risk</x-sw.article-h2>
    <x-sw.prose>
        <p>
            A listed company sits inside a machine designed to surface problems: quarterly results,
            continuous disclosure obligations, analyst scrutiny, and a price that reacts within minutes
            to bad news. None of that machinery exists around an unlisted company in the same form.
        </p>
        <p>
            So risk is not just higher in degree, it is different in kind. You hold something whose value
            you cannot observe, cannot easily verify, and cannot reliably convert back into cash on a
            schedule of your choosing.
        </p>
    </x-sw.prose>
    <x-sw.pullquote>
        In listed equity, the risk is that the price falls. In unlisted equity, the risk is that the
        price falls and you cannot sell.
    </x-sw.pullquote>

    <x-sw.article-h2 id="main-risks">The main risks explained</x-sw.article-h2>
    <x-sw.prose>
        <p><strong class="text-foreground">Illiquidity risk.</strong> Selling requires an actual
            buyer for that specific ISIN at your quantity. Active pre-IPO names can be sold in a day or
            two; thin names can take weeks, or require a discount to clear. Your exit date is a hope, not
            a plan.</p>
        <p><strong class="text-foreground">Valuation risk.</strong> Without continuous price
            discovery, a quote is one party's estimate anchored to the last few trades and the most
            recent funding round. Enthusiasm for a well-known name can push unlisted levels above what
            the eventual listing supports — investors who paid up before a listing have, in several
            cases, spent years underwater.</p>
        <p><strong class="text-foreground">No guaranteed IPO.</strong> Filing a DRHP is not
            listing, and approval is not a date. Companies withdraw filings, defer issues through weak
            markets, or restructure entirely. If your thesis needs a listing to work, the thesis has a
            dependency nobody controls.</p>
        <p><strong class="text-foreground">Limited disclosures.</strong> You typically get annual
            financials rather than quarterly, less segment detail, and no management commentary cadence.
            Problems surface later than they would in a listed peer, which means the price you are quoted
            may reflect information that is months stale.</p>
        <p><strong class="text-foreground">Wide bid-ask spreads.</strong> The gap between the buy
            quote and the sell quote in the same name is real money, and it is your first loss the moment
            you transact. On thin names that spread can be a meaningful part of the position, which makes
            short holding periods structurally unattractive.</p>
        <p><strong class="text-foreground">Regulatory &amp; lock-in risk.</strong> Pre-IPO holdings
            can face a post-listing lock-in restricting when you can sell. Shareholder agreements may
            include a right of first refusal or require board approval for transfer. Rules governing
            transfers and taxation also change, and not always in your favour.</p>
    </x-sw.prose>

    <x-sw.article-h2 id="avoid">Who should avoid unlisted shares</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Anyone investing emergency funds or money needed within the next two to three years.',
        'Anyone using borrowed money, a personal loan, or a credit line to fund the position.',
        'Investors without an existing diversified listed portfolio to sit behind the allocation.',
        'Anyone who would need to check a price weekly to stay calm — there isn\'t one.',
        'Investors buying purely on an IPO rumour, a WhatsApp group tip, or a promised allotment.',
        'First-time investors still building their core savings and insurance base.',
    ]" />

    <x-sw.article-h2 id="reduce">How to reduce these risks</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Size it so it doesn\'t matter: an allocation small enough that going nowhere for five years changes nothing in your plan.',
        'Diversify across more than one unlisted name and sector, rather than concentrating in one story.',
        'Verify the transaction: pay only into a verified company or escrow account, and confirm the ISIN in your own CDSL/NSDL statement.',
        'Read the annual report and the DRHP if one exists — form a view on how the business earns money, not on the listing timeline.',
        'Compare at least two quotes on the same day so you know what the spread actually is.',
        'Plan the holding period in years, and remember the 24-month tax threshold when you think about selling early.',
        'Assume no IPO. If the position only works with a listing, it isn\'t ready to be a position.',
    ]" />
    <x-sw.prose>
        <p>
            None of this makes unlisted shares safe — it makes them survivable. That distinction is the
            whole point, and it is the one we would want a friend to understand before they transferred
            money.
        </p>
    </x-sw.prose>
</x-sw.blog-post-layout>
@endsection
