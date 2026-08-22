@extends('layouts.sw')

@section('title', 'Is It Safe to Buy Unlisted Shares? What Every Investor Should Know (2026) | StockWitty')
@section('description', "Unlisted shares are legal in India — but 'risky' and 'unsafe' are different things. How to buy safely, the red flags and scams to avoid, and how genuine platforms protect you.")

@php
$toc = [
    ['id' => 'legal', 'label' => 'Are unlisted shares legal?'],
    ['id' => 'risky-vs-unsafe', 'label' => 'Risky vs unsafe'],
    ['id' => 'safely', 'label' => 'How to buy safely'],
    ['id' => 'red-flags', 'label' => 'Red flags & scams'],
    ['id' => 'platforms', 'label' => 'How genuine platforms protect you'],
    ['id' => 'sources', 'label' => 'Sources & references'],
    ['id' => 'faq', 'label' => 'FAQ'],
];

$faqs = [
    ['tab' => 'Basics', 'q' => 'Is it legal to buy unlisted shares in India?', 'a' => 'Yes. Buying shares of an unlisted company is legal for resident individuals. The shares are transferred privately and delivered as an off-market transfer through CDSL or NSDL into your demat account.'],
    ['tab' => 'Safety', 'q' => 'How do I avoid unlisted share scams?', 'a' => 'Refuse any guaranteed-return or assured-IPO claim, pay only into a verified company or escrow bank account matching your invoice, never transact under time pressure, and verify the ISIN and quantity in your own depository statement after delivery.'],
    ['tab' => 'Process', 'q' => 'Should I pay into a personal account?', 'a' => "Never. Legitimate transactions are settled into a company or escrow bank account whose name matches the entity on your contract note or invoice. A request to pay an individual's account, UPI handle or wallet is the single clearest warning sign in this market."],
    ['tab' => 'Process', 'q' => 'How do I verify I actually received the shares?', 'a' => 'Log in to your own CDSL (easi/easiest) or NSDL (IDeAS) account, or check the statement your broker sends, and confirm the ISIN, company name and quantity. Do not accept a screenshot or PDF forwarded by the counterparty as proof.'],
];
@endphp

@section('content')
<x-sw.blog-post-layout
    :crumbs="[['label' => 'Home', 'href' => '/'], ['label' => 'Blog', 'href' => '/blog/'], ['label' => 'Analysis', 'href' => '/blog/'], ['label' => 'Is It Safe to Buy Unlisted Shares?']]"
    :chips="['Unlisted Shares', 'Safety', '2026']"
    heroIcon="shield-check"
    title="Is It Safe to Buy Unlisted Shares? What Every Investor Should Know (2026)"
    description="Unlisted shares are legal in India — but 'risky' and 'unsafe' are different things. How to buy safely, the red flags and scams to avoid, and how genuine platforms protect you."
    authorLine="StockWitty Research · CA-reviewed"
    dateLabel="August 2026"
    readLabel="8 min read"
    :toc="$toc"
    :takeaways="[
        'Buying and selling unlisted shares is legal in India for resident individuals.',
        '\'Risky\' describes the investment; \'unsafe\' describes a bad process — don\'t confuse the two.',
        'Pay only into a verified company or escrow account matching the name on your invoice.',
        'Verify the ISIN and quantity yourself in your own CDSL/NSDL statement after delivery.',
        'Any guaranteed return, assured IPO date or same-day pressure tactic is a reason to walk away.',
    ]"
    :video="['caption' => 'Watch: Buying unlisted shares safely — the checks that matter', 'note' => 'Replace with your StockWitty YouTube video']"
    :faqTabs="['Basics', 'Safety', 'Process']"
    :faqs="$faqs"
    :sources="[
        ['label' => 'SEBI — investor cautions & regulations', 'href' => 'https://www.sebi.gov.in/'],
        ['label' => 'SEBI SCORES — investor complaints', 'href' => 'https://scores.sebi.gov.in/'],
        ['label' => 'CDSL — Central Depository Services', 'href' => 'https://www.cdslindia.com/'],
        ['label' => 'NSDL — National Securities Depository', 'href' => 'https://nsdl.co.in/'],
        ['label' => 'Income Tax Department, India', 'href' => 'https://www.incometax.gov.in/'],
    ]"
    :related="[
        ['title' => 'How to Buy Unlisted Shares in India', 'href' => '/blog/how-to-buy-unlisted-shares/', 'category' => 'Buying Guide', 'read' => '10 min read'],
        ['title' => 'Risks of Investing in Unlisted Shares', 'href' => '/blog/risks-of-investing-in-unlisted-shares/', 'category' => 'Risk', 'read' => '8 min read'],
        ['title' => 'How to Sell Unlisted Shares in India', 'href' => '/blog/how-to-sell-unlisted-shares/', 'category' => 'Selling Guide', 'read' => '8 min read'],
    ]"
    :leadForm="['heading' => 'Want to buy safely? Talk to us first.', 'subtext' => 'A StockWitty specialist will walk you through KYC, how settlement works and what to verify at each step — before you transfer a rupee. No obligation to transact.']"
