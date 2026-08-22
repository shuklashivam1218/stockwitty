@extends('layouts.sw')

@section('title', 'What Are Unlisted Shares? A Simple Guide for Indian Investors (2026) | StockWitty')
@section('description', 'Unlisted shares explained in plain English — what they are, how they trade in India, the types (pre-IPO, ESOP, delisted), why investors buy them, and who should stay away.')

@php
$toc = [
    ['id' => 'definition', 'label' => 'What are unlisted shares?'],
    ['id' => 'how-they-work', 'label' => 'How they work in India'],
    ['id' => 'types', 'label' => 'Types of unlisted shares'],
    ['id' => 'why', 'label' => 'Why investors buy them'],
    ['id' => 'who', 'label' => "Who should — and shouldn't"],
    ['id' => 'start', 'label' => 'How to get started'],
    ['id' => 'sources', 'label' => 'Sources & references'],
    ['id' => 'faq', 'label' => 'FAQ'],
];

$faqs = [
    ['tab' => 'Basics', 'q' => 'Are unlisted shares legal in India?', 'a' => 'Yes. Buying and selling shares of an unlisted company is legal. The transfer happens privately between two parties and is delivered as an off-market transfer through CDSL or NSDL, instead of matching on an exchange order book.'],
    ['tab' => 'Process', 'q' => 'Can I hold unlisted shares in a demat account?', 'a' => 'Yes, and in practice you must. Unlisted shares of most companies traded in the secondary market are dematerialised and sit in your regular CDSL or NSDL demat account alongside your listed holdings.'],
    ['tab' => 'Process', 'q' => 'How do I know the price is fair?', 'a' => "There is no exchange ticker, so compare at least two independent quotes on the same day, ask what recent transaction level the quote is based on, and cross-check the valuation of the company's most recent primary round. Wide gaps between quotes usually signal a thin, illiquid name."],
    ['tab' => 'Risk', 'q' => 'Are unlisted shares risky?', 'a' => 'Yes. They are illiquid, disclosure is thinner than for listed companies, valuation is negotiated rather than market-discovered, and there is no guarantee the company ever lists. Treat them as a small, long-horizon part of a portfolio, not a core holding.'],
];
@endphp

@section('content')
<x-sw.blog-post-layout
    :crumbs="[['label' => 'Home', 'href' => '/'], ['label' => 'Blog', 'href' => '/blog/'], ['label' => 'Basics', 'href' => '/blog/'], ['label' => 'What Are Unlisted Shares?']]"
    :chips="['Unlisted Shares', 'Basics', '2026']"
    heroIcon="help-circle"
    title="What Are Unlisted Shares? A Simple Guide for Indian Investors (2026)"
    description="Unlisted shares explained in plain English — what they are, how they trade in India, the types (pre-IPO, ESOP, delisted), why investors buy them, and who should stay away."
    authorLine="StockWitty Research · CA-reviewed"
    dateLabel="August 2026"
    readLabel="7 min read"
    :toc="$toc"
    :takeaways="[
        'Unlisted shares are equity in companies not yet listed on the NSE or BSE.',
        'They trade privately between buyer and seller, and are delivered into your CDSL/NSDL demat account.',
        'Prices are negotiated between parties — there is no exchange quote or live ticker.',
        'Common types include pre-IPO shares, ESOP shares sold by employees, and delisted company shares.',
        'They offer early access, but they are illiquid and higher-risk, with no guaranteed IPO or exit.',
    ]"
    :video="['caption' => 'Watch: Unlisted shares explained in 5 minutes — StockWitty', 'note' => 'Replace with your StockWitty YouTube video']"
    :faqTabs="['Basics', 'Process', 'Risk']"
    :faqs="$faqs"
    :sources="[
        ['label' => 'SEBI — Securities and Exchange Board of India', 'href' => 'https://www.sebi.gov.in/'],
        ['label' => 'CDSL — Central Depository Services', 'href' => 'https://www.cdslindia.com/'],
        ['label' => 'NSDL — National Securities Depository', 'href' => 'https://nsdl.co.in/'],
        ['label' => 'Income Tax Department, India', 'href' => 'https://www.incometax.gov.in/'],
        ['label' => 'Ministry of Corporate Affairs — company filings', 'href' => 'https://www.mca.gov.in/'],
    ]"
    :related="[
        ['title' => 'How to Buy Unlisted Shares in India', 'href' => '/blog/how-to-buy-unlisted-shares/', 'category' => 'Buying Guide', 'read' => '10 min read'],
        ['title' => 'Unlisted vs Listed Shares', 'href' => '/blog/unlisted-shares-vs-listed-shares/', 'category' => 'Comparison', 'read' => '7 min read'],
        ['title' => 'Is It Safe to Buy Unlisted Shares?', 'href' => '/blog/is-it-safe-to-buy-unlisted-shares/', 'category' => 'Safety', 'read' => '8 min read'],
    ]"
    :leadForm="['heading' => 'New to unlisted shares? Talk to a specialist.', 'subtext' => 'Tell us what you\'re curious about and a StockWitty specialist will call you back to explain the process, the current quotes and the risks — with no obligation to transact.']"
