<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'StockWitty – Invest Smart, Stay Witty')</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
  <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <!-- Owl Carousel -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet"/>
  <!-- Global shared CSS -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}"/>

  @yield('styles')
</head>
<body>

@include('partials.header')

@yield('content')

@include('partials.footer')

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Owl Carousel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<script>
$(document).ready(function(){

  // ── Mobile Search ─────────────────────────────────────────
  $('#mobileSearchTrigger').on('click', function(){
    $('#mobileSearchBar').addClass('active');
    setTimeout(function(){ $('#mobileSearchInput').focus(); }, 100);
  });
  $('#searchBackBtn').on('click', function(){
    $('#mobileSearchBar').removeClass('active');
    $('#mobileSearchInput').val('');
  });
  $('#searchClearBtn').on('click', function(){
    $('#mobileSearchInput').val('').focus();
  });

  // ── Hamburger toggle ──────────────────────────────────────
  $('#navToggler').on('click', function(){
    $('#hamburgerIcon').toggleClass('open');
  });
  $('#navMain').on('hidden.bs.collapse', function(){
    $('#hamburgerIcon').removeClass('open');
  });

  // ── Testimonials carousel ─────────────────────────────────
  $('#testiCarousel').owlCarousel({
    loop: true, margin: 20, autoplay: true, autoplayTimeout: 4000,
    autoplayHoverPause: true, dots: true, nav: false,
    responsive: { 0: { items: 1 }, 576: { items: 2 }, 992: { items: 3 } }
  });

  // ── Stock list mini carousel ──────────────────────────────
  $('#stockListCarousel').owlCarousel({
    loop: true, items: 1, autoplay: true, autoplayTimeout: 3000, dots: false, nav: false
  });

  // ── FAQ accordion ─────────────────────────────────────────
  $('.faq-btn').on('click', function(){
    const targetId = $(this).data('target');
    const $body = $('#' + targetId);
    const isOpen = $(this).hasClass('open');
    $('.faq-btn').removeClass('open').find('.faq-icon').text('+');
    $('.faq-body').slideUp(200);
    if (!isOpen) {
      $(this).addClass('open').find('.faq-icon').text('−');
      $body.slideDown(200);
    }
  });

  // ── Financials tabs ───────────────────────────────────────
  $('.fin-period-btn').on('click', function(){
    $('.fin-period-btn').removeClass('active');
    $(this).addClass('active');
  });
  $('.fin-sub-btn').on('click', function(){
    const target = $(this).data('table');
    $('.fin-sub-btn').removeClass('active');
    $(this).addClass('active');
    $('.fin-table-wrap').removeClass('active');
    $('#tbl-' + target).addClass('active');
  });

  // ── Period tabs ───────────────────────────────────────────
  $('.period-tabs .btn').on('click', function(){
    $('.period-tabs .btn').removeClass('active');
    $(this).addClass('active');
  });

  // ── Filter tags ───────────────────────────────────────────
  $('.filter-tags .tag').on('click', function(){
    $('.filter-tags .tag').removeClass('active');
    $(this).addClass('active');
  });

});
</script>

@stack('scripts')

</body>
</html>
