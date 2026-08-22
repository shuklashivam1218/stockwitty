@extends('layouts.sw')

@section('title', 'How to Sell Unlisted Shares in India: Step-by-Step Guide (2026) | StockWitty')
@section('description', 'A practical guide to selling unlisted shares in India — liquidity reality, getting a buy-side quote, the off-market transfer via DIS, lock-in and ROFR checks, documents and tax on sale.')

@php
$toc = [
    ['id' => 'liquidity', 'label' => 'Can you sell any time?'],
    ['id' => 'process', 'label' => 'How to sell — the process'],
    ['id' => 'price', 'label' => 'How the price is decided'],
    ['id' => 'restrictions', 'label' => 'Lock-in, ROFR & restrictions'],
    ['id' => 'tax', 'label' => 'Tax when you sell'],
    ['id' => 'documents', 'label' => 'Documents needed'],
    ['id' => 'sources', 'label' => 'Sources & references'],
    ['id' => 'faq', 'label' => 'FAQ'],
];

$faqs = [
    ['tab' => 'Basics', 'q' => 'Can I sell unlisted shares before the IPO?', 'a' => 'Usually yes, provided the shares are freely transferable and not under a contractual lock-in. Once a company files for an IPO, pre-IPO holdings can attract a lock-in period after listing, and some shareholder agreements restrict transfers earlier than that. Check your holding\'s terms before you commit to a sale.'],
    ['tab' => 'Process', 'q' => 'How long does selling take?', 'a' => 'Finding a buyer is the variable part — hours for an actively quoted name, days or longer for a thin one. Once a price is agreed, the off-market transfer and payment typically settle within one to two working days after your delivery instruction is processed by the depository.'],
    ['tab' => 'Process', 'q' => 'Who decides the selling price?', 'a' => 'You and the buyer. Quotes reference recent transaction levels in that name, the valuation of the last primary round, and how much supply is around. You are free to reject a quote — but on illiquid names, holding out can mean not selling at all.'],
    ['tab' => 'Tax', 'q' => 'Do I pay tax when I sell unlisted shares?', 'a' => 'Yes, on any capital gain. While the share is unlisted, gains on holdings sold after 24 months are long-term; sales inside 24 months are short-term and taxed at your slab rate. Rules change with each Finance Act, so confirm your position with a CA before filing.'],
];
@endphp

@section('content')
<x-sw.blog-post-layout
    :crumbs="[['label' => 'Home', 'href' => '/'], ['label' => 'Blog', 'href' => '/blog/'], ['label' => 'Buying & Selling', 'href' => '/blog/'], ['label' => 'How to Sell Unlisted Shares']]"
    :chips="['Unlisted Shares', 'Selling Guide', '2026']"
    heroIcon="banknote"
    title="How to Sell Unlisted Shares in India: Step-by-Step Guide (2026)"
    description="A practical guide to selling unlisted shares in India — liquidity reality, getting a buy-side quote, the off-market transfer via DIS, lock-in and ROFR checks, documents and tax on sale."
    authorLine="StockWitty Research · CA-reviewed"
    dateLabel="August 2026"
    readLabel="8 min read"
    :toc="$toc"
    :takeaways="[
        'You can\'t always sell instantly — liquidity depends entirely on demand for that specific company.',
        'You need a buy-side quote first, then an off-market transfer out of your demat via a DIS.',
        'The selling price is negotiated, not exchange-quoted; compare more than one buy-side quote.',
        'Check lock-in periods and any right of first refusal (ROFR) in shareholder agreements before agreeing a sale.',
        'Tax depends on your holding period — 24 months is the long-term threshold while a share is unlisted.',
    ]"
    :video="['caption' => 'Watch: Selling unlisted shares — the off-market transfer explained', 'note' => 'Replace with your StockWitty YouTube video']"
    :faqTabs="['Basics', 'Process', 'Tax']"
    :faqs="$faqs"
    :sources="[
        ['label' => 'SEBI — Securities and Exchange Board of India', 'href' => 'https://www.sebi.gov.in/'],
        ['label' => 'CDSL — off-market transfers & DIS', 'href' => 'https://www.cdslindia.com/'],
        ['label' => 'NSDL — National Securities Depository', 'href' => 'https://nsdl.co.in/'],
        ['label' => 'Income Tax Department, India', 'href' => 'https://www.incometax.gov.in/'],
    ]"
    :related="[
        ['title' => 'How to Buy Unlisted Shares in India', 'href' => '/blog/how-to-buy-unlisted-shares/', 'category' => 'Buying Guide', 'read' => '10 min read'],
        ['title' => 'Tax on Unlisted Shares in India', 'href' => '/blog/tax-on-unlisted-shares/', 'category' => 'Tax', 'read' => '8 min read'],
        ['title' => 'Is It Safe to Buy Unlisted Shares?', 'href' => '/blog/is-it-safe-to-buy-unlisted-shares/', 'category' => 'Safety', 'read' => '8 min read'],
    ]"
    :leadForm="['heading' => 'Looking to sell your unlisted shares? Get a quote.', 'subtext' => 'Share the company, quantity and ISIN and a StockWitty specialist will call you back with an indicative buy-side level and the paperwork involved. No obligation to sell.']"
