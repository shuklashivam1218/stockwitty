@extends('layouts.sw')

@section('title', 'How to Buy Unlisted Shares in India: Step-by-Step Guide (2026) | StockWitty')
@section('description', 'A step-by-step guide to buying unlisted and pre-IPO shares in India — KYC documents, price and lot confirmation, payment to a verified company account, demat delivery and ISIN verification.')

@php
$toc = [
    ['id' => 'before-you-start', 'label' => 'What you need before you start'],
    ['id' => 'five-steps', 'label' => 'How to buy — the 5 steps'],
    ['id' => 'journey', 'label' => "A real investor's journey"],
    ['id' => 'pricing', 'label' => 'How prices are decided'],
    ['id' => 'charges', 'label' => 'Charges & minimum lot size'],
    ['id' => 'tax', 'label' => 'Tax on unlisted shares'],
    ['id' => 'mistakes', 'label' => 'Common mistakes to avoid'],
    ['id' => 'conclusion', 'label' => 'Conclusion'],
    ['id' => 'sources', 'label' => 'Sources & references'],
    ['id' => 'faq', 'label' => 'FAQ'],
];

$faqs = [
    ['tab' => 'Basics', 'q' => 'What are unlisted shares?', 'a' => 'Unlisted shares are equity shares of a company that is not listed on the NSE or BSE. They are bought and sold privately between buyers and sellers, and delivered through an off-market transfer into your demat account instead of via an exchange order book.'],
    ['tab' => 'Process', 'q' => 'Do I need a demat account to buy unlisted shares?', 'a' => 'Yes. Unlisted shares are delivered as an off-market transfer into your CDSL or NSDL demat account, so an active demat account and completed KYC are mandatory before any transaction.'],
    ['tab' => 'Process', 'q' => 'How long does share delivery take?', 'a' => 'Once cleared funds are received and your CML details match, shares are usually credited the same working day. Depository processing can push the credit to the next working day.'],
    ['tab' => 'Basics', 'q' => 'How is the price of unlisted shares decided?', 'a' => 'There is no exchange ticker. Prices are negotiated between buyers and sellers, and dealers quote from recent transaction levels, the valuation of the last primary round, and how much of that particular name is available at the time.'],
    ['tab' => 'Basics', 'q' => 'Is buying unlisted shares safe?', 'a' => 'The transfer mechanism is safe when you pay only into a verified company or escrow account and verify the ISIN in your own depository statement. The investment itself is high-risk: unlisted shares are illiquid, disclosure is thinner than for listed companies, and there is no guaranteed IPO or exit.'],
    ['tab' => 'Tax', 'q' => 'How are unlisted shares taxed?', 'a' => 'While a share is unlisted the holding period for long-term treatment is 24 months. Gains on holdings sold after 24 months are taxed as long-term capital gains; sales inside 24 months are short-term and taxed at your slab rate. Once the company lists, listed-equity rules apply from that point. Rules change with each Finance Act — confirm your position with a CA.'],
];
@endphp

