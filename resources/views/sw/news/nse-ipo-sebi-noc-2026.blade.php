@extends('layouts.sw')

@section('title', 'NSE India Inches Closer to IPO as SEBI Grants No-Objection | StockWitty')
@section('description', "SEBI's no-objection moves NSE India's long-delayed listing forward. What the NOC covers, what still has to happen, and what it means for unlisted NSE shareholders.")

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'News', 'href' => '/news/'], ['label' => 'NSE India inches closer to IPO']]" />
    </div>

    <main>
        <article class="mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-16">
            <x-sw.reveal>
                <span class="rounded-full bg-mint/15 px-3 py-1 text-[0.7rem] font-bold tracking-wide text-primary uppercase">IPO</span>
                <h1 class="mt-4 text-3xl font-bold text-foreground sm:text-4xl">
                    NSE India Inches Closer to IPO as SEBI Grants No-Objection
                </h1>
                <p class="mt-4 text-xs font-semibold text-muted-foreground">
                    StockWitty Newsroom · 15 Aug 2026 · 4 min read
                </p>
            </x-sw.reveal>

            <div class="mt-8 space-y-5 text-base leading-relaxed text-muted-foreground">
                <p class="text-lg font-semibold text-foreground">
                    The National Stock Exchange's listing — talked about for the better part of a decade —
                    has taken its most concrete step yet, with the regulator issuing a no-objection that
                    clears the path for the exchange to move ahead with its offer documents.
                </p>
                <p>
                    A no-objection is not an approval of an IPO. It is the regulator confirming it has no
                    outstanding objection to the exchange proceeding, which unblocks the next stage of the
                    process: filing and clearing offer documents, appointing bankers formally, and settling
                    the structure of the issue.
                </p>
                <h2 class="pt-4 text-2xl font-bold text-foreground">What the NOC covers</h2>
                <p>
                    The clearance addresses the regulatory overhang that has sat over the exchange for years.
                    With that resolved in principle, the DRHP path becomes the operative constraint rather
                    than the pending matters that preceded it. Expect the draft prospectus, observations from
                    the regulator, and then a decision on timing and issue size — a sequence that typically
                    runs several quarters, not weeks.
                </p>
                <h2 class="pt-4 text-2xl font-bold text-foreground">What it means for unlisted holders</h2>
                <p>
                    Unlisted NSE shares have re-rated on this news before. Two things are worth holding in
                    mind. First, the unlisted quote already embeds a meaningful amount of IPO optimism — much
                    of the good news gets priced in before the prospectus is public. Second, shares acquired
                    within six months of the IPO are generally subject to a six-month lock-in from listing,
                    so buying late in the process trades liquidity away for headline exposure.
                </p>
                <p>
                    Our honest take: the regulatory risk in this name has genuinely reduced, which is a real
                    change in the thesis, not a sentiment blip. The valuation, however, has moved with it. We
                    score NSE India 8.5/10 on WittyScore — strong business, high-quality earnings, and a price
                    that no longer looks cheap. If you already hold it, this news is a reason to keep holding.
                    If you're buying now, buy the exchange, not the IPO date.
                </p>
                <h2 class="pt-4 text-2xl font-bold text-foreground">What we'd wait to confirm</h2>
                <ul class="ml-5 list-disc space-y-2">
                    <li>The DRHP itself — issue size, fresh issue versus offer for sale, and use of proceeds.</li>
                    <li>Regulator observations and any conditions attached.</li>
                    <li>Whether the price band, when it arrives, sits above or below current unlisted levels.</li>
                </ul>
            </div>

            <x-sw.reveal>
                <aside class="mt-10 rounded-2xl border border-border bg-green-50 p-6">
                    <p class="text-xs font-bold tracking-widest text-primary uppercase">Related</p>
                    <a href="/unlisted-shares/nse-india/" class="mt-2 inline-flex items-center gap-2 text-lg font-bold text-foreground hover:text-primary">
                        NSE India unlisted share price &amp; details <x-sw.icon name="arrow-right" class="size-4" />
                    </a>
                    <p class="mt-2 text-sm text-muted-foreground">
                        Also read our full
                        <a href="/unlisted-shares/nse-india/thesis/" class="font-semibold text-primary underline">
                            investment thesis on NSE India
                        </a>.
                    </p>
                </aside>
            </x-sw.reveal>

            <p class="mt-8 text-xs leading-relaxed text-muted-foreground">
                Disclaimer: StockWitty is an information portal and a distributor of unlisted shares, not a
                SEBI-registered investment adviser. This report is journalism, not investment advice. IPO
                timelines can slip or be abandoned entirely.
            </p>
        </article>
    </main>
</div>
@endsection
