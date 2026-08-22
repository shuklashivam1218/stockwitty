<section id="live-price" class="py-20 sm:py-28" x-data="showcase()" data-companies="{{ json_encode(config('sw.showcase_companies')) }}">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-sw.section-heading eyebrow="Live showcase — 250+ companies" title="Pick any company. The whole showcase follows."
                               subtitle="Tap a card in the slider to swap the price card, calculator and chart instantly." />

        <x-sw.reveal :delay="0.06">
            <div class="mt-8 overflow-hidden [mask-image:linear-gradient(90deg,transparent,#000_4%,#000_96%,transparent)]">
                <div class="marquee-track flex w-max gap-4">
                    <template x-for="(x, i) in [...companies, ...companies]" :key="x.slug + '-' + i">
                        <button type="button" @click="select(x.slug)" :aria-pressed="x.slug === slug"
                                :class="x.slug === slug ? 'border-mint ring-2 ring-mint/60' : 'border-border hover:border-mint/50'"
                                class="card-lift w-[15.5rem] shrink-0 rounded-2xl border bg-card p-4 text-left shadow-soft transition-all">
                            <div class="flex items-center gap-3">
                                <span class="grid size-10 place-items-center rounded-xl bg-muted text-xs font-bold text-primary" x-text="x.initials"></span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-foreground" x-text="x.name"></p>
                                    <p class="text-[0.7rem] font-bold tracking-wide text-mint uppercase" x-text="x.tag"></p>
                                </div>
                            </div>
                            <div class="mt-3 flex items-end justify-between">
                                <span class="text-lg font-bold text-foreground" x-text="'₹' + x.price.toLocaleString('en-IN')"></span>
                                <span class="inline-flex items-center gap-0.5 text-xs font-bold" :class="x.changePct >= 0 ? 'text-green-500' : 'text-destructive'">
                                    <span x-text="x.changePct >= 0 ? '↑' : '↓'"></span>
                                    <span x-text="Math.abs(x.changePct).toFixed(2) + '%'"></span>
                                </span>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </x-sw.reveal>

        <div class="mt-8 grid items-start gap-6 lg:grid-cols-5">
            <x-sw.reveal class="lg:col-span-3">
                <div class="bg-price-card card-lift relative overflow-hidden rounded-3xl border border-green-800/50 p-7 text-white sm:p-10">
                    <div aria-hidden class="pointer-events-none absolute -right-16 -top-16 size-64 rounded-full bg-mint/20 blur-3xl"></div>
                    <div class="relative">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="grid size-11 place-items-center rounded-xl bg-white/10 text-sm font-bold text-mint-bright" x-text="company.initials"></span>
                                <div>
                                    <p class="text-[0.7rem] font-bold tracking-[0.18em] text-mint-bright/90 uppercase">Live unlisted share price</p>
                                    <p class="text-lg font-bold" x-text="company.name"></p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 text-xs font-bold text-mint-bright">
                                <x-sw.icon name="sparkles" class="size-3.5" />
                                WittyScore <span x-countup="{ value: company.wittyScore, decimals: 1, duration: 900 }">0.0</span>/10
                            </span>
                        </div>

                        <div class="mt-7 flex flex-wrap items-end gap-4">
                            <p class="text-5xl font-bold tracking-tight sm:text-6xl" x-countup="{ value: company.price, prefix: '₹', duration: 900 }">₹0</p>
                            <p class="mb-2 inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-bold"
                               :class="company.changePct >= 0 ? 'bg-mint/15 text-mint-bright' : 'bg-white/10 text-white'">
                                <span x-text="company.changePct >= 0 ? '↑' : '↓'"></span>
                                ₹<span x-text="Math.abs(company.changeAbs)"></span>
                                (<span x-text="(company.changePct >= 0 ? '+' : '-') + Math.abs(company.changePct).toFixed(2) + '%'"></span>) this week
                            </p>
                        </div>

                        <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            <template x-for="s in [
                                { label: '52W High', value: '₹' + company.high52.toLocaleString('en-IN') },
                                { label: '52W Low', value: '₹' + company.low52.toLocaleString('en-IN') },
                                { label: 'Lot Size', value: company.lot.toLocaleString('en-IN') },
                                { label: 'Min Investment', value: minInvestment(company.price, company.lot) },
                                { label: 'Market Cap', value: company.mktCap },
                                { label: 'P/E Ratio', value: company.pe },
                            ]" :key="s.label">
                                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-4 backdrop-blur-sm transition-colors hover:border-mint/40">
                                    <p class="text-[0.7rem] font-semibold tracking-wide text-white/60 uppercase" x-text="s.label"></p>
                                    <p class="mt-1.5 text-lg font-bold" x-text="s.value"></p>
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                            <p class="flex items-center gap-2 text-xs font-medium text-white/60">
                                <span class="relative flex size-2.5">
                                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-mint-bright opacity-75"></span>
                                    <span class="relative inline-flex size-2.5 rounded-full bg-mint-bright"></span>
                                </span>
                                Prices are dealer-negotiated, not exchange quoted
                            </p>
                            <a :href="'/unlisted-shares/' + company.slug + '/'" class="inline-flex items-center gap-1.5 rounded-lg bg-white/10 px-3.5 py-2 text-xs font-bold text-mint-bright transition-colors hover:bg-white/20">
                                View full <span x-text="company.name"></span> page
                                <x-sw.icon name="external-link" class="size-3.5" />
                            </a>
                        </div>
                    </div>
                </div>
            </x-sw.reveal>

            <x-sw.reveal :delay="0.12" class="lg:col-span-2">
                <div class="card-lift rounded-3xl border border-border bg-card p-6 shadow-soft">
                    <div class="grid grid-cols-2 gap-1 rounded-xl bg-muted p-1">
                        <button type="button" @click="side = 'Buy'"
                                :class="side === 'Buy' ? 'bg-primary text-primary-foreground shadow-soft' : 'text-muted-foreground hover:text-primary'"
                                class="rounded-lg py-2.5 text-sm font-bold transition-all">Buy</button>
                        <button type="button" @click="side = 'Sell'"
                                :class="side === 'Sell' ? 'bg-primary text-primary-foreground shadow-soft' : 'text-muted-foreground hover:text-primary'"
                                class="rounded-lg py-2.5 text-sm font-bold transition-all">Sell</button>
                    </div>

                    <p class="mt-5 text-sm font-bold text-foreground">
                        <span x-text="side"></span> <span x-text="company.name"></span> @ ₹<span x-text="company.price.toLocaleString('en-IN')"></span>
                    </p>

                    <div class="mt-4">
                        <p class="text-xs font-bold tracking-wide text-muted-foreground uppercase">Quantity</p>
                        <div class="mt-2 flex items-center justify-between rounded-xl border border-border p-2">
                            <button type="button" aria-label="Decrease quantity" @click="dec()"
                                    class="grid size-10 place-items-center rounded-lg bg-muted text-primary transition-colors hover:bg-green-100">
                                <x-sw.icon name="minus" class="size-4" />
                            </button>
                            <span class="text-2xl font-bold text-foreground" x-text="qty.toLocaleString('en-IN')"></span>
                            <button type="button" aria-label="Increase quantity" @click="inc()"
                                    class="grid size-10 place-items-center rounded-lg bg-muted text-primary transition-colors hover:bg-green-100">
                                <x-sw.icon name="plus" class="size-4" />
                            </button>
                        </div>
                        <p class="mt-2 text-xs font-semibold text-muted-foreground">
                            Min lot <span x-text="company.lot.toLocaleString('en-IN')"></span> · Total ₹<span x-text="total.toLocaleString('en-IN')"></span>
                        </p>
                    </div>

                    <div class="mt-6 space-y-3">
                        <a href="#how-to-buy" class="bg-cta flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3.5 text-sm font-bold text-white shadow-glow transition-transform hover:scale-[1.02]">
                            <x-sw.icon name="trending-up" class="size-4" />
                            Get Quote on WhatsApp
                        </a>
                        <a :href="'/unlisted-shares/' + company.slug + '/about/'" class="flex w-full items-center justify-center gap-2 rounded-xl bg-beige px-5 py-3.5 text-sm font-bold text-green-950 transition-all hover:bg-green-100">
                            <x-sw.icon name="file-text" class="size-4 text-primary" />
                            Read company profile
                        </a>
                    </div>

                    <div class="mt-6 border-t border-border pt-5">
                        <p class="text-xs font-bold tracking-wide text-muted-foreground uppercase">Quick switch</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <template x-for="x in companies.slice(0, 6)" :key="x.slug">
                                <button type="button" @click="select(x.slug)"
                                        :class="x.slug === slug ? 'border-primary bg-primary text-primary-foreground' : 'border-border text-muted-foreground hover:border-mint hover:text-primary'"
                                        class="rounded-full border px-3 py-1.5 text-xs font-bold transition-all" x-text="x.initials"></button>
                            </template>
                        </div>
                    </div>
                </div>
            </x-sw.reveal>
        </div>

        <div id="chart" class="mt-14 scroll-mt-24">
            <x-sw.section-heading eyebrow="Screener — Price Movement" title="Unlisted price movement"
                                   subtitle="Dealer-negotiated levels tracked by StockWitty. Illustrative, not an exchange feed." />
            <x-sw.reveal :delay="0.1">
                <div class="card-lift mt-8 rounded-3xl border border-border bg-card p-4 shadow-soft sm:p-6">
                    <div class="h-[300px] w-full sm:h-[400px]">
                        <canvas x-ref="priceChart"></canvas>
                    </div>
                </div>
            </x-sw.reveal>
        </div>
    </div>
</section>
