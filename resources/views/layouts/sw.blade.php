<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'StockWitty — Invest Smart, Stay Witty')</title>
  <meta name="description" content="@yield('description', 'Research and buy unlisted & pre-IPO shares in India — live prices, DRHP tracking, honest research and same-day demat delivery. Invest Smart, Stay Witty.')" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
  <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,400&display=swap" />

  @vite(['resources/css/sw.css', 'resources/js/sw.js'])
  @yield('styles')
</head>
<body class="bg-background text-foreground">

@include('partials.sw.nav')

@yield('content')

@include('partials.sw.footer')

@stack('scripts')
</body>
</html>
