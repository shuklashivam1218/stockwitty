@extends('layouts.sw')

@section('title', 'Tax on Unlisted Shares in India: Complete Guide (2026) | StockWitty')
@section('description', 'How unlisted shares are taxed in India — the 24-month holding period, LTCG vs STCG treatment, what changes after the company lists, ITR reporting and the mistakes to avoid.')

@php
$toc = [
    ['id' => 'quick-answer', 'label' => 'How unlisted shares are taxed'],
    ['id' => 'holding-period', 'label' => 'Holding period — 24 months'],
    ['id' => 'ltcg', 'label' => 'LTCG on unlisted shares'],
    ['id' => 'stcg', 'label' => 'STCG on unlisted shares'],
    ['id' => 'post-ipo', 'label' => 'Tax when the company lists'],
    ['id' => 'itr', 'label' => 'How to report it in your ITR'],
    ['id' => 'mistakes', 'label' => 'Common tax mistakes'],
    ['id' => 'sources', 'label' => 'Sources & references'],
    ['id' => 'faq', 'label' => 'FAQ'],
];

$faqs = [
    ['tab' => 'Basics', 'q' => 'What is the holding period for unlisted shares?', 'a' => 'While a share is unlisted, 24 months is the threshold. Hold for more than 24 months and a sale is treated as long-term; sell within 24 months and it is short-term. Once the company lists, listed-equity holding periods apply from that point.'],
    ['tab' => 'Rates', 'q' => 'How much tax do I pay on unlisted share gains?', 'a' => 'Long-term gains on unlisted shares are taxed at the long-term capital gains rate applicable to unlisted securities for your category of taxpayer. Short-term gains are added to your total income and taxed at your slab rate. Rates and any indexation benefit are set by the prevailing Finance Act — confirm the current numbers with a CA before you file.'],
    ['tab' => 'Basics', 'q' => 'What happens to tax after the company IPOs?', 'a' => 'From the listing date the shares are listed securities, so listed-equity rules and holding-period thresholds apply to a sale made after listing. Separately, pre-IPO holders may face a lock-in that restricts when they can sell at all. The two are different things — one is tax, one is transferability.'],
    ['tab' => 'Filing', 'q' => 'Where do I report unlisted share gains in my ITR?', 'a' => 'Capital gains are reported in the capital gains schedule of the applicable ITR form, split between long-term and short-term. Indian ITR forms also require disclosure of unlisted equity shares held during the year, including opening and closing holdings and cost of acquisition — so you may need to report the holding even in a year you did not sell.'],
];
@endphp

@section('content')
<x-sw.blog-post-layout
    :crumbs="[['label' => 'Home', 'href' => '/'], ['label' => 'Blog', 'href' => '/blog/'], ['label' => 'Tax', 'href' => '/blog/'], ['label' => 'Tax on Unlisted Shares']]"
    :chips="['Unlisted Shares', 'Tax', '2026']"
    heroIcon="receipt"
    title="Tax on Unlisted Shares in India: Complete Guide (2026)"
    description="How unlisted shares are taxed in India — the 24-month holding period, LTCG vs STCG treatment, what changes after the company lists, ITR reporting and the mistakes to avoid."
    authorLine="StockWitty Research · CA-reviewed"
    dateLabel="August 2026"
    readLabel="8 min read"
    :toc="$toc"
    :takeaways="[
        'While unlisted, the long-term capital gains holding period is 24 months.',
        'Sold after 24 months = long-term capital gains; sold within 24 months = short-term, taxed at your slab rate.',
        'Once the company lists, listed-equity rules apply to sales made from the listing date onward.',
        'Gains must be reported in the capital gains schedule of your ITR, and unlisted equity holdings disclosed separately.',
        'Rules change with every Finance Act — verify the current position with a CA before you file.',
    ]"
    :video="['caption' => 'Watch: Tax on unlisted shares, explained simply — StockWitty', 'note' => 'Replace with your StockWitty YouTube video']"
    :faqTabs="['Basics', 'Rates', 'Filing']"
    :faqs="$faqs"
    :sources="[
        ['label' => 'Income Tax Department, India', 'href' => 'https://www.incometax.gov.in/'],
        ['label' => 'Income Tax Department — ITR forms & instructions', 'href' => 'https://www.incometax.gov.in/iec/foportal/help/individual/return-applicable-1'],
        ['label' => 'SEBI — Securities and Exchange Board of India', 'href' => 'https://www.sebi.gov.in/'],
        ['label' => 'CDSL — Central Depository Services', 'href' => 'https://www.cdslindia.com/'],
        ['label' => 'NSDL — National Securities Depository', 'href' => 'https://nsdl.co.in/'],
    ]"
    :related="[
        ['title' => 'How to Buy Unlisted Shares in India', 'href' => '/blog/how-to-buy-unlisted-shares/', 'category' => 'Buying Guide', 'read' => '10 min read'],
        ['title' => 'How to Sell Unlisted Shares in India', 'href' => '/blog/how-to-sell-unlisted-shares/', 'category' => 'Selling Guide', 'read' => '8 min read'],
        ['title' => 'Unlisted vs Listed Shares', 'href' => '/blog/unlisted-shares-vs-listed-shares/', 'category' => 'Comparison', 'read' => '7 min read'],
    ]"
    :leadForm="['heading' => 'Questions on unlisted-share tax? We\'ll connect you.', 'subtext' => 'Tell us what you\'re holding and what you\'re planning. A StockWitty specialist will call you back and, where it\'s a tax question rather than a transaction question, point you to a qualified CA.']"
