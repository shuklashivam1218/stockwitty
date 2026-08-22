<style>
.sw-navbar { background: #fff; border-bottom: 1px solid #e8e8e8; position: sticky; top: 0; z-index: 1000; }
.sw-navbar .container { display: flex; align-items: center; height: 64px; gap: 32px; }
.sw-brand { font-size: 1.35rem; font-weight: 700; color: #076550; text-decoration: none; letter-spacing: -.5px; flex-shrink: 0; }
.sw-brand span { color: #0ECBA1; }
.sw-nav { display: flex; align-items: center; gap: 4px; flex: 1; }
.sw-nav a { padding: 6px 14px; border-radius: 6px; font-size: .9rem; color: #444; text-decoration: none; font-weight: 500; transition: background .15s, color .15s; }
.sw-nav a:hover { background: #f4f4f4; color: #076550; }
.sw-nav a.active { color: #076550; background: #e8f5f1; font-weight: 600; }

.sw-auth { display: flex; align-items: center; gap: 8px; margin-left: auto; }
.sw-auth .btn-signin { border: 1.5px solid #076550; color: #076550; background: transparent; border-radius: 8px; padding: 7px 18px; font-size: .875rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: background .15s, color .15s; }
.sw-auth .btn-signin:hover { background: #076550; color: #fff; }
.sw-auth .btn-started { background: #076550; color: #fff; border: none; border-radius: 8px; padding: 7px 18px; font-size: .875rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: opacity .15s; }
.sw-auth .btn-started:hover { opacity: .88; color: #fff; }

/* User dropdown */
.sw-user { position: relative; }
.sw-user-trigger { display: flex; align-items: center; gap: 8px; background: none; border: 1.5px solid #e0e0e0; border-radius: 8px; padding: 6px 12px 6px 6px; cursor: pointer; font-family: inherit; font-size: .875rem; font-weight: 500; color: #222; transition: border-color .15s; }
.sw-user-trigger:hover { border-color: #076550; }
.sw-user-avatar { width: 28px; height: 28px; border-radius: 50%; background: #076550; color: #fff; display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 700; flex-shrink: 0; }
.sw-user-chevron { margin-left: 4px; color: #888; font-size: .7rem; transition: transform .2s; }
.sw-user.open .sw-user-chevron { transform: rotate(180deg); }
.sw-dropdown { position: absolute; top: calc(100% + 8px); right: 0; background: #fff; border: 1px solid #e8e8e8; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.1); min-width: 200px; padding: 6px; display: none; z-index: 2000; }
.sw-user.open .sw-dropdown { display: block; }
.sw-dropdown a, .sw-dropdown button { display: flex; align-items: center; gap: 9px; padding: 9px 12px; border-radius: 7px; font-size: .855rem; color: #333; text-decoration: none; cursor: pointer; background: none; border: none; width: 100%; font-family: inherit; font-weight: 500; transition: background .12s; }
.sw-dropdown a:hover, .sw-dropdown button:hover { background: #f5f5f5; }
.sw-dropdown .danger { color: #dc3545; }
.sw-dropdown .danger:hover { background: #fff5f5; }
.sw-dropdown hr { border: none; border-top: 1px solid #f0f0f0; margin: 4px 0; }

/* Hamburger */
.sw-hamburger { display: none; background: none; border: none; padding: 4px; cursor: pointer; flex-direction: column; gap: 5px; }
.sw-hamburger span { display: block; width: 22px; height: 2px; background: #333; border-radius: 2px; transition: all .2s; }

@media (max-width: 767px) {
  .sw-nav { display: none; position: absolute; top: 64px; left: 0; right: 0; background: #fff; border-bottom: 1px solid #e8e8e8; flex-direction: column; align-items: stretch; gap: 0; padding: 8px 16px 12px; }
  .sw-nav.open { display: flex; }
  .sw-nav a { padding: 10px 12px; }
  .sw-hamburger { display: flex; }
  .sw-auth .btn-started { display: none; }
}
</style>

<nav class="sw-navbar">
  <div class="container" style="position:relative;">

    <a href="{{ url('/') }}" class="sw-brand">Stock<span>Witty</span></a>

    <div class="sw-nav" id="swNav">
      <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
      <a href="{{ url('/unlisted') }}" class="{{ request()->is('unlisted*') ? 'active' : '' }}">Unlisted Shares</a>
      <a href="{{ url('/blog') }}" class="{{ request()->is('blog*') ? 'active' : '' }}">Blog</a>
    </div>

    <div class="sw-auth">
      @if(session('uid'))
        @php
          $displayName = session('name', session('email', 'User'));
          $initial = strtoupper(mb_substr($displayName, 0, 1));
          $firstName = explode(' ', trim($displayName))[0];
        @endphp
        <div class="sw-user" id="swUser">
          <button class="sw-user-trigger" id="swUserTrigger">
            <span class="sw-user-avatar">{{ $initial }}</span>
            <span>{{ $firstName }}</span>
            <svg class="sw-user-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sw-dropdown" id="swDropdown">
            @if(!empty(session('privilege')))
              <a href="{{ url('/admin') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Admin Panel
              </a>
              <hr>
            @endif
            <form action="{{ route('logout') }}" method="POST" style="margin:0">
              @csrf
              <button type="submit" class="danger">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
              </button>
            </form>
          </div>
        </div>
      @else
        <a href="{{ url('/login') }}" class="btn-signin">Sign In</a>
        <a href="{{ url('/login') }}#signup" class="btn-started">Get Started</a>
      @endif
    </div>

    <button class="sw-hamburger" id="swHamburger" aria-label="Toggle menu">
      <span></span><span></span><span></span>
    </button>

  </div>
</nav>

<script>
  // User dropdown toggle
  (function(){
    var user = document.getElementById('swUser');
    var trigger = document.getElementById('swUserTrigger');
    if (trigger) {
      trigger.addEventListener('click', function(e){ e.stopPropagation(); user.classList.toggle('open'); });
      document.addEventListener('click', function(){ if(user) user.classList.remove('open'); });
    }
    // Mobile hamburger
    var ham = document.getElementById('swHamburger');
    var nav = document.getElementById('swNav');
    if (ham) {
      ham.addEventListener('click', function(){ nav.classList.toggle('open'); });
    }
  })();
</script>
