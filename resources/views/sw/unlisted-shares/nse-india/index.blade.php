@extends('layouts.sw')

@section('title', 'NSE India Unlisted Share Price ₹1,960 | Buy NSE Pre-IPO Shares | StockWitty')
@section('description', 'Live NSE India unlisted share price ₹1,960, fundamentals, financials, IPO roadmap and how to buy. WittyScore 8.5/10. Distributor, not investment advice.')

@php
$sections = [
    ['id' => 'price-chart', 'label' => 'Price Chart'],
    ['id' => 'about', 'label' => 'About'],
    ['id' => 'fundamentals', 'label' => 'Fundamentals'],
    ['id' => 'founders-take', 'label' => "Founder's Take"],
    ['id' => 'how-to-buy', 'label' => 'How to Buy'],
    ['id' => 'financials', 'label' => 'Financials'],
    ['id' => 'ipo-roadmap', 'label' => 'IPO Roadmap'],
    ['id' => 'faq', 'label' => 'FAQ'],
];

$series = [
    '1M' => [['label' => 'Wk 1', 'price' => 1908], ['label' => 'Wk 2', 'price' => 1935], ['label' => 'Wk 3', 'price' => 1922], ['label' => 'Wk 4', 'price' => 1948], ['label' => 'Now', 'price' => 1960]],
    '6M' => [['label' => 'Dec', 'price' => 2180], ['label' => 'Jan', 'price' => 2360], ['label' => 'Feb', 'price' => 2490], ['label' => 'Mar', 'price' => 2080], ['label' => 'Apr', 'price' => 1960], ['label' => 'May', 'price' => 1990]],
    '1Y' => [['label' => 'Jun 25', 'price' => 1705], ['label' => 'Sep 25', 'price' => 1880], ['label' => 'Dec 25', 'price' => 2180], ['label' => 'Feb 26', 'price' => 2490], ['label' => 'Apr 26', 'price' => 1960], ['label' => 'Jun 26', 'price' => 1960]],
    '3Y' => [['label' => '2023', 'price' => 640], ['label' => '2024', 'price' => 1120], ['label' => '2025', 'price' => 1705], ['label' => '2026', 'price' => 1960]],
    '5Y' => [['label' => '2021', 'price' => 210], ['label' => '2022', 'price' => 385], ['label' => '2023', 'price' => 640], ['label' => '2024', 'price' => 1120], ['label' => '2025', 'price' => 1705], ['label' => '2026', 'price' => 1960]],
    'Max' => [['label' => '2018', 'price' => 62], ['label' => '2019', 'price' => 98], ['label' => '2020', 'price' => 145], ['label' => '2021', 'price' => 210], ['label' => '2022', 'price' => 385], ['label' => '2023', 'price' => 640], ['label' => '2024', 'price' => 1120], ['label' => '2025', 'price' => 1705], ['label' => '2026', 'price' => 1960]],
];

$fundamentalCells = [
    ['Share Price', '₹1,960'], ['Lot Size', '250 shares'], ['52W High', '₹2,470'], ['52W Low', '₹1,705'],
    ['Market Cap', '₹4.85 L Cr'], ['P/E', '40.0x'], ['P/B', '15.76x'], ['Debt-to-Equity', '0.00'],
    ['ROE', '31.7%'], ['Book Value', '₹129.79'], ['Face Value', '₹1'], ['EPS (FY25)', '₹49'],
    ['Total Shares', '247.5 Cr'], ['Depository', 'NSDL & CDSL'], ['ISIN', 'INE721I01049'], ['PAN', 'AAACN1797L'],
    ['CIN', 'U67120MH1992PLC069769'], ['RTA', 'Link Intime'], ['Founded', '1992'], ['HQ', 'Mumbai'],
];

