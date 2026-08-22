<footer class="bg-dark text-white mt-5 pt-5 pb-3">
  <div class="container">
    <div class="row g-4 mb-4">

      <div class="col-lg-4">
        <div class="fw-bold mb-2" style="font-size:1.5rem;color:#11F1C4;">
          Stock<span class="text-white">Witty</span>
        </div>
        <p class="text-white-50 small">Invest Smart, Stay Witty. Breaking down unlisted shares, IPOs, and market trends for retail investors.</p>
      </div>

      <div class="col-lg-2 col-6">
        <h6 class="fw-semibold mb-3" style="color:#11F1C4;">Explore</h6>
        <ul class="list-unstyled small">
          <li class="mb-1"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
          <li class="mb-1"><a href="{{ url('/unlisted') }}" class="text-white-50 text-decoration-none">Unlisted Shares</a></li>
          <li class="mb-1"><a href="{{ url('/blog') }}" class="text-white-50 text-decoration-none">Blog</a></li>
        </ul>
      </div>

      <div class="col-lg-2 col-6">
        <h6 class="fw-semibold mb-3" style="color:#11F1C4;">Account</h6>
        <ul class="list-unstyled small">
          <li class="mb-1"><a href="{{ url('/login') }}" class="text-white-50 text-decoration-none">Sign In</a></li>
          <li class="mb-1"><a href="{{ url('/login') }}" class="text-white-50 text-decoration-none">Get Started</a></li>
        </ul>
      </div>

      <div class="col-lg-4">
        <h6 class="fw-semibold mb-3" style="color:#11F1C4;">Disclaimer</h6>
        <p class="text-white-50 small">StockWitty is an information portal. It is not an investment advisory platform and does not make any investment recommendations. Investors are advised to do their own due diligence.</p>
      </div>

    </div>

    <hr class="border-secondary">
    <p class="text-center text-white-50 small mb-0">&copy; {{ date('Y') }} StockWitty. All Rights Reserved.</p>
  </div>
</footer>
