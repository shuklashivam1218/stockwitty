@extends('layouts.sw')

@php
$study = collect(config('sw.case_studies'))->firstWhere('slug', 'first-time-unlisted-kyc');
$related = collect(config('sw.case_studies'))->reject(fn ($c) => $c['slug'] === 'first-time-unlisted-kyc')->values()->all();
@endphp

@section('title', $study['title'] . ' — Case Study | StockWitty')
@section('description', $study['summary'] . ' Illustrative investor journey — not investment advice.')

@section('content')
<div class="min-h-screen bg-background">
    <x-sw.case-study-layout :study="$study" :related="$related" :disclaimer="config('sw.case_studies_disclaimer')" />
</div>
@endsection
