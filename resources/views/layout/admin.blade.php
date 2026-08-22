<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="semi-dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin | StockWitty')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin-theme/plugins/simplebar/css/simplebar.css') }}?v={{ filemtime(public_path('assets/admin-theme/plugins/simplebar/css/simplebar.css')) }}">
    <link rel="stylesheet" href="{{ asset('assets/admin-theme/plugins/metismenu/css/metisMenu.min.css') }}?v={{ filemtime(public_path('assets/admin-theme/plugins/metismenu/css/metisMenu.min.css')) }}">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin-theme/css/pace.min.css') }}?v={{ filemtime(public_path('assets/admin-theme/css/pace.min.css')) }}">
    <link rel="stylesheet" href="{{ asset('assets/admin-theme/css/bootstrap.min.css') }}?v={{ filemtime(public_path('assets/admin-theme/css/bootstrap.min.css')) }}">
    <link rel="stylesheet" href="{{ asset('assets/admin-theme/css/bootstrap-extended.css') }}?v={{ filemtime(public_path('assets/admin-theme/css/bootstrap-extended.css')) }}">
    <link rel="stylesheet" href="{{ asset('assets/admin-theme/css/app.css') }}?v={{ filemtime(public_path('assets/admin-theme/css/app.css')) }}">
    <link rel="stylesheet" href="{{ asset('assets/admin-theme/css/semi-dark.css') }}?v={{ filemtime(public_path('assets/admin-theme/css/semi-dark.css')) }}">

    <!-- Icons (Font Awesome — reliable CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Pace loader -->
    <script src="{{ asset('assets/admin-theme/js/pace.min.js') }}?v={{ filemtime(public_path('assets/admin-theme/js/pace.min.js')) }}" defer></script>

    <!-- Global Admin CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v={{ filemtime(public_path('assets/css/admin.css')) }}">

    @stack('styles')
</head>

<body>
@php
    $adminName    = session('name', session('email', 'Admin'));
    $adminInitial = strtoupper(mb_substr($adminName, 0, 1));
    $priv         = session('privilege', []);
@endphp

<div class="wrapper">

    {{-- ══════════════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════════════ --}}
    <div class="sidebar-wrapper" data-simplebar="true">

        <div class="sidebar-header">
            <a href="{{ url('/admin/dashboard') }}" class="sw-logo-link">
                <span class="sw-logo-text">Stock<span>Witty</span></span>
            </a>
            <div class="toggle-icon ms-auto" title="Toggle Sidebar">
                <i class="fa-solid fa-angles-left"></i>
            </div>
        </div>

        <ul class="metismenu" id="menu">

            @if(!empty($priv['admin']))
            <li class="{{ request()->is('admin/dashboard') ? 'mm-active' : '' }}">
                <a href="{{ url('/admin/dashboard') }}">
                    <div class="parent-icon"><i class="fa-solid fa-house"></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>
            @endif

            @php
                $_ul    = $priv['unlisted'] ?? [];
                $_ulAny = !empty($priv['admin']) || !empty($priv['user_master']) || !empty(array_filter($_ul));
            @endphp
            @if($_ulAny)
            <li class="{{ request()->is('admin/unlisted*') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="fa-solid fa-chart-bar"></i></div>
                    <div class="menu-title">Unlisted Stocks</div>
                </a>
                <ul>
                    @if(!empty($priv['admin']) || !empty($_ul['stockx']))
                    <li><a href="{{ url('/admin/unlisted') }}"
                           class="{{ request()->is('admin/unlisted') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-dot me-1" style="font-size:8px;"></i>Stocks
                    </a></li>
                    @if(request()->is('admin/unlisted'))
                    <li class="sidebar-action-group">
                        <button id="stocksNavBtn" class="sidebar-action-btn">
                            <i class="fa-solid fa-plus"></i> Add Stock
                        </button>
                        <button id="industryNavBtn" class="sidebar-action-btn">
                            <i class="fa-solid fa-industry"></i> Add Industry
                        </button>
                    </li>
                    @endif
                    @endif
                    @if(!empty($priv['admin']) || !empty($_ul['leads']))
                    <li><a href="{{ url('/admin/unlisted/leads') }}"
                           class="{{ request()->is('admin/unlisted/leads*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-dot me-1" style="font-size:8px;"></i>Leads
                    </a></li>
                    @endif
                    @if(!empty($priv['admin']) || !empty($_ul['orders']))
                    <li><a href="{{ url('/admin/unlisted/orders') }}"
                           class="{{ request()->is('admin/unlisted/orders*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-dot me-1" style="font-size:8px;"></i>Orders
                    </a></li>
                    @endif
                    @if(!empty($priv['admin']) || !empty($_ul['unlisted_reports']))
                    <li><a href="{{ url('/admin/unlisted/reports') }}"
                           class="{{ request()->is('admin/unlisted/reports*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-dot me-1" style="font-size:8px;"></i>Reports
                    </a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(!empty($priv['admin']) || !empty($priv['user_master']))
            <li class="{{ request()->is('admin/users*') ? 'mm-active' : '' }}">
                <a href="{{ url('/admin/users') }}">
                    <div class="parent-icon"><i class="fa-solid fa-users"></i></div>
                    <div class="menu-title">Users</div>
                </a>
            </li>
            @endif

            @if(!empty($priv['admin']))
            <li class="{{ request()->is('admin/cms*') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="fa-solid fa-file-lines"></i></div>
                    <div class="menu-title">CMS</div>
                </a>
                <ul>
                    <li><a href="{{ url('/admin/cms') }}"
                           class="{{ request()->is('admin/cms') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-dot me-1" style="font-size:8px;"></i>Disclaimer Page
                    </a></li>
                </ul>
            </li>
            @endif

        </ul>
    </div>
    {{-- END SIDEBAR --}}

    {{-- ══════════════════════════════════════════════
         TOPBAR
    ══════════════════════════════════════════════ --}}
    <header>
        <div class="topbar">
            <nav class="navbar navbar-expand">

                <div class="mobile-toggle-menu" title="Toggle Menu">
                    <i class="fa-solid fa-bars"></i>
                </div>

                <div class="top-menu ms-auto">
                    <ul class="navbar-nav align-items-center gap-1">

                        <li class="nav-item">
                            <a class="nav-link topbar-icon-btn" href="{{ url('/') }}" target="_blank" title="View Site">
                                <i class="fa-solid fa-globe"></i>
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret sw-user-trigger"
                               href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar-sm">{{ $adminInitial }}</div>
                                <span class="d-none d-md-inline ms-1" style="font-size:13px;font-weight:500;color:#333;">{{ $adminName }}</span>
                                <i class="fa-solid fa-chevron-down ms-1" style="font-size:10px;color:#aaa;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end sw-user-dropdown">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar-lg">{{ $adminInitial }}</div>
                                        <div>
                                            <div style="font-size:13px;font-weight:600;color:#111;line-height:1.3">{{ $adminName }}</div>
                                            <div style="font-size:11px;color:#999;">Administrator</div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ url('/') }}" target="_blank">
                                        <i class="fa-solid fa-globe" style="font-size:14px;color:#666;"></i> View Public Site
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-right-from-bracket" style="font-size:14px;"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
        </div>
    </header>
    {{-- END TOPBAR --}}

    {{-- ══════════════════════════════════════════════
         PAGE CONTENT
    ══════════════════════════════════════════════ --}}
    <div class="page-wrapper">
        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <div class="overlay toggle-icon"></div>