>
    <x-slot:intro>
        <p>
            Tax is where unlisted shares stop resembling the listed equity most investors know. The
            holding period is longer, the rate treatment is different, and the ITR asks you to
            disclose the holding whether or not you sold anything during the year.
        </p>
        <p>
            None of it is complicated once you see the structure: how long you held, whether the
            company was listed at the time of sale, and which schedule the number goes into.
        </p>
        <p>
            This guide lays out that structure in order. It is educational, not tax advice — the
            thresholds and rates move with each Finance Act, and your own position depends on facts we
            cannot see.
        </p>
    </x-slot:intro>

    <x-sw.callout title="Not tax advice — verify with a CA">
        This article explains the general framework for educational purposes. Capital gains rates,
        holding-period thresholds and indexation rules are amended by each Finance Act, and treatment
        differs for residents, non-residents, HUFs and companies. Confirm your own position with a
        qualified chartered accountant before filing. Any figure used here is illustrative.
    </x-sw.callout>

    <x-sw.article-h2 id="quick-answer">How are unlisted shares taxed?</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Gains on unlisted shares are taxed as capital gains — not as business income, unless you
            trade them as a business. Two questions determine the treatment: how long you held the
            shares, and whether the company was listed at the time you sold.
        </p>
        <p>
            If the company was still unlisted at sale, the 24-month test applies. If it had listed and
            you sold after listing, the shares are listed securities for that sale and listed-equity
            rules apply instead. There is no STT on an off-market unlisted transfer, which is one reason
            the treatment differs from exchange-traded equity.
        </p>
    </x-sw.prose>
    <x-sw.comparison-table
        :head="['Scenario', 'Holding period test', 'Treatment']"
        :rows="[
            ['Unlisted at sale, held over 24 months', 'Over 24 months', 'Long-term capital gains'],
            ['Unlisted at sale, held 24 months or less', '24 months or less', 'Short-term — taxed at your slab rate'],
            ['Company listed, sold after listing', 'Listed-equity thresholds apply', 'Listed-equity capital gains rules from the listing date'],
        ]"
    />

    <x-sw.article-h2 id="holding-period">Holding period — long-term vs short-term (24 months)</x-sw.article-h2>
    <x-sw.prose>
        <p>
            The clock starts on the date the shares are credited to your demat account and ends on the
            date of transfer out. For unlisted shares the long-term line sits at 24 months — twice the
            12-month threshold that applies to listed equity.
        </p>
        <p>
            That gap matters for planning. An investor 20 months into a holding who is offered a good
            buy-side level is making a tax decision as well as an investment one, and the four-month
            wait can change the effective outcome materially. Keep the credit date from your depository
            statement — it is the evidence for your holding period.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="ltcg">LTCG on unlisted shares</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Where a holding qualifies as long-term, the gain is computed as sale consideration minus
            cost of acquisition and eligible transfer expenses, and taxed at the long-term rate
            prescribed for unlisted securities for your category of taxpayer. Whether indexation of cost
            is available depends on the rules in force for the year of sale — this is exactly the point
            the last few Finance Acts have moved, so check the current position rather than an older
            article.
        </p>
        <p>
            Long-term losses can generally be set off against long-term capital gains and carried
            forward for the number of years the Act allows, provided the return is filed on time.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="stcg">STCG on unlisted shares</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Sell within 24 months and the gain is short-term. It is added to your total income and taxed
            at your applicable slab rate — there is no special concessional rate as there is for
            exchange-traded listed equity, because no STT was paid on the transfer.
        </p>
        <p>
            For someone in a higher slab, this is the single most expensive way to hold unlisted shares:
            a quick flip can be taxed at more than double the long-term treatment. It is another
            argument for sizing the position so you are never forced to sell early.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="post-ipo">Tax when the company lists (post-IPO)</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Listing does not trigger tax by itself — nothing has been transferred. What changes is the
            character of the security for future sales. Sell on the exchange after listing and the sale
            is a listed-equity transaction, with the listed holding-period thresholds and rate structure
            applying, and STT paid on the trade.
        </p>
        <p>
            Separately, pre-IPO shareholders may be locked in for a period after listing. Lock-in
            restricts when you can sell; it has no bearing on how the eventual gain is taxed. Treat the
            two as independent constraints when you plan an exit.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="itr">How to report it in your ITR</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Use the ITR form applicable to you — holding unlisted equity shares generally rules out the simplest forms.',
        'Report gains in the capital gains schedule, split correctly between long-term and short-term.',
        'Complete the separate disclosure of unlisted equity shares held during the year: company name, PAN, opening holding, shares acquired and transferred, cost of acquisition and closing holding.',
        'Keep your purchase invoice, bank advice and depository statements as proof of cost and holding period.',
        'Report the holding even in years you did not sell — the disclosure requirement is about ownership, not just transactions.',
        'File by the due date if you want to carry forward capital losses.',
    ]" />

    <x-sw.article-h2 id="mistakes">Common tax mistakes</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Assuming the 12-month listed-equity holding period applies while the share is still unlisted.',
        'Selling at month 22 without checking what waiting to month 25 would have saved.',
        'Skipping the unlisted-equity disclosure schedule in a year with no sale.',
        'Losing the purchase invoice, then guessing at cost of acquisition years later.',
        'Applying an old article\'s rates — indexation and LTCG rules have been amended more than once.',
        'Filing after the due date and losing the ability to carry forward a capital loss.',
    ]" />
</x-sw.blog-post-layout>
@endsection
