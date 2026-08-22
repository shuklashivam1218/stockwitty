@php
$swAccount = [
    ['label' => 'Sign In', 'href' => '/login/'],
    ['label' => 'Get Started', 'href' => '/signup/'],
];
@endphp

<footer id="footer" class="bg-price-card text-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <p class="text-2xl text-white font-bold tracking-tight">
                    Stock<span class="text-mint">Witty</span>
                </p>
                <p class="mt-2 text-sm font-semibold text-mint-bright">Invest Smart, Stay Witty.</p>
                <p class="mt-4 max-w-md text-sm text-white/70">
                    One platform for unlisted &amp; pre-IPO shares, listed stocks, mutual funds, PMS, fixed
                    deposits, digital gold &amp; silver and ETFs — with transparent pricing and honest
                    research.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="/#live-price" class="bg-cta inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-bold text-white transition-transform hover:scale-[1.02]">
                        <x-sw.icon name="message-circle" class="size-4" />
                        Chat on WhatsApp
                    </a>
                    <a href="mailto:hello@stockswitty.com" class="inline-flex items-center gap-2 rounded-xl border border-white/25 px-5 py-3 text-sm font-bold text-white transition-colors hover:bg-white/10">
                        <x-sw.icon name="mail" class="size-4" />
                        hello@stockswitty.com
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold tracking-widest text-mint-bright uppercase">Products</h3>
                <ul class="mt-4 space-y-2.5">
                    @foreach (config('sw.products') as $p)
                        <li><a href="{{ $p['href'] }}" class="text-sm text-white/70 transition-colors hover:text-mint-bright">{{ $p['title'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-bold tracking-widest text-mint-bright uppercase">Research</h3>
                <ul class="mt-4 space-y-2.5">
                    @foreach (config('sw.tools') as $t)
                        <li><a href="{{ $t['href'] }}" class="text-sm text-white/70 transition-colors hover:text-mint-bright">{{ $t['title'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-bold tracking-widest text-mint-bright uppercase">Account</h3>
                <ul class="mt-4 space-y-2.5">
                    @foreach ($swAccount as $a)
                        <li><a href="{{ $a['href'] }}" class="text-sm text-white/70 transition-colors hover:text-mint-bright">{{ $a['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-12 rounded-2xl border border-white/10 bg-white/[0.05] p-5">
            <h4 class="text-xs font-bold tracking-widest text-mint-bright uppercase">Disclaimer</h4>
            <p class="mt-2 text-xs leading-relaxed text-white/65">
                StockWitty is an information portal and a distributor of unlisted shares. It is not a
                SEBI-registered investment adviser and does not make investment recommendations. Unlisted
                shares are illiquid and high-risk, prices are dealer-negotiated, and an IPO may be
                delayed or may never happen. Mutual funds, PMS, ETFs and market-linked products carry
                market risk — read all scheme documents carefully. Do your own due diligence.
            </p>
        </div>

        <p class="mt-8 border-t border-white/10 pt-6 text-xs text-white/50">
            © {{ date('Y') }} StockWitty. All rights reserved.
        </p>
    </div>
</footer>