$financials = [
    'Yearly' => [
        'Income Statement' => ['cols' => ['FY22', 'FY23', 'FY24', 'FY25'], 'rows' => [
            ['label' => 'Revenue from operations', 'values' => ['6,202', '8,926', '12,285', '15,491']],
            ['label' => 'Other Income', 'values' => ['650', '674', '1,654', '2,036']],
            ['label' => 'Total Revenue', 'values' => ['6,852', '11,856', '16,434', '19,177'], 'strong' => true],
            ['label' => 'Employee Benefits Expenses', 'values' => ['352', '482', '632', '815']],
            ['label' => 'Other Expenses', 'values' => ['650', '674', '1,654', '2,036']],
        ]],
        'Balance Sheet' => ['cols' => ['FY22', 'FY23', 'FY24', 'FY25'], 'rows' => [
            ['label' => 'Total Assets', 'values' => ['18,420', '24,610', '31,480', '38,240'], 'strong' => true],
            ['label' => 'Cash & Equivalents', 'values' => ['4,210', '5,680', '6,940', '8,320']],
            ['label' => 'Total Debt', 'values' => ['0', '0', '0', '0']],
            ['label' => "Shareholders' Equity", 'values' => ['12,300', '17,130', '22,570', '27,990'], 'strong' => true],
        ]],
        'Cash Flow' => ['cols' => ['FY22', 'FY23', 'FY24', 'FY25'], 'rows' => [
            ['label' => 'Cash from Operations', 'values' => ['3,480', '6,120', '8,460', '10,120'], 'strong' => true],
            ['label' => 'Cash from Investing', 'values' => ['-2,140', '-3,860', '-5,210', '-6,480']],
            ['label' => 'Cash from Financing', 'values' => ['-980', '-1,420', '-2,010', '-2,360']],
            ['label' => 'Net Cash Flow', 'values' => ['360', '840', '1,240', '1,280'], 'strong' => true],
        ]],
    ],
    'Quarterly' => [
        'Income Statement' => ['cols' => ['Q1 FY26', 'Q4 FY25', 'Q3 FY25', 'Q2 FY25'], 'rows' => [
            ['label' => 'Revenue from operations', 'values' => ['4,180', '4,020', '3,890', '3,740']],
            ['label' => 'Other Income', 'values' => ['540', '520', '500', '480']],
            ['label' => 'Total Revenue', 'values' => ['4,720', '4,540', '4,390', '4,220'], 'strong' => true],
            ['label' => 'Employee Benefits Expenses', 'values' => ['228', '214', '205', '198']],
        ]],
        'Balance Sheet' => ['cols' => ['Q1 FY26', 'Q4 FY25', 'Q3 FY25', 'Q2 FY25'], 'rows' => [
            ['label' => 'Total Assets', 'values' => ['39,860', '38,240', '36,420', '34,910'], 'strong' => true],
            ['label' => 'Cash & Equivalents', 'values' => ['8,640', '8,320', '7,810', '7,420']],
            ['label' => "Shareholders' Equity", 'values' => ['29,320', '27,990', '26,480', '25,120'], 'strong' => true],
        ]],
        'Cash Flow' => ['cols' => ['Q1 FY26', 'Q4 FY25', 'Q3 FY25', 'Q2 FY25'], 'rows' => [
            ['label' => 'Cash from Operations', 'values' => ['2,810', '2,640', '2,510', '2,380'], 'strong' => true],
            ['label' => 'Net Cash Flow', 'values' => ['450', '390', '350', '340'], 'strong' => true],
        ]],
    ],
];

$steps = [
    ['n' => '01', 'icon' => 'file-check-2', 'title' => 'Submit KYC', 'body' => 'Share your CML copy + PAN + Cancelled Cheque + Aadhaar for verification. Takes ~30 minutes.'],
    ['n' => '02', 'icon' => 'landmark', 'title' => 'Transfer Payment', 'body' => 'Transfer to the verified StockWitty company account (never personal). UPI, NEFT, RTGS accepted.'],
    ['n' => '03', 'icon' => 'wallet', 'title' => 'Receive Shares', 'body' => 'Shares credited to your demat (CDSL or NSDL) the same day. Independent ISIN verification available.'],
];

$timeline = [
    ['date' => '30 Jan 2026', 'text' => 'SEBI grants long-awaited NOC after NSE pays ₹1,600 Cr settlement for the co-location case.'],
    ['date' => '6 Feb 2026', 'text' => 'NSE Board formally approves the IPO plan.'],
    ['date' => 'Mar 2026', 'text' => 'Writ petition filed in the Delhi High Court challenging NOC validity.'],
    ['date' => 'Apr 2026', 'text' => '20+ investment banks shortlisted.'],
    ['date' => 'Expected Jun 2026', 'text' => 'DRHP filing with SEBI.'],
    ['date' => 'Expected Q3/Q4 2026', 'text' => 'Listing on NSE and BSE.'],
];

$ipoFacts = [
    ['Issue Type', 'Offer for Sale (OFS)'], ['Major Sellers', 'LIC, SBI, Temasek + PSU/private'],
    ['Total Issue Size', '~$2.5 Bn (₹22,725 Cr)'], ['Independent Advisor', 'Rothschild & Co'],
    ['Probable Valuation', '₹5.19 L Cr (₹2,100+/share)'], ['Our IPO Price Estimate', '₹1,950 – ₹2,200/share'],
];

$badges = [
    ['icon' => 'star', 'title' => 'Trustpilot', 'sub' => 'Read our reviews'],
    ['icon' => 'badge-check', 'title' => 'Google Reviews', 'sub' => 'Rated by investors'],
    ['icon' => 'shield-check', 'title' => 'ISIN Verified', 'sub' => 'CDSL / NSDL demat'],
    ['icon' => 'check-circle-2', 'title' => 'CA Reviewed', 'sub' => 'Data checked for accuracy'],
    ['icon' => 'message-circle', 'title' => 'Human Support', 'sub' => 'Real people, no bots'],
];