>
    <x-slot:intro>
        <p>
            Buying unlisted shares is the easy half. Selling is where the asset shows its real
            character, because there is no exchange standing ready to take the other side of your
            trade. You need an actual buyer who wants that specific company, at a price you both
            accept, on the day you want to exit.
        </p>
        <p>
            None of that makes selling hard — thousands of off-market transfers settle smoothly every
            month. It just makes it a process with a timeline you don't fully control.
        </p>
        <p>
            Here is what the sale looks like end to end, including the checks people skip and
            regret: lock-in terms, right of first refusal, and the tax that lands after the money
            does.
        </p>
    </x-slot:intro>

    <x-sw.article-h2 id="liquidity">Can you sell unlisted shares any time?</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Technically yes — practically, it depends on the name. Widely followed pre-IPO companies
            usually have standing buy-side interest, and a sale can be arranged within a day or two.
            For a thinly traded name, you may wait, or accept a lower level to find a buyer at all.
        </p>
        <p>
            That is the illiquidity discount in action. It is also why position sizing matters more
            here than in listed equity: if you may need the money on a fixed date, an unlisted holding
            is the wrong place for it.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="process">How to sell unlisted shares — the process</x-sw.article-h2>
    <x-sw.numbered-steps :steps="[
        ['t' => 'Share your holding details — ISIN and quantity', 'd' => 'Pull your CDSL or NSDL holding statement and confirm the exact ISIN, company name and number of shares you hold. Quotes are given against a specific ISIN, not a company nickname.'],
        ['t' => 'Get a buy-side quote', 'd' => 'Ask for the level in writing, along with the settlement timeline and whether the quote is firm or indicative. Compare at least two buyers where the name is liquid enough to have two.'],
        ['t' => 'Initiate the off-market transfer from your demat', 'd' => 'Submit a Delivery Instruction Slip (DIS) to your broker — physical or online — with the buyer\'s DP ID, Client ID, ISIN, quantity and \'off-market sale\' as the reason. The shares move out of your account through CDSL or NSDL.'],
        ['t' => 'Receive payment to your bank account', 'd' => 'Funds are credited to the bank account registered in your name — never to a third party. Confirm the credit and keep the contract note or sale invoice for your tax records.'],
    ]" />
    <x-sw.callout title="Sequencing is negotiable — put it in writing">
        Whether shares move before funds, or funds before shares, depends on the counterparty and any
        escrow arrangement. Whatever the agreed order, get it in writing before you sign the DIS, and
        deal only with counterparties whose bank account matches the entity on your paperwork.
    </x-sw.callout>

    <x-sw.article-h2 id="price">How is the selling price decided?</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Buy-side quotes are built from the last transactions the buyer has seen in that ISIN, the
            valuation of the most recent primary round, and how much supply is currently on offer. If
            three sellers of the same name appear in a week, levels soften; if a DRHP filing lands,
            they firm up.
        </p>
        <p>
            Expect a spread between what a buyer pays you and what the same dealer quotes a buyer —
            that spread is the dealer's margin and the cost of finding the other side. On thin names it
            widens. Ask which recent trade a quote references; a counterparty who answers that plainly
            is usually the one worth dealing with.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="restrictions">Lock-in, ROFR &amp; restrictions to check</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'IPO lock-in — pre-IPO shareholders can face a post-listing lock-in period during which shares cannot be sold. Confirm before you promise delivery.',
        'Right of first refusal (ROFR) — some shareholder agreements require you to offer shares to existing shareholders or the promoter first.',
        'Transfer restrictions in the articles of association — private company shares can require board approval for transfer.',
        'ESOP-specific terms — vested employee shares may carry a holding requirement or company consent condition.',
        'Pledged or frozen holdings — shares under pledge, or in a frozen demat account, cannot be transferred until released.',
    ]" />

    <x-sw.article-h2 id="tax">Tax when you sell</x-sw.article-h2>
    <x-sw.prose>
        <p>
            While the share is unlisted, the long-term threshold is 24 months. Sell after 24 months and
            the gain is a long-term capital gain; sell within 24 months and it is short-term, taxed at
            your slab rate. If the company has since listed, listed-equity rules apply from the listing
            date onward.
        </p>
        <p>
            Non-residents and corporate holders have separate considerations, and thresholds change
            with each Finance Act. Our
            <a href="/blog/tax-on-unlisted-shares/" class="font-semibold text-primary underline underline-offset-4">
                full tax guide
            </a>
            covers the detail — confirm your own position with a CA before filing.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="documents">Documents needed to sell</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'PAN card and Aadhaar for KYC.',
        'Client Master List (CML) copy showing your DP ID, Client ID and name as the depository holds it.',
        'Latest CDSL/NSDL holding statement showing the ISIN and quantity.',
        'A signed Delivery Instruction Slip (DIS) — or your broker\'s online off-market transfer authorisation.',
        'Cancelled cheque of the bank account where you want the sale proceeds credited.',
        'Sale invoice or contract note — keep it; you will need it when you compute capital gains.',
    ]" />
</x-sw.blog-post-layout>
@endsection
