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
/>
@endsection