$references = [
    ['n' => 1, 'label' => 'NSE India Annual Report FY25', 'href' => 'https://www.nseindia.com'],
    ['n' => 2, 'label' => 'SEBI — regulatory settlement & IPO no-objection, Jan 2026', 'href' => 'https://www.sebi.gov.in'],
    ['n' => 3, 'label' => 'CDSL / NSDL — ISIN & demat verification', 'href' => 'https://www.cdslindia.com'],
    ['n' => 4, 'label' => 'StockWitty WittyScore methodology', 'href' => '/wittyscore/'],
];

$faqs = [
    ['q' => 'What are unlisted shares?', 'a' => 'Unlisted shares are equity shares of a company that is not yet traded on the NSE or BSE. They change hands privately — between employees with ESOPs, early investors and buyers like you — at a negotiated price, and settle off-market into your own demat account.'],
    ['q' => 'How do I buy NSE India unlisted shares?', 'a' => "Share your CML copy, PAN, Aadhaar and a cancelled cheque for KYC, confirm quantity and price on your quote, then transfer the amount to StockWitty's verified company current account. We credit the shares off-market to your CDSL or NSDL demat, usually the same working day."],
    ['q' => 'Is the NSE IPO confirmed?', 'a' => 'No. SEBI issued its no-objection in January 2026 and the board approved an IPO plan, but the DRHP is still expected and a writ petition challenging the NOC is pending in the Delhi High Court. Treat the listing as likely-but-undated, not as a promise.'],
    ['q' => 'What is the minimum investment amount?', 'a' => 'The minimum lot for NSE India is 250 shares. At ₹1,960 per share that is ₹4,90,000 all-inclusive. Smaller exposure is possible in other unlisted names where lots start under ₹25,000.'],
    ['q' => 'How are shares delivered to my demat?', 'a' => 'Through an off-market delivery instruction from our depository account to yours. For payments confirmed before 2:00 PM on a working day, credit is usually the same day, and you can verify ISIN INE721I01049 independently on the CDSL or NSDL portal.'],
];

$SUMMARY = "NSE India is the country's largest stock exchange, trading at ₹1,960 per unlisted share as of June 2026. It runs a near-monopoly with roughly 99.9% market share in equity derivatives, posting FY25 revenue of ₹19,177 crore and a ₹12,188 crore profit — an exceptional 63% net margin. Its WittyScore is 8.5 out of 10 (Strong). SEBI granted its IPO no-objection in January 2026, with a listing possible in late 2026, though a Delhi High Court petition is pending. At about 40x earnings it isn't cheap, but a successful IPO at the rumoured ₹5 lakh crore valuation could reward patient investors. Unlisted shares are illiquid and high-risk.";
@endphp

