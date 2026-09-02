@extends('layouts.sw')

@section('title', ($page->CMS_PAGE_TITLE ?? 'Disclaimer') . ' | StockWitty')
@section('description', $page->CMS_PAGE_DESCRIPTION ?? 'StockWitty disclaimer — read this before you invest.')

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Disclaimer']]" />
    </div>

    <main class="pb-24">
        <x-sw.page-hero eyebrow="Disclaimer" :title="$page->CMS_PAGE_TITLE" :subtitle="$page->CMS_PAGE_DESCRIPTION" />

        <div class="pt-10 sm:pt-14">
            @if (count($toc))
                <x-sw.toc-layout :items="$toc">
                    <x-sw.callout title="Read this before you invest">
                        Investments in securities markets are subject to market risks. Read all the related
                        documents carefully before investing. The value of investments can go down as well as
                        up, and you may get back less than you invested. StockWitty does not guarantee any
                        returns.
                    </x-sw.callout>

                    <x-sw.prose>{!! $content !!}</x-sw.prose>
                </x-sw.toc-layout>
            @else
                <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                    <x-sw.callout title="Read this before you invest">
                        Investments in securities markets are subject to market risks. Read all the related
                        documents carefully before investing. The value of investments can go down as well as
                        up, and you may get back less than you invested. StockWitty does not guarantee any
                        returns.
                    </x-sw.callout>

                    <x-sw.prose>{!! $content !!}</x-sw.prose>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