@section('content')
<x-sw.blog-post-layout
    :crumbs="[['label' => 'Home', 'href' => '/'], ['label' => 'Blog', 'href' => '/blog/'], ['label' => 'Buying & Selling', 'href' => '/blog/'], ['label' => 'How to Buy Unlisted Shares']]"
    :chips="['Unlisted Shares', 'Buying Guide', '2026']"
    title="How to Buy Unlisted Shares in India: Step-by-Step Guide (2026)"
    description="A step-by-step guide to buying unlisted and pre-IPO shares in India — KYC documents, price and lot confirmation, payment to a verified company account, demat delivery and ISIN verification."
    authorLine="StockWitty Research · CA-reviewed"
    dateLabel="August 2026"
    readLabel="10 min read"
    :hero="['src' => asset('images/sw/blog-how-to-buy-unlisted-shares.jpg'), 'alt' => 'Illustration of a share certificate, a rising green market chart and a phone showing demat holdings, representing buying unlisted shares in India', 'width' => 1600, 'height' => 900]"
    :toc="$toc"
    :takeaways="[
        'You need a demat account plus full KYC (PAN, CML copy, cancelled cheque, Aadhaar) before you can buy.',
        'Always pay into a verified company or escrow account — never a personal account, UPI handle or wallet.',
        'Shares are credited to your CDSL/NSDL demat, usually the same day; verify the ISIN yourself.',
        'Prices are negotiated, not exchange-quoted — compare quotes before you commit.',
        'Unlisted shares are illiquid and high-risk; there is no guaranteed IPO or exit.',
    ]"
    :video="['caption' => 'Watch: How to buy unlisted shares safely — StockWitty', 'note' => 'Replace with your StockWitty YouTube video']"
    :faqTabs="['Basics', 'Process', 'Tax']"
    :faqs="$faqs"
    :sources="[
        ['label' => 'SEBI — Securities and Exchange Board of India', 'href' => 'https://www.sebi.gov.in/'],
        ['label' => 'CDSL — Central Depository Services', 'href' => 'https://www.cdslindia.com/'],
        ['label' => 'NSDL — National Securities Depository', 'href' => 'https://nsdl.co.in/'],
        ['label' => 'Income Tax Department, India', 'href' => 'https://www.incometax.gov.in/'],
    ]"
    :related="[
        ['title' => 'Tax on Unlisted Shares in India', 'href' => '/blog/tax-on-unlisted-shares/', 'category' => 'Tax', 'read' => '7 min read'],
        ['title' => 'Unlisted vs Listed Shares', 'href' => '/blog/unlisted-shares-vs-listed-shares/', 'category' => 'Basics', 'read' => '6 min read'],
        ['title' => 'Is It Safe to Buy Unlisted Shares?', 'href' => '/blog/is-it-safe-to-buy-unlisted-shares/', 'category' => 'Analysis', 'read' => '8 min read'],
    ]"
    :leadForm="['heading' => 'Want help buying your first unlisted shares?', 'subtext' => 'Tell us what you\'re looking at and a StockWitty specialist will call you back — walk you through KYC, current quotes, lot sizes and the risks, with no obligation to transact.']"