@section('content')
<div class="min-h-screen bg-background" x-data="nsePricePage('nse-india')" data-companies="{{ json_encode(config('sw.showcase_companies')) }}">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Unlisted Shares', 'href' => '/unlisted-shares/'], ['label' => 'NSE India']]" />
    </div>

    <main>
        <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
            <div class="flex items-start gap-3 rounded-2xl border border-dashed border-amber-500/60 bg-amber-50 p-4 text-sm text-amber-900">
                <x-sw.icon name="alert-triangle" class="mt-0.5 size-4 shrink-0" />
                <p><strong>Illustrative demo data —</strong> figures on this page are for layout purposes and should be verified against official filings before publishing.</p>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 rounded-2xl border border-border bg-green-50 p-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <label class="mb-1.5 block text-[11px] font-bold tracking-widest text-muted-foreground uppercase">Choose a company</label>
                    @include('partials.sw._company-dropdown')
                </div>
                <p class="text-xs font-semibold text-muted-foreground">
                    Live company data is illustrative in this demo. Profile, financials and IPO roadmap below remain NSE India–specific.
                </p>
            </div>
        </div>

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[1.35fr_1fr]">
                <div>
                    <x-sw.reveal>
                        <div class="flex items-start gap-4">
                            <span class="grid size-16 shrink-0 place-items-center rounded-2xl bg-primary text-xl font-bold text-primary-foreground shadow-soft" x-text="company.initials"></span>
                            <div>
                                <h1 class="text-2xl leading-tight font-bold text-foreground sm:text-4xl" x-text="company.slug === 'nse-india' ? 'National Stock Exchange (NSE India)' : company.name"></h1>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <template x-for="c in [company.tag, 'Unlisted', 'Active']" :key="c">
                                        <span class="rounded-full border border-border bg-green-50 px-3 py-1 text-xs font-semibold text-primary" x-text="c"></span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2">
                            <span x-show="company.slug === 'nse-india'" style="display: none;" class="rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground">
                                ISIN: <span class="text-foreground">INE721I01049</span>
                            </span>
                            <span class="rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground">
                                WittyScore: <span class="text-foreground" x-text="company.wittyScore + '/10'"></span>
                            </span>
                            <span class="rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground">
                                Min lot: <span class="text-foreground" x-text="company.lot.toLocaleString('en-IN') + ' sh'"></span>
                            </span>
                            <a href="https://www.nseindia.com" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-primary hover:bg-green-50">
                                Website <x-sw.icon name="external-link" class="size-3.5" />
                            </a>
                        </div>
                    </x-sw.reveal>

                    <x-sw.reveal :delay="0.08">
                        <div class="mt-7 rounded-3xl border border-border bg-beige p-6 shadow-soft sm:p-7">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <span class="rounded-full bg-primary px-3 py-1 text-[11px] font-bold tracking-widest text-primary-foreground uppercase">AI Summary</span>
                                <button type="button" @click="window.speechSynthesis && window.speechSynthesis.speak(new SpeechSynthesisUtterance(document.getElementById('ai-summary-text').textContent))"
                                        class="inline-flex items-center gap-2 rounded-xl border border-primary/25 bg-card px-3.5 py-2 text-xs font-bold text-primary transition-colors hover:bg-green-50 sm:text-sm">
                                    <x-sw.icon name="volume-2" class="size-4" />
                                    Listen
                                </button>
                            </div>
                            <h2 class="mt-4 text-lg font-bold text-foreground sm:text-xl">NSE India unlisted shares, in 30 seconds</h2>
                            <p id="ai-summary-text" class="mt-3 text-sm leading-relaxed text-muted-foreground sm:text-base">{{ $SUMMARY }}</p>
                            <p class="mt-4 border-t border-border pt-3 text-xs font-semibold text-muted-foreground">
                                Reviewed by StockWitty Research (CA-reviewed) · Updated June 2026 · Sources: NSE annual report, SEBI filings
                            </p>
                            <div class="mt-5 flex flex-wrap items-center gap-2" x-data="{ copied: false }">
                                <a href="https://wa.me/?text=NSE%20India%20unlisted%20share%20price%20and%20how%20to%20buy%20%E2%80%94%20StockWitty" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground transition-colors hover:border-primary/40 hover:text-primary">
                                    <x-sw.icon name="message-circle" class="size-3.5" /> WhatsApp
                                </a>
                                <a href="https://twitter.com/intent/tweet?text=NSE%20India%20unlisted%20share%20price%20and%20how%20to%20buy" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground transition-colors hover:border-primary/40 hover:text-primary">
                                    <x-sw.icon name="arrow-up-right" class="size-3.5" /> X
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground transition-colors hover:border-primary/40 hover:text-primary">
                                    LinkedIn
                                </a>
                                <button type="button" @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="inline-flex items-center gap-1.5 rounded-full border border-border bg-card px-3 py-1.5 text-xs font-semibold text-muted-foreground transition-colors hover:border-primary/40 hover:text-primary">
                                    <x-sw.icon name="copy" class="size-3.5" />
                                    <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                                </button>
                            </div>
                        </div>
                    </x-sw.reveal>
                </div>

                <div id="trade" class="scroll-mt-24 lg:sticky lg:top-24">
                    <div class="space-y-5">
                        <div class="bg-price-card relative overflow-hidden rounded-3xl p-6 shadow-soft sm:p-8">
                            <div class="pointer-events-none absolute -top-24 -right-16 size-72 rounded-full bg-mint/20 blur-3xl"></div>
                            <div class="relative flex items-center justify-between text-[11px] font-bold tracking-widest text-mint-bright uppercase">
                                <span>Current Price</span>
                                <span class="inline-flex items-center gap-1.5 text-white/70">
                                    <x-sw.icon name="clock" class="size-3.5" /> 27 Jun 2026
                                </span>
                            </div>
                            <p class="relative mt-4 text-5xl font-bold text-white sm:text-6xl" x-countup="{ value: company.price, prefix: '₹', duration: 1200 }">₹0</p>
                            <p class="relative mt-2 text-sm font-semibold text-mint-bright">
                                <span x-text="company.changePct >= 0 ? '▲' : '▼'"></span>
                                ₹<span x-text="Math.abs(company.changeAbs)"></span>
                                (<span x-text="(company.changePct >= 0 ? '+' : '−') + Math.abs(company.changePct).toFixed(2) + '%'"></span>) this week
                            </p>
                            <p class="relative mt-4 text-xs font-semibold text-white/75">
                                Min lot: <span x-text="company.lot.toLocaleString('en-IN')"></span> shares · Total: ₹<span x-text="(company.lot * company.price).toLocaleString('en-IN')"></span>
                            </p>
                            <a href="https://wa.me/919999999999" target="_blank" rel="noopener noreferrer"
                               class="relative mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-mint to-mint-bright px-4 py-3 text-sm font-bold text-[#052a14] transition-transform hover:-translate-y-0.5">
                                <x-sw.icon name="message-circle" class="size-4" />
                                Get Quote on WhatsApp
                            </a>
                            <a href="#lead" class="relative mt-3 block text-center text-xs font-semibold text-mint-bright underline-offset-4 hover:underline">
                                Fill inquiry form instead →
                            </a>
                        </div>

                        <div x-data="{ side: 'Buy', qty: company.lot }" x-effect="qty = company.lot" class="rounded-3xl border border-border bg-card p-6 shadow-soft">
                            <h2 class="text-lg font-bold text-foreground">Trade</h2>
                            <div role="group" aria-label="Order side" class="mt-4 grid grid-cols-2 gap-1 rounded-xl border border-border bg-green-50 p-1">
                                <button type="button" @click="side = 'Buy'" :aria-pressed="side === 'Buy'"
                                        :class="side === 'Buy' ? 'bg-primary text-primary-foreground shadow-soft' : 'text-muted-foreground hover:text-primary'"
                                        class="rounded-lg px-4 py-2 text-sm font-bold transition-colors">Buy</button>
                                <button type="button" @click="side = 'Sell'" :aria-pressed="side === 'Sell'"
                                        :class="side === 'Sell' ? 'bg-primary text-primary-foreground shadow-soft' : 'text-muted-foreground hover:text-primary'"
                                        class="rounded-lg px-4 py-2 text-sm font-bold transition-colors">Sell</button>
                            </div>

                            <label class="mt-5 block text-[11px] font-bold tracking-widest text-muted-foreground uppercase">Quantity</label>
                            <div class="mt-2 flex items-center gap-2">
                                <button type="button" aria-label="Decrease quantity" @click="qty = Math.max(1, qty - 1)"
                                        class="grid size-10 shrink-0 place-items-center rounded-xl border border-border bg-green-50 text-primary transition-colors hover:border-primary/50">
                                    <x-sw.icon name="minus" class="size-4" />
                                </button>
                                <input type="number" inputmode="numeric" min="1" x-model.number="qty"
                                       class="h-10 min-w-0 flex-1 rounded-xl border border-border bg-background px-3 text-center text-base font-bold text-foreground outline-none" />
                                <button type="button" aria-label="Increase quantity" @click="qty = qty + 1"
                                        class="grid size-10 shrink-0 place-items-center rounded-xl border border-border bg-green-50 text-primary transition-colors hover:border-primary/50">
                                    <x-sw.icon name="plus" class="size-4" />
                                </button>
                            </div>

                            <div class="mt-5 flex items-baseline justify-between rounded-xl border border-border bg-green-50 px-4 py-3">
                                <span class="text-[11px] font-bold tracking-widest text-muted-foreground uppercase">Total Amount</span>
                                <span class="text-xl font-bold text-primary sm:text-2xl" x-text="'₹' + (Math.max(qty, 0) * company.price).toLocaleString('en-IN')"></span>
                            </div>
                            <p class="mt-2 text-xs font-semibold text-muted-foreground">
                                Min lot: <span x-text="company.lot.toLocaleString('en-IN')"></span> shares · ₹<span x-text="company.price.toLocaleString('en-IN')"></span> per share
                            </p>

                            <div x-data="{ submitted: false }">
                                <button type="button" @click="submitted = true"
                                        class="mt-5 w-full rounded-xl bg-gradient-to-r from-[#0C7031] to-[#11F1C4] px-4 py-3 text-sm font-bold text-[#052a14] transition-transform hover:-translate-y-0.5">
                                    Confirm <span x-text="side"></span> Order
                                </button>
                                <div x-show="submitted" style="display: none;" class="mt-4 space-y-3 rounded-xl border border-border bg-beige px-4 py-3">
                                    <p class="text-xs font-semibold text-foreground">We'll confirm live availability &amp; price and get back to you.</p>
                                    <a :href="'https://wa.me/919999999999?text=' + encodeURIComponent('Hi StockWitty, I would like to ' + side.toLowerCase() + ' ' + Math.max(qty,0) + ' shares of ' + company.name + '.')"
                                       target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-bold text-primary-foreground transition-transform hover:-translate-y-0.5">
                                        <x-sw.icon name="message-circle" class="size-4" />
                                        Continue on WhatsApp
                                    </a>
                                    <p class="text-[11px] text-muted-foreground">StockWitty is a distributor — this is a quote/lead request, not instant execution.</p>
                                </div>
                            </div>

                            <div class="mt-6 border-t border-border pt-5">
                                <p class="text-xs font-bold tracking-wide text-muted-foreground uppercase">Quick switch</p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <template x-for="c in companies.slice(0, 6)" :key="c.slug">
                                        <button type="button" @click="selectCompany(c.slug)"
                                                :class="c.slug === slug ? 'border-primary bg-primary text-primary-foreground' : 'border-border text-muted-foreground hover:border-mint hover:text-primary'"
                                                class="rounded-full border px-3 py-1.5 text-xs font-bold transition-all" x-text="c.initials"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-y border-border bg-green-50 py-6">
            <div class="mx-auto grid max-w-7xl grid-cols-2 gap-3 px-4 sm:grid-cols-4 sm:px-6 lg:grid-cols-8 lg:px-8">
                <template x-for="s in [
                    { label: 'Price', count: true, value: company.price, prefix: '₹' },
                    { label: 'Market Cap', count: false, text: company.mktCap },
                    { label: 'P/E', count: false, text: company.pe },
                    { label: 'WittyScore', count: true, value: company.wittyScore, decimals: 1, suffix: '/10' },
                    { label: '52W High', count: true, value: company.high52, prefix: '₹' },
                    { label: '52W Low', count: true, value: company.low52, prefix: '₹' },
                    { label: 'Min Lot', count: true, value: company.lot, suffix: ' sh' },
                    { label: 'Lot Value', count: false, text: '₹' + (company.lot * company.price).toLocaleString('en-IN') },
                ]" :key="s.label">
                    <div class="rounded-xl border border-border bg-card px-3 py-3 text-center shadow-soft">
                        <p class="text-sm font-bold text-primary sm:text-base">
                            <span x-show="s.count" x-countup="{ value: s.value, decimals: s.decimals || 0, prefix: s.prefix || '', suffix: s.suffix || '' }">0</span>
                            <span x-show="!s.count" x-text="s.text"></span>
                        </p>
                        <p class="mt-1 text-[11px] font-semibold text-muted-foreground" x-text="s.label"></p>
                    </div>
                </template>
            </div>
        </section>

        <div x-data="tocSpy({{ Illuminate\Support\Js::from(array_column($sections, 'id')) }})" class="sticky top-16 z-30 border-y border-border bg-background/85 backdrop-blur">
            <div class="mx-auto max-w-7xl overflow-x-auto px-4 sm:px-6 lg:px-8">
                <nav aria-label="Page sections" class="flex gap-1 py-2 whitespace-nowrap">
                    @foreach ($sections as $s)
                        <a href="#{{ $s['id'] }}" @click.prevent="scrollToSection('{{ $s['id'] }}')"
                           :class="active === '{{ $s['id'] }}' ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-green-50 hover:text-primary'"
                           class="rounded-full px-3.5 py-2 text-xs font-bold transition-colors sm:text-sm">
                            {{ $s['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <section id="price-chart" class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Price movement" title="NSE India unlisted price history"
                                       subtitle="Indicative levels from dealer bids and asks. Illustrative demo data." />
                <div class="mt-8 rounded-3xl border border-border bg-card p-5 shadow-soft sm:p-7" x-data="nsePriceChart()" data-series="{{ json_encode($series) }}">
                    <x-sw.chips :options="['1M', '6M', '1Y', '3Y', '5Y', 'Max']" model="period" />
                    <div class="mt-6 h-[280px] w-full sm:h-[360px]" x-effect="period; updateChart()">
                        <canvas x-ref="chart"></canvas>
                    </div>
                    <p class="mt-4 text-xs text-muted-foreground">
                        Indicative dealer levels, illustrative for layout. Unlisted prices are negotiated, not exchange-quoted.
                    </p>
                </div>
            </div>
        </section>

        <section id="about" class="bg-green-50/60 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="About the company" title="About NSE India" />
                <div class="mt-6 max-w-3xl space-y-4 text-base leading-relaxed text-muted-foreground">
                    <p>
                        NSE is India's largest stock exchange and a leading financial-market-infrastructure
                        institution, running a fully automated screen-based trading system and home to the NIFTY
                        50 index.
                    </p>
                    <p>
                        It is where Reliance, TCS, HDFC Bank and 2,700+ other companies trade, and it earns from
                        transaction fees, clearing, listing, data and index services — effectively a
                        near-monopoly toll booth on Indian capital markets.
                    </p>
                </div>
                <a href="/unlisted-shares/nse-india/about/" class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-primary underline-offset-4 hover:underline">
                    Read the full company profile <x-sw.icon name="arrow-right" class="size-4" />
                </a>
            </div>
        </section>

        <section id="fundamentals" class="bg-mesh py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Fundamentals" title="Key metrics and identifiers for NSE India Limited Unlisted Shares" />
                <div class="mt-8 rounded-3xl border border-border bg-card p-5 shadow-soft sm:p-7">
                    <dl class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach ($fundamentalCells as [$k, $v])
                            <div class="rounded-xl border border-border bg-green-50/70 px-4 py-3">
                                <dt class="text-[11px] font-semibold tracking-wide text-muted-foreground uppercase">{{ $k }}</dt>
                                <dd class="mt-1 text-sm font-bold break-words text-foreground">{{ $v }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
                <p class="mt-3 text-xs text-muted-foreground">Identifiers and figures are illustrative — verify against official filings.</p>
            </div>
        </section>

        <section id="founders-take" class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Founder's Take" title="The honest story of NSE India" />
                <p class="mt-5 max-w-2xl text-base text-muted-foreground">
                    NSE has been "about to IPO" since 2016 — here's the short version.
                </p>
                <x-sw.reveal>
                    <blockquote class="mt-6 max-w-3xl border-l-4 border-amber-400 bg-beige px-6 py-5 text-lg font-semibold text-foreground italic">
                        "NSE going public is like that one friend who's been saying they'll quit their job and
                        start a business for a decade. Eventually they will — just don't bet your rent on the
                        timeline."
                    </blockquote>
                </x-sw.reveal>
                <x-sw.reveal :delay="0.08">
                    <div class="bg-price-card mt-6 max-w-3xl rounded-2xl p-6 text-sm leading-relaxed text-white shadow-soft sm:text-base">
                        <span class="text-[11px] font-bold tracking-widest text-mint-bright uppercase">Our verdict</span>
                        <p class="mt-2">
                            At ₹1,960 you're buying a ~99.9% market-share business at roughly 40x earnings —
                            expensive on paper, potentially cheap if the IPO lands by late 2026. WittyScore 8.5/10.
                        </p>
                    </div>
                </x-sw.reveal>
                <a href="/unlisted-shares/nse-india/thesis/" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-bold text-primary-foreground transition-transform hover:-translate-y-0.5">
                    Read our full thesis &amp; WittyScore breakdown <x-sw.icon name="arrow-right" class="size-4" />
                </a>
            </div>
        </section>

        <section id="financials" class="bg-green-50/60 py-14 sm:py-20" x-data="{ range: 'Yearly', tab: 'Income Statement' }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Financials" title="Financial Performance (₹ Cr)"
                                       subtitle="Illustrative figures for explanation — verify against NSE's audited annual reports." />
                <div class="mt-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <x-sw.chips :options="['Yearly', 'Quarterly']" model="range" />
                        <x-sw.chips :options="['Income Statement', 'Balance Sheet', 'Cash Flow']" model="tab" />
                    </div>
                    <div class="mt-6 overflow-x-auto rounded-2xl border border-border shadow-soft">
                        @foreach ($financials as $rangeKey => $tabs)
                            @foreach ($tabs as $tabKey => $table)
                                <table x-show="range === '{{ $rangeKey }}' && tab === '{{ $tabKey }}'" style="display: none;"
                                       class="w-full min-w-[640px] border-collapse text-left text-sm">
                                    <thead>
                                        <tr class="bg-green-50">
                                            <th class="px-4 py-3 text-xs font-bold tracking-wide text-primary uppercase">Particulars</th>
                                            @foreach ($table['cols'] as $c)
                                                <th class="px-4 py-3 text-right text-xs font-bold tracking-wide text-primary uppercase">{{ $c }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($table['rows'] as $r)
                                            <tr class="border-t border-border bg-card">
                                                <td class="px-4 py-3 {{ !empty($r['strong']) ? 'font-bold text-primary' : 'font-semibold text-foreground' }}">{{ $r['label'] }}</td>
                                                @foreach ($r['values'] as $v)
                                                    <td class="px-4 py-3 text-right tabular-nums {{ !empty($r['strong']) ? 'font-bold text-primary' : 'text-muted-foreground' }}">{{ $v }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="how-to-buy" class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Process" title="How to buy unlisted shares" align="center" />
                <div class="mt-10 grid gap-6 lg:grid-cols-3">
                    @foreach ($steps as $i => $s)
                        <x-sw.reveal :delay="$i * 0.12" class="card-lift rounded-2xl border border-border bg-card p-7 text-center shadow-soft">
                            <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-primary text-lg font-bold text-primary-foreground">{{ $s['n'] }}</span>
                            <x-sw.icon :name="$s['icon']" class="mx-auto mt-5 size-6 text-mint" />
                            <h3 class="mt-3 text-lg font-bold text-foreground">{{ $s['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $s['body'] }}</p>
                        </x-sw.reveal>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="ipo-roadmap" class="bg-green-50/60 py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="IPO Roadmap" title="NSE IPO — where things stand" />
                <ol class="mt-8 max-w-3xl border-l-2 border-green-100 pl-6">
                    @foreach ($timeline as $t)
                        <li class="relative pb-7 last:pb-0">
                            <span class="absolute -left-[31px] mt-1 grid size-4 place-items-center rounded-full border-2 border-background bg-primary"></span>
                            <p class="inline-flex items-center gap-2 text-sm font-bold text-primary">
                                <x-sw.icon name="calendar-clock" class="size-4" />
                                {{ $t['date'] }}
                            </p>
                            <p class="mt-1.5 text-sm leading-relaxed text-muted-foreground">{{ $t['text'] }}</p>
                        </li>
                    @endforeach
                </ol>

                <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($ipoFacts as [$k, $v])
                        <div class="rounded-2xl border border-border bg-card p-5 shadow-soft">
                            <p class="text-[11px] font-semibold tracking-wide text-muted-foreground uppercase">{{ $k }}</p>
                            <p class="mt-1.5 text-sm font-bold text-foreground">{{ $v }}</p>
                        </div>
                    @endforeach
                </div>
                <p class="mt-3 text-xs text-muted-foreground">IPO timeline, sizing and valuation figures are illustrative and subject to change.</p>
            </div>
        </section>

        <section id="faq" class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="FAQ" title="Buying NSE India unlisted shares" align="center" />
                <div class="mx-auto mt-8 max-w-3xl">
                    @foreach ($faqs as $f)
                        <details class="sw-faq-item mb-3 overflow-hidden rounded-2xl border border-border bg-card px-5 shadow-soft transition-colors">
                            <summary class="flex cursor-pointer items-center justify-between gap-3 py-5 text-left text-base font-bold text-foreground">
                                {{ $f['q'] }}
                                <x-sw.icon name="chevron-down" class="sw-faq-chevron size-4 shrink-0 text-muted-foreground" />
                            </summary>
                            <p class="pb-5 text-sm leading-relaxed text-muted-foreground">{{ $f['a'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="border-y border-border bg-green-50 py-8">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-center gap-3 px-4 sm:px-6 lg:px-8">
                @foreach ($badges as $b)
                    <div class="inline-flex items-center gap-2.5 rounded-2xl border border-border bg-card px-4 py-3 shadow-soft">
                        <x-sw.icon :name="$b['icon']" class="size-5 shrink-0 text-primary" />
                        <span>
                            <span class="block text-sm font-bold text-foreground">{{ $b['title'] }}</span>
                            <span class="block text-xs text-muted-foreground">{{ $b['sub'] }}</span>
                        </span>
                    </div>
                @endforeach
            </div>
        </section>

        <section id="lead" class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.section-heading eyebrow="Who wrote this &amp; sources" title="Author &amp; References" />
                <div class="mt-8 grid gap-6 lg:grid-cols-2">
                    <div class="rounded-3xl border border-border bg-card p-6 shadow-soft">
                        <div class="flex items-center gap-4">
                            <span class="grid size-14 place-items-center rounded-2xl bg-primary text-base font-bold text-primary-foreground">SW</span>
                            <div>
                                <p class="text-base font-bold text-foreground">StockWitty Research</p>
                                <p class="text-sm text-muted-foreground">Independent research on unlisted &amp; pre-IPO shares · CA-reviewed</p>
                            </div>
                        </div>
                        <p class="mt-4 flex items-start gap-2 rounded-xl border border-dashed border-amber-500/60 bg-amber-50 px-4 py-3 text-xs text-amber-900">
                            <x-sw.icon name="user-round" class="mt-0.5 size-4 shrink-0" />
                            <span>Editable placeholder: add the real author name and reviewing CA's credentials here before publishing.</span>
                        </p>
                    </div>

                    <div class="rounded-3xl border border-border bg-card p-6 shadow-soft">
                        <p class="text-sm font-bold text-foreground">References</p>
                        <ol class="mt-3 space-y-2.5 text-sm text-muted-foreground">
                            @foreach ($references as $r)
                                <li class="flex gap-2">
                                    <span class="font-bold text-primary">{{ $r['n'] }}.</span>
                                    <a href="{{ $r['href'] }}" target="_blank" rel="noopener noreferrer" class="underline-offset-4 hover:text-primary hover:underline">{{ $r['label'] }}</a>
                                </li>
                            @endforeach
                        </ol>
                        <p class="mt-4 border-t border-border pt-3 text-xs text-muted-foreground">
                            Last fact-checked June 2026. Figures are illustrative where noted and should be verified against official filings before investing.
                        </p>
                    </div>
                </div>

                <p class="mt-8 rounded-2xl border border-border bg-green-50 px-5 py-4 text-xs leading-relaxed text-muted-foreground">
                    <strong class="text-foreground">Disclaimer:</strong> StockWitty is a distributor of
                    unlisted and pre-IPO shares, not a SEBI-registered investment adviser. Unlisted shares are
                    illiquid and high-risk, prices are negotiated and an IPO may be delayed or never happen.
                    Nothing on this page is investment advice.
                </p>
            </div>
        </section>
    </main>
</div>
@endsection
