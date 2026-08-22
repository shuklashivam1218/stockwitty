@props(['crumbLabel', 'eyebrow', 'title', 'subtitle', 'points' => []])

<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => $crumbLabel]]" />
    </div>

    <main>
        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-stretch gap-6 lg:grid-cols-2">
                    <x-sw.reveal>
                        <div class="bg-price-card h-full rounded-3xl p-8 text-white">
                            <p class="text-xs font-bold tracking-widest text-mint-bright uppercase">{{ $eyebrow }}</p>
                            <h1 class="mt-4 text-3xl font-bold sm:text-4xl">{{ $title }}</h1>
                            <p class="mt-3 text-sm text-white/75">{{ $subtitle }}</p>
                            <ul class="mt-8 space-y-3 text-sm text-white/85">
                                @foreach ($points as $p)
                                    <li class="flex items-start gap-3">
                                        <x-sw.icon name="check" class="mt-0.5 size-4 shrink-0 text-mint-bright" />
                                        {{ $p }}
                                    </li>
                                @endforeach
                            </ul>
                            <p class="mt-8 border-t border-white/10 pt-5 text-xs text-white/55">
                                StockWitty is a distributor of unlisted shares, not a SEBI-registered investment
                                adviser. Unlisted shares are illiquid and high-risk.
                            </p>
                        </div>
                    </x-sw.reveal>

                    <x-sw.reveal :delay="0.08">
                        <div class="h-full rounded-3xl border border-border bg-card p-7 shadow-soft sm:p-8">
                            {{ $slot }}
                        </div>
                    </x-sw.reveal>
                </div>
            </div>
        </section>
    </main>
</div>
