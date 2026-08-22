@extends('layouts.sw')

@section('title', 'NSE India: Company Profile, Business Verticals & How It Works | StockWitty')
@section('description', 'A clear profile of NSE India (National Stock Exchange) — its business verticals, revenue segments, history, industry position, shareholding, SWOT and FAQs. Company information, not investment advice.')

@php
$toc = [
    ['id' => 'overview', 'label' => 'Overview of the company'],
    ['id' => 'verticals', 'label' => 'Business verticals'],
    ['id' => 'operations', 'label' => 'What NSE does & how it operates'],
    ['id' => 'revenue', 'label' => 'Revenue segments'],
    ['id' => 'geography', 'label' => 'Geographical presence'],
    ['id' => 'history', 'label' => 'History & evolution'],
    ['id' => 'industry', 'label' => 'Industry position'],
    ['id' => 'shareholding', 'label' => 'Shareholding framework'],
    ['id' => 'interest', 'label' => 'Why NSE draws investor interest'],
    ['id' => 'swot', 'label' => 'SWOT analysis'],
    ['id' => 'landscape', 'label' => 'Market landscape & reach'],
    ['id' => 'products', 'label' => 'Products & services'],
    ['id' => 'competitive', 'label' => 'Competitive strength'],
    ['id' => 'sources', 'label' => 'Sources & references'],
    ['id' => 'faq', 'label' => 'FAQ'],
];

$faqs = [
    ['tab' => 'Basics', 'q' => 'What is NSE India?', 'a' => "The National Stock Exchange of India (NSE) is India's largest stock exchange by turnover and a core piece of the country's financial market infrastructure. It runs a fully automated, screen-based electronic trading system across equities, derivatives, currency and debt, and it owns and maintains the NIFTY 50 index."],
    ['tab' => 'Investment', 'q' => 'Can retail investors buy NSE India unlisted shares?', 'a' => 'NSE is not listed on an exchange, so its shares change hands privately in the unlisted market as off-market transfers into a CDSL or NSDL demat account. Availability, lot sizes and quotes vary from day to day and depend on whether a seller exists. This page is company information only — not a recommendation.'],
    ['tab' => 'Investment', 'q' => 'What makes NSE India a compelling profile to research?', 'a' => 'Informationally, investors study NSE because it is a market-infrastructure business with several revenue streams — transaction fees, listing charges, clearing, data licensing and index services — in a market where retail participation and product breadth have both been expanding. Whether that suits your portfolio is a separate question you should assess independently.'],
    ['tab' => 'Business', 'q' => 'Which sectors is NSE involved in?', 'a' => 'Equity trading, equity derivatives, currency derivatives, commodity derivatives through the NSE group, debt market instruments, clearing and settlement, index services, market education and certification, the NSE Emerge SME platform, and data analytics and information services.'],
    ['tab' => 'Business', 'q' => "What are NSE's sources of income?", 'a' => 'Transaction fees on traded volumes, new listing and annual listing charges, clearing and settlement income, market data licensing, index licensing for ETFs and derivative products, market infrastructure services, and education and certification revenue.'],
    ['tab' => 'Business', 'q' => 'Is NSE India profitable?', 'a' => 'NSE has historically operated as a profitable exchange business with revenue spread across transaction, listing, clearing, data and index streams. Specific numbers change with each reporting period and should be read from current filings rather than from a profile page.'],
    ['tab' => 'Basics', 'q' => 'What is the significance of NIFTY 50?', 'a' => 'NIFTY 50 is India\'s headline equity benchmark, maintained by NSE Indices. It is the reference for a large share of Indian index funds, ETFs and index derivatives, which makes it both a market barometer and a licensable product for the exchange.'],
    ['tab' => 'Basics', 'q' => 'How does NSE ensure trading transparency?', 'a' => 'Orders are matched electronically on a price-time priority basis, with the order book, traded prices and volumes visible to all participants. Surveillance systems, circuit filters, member obligations and clearing through NSE Clearing Ltd add further oversight under SEBI regulation.'],
    ['tab' => 'Business', 'q' => "What is NSE's position in the derivatives market?", 'a' => "NSE has ranked as the world's largest derivatives exchange by number of contracts traded in recent years, driven by index and stock derivatives volumes alongside currency contracts."],
    ['tab' => 'Business', 'q' => 'Does NSE support SME and startup listings?', 'a' => 'Yes. NSE Emerge is its platform for small and medium enterprises and early-stage companies to raise capital and list, with lighter requirements than the main board while remaining inside the regulated framework.'],
];