>
    <x-slot:intro>
        <p>
            Unlisted shares let you buy into a company before it reaches the stock exchange — the
            same equity, held in the same demat account, just without a live ticker. For patient
            investors that early access is the whole appeal.
        </p>
        <p>
            Done properly, the process is unglamorous and straightforward: complete KYC, agree a
            price and lot, pay into a verified company account, and watch the shares land in your
            demat. Done carelessly — money sent to a personal UPI handle, no ISIN check, a name
            bought purely on IPO rumour — it is one of the easier ways to lose capital in Indian
            markets.
        </p>
        <p>
            This guide walks through each step in the order it actually happens, and is honest about
            what you give up: liquidity, daily price discovery and any certainty that an IPO ever
            arrives.
        </p>
    </x-slot:intro>

    <x-sw.article-h2 id="before-you-start">What you need before you start</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Every unlisted transaction is a private transfer between two parties, so the paperwork is
            front-loaded. Get these five things in place first and the rest takes hours, not days.
        </p>
    </x-sw.prose>
    <x-sw.checklist-card :items="[
        'An active demat account with CDSL or NSDL — unlisted shares can only be delivered as an off-market transfer.',
        'PAN card and Aadhaar for KYC.',
        'A Client Master List (CML) copy from your broker — it carries your DP ID, Client ID and name exactly as the depository holds it.',
        'A cancelled cheque of the bank account you\'ll pay from.',
        'Funds ready in that same account — third-party payments are not accepted.',
    ]" />

    <x-sw.article-h2 id="five-steps">How to buy unlisted shares — the 5 steps</x-sw.article-h2>
    <x-sw.numbered-steps :steps="[
        ['t' => 'Complete KYC', 'd' => 'Share PAN, Aadhaar, CML copy and a cancelled cheque. The name must match across all four. Expect the check to run both ways — a genuine counterparty verifies you too.'],
        ['t' => 'Choose the company, confirm price and lot', 'd' => 'Get the quote in writing along with minimum lot size and total consideration. Unlisted quotes move; a quote is valid for a window, not forever.'],
        ['t' => 'Transfer to a verified company account', 'd' => 'Pay only into the entity\'s bank or escrow account, matched to the name on your contract note or invoice. Never an individual\'s account, UPI handle or wallet, however convincing the reason.'],
        ['t' => 'Shares are credited to your demat', 'd' => 'The seller initiates an off-market transfer using your DP ID and Client ID. With cleared funds and matching CML details, the credit is usually same working day via CDSL or NSDL.'],
        ['t' => 'Verify the ISIN independently', 'd' => 'Open your own CDSL/NSDL statement and confirm the ISIN, company name and quantity yourself — not from a screenshot someone sends you.'],
    ]" />

    <x-sw.article-h2 id="journey">A real investor's journey</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Rahul, a product manager in Pune, bought his first unlisted shares years before that
            company's listing was even a serious conversation. He wasn't chasing a tip — he had read
            the annual report, understood how the business made money, and was willing to sit still.
            He bought a small lot, added twice on weakness, and then did nothing at all.
        </p>
        <p>
            He'll also tell you the unflattering part. For long stretches the quoted price went
            nowhere, and there were months where he could not have sold quickly at any sensible price.
            The position worked out over the long term because he sized it so that going nowhere for
            years was survivable — not because he timed anything.
        </p>
    </x-sw.prose>
    <x-sw.pullquote>
        "The only edge I had was that I never needed the money back on a deadline."
    </x-sw.pullquote>
    <x-sw.prose>
        <p>
            That's the honest template: conviction in the business, a position size you can forget
            about, and no assumption of an exit date. Outcomes differ, and plenty of unlisted stories
            end flat or worse.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="pricing">How are unlisted share prices decided?</x-sw.article-h2>
    <x-sw.prose>
        <p>
            There is no exchange, so there is no single price and no live ticker. Dealers quote from
            the last few transactions they have seen, the valuation of the most recent primary round,
            and how much of a particular name is available that day. Demand and supply do the rest.
        </p>
        <p>
            Two dealers can be 3–6% apart on the same day, and the spread widens on thin names. Ask
            what recent transaction level a quote is based on — a counterparty who answers that plainly
            is usually the one worth dealing with. Compare at least two quotes before you commit.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="charges">Charges &amp; minimum lot size</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Most unlisted deals are quoted all-in: the price per share already includes the dealer's
            margin and there is no separate brokerage line. Your broker may levy a small off-market
            transfer or DP charge per instruction.
        </p>
        <p>
            Minimum lots vary widely with the share price — a few dozen shares on an expensive name, a
            thousand on a cheap one — so the practical entry ticket typically sits between ₹50,000 and
            ₹1,50,000. Settlement is fast once funds clear: same working day in most cases, next
            working day if the depository queue is slow.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="tax">Tax on unlisted shares (current rules)</x-sw.article-h2>
    <x-sw.prose>
        <p>
            While the share is unlisted, the holding period for long-term treatment is 24 months. Sell
            after 24 months and gains are long-term capital gains; sell inside 24 months and the gain
            is short-term, taxed at your slab rate. Once the company lists, listed-equity rules and
            holding-period thresholds apply from that point, and a pre-IPO lock-in may restrict when
            you can sell at all.
        </p>
        <p>
            Tax treatment changes with each Finance Act and depends on your own situation — verify the
            current position with your CA before you file. Our
            <a href="/blog/tax-on-unlisted-shares/" class="font-semibold text-primary underline underline-offset-4">
                full guide to tax on unlisted shares
            </a>
            goes deeper.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="mistakes">Common mistakes to avoid</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Paying into a personal account because the deal was \'closing today\'.',
        'Skipping the ISIN check in your own depository statement after delivery.',
        'Over-allocating to one illiquid name because the lot size pushed you there.',
        'Buying purely on IPO hype, with no view on how the business earns money.',
        'Assuming you can exit whenever you want — plan the position in years, not weeks.',
    ]" />

    <x-sw.article-h2 id="conclusion">Conclusion</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Buying unlisted shares in India is a documented, boring process when you do it in the right
            order: KYC first, price and lot in writing, payment only to a verified company account,
            demat credit, ISIN verified by you. The mechanics are the easy part.
        </p>
        <p>
            The hard part is judgment — picking a business worth owning, paying a defensible price, and
            sizing the position so illiquidity never forces your hand. If an IPO never arrives, you
            should still be comfortable holding what you bought.
        </p>
    </x-sw.prose>
</x-sw.blog-post-layout>
@endsection
