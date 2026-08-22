@extends('layouts.auth')

@section('title', 'Sign In – StockWitty')

@section('styles')
<style>
  :root {
    --cream: #f5f0e8;
    --teal-dark: #052A14;
    --border: #D1D1D6;
    --label: #1e1e1e;
    --input-bg: #fff;
  }

  * { box-sizing: border-box; }
  body { font-family: 'Open Sans', sans-serif; background: var(--cream); margin: 0; min-height: 100vh; }

  .left-panel { position: relative; height: 100vh; overflow: hidden; background: #1a1a2e; }
  .left-panel img.bg-img { width: 100%; height: 100%; object-fit: cover; display: block; }

  .stats-overlay {
    position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
    display: grid; grid-template-columns: 1fr 1fr; gap: 12px; width: 72%;
  }
  .stat-card {
    background: rgba(253,250,243,.58); backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,.28); border-radius: 14px; padding: 18px 20px 14px;
  }
  .stat-card .icon { width: 36px; height: 36px; background: #E7F1EB; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
  .stat-card .icon svg { width: 18px; height: 18px; stroke: #000; fill: none; stroke-width: 1.8; }
  .stat-card .val { font-size: 1.9rem; line-height: 1; margin-bottom: 4px; color: #000; font-weight: 600; }
  .stat-card .lbl { font-size: 0.65rem; letter-spacing: .12em; opacity: .7; text-transform: uppercase; color: #000; font-weight: 600; }

  .left-panel.dark-left {
    background: url('{{ asset('img/signup-bg.png') }}') center / cover no-repeat;
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px;
  }
  .left-panel.dark-left::after { content: ''; position: absolute; inset: 0; background: rgba(5,42,20,.55); }
  .left-panel.dark-left > * { position: relative; z-index: 2; }
  .left-panel.dark-left .brand-logo {
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2); border-radius: 12px;
    padding: 10px 18px; color: #fff; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;
  }
  .left-panel.dark-left h3 { color: #fff; font-size: 1.7rem; text-align: center; margin: 0; }
  .left-panel.dark-left p { color: rgba(255,255,255,.6); font-size: .85rem; text-align: center; margin: 0; }
  .feature-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; }
  .feature-list li { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,.85); font-size: .88rem; }
  .feature-list li .num { width: 28px; height: 28px; border-radius: 50%; background: rgba(255,255,255,.15); display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 600; color: #fff; flex-shrink: 0; }

  .right-panel { height: 100vh; background: var(--cream); display: flex; align-items: center; justify-content: center; padding: 32px 48px; overflow-y: auto; }
  .auth-box { width: 100%; max-width: 445px; }
  .auth-box h2 { font-size: 1.7rem; color: #1a1a1a; margin-bottom: 4px; font-weight: 500; }
  .auth-box .subtitle { font-size: 15px; color: #1a1a1a; margin-bottom: 15px; }

  .form-label { font-size: .9rem; color: var(--label); font-weight: 500; margin-bottom: 5px; }
  .form-control { border: 1px solid var(--border); border-radius: 8px; padding: 12px 16px; font-size: .875rem; font-family: 'Open Sans', sans-serif; background: var(--input-bg); color: #222; width: 100%; }
  .form-control:focus { border-color: #1e5047; box-shadow: 0 0 0 3px rgba(30,80,71,.1); outline: none; }
  .form-control::placeholder { color: #bbb; }
  .form-control.is-invalid { border-color: #dc3545; }

  .btn-primary-custom { width: 100%; background: linear-gradient(254.49deg, #076550 50%, #0ECBA1 144.25%); color: #fff; border: none; border-radius: 8px; padding: 11px; font-size: .9rem; font-family: 'Open Sans', sans-serif; font-weight: 500; cursor: pointer; transition: opacity .2s; margin-top: 4px; }
  .btn-primary-custom:hover { opacity: .88; }
  .btn-primary-custom:disabled { opacity: .65; cursor: not-allowed; }

  .signup-link { text-align: center; font-size: 1rem; color: #052A14; margin-top: 10px; }
  .signup-link a { color: var(--teal-dark); font-weight: 700; text-decoration: none; }
  .forgot-link { font-size: .78rem; color: var(--teal-dark); text-decoration: none; font-weight: 500; }
  .forgot-link:hover { text-decoration: underline; }
  .trust-row { display: flex; gap: 14px; flex-wrap: wrap; justify-content: center; margin-top: 18px; }
  .trust-badge { display: flex; align-items: center; gap: 5px; font-size: .8rem; color: #1e1e1e; font-weight: 500; }
  .trust-badge svg { width: 13px; height: 13px; }
  .back-link { color: var(--teal-dark); font-size: .82rem; text-decoration: none; display: flex; align-items: center; gap: 5px; }
  .back-link:hover { text-decoration: underline; }

  .field-error { font-size: .75rem; color: #dc3545; margin-top: 3px; display: none; }
  .alert-error { background: #fff0f0; border: 1px solid #f5c6cb; border-radius: 8px; padding: 10px 14px; font-size: .82rem; color: #721c24; margin-bottom: 14px; display: none; }

  .page { display: none; }
  .page.active { display: block; }

  @media (max-width: 767px) {
    .left-panel { height: 220px; }
    .right-panel { height: auto; padding: 28px 24px; }
    .stats-overlay { width: 88%; }
  }
</style>
@endsection

@section('content')

{{-- ══════════════════════════════════════════════
     PAGE 1 – LOGIN
══════════════════════════════════════════════ --}}
<div class="page active" id="login-email">
  <div class="container-fluid p-0">
    <div class="row g-0" style="min-height:100vh">
      <div class="col-md-6 left-panel">
        <img class="bg-img" src="{{ asset('img/login-bg.png') }}" alt="StockWitty"/>
        <div class="stats-overlay">@include('auth._stat_cards')</div>
      </div>
      <div class="col-md-6 right-panel">
        <div class="auth-box">
          <h2>Welcome Back</h2>
          <p class="subtitle">Sign in to continue trading unlisted shares</p>

          <div class="alert-error" id="login-error"></div>

          <form id="loginForm" novalidate>
            @csrf
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" id="login-email-input" class="form-control" placeholder="Enter your email" required/>
              <div class="field-error" id="err-login-email"></div>
            </div>
            <div class="mb-1">
              <div class="d-flex justify-content-between align-items-center">
                <label class="form-label mb-0">Password</label>
                <a href="#" class="forgot-link" onclick="showPage('forgot'); return false;">Forgot password?</a>
              </div>
              <input type="password" name="password" id="login-password-input" class="form-control mt-1" placeholder="Enter your password" required/>
              <div class="field-error" id="err-login-password"></div>
            </div>
            <button type="submit" class="btn-primary-custom mt-3" id="loginBtn">Sign In</button>
          </form>

          <div class="signup-link mt-2">Don't have an account? <a href="#" onclick="showPage('signup'); return false;">Sign Up</a></div>
          @include('auth._trust_badges')
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════
     PAGE 2 – FORGOT PASSWORD
══════════════════════════════════════════════ --}}
<div class="page" id="forgot">
  <div class="container-fluid p-0">
    <div class="row g-0" style="min-height:100vh">
      <div class="col-md-6 left-panel">
        <img class="bg-img" src="{{ asset('img/login-bg.png') }}" alt="StockWitty"/>
        <div class="stats-overlay">@include('auth._stat_cards')</div>
      </div>
      <div class="col-md-6 right-panel">
        <div class="auth-box">
          <div class="mb-3">
            <a href="#" class="back-link" onclick="showPage('login-email'); return false;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
              Back to Sign In
            </a>
          </div>
          <h2>Forgot Password?</h2>
          <p class="subtitle">Enter your registered email and we'll send you a reset link</p>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="Enter your registered email"/>
          </div>
          <button class="btn-primary-custom" onclick="document.getElementById('reset-success').style.display='block'">Send Reset Link</button>
          <div class="mt-3 p-3" style="background:#f0faf6;border-radius:8px;font-size:.8rem;color:#1a3d35;border:1px solid #bde8d8;display:none" id="reset-success">
            ✅ Reset link sent! Check your inbox.
          </div>
          <div class="signup-link">Remember your password? <a href="#" onclick="showPage('login-email'); return false;">Sign In</a></div>
          @include('auth._trust_badges')
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════
     PAGE 3 – SIGN UP
══════════════════════════════════════════════ --}}
<div class="page" id="signup">
  <div class="container-fluid p-0">
    <div class="row g-0" style="min-height:100vh">
      <div class="col-md-6 left-panel dark-left">
        <div class="brand-logo">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
          StockWitty
        </div>
        <h3>Get Started With Us</h3>
        <p>Complete these easy steps to register</p>
        <ul class="feature-list">
          <li><span class="num">1</span> Live unlisted share prices</li>
          <li><span class="num">2</span> The witty weekly newsletter</li>
          <li><span class="num">3</span> Price alerts &amp; watchlists</li>
        </ul>
      </div>

      <div class="col-md-6 right-panel">
        <div class="auth-box">
          <div class="text-center mb-1" style="font-size:1.05rem;color:#444">
            Start Smart, Stay <span style="color:#c8960c;border-bottom:2px solid #c8960c">Witty</span> ✦
          </div>
          <h2 class="text-center" style="margin-bottom:2px">Sign Up Account</h2>
          <p class="subtitle text-center">Enter your data to create your profile</p>

          <div class="alert-error" id="register-error"></div>

          <form id="registerForm" novalidate>
            @csrf
            <input type="hidden" name="unlisted_user_type" value="unlisted"/>
            <div class="mb-2">
              <label class="form-label">Full Name</label>
              <input type="text" name="name" class="form-control" placeholder="Enter your full name" required/>
              <div class="field-error" id="err-name"></div>
            </div>
            <div class="mb-2">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="Enter your email" required/>
              <div class="field-error" id="err-email"></div>
            </div>
            <div class="mb-2">
              <label class="form-label">Phone Number</label>
              <input type="tel" name="phone" class="form-control" placeholder="10-digit mobile number" maxlength="10" required/>
              <div class="field-error" id="err-phone"></div>
            </div>
            <div class="mb-1">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" placeholder="At least 6 characters" required/>
              <div class="field-error" id="err-password"></div>
            </div>
            <button type="submit" class="btn-primary-custom mt-2" id="registerBtn">Create Account</button>
          </form>

          <div class="signup-link">Already have an account? <a href="#" onclick="showPage('login-email'); return false;">Sign In</a></div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // ── Page switcher ──────────────────────────────────────────
  function showPage(id) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    window.scrollTo(0, 0);
  }

  // Check URL hash on load
  const hash = window.location.hash.replace('#', '');
  if (hash && document.getElementById(hash)) showPage(hash);

  // ── Helper: clear all field errors in a form ──────────────
  function clearErrors(formId) {
    $('#' + formId + ' .form-control').removeClass('is-invalid');
    $('#' + formId + ' .field-error').hide().text('');
    $('#' + formId.replace('Form','') + '-error').hide().text('');
  }

  function showFieldErrors(errors, prefix) {
    $.each(errors, function(field, messages) {
      var $input = $('[name="' + field + '"]').closest('form').find('[name="' + field + '"]');
      $input.addClass('is-invalid');
      var $err = $('#err-' + (prefix ? prefix + '-' : '') + field);
      if (!$err.length) $err = $('#err-' + field);
      if ($err.length) $err.text(messages[0]).show();
    });
  }

  // ── LOGIN via jQuery AJAX ─────────────────────────────────
  $('#loginForm').on('submit', function(e) {
    e.preventDefault();
    clearErrors('loginForm');

    var $btn = $('#loginBtn');
    $btn.prop('disabled', true).text('Signing in…');

    $.ajax({
      url: '{{ route("login.submit") }}',
      method: 'POST',
      data: $(this).serialize(),
      success: function(res) {
        if (res.success) {
          window.location.href = res.redirect || '/';
        } else {
          $('#login-error').text(res.message || 'Something went wrong.').show();
          $btn.prop('disabled', false).text('Sign In');
        }
      },
      error: function(xhr) {
        var res = xhr.responseJSON || {};
        if (xhr.status === 422 && res.errors) {
          showFieldErrors(res.errors, 'login');
        } else {
          $('#login-error').text(res.message || 'Something went wrong. Please try again.').show();
        }
        $btn.prop('disabled', false).text('Sign In');
      }
    });
  });

  // ── REGISTER via jQuery AJAX ──────────────────────────────
  $('#registerForm').on('submit', function(e) {
    e.preventDefault();
    clearErrors('registerForm');

    var $btn = $('#registerBtn');
    $btn.prop('disabled', true).text('Creating account…');

    $.ajax({
      url: '{{ route("register.submit") }}',
      method: 'POST',
      data: $(this).serialize(),
      success: function(res) {
        if (res.success) {
          window.location.href = res.redirect || '/';
        } else {
          $('#register-error').text(res.message || 'Something went wrong.').show();
          $btn.prop('disabled', false).text('Create Account');
        }
      },
      error: function(xhr) {
        var res = xhr.responseJSON || {};
        if (xhr.status === 422 && res.errors) {
          showFieldErrors(res.errors, '');
        } else {
          $('#register-error').text(res.message || 'Something went wrong. Please try again.').show();
        }
        $btn.prop('disabled', false).text('Create Account');
      }
    });
  });
</script>
@endpush