</div>{{-- .wrapper --}}

{{-- JS --}}
<script src="{{ asset('assets/admin-theme/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin-theme/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/admin-theme/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/admin-theme/plugins/metismenu/js/metisMenu.min.js') }}"></script>

<script>
$(function () {
    // Metismenu
    $('#menu').metisMenu();

    // Sidebar toggle (collapse to icon rail)
    $('.toggle-icon').on('click', function () {
        if ($('.wrapper').hasClass('toggled')) {
            $('.wrapper').removeClass('toggled');
            $('.sidebar-wrapper').off('mouseenter mouseleave');
        } else {
            $('.wrapper').addClass('toggled');
            $('.sidebar-wrapper').on('mouseenter', function () {
                $('.wrapper').addClass('sidebar-hovered');
            }).on('mouseleave', function () {
                $('.wrapper').removeClass('sidebar-hovered');
            });
        }
    });

    // Mobile hamburger — show sidebar as overlay
    $('.mobile-toggle-menu').on('click', function () {
        $('.wrapper').addClass('toggled');
    });

    // Click overlay to close mobile sidebar
    $('.overlay').on('click', function () {
        $('.wrapper').removeClass('toggled');
    });

    // Auto-highlight active menu item
    var currentUrl = window.location.href;
    $('#menu a').filter(function () {
        return this.href === currentUrl;
    }).parentsUntil('#menu', 'li').addClass('mm-active');
});
</script>

@stack('scripts')
</body>
</html>
