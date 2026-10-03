@extends('layouts.sw')

@section('title', 'Create Your Free StocksWitty Account — Get Started')
@section('description', 'Open a free StocksWitty account to research unlisted and pre-IPO shares, build a watchlist and get help with off-market demat transfers.')

@section('content')
@include('auth._form', ['initialMode' => 'signup', 'crumbLabel' => 'Sign Up'])
@endsection
