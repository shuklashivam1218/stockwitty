@extends('layouts.sw')

@section('title', 'Compare High-Interest Fixed Deposits — Up to 9.40% p.a. | StockWitty')
@section('description', 'Compare bank and corporate fixed deposit rates in India — general and senior-citizen rates, tenures and DICGC insurance status, side by side.')

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Fixed Deposits']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Fixed deposits" title="Compare high-interest FDs — up to 9.40% p.a."
                        subtitle="Small finance bank FDs pay more for a reason. We show the rate, the tenure and whether your deposit is DICGC insured — so the risk is visible, not buried." />

        <section class="py-14 sm:py-20" x-data="fixedDeposits()" data-fds="{{ json_encode(config('sw.fds')) }}">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <x-sw.chips :options="['All', 'Bank', 'Corporate']" model="type" />
                    <select x-model="tenure" aria-label="Filter by tenure"
                            class="rounded-xl border border-border bg-card px-3 py-2.5 text-sm font-semibold outline-none focus:border-primary">
                        @foreach (['Any tenure', 'Up to 2 years', '3 years and above'] as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <x-sw.reveal>
                    <div class="mt-6 overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
                        <table class="w-full min-w-[44rem] text-sm">
                            <caption class="sr-only">Fixed deposit rate comparison</caption>
                            <thead class="bg-green-50 text-left text-xs font-bold tracking-wide text-primary uppercase">
                                <tr>
                                    <th scope="col" class="px-5 py-3">Issuer</th>
                                    <th scope="col" class="px-5 py-3">Type</th>
                                    <th scope="col" class="px-5 py-3 text-right">General</th>
                                    <th scope="col" class="px-5 py-3 text-right">Senior citizen</th>
                                    <th scope="col" class="px-5 py-3">Best tenure</th>
                                    <th scope="col" class="px-5 py-3">Insurance</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <template x-for="f in list" :key="f.slug">
                                    <tr class="hover:bg-green-50/60">
                                        <th scope="row" class="px-5 py-4 text-left">
                                            <a :href="'/fixed-deposits/' + f.slug + '/'" class="font-bold text-foreground hover:text-primary" x-text="f.issuer"></a>
                                        </th>
                                        <td class="px-5 py-4 text-muted-foreground" x-text="f.type"></td>
                                        <td class="px-5 py-4 text-right font-bold text-foreground" x-text="f.general"></td>
                                        <td class="px-5 py-4 text-right font-bold text-primary" x-text="f.senior"></td>
                                        <td class="px-5 py-4 text-muted-foreground" x-text="f.tenure"></td>
                                        <td class="px-5 py-4">
                                            <span x-show="f.insured" style="display: none;" class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-bold text-primary">
                                                <x-sw.icon name="shield-check" class="size-3.5" /> DICGC ₹5L
                                            </span>
                                            <span x-show="!f.insured" style="display: none;" class="text-xs font-semibold text-muted-foreground">Not DICGC insured</span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </x-sw.reveal>

                <x-sw.illustrative-note>
                    Rates shown are illustrative and change frequently — verify the current card rate with the
                    issuer before opening a deposit. Corporate FDs are not covered by DICGC insurance.
                </x-sw.illustrative-note>
            </div>
        </section>
    </main>
</div>
@endsection
