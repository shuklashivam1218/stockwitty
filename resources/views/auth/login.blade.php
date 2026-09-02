@extends('layouts.sw')

@section('title', 'Sign In to StockWitty — Unlisted & Pre-IPO Shares')
@section('description', 'Sign in to your StockWitty account to track unlisted share prices, view your watchlist and follow up on open orders.')

@section('content')
@include('auth._form', ['initialMode' => 'login', 'crumbLabel' => 'Sign In'])
@endsection