$swot = [
    ['label' => 'Strengths', 'points' => ["Brand credibility as India's primary exchange.", 'Global leadership in derivatives volumes.', 'Advanced trading and surveillance technology.', 'A large investor, member and broker network.']],
    ['label' => 'Weaknesses', 'points' => ['Dependence on the regulatory framework and fee approvals.', 'Competition from BSE in some segments.', 'Limited international trading presence relative to global peers.']],
    ['label' => 'Opportunities', 'points' => ['Growth in ETFs and index-linked products.', 'International expansion and cross-listing partnerships.', 'Rising demand for market data and analytics.', 'SME and startup listings through NSE Emerge.']],
    ['label' => 'Threats', 'points' => ['Cybersecurity and operational-resilience risk.', 'Regulatory change affecting fees or product structures.', 'Technology disruption in trading and matching.', 'Competition from global exchange groups.']],
];

$timeline = [
    ['year' => '1992', 'text' => 'Operations begin, introducing electronic trading to India.'],
    ['year' => '1993', 'text' => 'Recognised as a stock exchange.'],
    ['year' => '1994', 'text' => 'Wholesale debt and capital market segments launched.'],
    ['year' => '1996', 'text' => 'NIFTY 50 index launched.'],
    ['year' => '2000', 'text' => 'Derivatives trading introduced in India.'],
    ['year' => '2010+', 'text' => 'Currency and commodity segments expanded.'],
    ['year' => '2021–2024', 'text' => "Became the world's largest derivatives exchange by contracts traded."],
];
@endphp

@section('content')
<x-sw.blog-post-layout
    :crumbs="[['label' => 'Home', 'href' => '/'], ['label' => 'Unlisted Shares', 'href' => '/unlisted-shares/'], ['label' => 'NSE India', 'href' => '/unlisted-shares/nse-india/'], ['label' => 'About']]"
    :chips="['NSE India', 'Company Profile', 'Unlisted']"
    heroIcon="landmark"
    title="NSE India: Company Profile, Business Verticals & How It Works"
    description="A clear profile of NSE India — business verticals, revenue segments, history, industry position, shareholding, SWOT and FAQs."
    authorLine="SW · StockWitty Research"
    dateLabel="Updated August 2026"
    readLabel="8 min read"
    :toc="$toc"
    :takeaways="[
        'NSE is India\'s No.1 stock exchange, running a fully automated electronic trading system.',
        'It operates multiple verticals: equity, derivatives, currency, debt, clearing, index services, education and an SME platform.',
        'It has ranked as the world\'s largest derivatives exchange by number of contracts traded.',
        'Revenue is diversified across transaction fees, listing charges, clearing, data licensing and index services.',
        'It owns and maintains the NIFTY 50 — India\'s headline equity index.',
    ]"
    :video="['caption' => 'NSE India explained — StockWitty', 'note' => 'Replace with your StockWitty YouTube video']"
    :faqTabs="['Basics', 'Business', 'Investment']"
    :faqs="$faqs"
    :sources="[
        ['label' => 'SEBI — Securities and Exchange Board of India', 'href' => 'https://www.sebi.gov.in/'],
        ['label' => 'NSE India — official website', 'href' => 'https://www.nseindia.com/'],
        ['label' => 'NSE Indices — Nifty Indices', 'href' => 'https://www.niftyindices.com/'],
        ['label' => 'CDSL — Central Depository Services', 'href' => 'https://www.cdslindia.com/'],
    ]"
    :related="[
        ['title' => 'NSE India — live price & how to buy', 'href' => '/unlisted-shares/nse-india/', 'category' => 'Price', 'read' => 'Live data'],
        ['title' => 'NSE India — our thesis & WittyScore', 'href' => '/unlisted-shares/nse-india/thesis/', 'category' => 'Analysis', 'read' => '11 min read'],
        ['title' => 'What Are Unlisted Shares?', 'href' => '/blog/what-are-unlisted-shares/', 'category' => 'Basics', 'read' => '7 min read'],
    ]"
    :leadForm="['heading' => 'Interested in NSE India unlisted shares?', 'subtext' => 'Leave your details and a StockWitty specialist will call you back to explain current availability, the price you\'d actually transact at, and the end-to-end process — with no obligation to buy.']"
