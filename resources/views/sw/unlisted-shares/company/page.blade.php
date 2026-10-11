{{-- A stock's About, Thesis or WittyScore page, written in the CMS and laid out
     like a blog post (see Sw\StockPageController). --}}
@extends('layouts.sw')

@section('title', $metaTitle)
@section('description', $metaDesc)
@if ($post->meta_keywords)
    @section('keywords', $post->meta_keywords)
@endif

@section('content')
<x-sw.blog-post
    :post="$post"
    :crumbs="$crumbs"
    :chips="$chips"
    :authorLine="$authorLine"
    :author="$author"
    :dateLabel="$dateLabel"
    :hero="$hero"
    :introParas="$introParas"
    :toc="$toc"
    :body="$body"
    :related="$related"
    :leadForm="$leadForm"
    :preview="$preview"
>
    @if ($wittyScore)
        <x-sw.wittyscore-breakdown :company="$stock->UL_STOCKS_COMPNAME" :score="$wittyScore" />
    @endif
</x-sw.blog-post>
@endsection
