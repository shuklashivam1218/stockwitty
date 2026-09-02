@extends('layouts.sw')

@section('title', ($page->CMS_PAGE_TITLE ?? 'Disclaimer') . ' | StockWitty')
@section('description', $page->CMS_PAGE_DESCRIPTION ?? 'StockWitty disclaimer — read this before you invest.')

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Disclaimer']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Disclaimer" :title="$page->CMS_PAGE_TITLE" :subtitle="$page->CMS_PAGE_DESCRIPTION" />

        <section class="py-14 sm:py-20">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <x-sw.callout title="Read this before you invest">
                    Investments in securities markets are subject to market risks. Read all the related
                    documents carefully before investing. The value of investments can go down as well as
                    up, and you may get back less than you invested. StockWitty does not guarantee any
                    returns.
                </x-sw.callout>

                <x-sw.prose>{!! $page->CMS_PAGE_CONTENT !!}</x-sw.prose>
            </div>
        </section>
    </main>
</div>
@endsection