>
    <x-slot:topSlot>
        <div x-data="companySelectorWidget('nse-india')" data-companies="{{ json_encode(config('sw.showcase_companies')) }}"
             class="not-prose flex flex-col gap-4 rounded-2xl border border-border bg-green-50 p-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <label class="mb-1.5 block text-[11px] font-bold tracking-widest text-muted-foreground uppercase">Company profile</label>
                @include('partials.sw._company-dropdown')
                <p class="mt-2 text-xs font-semibold text-muted-foreground">
                    Viewing: <span class="text-foreground" x-text="company.name"></span> · profile body below is NSE India–specific in this demo.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-2 rounded-full border border-border bg-card px-3.5 py-2 text-xs font-bold text-foreground shadow-soft">
                    Current price ₹<span x-text="company.price.toLocaleString('en-IN')"></span>
                    <span :class="company.changePct >= 0 ? 'text-primary' : 'text-red-600'">
                        <span x-text="company.changePct >= 0 ? '▲' : '▼'"></span><span x-text="Math.abs(company.changePct).toFixed(2) + '%'"></span>
                    </span>
                </span>
                <a :href="'/unlisted-shares/' + company.slug + '/'" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary underline-offset-4 hover:underline">
                    View full price &amp; buy <x-sw.icon name="arrow-right" class="size-3.5" />
                </a>
            </div>
        </div>
    </x-slot:topSlot>

    <x-slot:intro>
        <p>
            The National Stock Exchange of India is one of the world's leading
            financial-market-infrastructure institutions and, in practical terms, the backbone of
            Indian capital markets. It replaced floor trading with an automated, screen-based system
            that gave every participant the same visible price, and it has kept that technology-first
            character as the market grew into equities, derivatives, currency, debt, clearing and
            index services.
        </p>
        <p>
            Because NSE itself is not listed, its shares trade in India's unlisted market — which is
            where investor interest comes from: a high-growth, pre-IPO opportunity in a business with
            strong fundamentals and a central position in the system it operates. This page is the
            profile: what the company is and how it works. Price, valuation and our verdict live on
            separate pages.
        </p>
    </x-slot:intro>

    <x-sw.article-h2 id="overview">Overview of the company</x-sw.article-h2>
    <x-sw.prose>
        <p>
            NSE is the No.1 stock exchange in India by traded turnover. Its trading is fully automated
            and screen-based: orders are matched electronically on price-time priority, so a retail
            investor in a small town sees the same order book as an institution in Mumbai. That design
            choice — transparency by architecture rather than by promise — is the single biggest reason
            Indian equity markets modernised as quickly as they did.
        </p>
        <p>
            Today the exchange connects millions of participants through brokers, clearing members and
            API-driven platforms, and it is the home of the NIFTY 50, the index most Indians mean when
            they say "the market".
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="verticals">Business verticals</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Equity trading — cash-market order matching across listed companies.',
        'Equity derivatives — index and single-stock futures and options.',
        'Currency derivatives — exchange-traded currency futures and options.',
        'Commodity derivatives — offered through the NSE group.',
        'Debt market instruments — government securities, corporate bonds and the wholesale debt segment.',
        'Clearing & settlement — handled by NSE Clearing Ltd.',
        'Index services — NIFTY and other benchmarks via NSE Indices.',
        'Market education & certification — through NISM and NSE Academy.',
        'SME platform — NSE Emerge for small, medium and early-stage companies.',
        'Data analytics & information services — market data, feeds and analytics products.',
    ]" />
    <x-sw.callout title="Core market presence">
        The world's largest derivatives market by number of contracts traded, with deep liquidity and world-class trading technology.
    </x-sw.callout>

    <x-sw.article-h2 id="operations">What NSE does &amp; how it operates</x-sw.article-h2>
    <x-sw.numbered-steps :steps="[
        ['t' => 'Hosts nationwide trading platforms', 'd' => 'Electronic trading infrastructure that brokers and members across India connect to, carrying equity, derivative, currency and debt orders.'],
        ['t' => 'Provides clearing & settlement', 'd' => 'Trades are novated and settled through NSE Clearing Ltd, which manages counterparty risk, margins and the settlement cycle.'],
        ['t' => 'Calculates key market indices', 'd' => 'NSE Indices constructs and maintains NIFTY 50 and a wide family of sectoral, thematic and strategy indices.'],
        ['t' => 'Supplies trading statistics & analysis', 'd' => 'Real-time and historical market data, turnover statistics and analytics used by brokers, funds, media and research desks.'],
        ['t' => 'Runs investor education & certification', 'd' => 'Certification and training programmes through NISM and NSE Academy for market professionals and retail investors.'],
        ['t' => 'Supports startups & SMEs via NSE Emerge', 'd' => 'A dedicated platform for smaller companies to raise growth capital and list within a regulated framework.'],
    ]" />

    <x-sw.article-h2 id="revenue">Revenue segments</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Transaction fees — charged on traded volumes across segments; the largest and most volume-sensitive stream.',
        'New listing charges — fees paid by companies admitting their securities to trading.',
        'Clearing & settlement income — earned for guaranteeing and settling trades.',
        'Data licensing — real-time feeds, historical data and analytics sold to institutions and vendors.',
        'Index services — NIFTY licensing for ETFs, index funds and derivative products in India and globally.',
        'Market infrastructure services — connectivity, colocation and technology services for members.',
        'Education & certification revenue — programmes and examinations run through NSE Academy.',
    ]" />

    <x-sw.article-h2 id="geography">Geographical presence</x-sw.article-h2>
    <x-sw.prose>
        <p>
            NSE has a presence in every major Indian financial centre, and its member and broker network
            reaches investors nationwide through terminals, internet trading and mobile apps. Beyond
            India, the exchange maintains international partnerships and index tie-ups, and NIFTY-linked
            products give global investors an accessible route to Indian market exposure without
            trading Indian equities directly.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="history">History &amp; evolution</x-sw.article-h2>
    <ol class="mt-6 space-y-3 border-l-2 border-mint/40 pl-5">
        @foreach ($timeline as $i => $t)
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
    <x-sw.prose>
        <p>
            Read end to end, the arc is a shift from a technology-driven exchange into a full financial
            ecosystem — trading, clearing, education, data and index services — where several parts of
            the business no longer depend on a single day's turnover.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="industry">Industry position</x-sw.article-h2>
    <x-sw.prose>
        <p>
            India's capital-market-infrastructure sector has been growing on the back of rising retail
            participation, the digitisation of onboarding and trading, a wider product set, and higher
            mutual fund and FPI flows. Exchanges sit at the toll gate of all of it.
        </p>
        <p>
            Within that sector, NSE is the world's largest derivatives exchange by number of contracts
            traded and the clear leader in Indian equity liquidity and turnover — which is what makes
            its position structural rather than cyclical.
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="shareholding">Shareholding framework</x-sw.article-h2>
    <x-sw.prose>
        <p>
            Ownership is diverse rather than concentrated. The register spans domestic banks, financial
            institutions, insurance companies, international investors, asset-management companies and
            public-sector entities — a structure consistent with how market infrastructure institutions
            are expected to be held.
        </p>
    </x-sw.prose>
    <x-sw.callout title="On percentages">
        Precise shareholding percentages change with regulatory filings — see the live price page for current data.
    </x-sw.callout>

    <x-sw.article-h2 id="interest">Why NSE draws investor interest</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Fast growth in India\'s equity and derivatives markets.',
        'Rising retail and demat account participation broadening the user base.',
        'A genuine technology edge in matching, surveillance and settlement.',
        'Expansion into data, analytics and globally licensed index products.',
        'Consistent profitability supported by diversified revenue streams.',
    ]" />
    <x-sw.prose>
        <p>For live price, valuation and financials, see the
            <a href="/unlisted-shares/nse-india/" class="inline-flex items-center gap-1 font-semibold text-primary underline-offset-4 hover:underline">
                NSE India price page <x-sw.icon name="arrow-right" class="size-4" />
            </a>
        </p>
    </x-sw.prose>

    <x-sw.article-h2 id="swot">SWOT analysis</x-sw.article-h2>
    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        @foreach ($swot as $i => $s)
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
        @endforeach
    </div>

    <x-sw.article-h2 id="landscape">Market landscape &amp; reach</x-sw.article-h2>
    <x-sw.prose>
        <p>
            NSE's reach is built on brokers and members spread across India, while its index and data
            products are accessible to participants in Asia, Europe and the US through global
            partnerships and distributors.
        </p>
    </x-sw.prose>
    <x-sw.checklist-card :items="[
        'Channel network — traders and brokers, clearing members, banks and financial institutions, API-driven fintech platforms, and retail investors through mobile apps.',
        'Distribution — electronic order matching, nationwide terminals and online infrastructure, plus data and index licensing via global distributors.',
    ]" />

    <x-sw.article-h2 id="products">Products &amp; services</x-sw.article-h2>
    <x-sw.checklist-card :items="[
        'Equity — cash-market trading, index products and market analytics.',
        'Derivatives — index derivatives, stock derivatives, currency and commodity contracts.',
        'Debt — government securities, corporate bonds and the wholesale debt segment.',
        'Clearing & settlement through NSE Clearing Ltd.',
        'Market data products and information services.',
        'Investor education and certification programmes.',
        'SME and startup listings via NSE Emerge.',
        'Technology-driven trading infrastructure and connectivity services.',
    ]" />

    <x-sw.article-h2 id="competitive">Competitive strength</x-sw.article-h2>
    <x-sw.prose>
        <p>
            NSE commands the larger share of Indian equity turnover and ranks first domestically in
            derivatives, while also standing as the global No.1 by equity-derivatives volume. The
            durable advantages behind that are a technology edge, deep liquidity that attracts more
            liquidity, fast and reliable settlement, and advanced surveillance that keeps institutional
            participants comfortable.
        </p>
        <p class="rounded-2xl border border-border bg-green-50 px-4 py-3 text-sm">
            This page is company information, not investment advice. Business details, figures and
            shareholding change over time — verify current data from official filings before making any
            decision.
        </p>
    </x-sw.prose>
</x-sw.blog-post-layout>
@endsection
