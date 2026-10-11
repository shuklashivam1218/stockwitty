@extends('layout.admin')

@section('title', 'Unlisted Stocks | Admin | StocksWitty')

@php
    use App\Http\Controllers\UnlistedStockEditorController as Editor;

    // The company page's own content; About / Thesis / WittyScore live in the CMS.
    $chipLabels = ['Profile', 'Price', 'Financials', 'Insights', 'FAQs', 'SEO'];
    $chipTitles = ['on' => 'live on the site', 'off' => 'saved but switched off', 'none' => 'not filled in yet'];
@endphp

@section('content')
<div class="admin-main">

    <div class="sl-head">
        <h1 class="admin-page-title">Unlisted Stocks</h1>
        <div class="sl-head-actions">
            <button type="button" class="cms-action-btn js-add-industry"><i class="fa-solid fa-industry"></i> Add Industry</button>
            <a href="{{ route('admin.unlisted.stocks.create') }}" class="cms-new-btn"><i class="fa-solid fa-plus"></i> New Stock</a>
        </div>
    </div>

    @if(session('success'))
        <div class="cms-flash-success">{{ session('success') }}</div>
    @endif

    <div class="admin-card">

        <div class="sl-toolbar">
            <div class="cms-status-tabs">
                @foreach(['' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'] as $key => $label)
                    <a href="{{ route('admin.unlisted', array_filter(['status' => $key, 'search' => $search])) }}"
                       class="cms-status-tab {{ $status === $key ? 'active' : '' }}">
                        {{ $label }} <span class="sl-count">{{ $counts[$key] }}</span>
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.unlisted') }}" class="sl-search">
                @if($status !== '') <input type="hidden" name="status" value="{{ $status }}"> @endif
                <input type="search" name="search" value="{{ $search }}" placeholder="Name, short name, ISIN or fincode" class="cms-search-input">
                <button type="submit" class="cms-search-btn" aria-label="Search"><i class="fa-solid fa-search"></i></button>
                @if($search !== '')
                    <a href="{{ route('admin.unlisted', array_filter(['status' => $status])) }}" class="sl-clear">Clear</a>
                @endif
            </form>
        </div>

        <div class="sl-legend">
            <span><i class="sl-chip-dot on"></i> Live</span>
            <span><i class="sl-chip-dot off"></i> Saved, switched off</span>
            <span><i class="sl-chip-dot none"></i> Empty</span>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table sl-table">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Latest price</th>
                        <th>Category</th>
                        <th>Content</th>
                        <th>On site</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        @php
                            $fc       = $stock->UL_STOCKS_FINCODE;
                            $editUrl  = route('admin.unlisted.stocks.edit', $fc);
                            $lp       = $latestPrices->get($fc);
                            $chips    = ($content[$fc] ?? []) + ['Profile' => filled($stock->UL_STOCKS_ABOUT) ? 'on' : null];
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ $editUrl }}" class="sl-company">
                                    {{-- The initial sits underneath; a logo that fails to load removes itself and reveals it. --}}
                                    <span class="sl-logo">
                                        {{ mb_strtoupper(mb_substr($stock->UL_STOCKS_COMPNAME ?? '?', 0, 1)) }}
                                        @if($stock->UL_STOCKS_LOGO_LINK)
                                            <img src="{{ asset($stock->UL_STOCKS_LOGO_LINK) }}" alt="" loading="lazy" onerror="this.remove()">
                                        @endif
                                    </span>
                                    <span>
                                        <strong>{{ $stock->UL_STOCKS_COMPNAME }}</strong>
                                        <small>#{{ $fc }} · /{{ $stock->UL_STOCKS_SLUG }}/</small>
                                    </span>
                                </a>
                            </td>
                            <td class="sl-price">
                                @if($lp)
                                    &#8377;{{ number_format($lp->UL_PD_BID_PRICE, 2) }}
                                    <small>{{ \Carbon\Carbon::parse($lp->UL_PD_DATE)->format('d M Y') }}</small>
                                @else
                                    <span class="sl-muted">—</span>
                                @endif
                            </td>
                            <td>
                                {{ Editor::CATEGORIES[$stock->UL_STOCKS_CATEGORY] ?? '—' }}
                                @if($stock->UL_STOCKS_TAG)
                                    <span class="admin-badge badge-admin" style="margin-left:4px;">{{ $stock->UL_STOCKS_TAG }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="sl-chips">
                                    @foreach($chipLabels as $label)
                                        @php $state = $chips[$label] ?? 'none'; @endphp
                                        <span class="sl-chip {{ $state }}" title="{{ $label }}: {{ $chipTitles[$state] }}">{{ $label }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <label class="se-switch" title="Show or hide this stock on the public site">
                                    <input type="checkbox" class="stock-toggle" data-fincode="{{ $fc }}" @checked($stock->UL_STOCKS_STATUS === '1')>
                                    <span class="se-switch-track"></span>
                                    <span class="se-switch-label"><span class="se-on">Active</span><span class="se-off">Inactive</span></span>
                                </label>
                            </td>
                            <td class="sl-actions">
                                <a href="{{ $editUrl }}" class="cms-action-btn"><i class="fa-solid fa-pen"></i> Edit</a>
                                <a href="{{ route('sw.unlisted-shares.company', $stock->UL_STOCKS_SLUG) }}" target="_blank" rel="noopener"
                                   class="sl-live-link" title="Open the public page"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="sl-empty">
                                <i class="fa-regular fa-folder-open"></i>
                                @if($search !== '' || $status !== '')
                                    No stocks match. <a href="{{ route('admin.unlisted') }}">Show all</a>
                                @else
                                    No stocks yet. <a href="{{ route('admin.unlisted.stocks.create') }}">Add the first one</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($stocks->hasPages())
            <div style="margin-top:16px">{{ $stocks->links() }}</div>
        @endif
    </div>
</div>

@include('admin.unlisted.industry-modal')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/cms-index.css') }}?v={{ filemtime(public_path('assets/css/admin/cms-index.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin/stock-editor.css') }}?v={{ filemtime(public_path('assets/css/admin/stock-editor.css')) }}">
@endpush
@endsection

@push('scripts')
<script>
$(function () {
    const CSRF = $('meta[name="csrf-token"]').attr('content');
    const BASE = @json(url('/admin/unlisted/stocks'));

    // The endpoint flips the status; the response says which way it went.
    $(document).on('change', '.stock-toggle', function () {
        const $cb = $(this);
        $.ajax({ url: BASE + '/' + $cb.data('fincode') + '/toggle', method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF } })
            .done(function (res) {
                if (res.success) $cb.prop('checked', res.status === '1');
                else $cb.prop('checked', !$cb.prop('checked'));
            })
            .fail(function () {
                $cb.prop('checked', !$cb.prop('checked'));
                alert('Could not change the status. Please try again.');
            });
    });
});
</script>
@endpush
