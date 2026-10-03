@use('App\Support\AgentView\AgentRequest')
@use('App\Support\Seo\JsonLd')
@php
    // Null on routes without a Markdown twin (login, signup...): no toggle there.
    $markdownTwin = AgentRequest::currentMarkdownUrl();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  @humanView
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-XR1CJL2R78"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-XR1CJL2R78');
  </script>
  @endhumanView

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="google-site-verification" content="urNHDEB3xPxqJKbGweraXE4Z2BEBK9wZrdmFtRwROIw" />
  <title>@yield('title', 'StockWitty — Invest Smart, Stay Witty')</title>
  <meta name="description" content="@yield('description', 'Research and buy unlisted & pre-IPO shares in India — live prices, DRHP tracking, honest research and same-day demat delivery. Invest Smart, Stay Witty.')" />
  <meta name="keywords" content="@yield('keywords', 'unlisted shares, pre-IPO shares, buy unlisted shares in India, unlisted stock price, StockWitty')" />
  {{-- url()->current() rtrims the trailing slash, which fights the site's
       own /-terminated URL convention — build from the raw path instead so
       /wittyscore/ stays /wittyscore/ and the homepage stays "/". --}}
  <link rel="canonical" href="{{ rtrim(config('app.url'), '/') . request()->getPathInfo() }}" />
  @if ($markdownTwin)
  <link rel="alternate" type="text/markdown" href="{{ rtrim(config('app.url'), '/') . $markdownTwin }}" title="Markdown version for AI agents" />
  @endif
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
  <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

  {!! JsonLd::script(JsonLd::organization(), JsonLd::website()) !!}
  @stack('jsonld')

  @humanView
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,400&display=swap" />

  @vite(['resources/css/sw.css', 'resources/js/sw.js'])
  @yield('styles')
  @endhumanView
</head>
<body class="bg-background text-foreground">

{{-- The Markdown twin (AgentRequest::isRendering()) is built from this same
     page, minus the site chrome: nav, footer and scripts are human-only. --}}
@humanView
@include('partials.sw.nav')
@endhumanView

@yield('content')

@humanView
@include('partials.sw.footer')

@if ($markdownTwin)
    <x-sw.agent-view :markdown-url="$markdownTwin" />
@endif

@stack('scripts')
@endhumanView
</body>
</html>