>
    <x-slot:intro>
        <p>
            "Is it safe?" is the first question almost every investor asks about unlisted shares, and
            it is usually two questions wearing one coat. One is about the transaction: will I
            actually receive the shares I paid for? The other is about the investment: could I lose
            money?
        </p>
        <p>
            The answers are genuinely different. The transaction can be made close to watertight with
            a handful of non-negotiable checks. The investment cannot be made safe at all — it is
            illiquid, thinly disclosed equity, and no process fixes that.
        </p>
        <p>
            This piece separates the two, then gives you the checklist we would use ourselves before
            wiring money for an unlisted name.
        </p>
    </x-slot:intro>

    <x-sw.article-h2 id="legal">Are unlisted shares legal in India?</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Yes. There is nothing unusual or grey about owning equity in a company that has not listed —
            most companies in India are unlisted. Transfers happen privately and are recorded by CDSL or
            NSDL as off-market transfers, using the same depository infrastructure that holds your
            listed shares.
        </p>
        <p>
            What is different is the regulatory surface. A listed company files quarterly results and
            continuous disclosures under SEBI's listing rules. An unlisted company files far less, on a
            slower clock. Legal, then — but with less information reaching you as a shareholder.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="risky-vs-unsafe">Risky vs unsafe — the key distinction</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Risky means the outcome is uncertain: the price can fall, the IPO can slip by years, the
            business can disappoint, and you may not be able to exit when you want. That is inherent to
            the asset and it does not go away with a better counterparty.
        </p>
        <p>
            Unsafe means the process can fail you: money sent to the wrong account, shares that never
            arrive, a "quote" with no transaction behind it. That is entirely avoidable, and it is where
            your diligence actually changes the outcome.
        </p>
    </x-sw.prose>
    <x-sw.pullquote>
        You cannot make unlisted shares safe. You can make the transaction boring — and boring is the goal.
    </x-sw.pullquote>

    <x-sw.article-h2 id="safely">How to buy unlisted shares safely</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Pay only into a verified company or escrow bank account whose name matches the entity on your contract note or invoice.',
        'Get the price, minimum lot, total consideration and settlement timeline in writing before you pay.',
        'Verify the ISIN, company name and quantity in your own CDSL/NSDL statement after delivery — not from a forwarded screenshot.',
        'Confirm the counterparty\'s credentials: registered entity name, CIN, GST details and a working office address you can check.',
        'Fund the purchase from your own bank account — third-party payments should never be requested or accepted.',
        'Keep every document: invoice, bank advice, DIS acknowledgement and the depository statement showing the credit.',
    ]" />
    <x-sw.callout title="One rule beats all the others">
        If a counterparty ever asks you to pay an individual, a UPI handle or a wallet — for any
        reason, however senior the person or urgent the deadline — stop the transaction there. No
        legitimate unlisted deal requires it.
    </x-sw.callout>

    <x-sw.article-h2 id="red-flags">Red flags &amp; scams to avoid</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Guaranteed returns or \'assured\' listing gains — nobody can promise either, and offering to is a warning in itself.',
        'A confident IPO date. Listings depend on regulatory approval and market conditions; even filed companies slip.',
        'Payment to a personal account, UPI ID or wallet.',
        'Pressure tactics: \'the allocation closes tonight\', \'only two lots left at this price\'.',
        'Quotes with no reference transaction — a genuine dealer can tell you what recent level the price is based on.',
        'Refusal to share the ISIN, or discouraging you from checking your own depository statement.',
        'Unsolicited WhatsApp or Telegram groups pushing a single unlisted name with screenshots as evidence.',
    ]" />

    <x-sw.article-h2 id="platforms">How genuine platforms protect you</x-sw.article-h2>
    <x-sw.prose>
        <p>
            A credible distributor makes its own process auditable. That means KYC in both directions,
            settlement into a named company or escrow account, a contract note per transaction, and
            delivery through CDSL or NSDL that you can independently verify — not a promise you have to
            take on trust.
        </p>
        <p>
            It also means honest framing. StockWitty is a distributor, not a SEBI-registered investment
            adviser: we can explain a company, a price and a risk, and we will tell you when we think a
            name looks expensive — but we cannot promise you an outcome, and any figure we publish is
            illustrative until you verify it against official filings.
        </p>
    </x-sw.prose>
</x-sw.blog-post-layout>
@endsection