>
    <x-slot:intro>
        <p>
            Most Indian investors meet equity through the exchange: you open an app, you see a live
            price, you buy. Unlisted shares are the same asset — ownership in a company — minus that
            screen. The company has not listed on the NSE or BSE, so there is no ticker, no order
            book and no five-second price update.
        </p>
        <p>
            That single difference changes almost everything practical about owning them: how you
            find a price, how long a purchase takes, how easily you can sell, and how much you can
            actually read about the business before you commit.
        </p>
        <p>
            This guide is the plain-English version — what unlisted shares are, how they move in
            India, and an honest read on who they suit.
        </p>
    </x-slot:intro>

    <x-sw.article-h2 id="definition">What are unlisted shares?</x-sw.article-h2>
    <x-sw.prose>
        <p>
            An unlisted share is an equity share of a company whose shares are not admitted for
            trading on a recognised stock exchange. Legally it is the same instrument as a listed
            share: it gives you part-ownership, a claim on profits, and voting rights as per the
            company's articles. What is missing is the exchange — the venue that turns thousands of
            buy and sell orders into one visible price.
        </p>
        <p>
            So instead of an order book, each transaction is a negotiated private deal. You agree a
            price and quantity with a counterparty, pay them, and they transfer the shares to your
            demat account. Nobody else sees that price, which is exactly why unlisted quotes differ
            from one dealer to the next.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="how-they-work">How do unlisted shares work in India?</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Three practical mechanics define the market. First, it is a private market: buyers and
            sellers are matched by dealers and platforms rather than by an exchange, and there is no
            guarantee a buyer exists for a given name on a given day.
        </p>
        <p>
            Second, delivery is dematerialised. Once you have paid, the seller initiates an off-market
            transfer using your DP ID and Client ID, and the shares appear in the same CDSL or NSDL
            demat account that holds your listed stocks. You verify the ISIN and quantity in your own
            depository statement — never from a screenshot.
        </p>
        <p>
            Third, there is no live price. Quotes are indicative and valid for a window. Movement in an
            unlisted name is often news-driven — a funding round, a DRHP filing, a results release —
            rather than continuous.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="types">Types of unlisted shares</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Pre-IPO shares — companies that have filed or are widely expected to file for a listing. The most actively quoted category.',
        'ESOP shares — employees of private companies selling vested stock in the secondary market for liquidity.',
        'Delisted shares — companies that were once listed and have exited the exchange; shares still exist and can change hands privately.',
        'Private / unquoted company shares — established businesses with no listing plan at all, held for dividends or long-term ownership.',
    ]" />

    <x-sw.article-h2 id="why">Why do investors buy them?</x-sw.article-h2>
    <x-sw.prose>
        <p>
            The honest answer is early access. If you believe a business will be materially larger in
            five years, owning it before the listing spotlight arrives means paying a price set by
            negotiation rather than by a bidding public market. Sometimes that is cheaper. Sometimes it
            isn't.
        </p>
        <p>
            The second reason is diversification of exposure — some of India's more interesting
            businesses simply are not on the exchange yet, so a listed-only portfolio cannot own them
            at all. Neither reason changes the fact that upside here is a possibility, not a promise.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="who">Who should — and shouldn't — consider them</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Suits: investors with a genuine 3–5+ year horizon who will not need the money on a deadline.',
        'Suits: people who already hold a diversified listed portfolio and are allocating a small satellite slice.',
        'Suits: investors comfortable reading annual reports and forming their own view without a live price.',
        'Doesn\'t suit: anyone investing emergency funds, borrowed money, or capital needed within two years.',
        'Doesn\'t suit: investors who need daily price visibility, or who are buying purely on IPO rumour.',
    ]" />

    <x-sw.article-h2 id="start">How to get started</x-sw.article-h2>
    <x-sw.prose>
        <p>
            The prerequisites are boring and non-negotiable: an active CDSL or NSDL demat account, PAN
            and Aadhaar for KYC, a Client Master List (CML) copy from your broker, and a cancelled
            cheque of the account you will pay from. Funds must come from your own bank account —
            third-party payments are not accepted.
        </p>
        <p>
            From there it is quote, confirm, pay into a verified company account, receive the demat
            credit, and verify the ISIN yourself. Our
            <a href="/blog/how-to-buy-unlisted-shares/" class="font-semibold text-primary underline underline-offset-4">
                step-by-step guide to buying unlisted shares
            </a>
            walks through each stage in order.
        </p>
    </x-sw.prose>
</x-sw.blog-post-layout>
@endsection
