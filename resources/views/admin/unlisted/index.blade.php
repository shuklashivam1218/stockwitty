@extends('layout.admin')

@section('title', 'Unlisted Stocks | Admin | StockWitty')

@push('styles')
<style>
    /* ── Toggle switch ─────────────── */
    .tgl-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        cursor: pointer;
    }

    .tgl-switch input {
        opacity: 0;
        width: 0;
        height: 0;
        position: absolute;
    }

    .tgl-slider {
        position: absolute;
        inset: 0;
        background: #ccc;
        border-radius: 24px;
        transition: background 0.2s;
    }

    .tgl-slider::before {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        left: 3px;
        top: 3px;
        background: #fff;
        border-radius: 50%;
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }

    .tgl-switch input:checked+.tgl-slider {
        background: #076550;
    }

    .tgl-switch input:checked+.tgl-slider::before {
        transform: translateX(20px);
    }

    /* ── Price / Thesis / Financials cell ─ */
    .ptf-cell {
        white-space: nowrap;
        font-size: 13px;
    }

    .ptf-label {
        color: #1a1a1a;
        font-weight: 500;
    }

    .ptf-sep {
        color: #ccc;
        margin: 0 4px;
    }

    .ptf-icon-edit {
        color: #2196f3;
        font-size: 11px;
        cursor: pointer;
    }

    .ptf-icon-add {
        color: #4caf50;
        font-size: 11px;
        cursor: pointer;
    }

    .ptf-icon-view {
        color: #2196f3;
        font-size: 11px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="admin-main">

    <h1 class="admin-page-title">Dashboard</h1>

    <div class="admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fincode</th>
                        <th>Company</th>
                        <th>Latest Price</th>
                        <th>Price / Thesis / About / Financials / FAQ</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                    <tr>
                        <td>{{ $stock->UL_STOCKS_FINCODE }}</td>
                        <td>
                            <a href="{{ route('sw.unlisted-shares.company', $stock->UL_STOCKS_SLUG) }}" target="_blank" rel="noopener"
                               style="color:inherit;text-decoration:none;font-weight:inherit;">
                                {{ $stock->UL_STOCKS_COMPNAME }}
                                <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;color:#94a3b8;margin-left:4px;"></i>
                            </a>
                        </td>
                        <td style="white-space:nowrap;font-size:13px;font-weight:600;color:#1a1a1a">
                            @php $lp = $latestPrices->get($stock->UL_STOCKS_FINCODE); @endphp
                            @if($lp)
                                &#8377;{{ number_format($lp->UL_PD_BID_PRICE, 2) }}
                            @else
                                <span style="color:#cbd5e1;font-weight:400">—</span>
                            @endif
                        </td>
                        <td class="ptf-cell">
                            <span class="ptf-label">Overview</span>
                            <i class="fa-solid fa-pen ptf-icon-edit overview-btn"
                                data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                                data-name="{{ $stock->UL_STOCKS_COMPNAME }}"
                                style="cursor:pointer" title="Edit overview"></i>
                            <span class="ptf-sep">|</span>
                            <span class="ptf-label">Price</span>
                            <i class="fa-solid fa-plus ptf-icon-add price-add-btn"
                                data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                                data-name="{{ $stock->UL_STOCKS_COMPNAME }}"
                                style="cursor:pointer" title="Add price"></i>
                            <i class="fa-regular fa-eye ptf-icon-view price-view-btn"
                                data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                                style="cursor:pointer" title="View prices"></i>
                            <span class="ptf-sep">|</span>
                            <span class="ptf-label">Financials</span>
                            <i class="fa-solid fa-plus ptf-icon-add fin-add-btn"
                               data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                               data-name="{{ $stock->UL_STOCKS_COMPNAME }}"
                               style="cursor:pointer" title="Add financials"></i>
                            <i class="fa-regular fa-eye ptf-icon-view fin-view-btn"
                               data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                               style="cursor:pointer" title="View financials"></i>
                            <span class="ptf-sep">|</span>
                            <span class="ptf-label">Thesis</span>
                            <i class="fa-solid fa-pen ptf-icon-edit thesis-btn"
                               data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                               data-name="{{ $stock->UL_STOCKS_COMPNAME }}"
                               style="cursor:pointer" title="Edit thesis"></i>
                            <span class="ptf-sep">|</span>
                            <span class="ptf-label">About</span>
                            <i class="fa-solid fa-pen ptf-icon-edit about-btn"
                               data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                               data-name="{{ $stock->UL_STOCKS_COMPNAME }}"
                               style="cursor:pointer" title="Edit about"></i>
                            <span class="ptf-sep">|</span>
                            <span class="ptf-label">WittyScore</span>
                            <i class="fa-solid fa-pen ptf-icon-edit witty-score-btn"
                               data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                               data-name="{{ $stock->UL_STOCKS_COMPNAME }}"
                               style="cursor:pointer" title="Edit WittyScore"></i>
                            <span class="ptf-sep">|</span>
                            <span class="ptf-label">About Sections</span>
                            <i class="fa-solid fa-pen ptf-icon-edit about-extra-btn"
                               data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                               data-name="{{ $stock->UL_STOCKS_COMPNAME }}"
                               style="cursor:pointer" title="Edit About page sections"></i>
                            <span class="ptf-sep">|</span>
                            <span class="ptf-label">Insights</span>
                            <i class="fa-solid fa-pen ptf-icon-edit insights-btn"
                               data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                               data-name="{{ $stock->UL_STOCKS_COMPNAME }}"
                               style="cursor:pointer" title="Edit AI Summary / Founder's Take / IPO Roadmap"></i>
                            <span class="ptf-sep">|</span>
                            <span class="ptf-label">FAQ</span>
                            <i class="fa-regular fa-eye ptf-icon-view faq-view-btn"
                               data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                               style="cursor:pointer" title="Manage FAQs"></i>
                        </td>
                        <td>
                            <span class="admin-badge {{ $stock->UL_STOCKS_STATUS === '1' ? 'badge-admin' : 'badge-locked' }}">
                                {{ $stock->UL_STOCKS_STATUS === '1' ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <label class="tgl-switch" title="Toggle status">
                                <input type="checkbox"
                                    class="stock-toggle"
                                    data-fincode="{{ $stock->UL_STOCKS_FINCODE }}"
                                    {{ $stock->UL_STOCKS_STATUS === '1' ? 'checked' : '' }}>
                                <span class="tgl-slider"></span>
                            </label>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:#aaa;padding:32px">
                            <i class="fa-regular fa-folder-open" style="font-size:24px;display:block;margin-bottom:8px"></i>
                            No stocks added yet. Click <strong>+ Add Stocks</strong> to get started.
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

{{-- Must load before any modal's tinymce.init() call — those now run at
     page-load (via @push('scripts')) rather than on first click, so the
     library itself has to be present in the DOM earlier than the stack. --}}
<script src="{{ asset('js/tinymce_6.1.2/tinymce.min.js') }}"></script>

@include('admin.unlisted.stocks-modal')
@include('admin.unlisted.industry-modal')
@include('admin.unlisted.overview-modal', ['industries' => $industries])
@include('admin.unlisted.price-modal')
@include('admin.unlisted.financials-modal')
@include('admin.unlisted.thesis-modal')
@include('admin.unlisted.about-modal')
@include('admin.unlisted.witty-score-modal')
@include('admin.unlisted.about-extra-modal')
@include('admin.unlisted.insights-modal')
@include('admin.unlisted.faq-modal')

{{-- Price list modal container — still server-rendered on demand (paginated table) --}}
<div id="priceListModalWrap"></div>

{{-- Financials list modal container — still server-rendered on demand (paginated table) --}}
<div id="finListModalWrap"></div>

{{-- FAQ list modal container — still server-rendered on demand (table) --}}
<div id="faqListModalWrap"></div>
@endsection

@push('scripts')
<script>
    // globally available so modal blade scripts can reference them
    window.STOCKS_BASE = '{{ url("/admin/unlisted/stocks") }}';
    var CSRF = $('meta[name="csrf-token"]').attr('content');

    // ── Stock toggle ───────────────────────────────────────
    $(document).on('change', '.stock-toggle', function () {
        var $cb     = $(this);
        var fincode = $cb.data('fincode');
        var $badge  = $cb.closest('tr').find('.admin-badge');
        $.ajax({ url: window.STOCKS_BASE + '/' + fincode + '/toggle', method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF } })
            .done(function (res) {
                if (res.success) {
                    var active = res.status === '1';
                    $badge.text(active ? 'Active' : 'Inactive')
                          .removeClass('badge-admin badge-locked')
                          .addClass(active ? 'badge-admin' : 'badge-locked');
                } else { $cb.prop('checked', !$cb.prop('checked')); }
            })
            .fail(function () { $cb.prop('checked', !$cb.prop('checked')); });
    });

    // ── Overview modal ─────────────────────────────────────
    $(document).on('click', '.overview-btn', function () {
        window.openOverviewModal($(this).data('fincode'), $(this).data('name'));
    });

    // ── Price add modal ────────────────────────────────────
    $(document).on('click', '.price-add-btn', function () {
        window.openPriceModal($(this).data('fincode'), $(this).data('name'));
    });

    // ── Price list modal ───────────────────────────────────
    window.plFincode = null;

    $(document).on('click', '.price-view-btn', function () {
        window.plFincode = $(this).data('fincode');
        loadPriceListPage(1);
    });

    function loadPriceListPage(page) {
        if (!window.plFincode) return;
        $('#priceListModalWrap').html(loadingSpinner());
        $.ajax({
            url:    window.STOCKS_BASE + '/' + window.plFincode + '/price-list',
            method: 'POST',
            data:   { _token: CSRF, page: page },
        })
        .done(function (html) { $('#priceListModalWrap').html(html); })
        .fail(function ()     { $('#priceListModalWrap').empty(); alert('Failed to load.'); });
    }

    function closePriceListModal() {
        $('#priceListModalWrap').empty();
        window.plFincode = null;
    }

    // paginator — single permanent handler for all pages in the project
    $(document).on('click', '.pagi-btn:not(:disabled)', function () {
        var fn   = $(this).data('cb');
        var page = $(this).data('page');
        if (fn && typeof window[fn] === 'function') window[fn](page);
    });

    // ── Financials add modal ───────────────────────────────
    $(document).on('click', '.fin-add-btn', function () {
        window.openFinancialsModal($(this).data('fincode'), $(this).data('name'));
    });

    // ── Financials list modal ──────────────────────────────
    window.flFincode = null;

    $(document).on('click', '.fin-view-btn', function () {
        window.flFincode = $(this).data('fincode');
        loadFinancialsListPage(1);
    });

    function loadFinancialsListPage(page) {
        if (!window.flFincode) return;
        $('#finListModalWrap').html(loadingSpinner());
        $.ajax({
            url:    window.STOCKS_BASE + '/' + window.flFincode + '/financials-list',
            method: 'POST',
            data:   { _token: CSRF, page: page },
        })
        .done(function (html) { $('#finListModalWrap').html(html); })
        .fail(function ()     { $('#finListModalWrap').empty(); alert('Failed to load.'); });
    }

    function closeFinancialsListModal() {
        $('#finListModalWrap').empty();
        window.flFincode = null;
    }

    // ── Thesis modal ───────────────────────────────────────
    $(document).on('click', '.thesis-btn', function () {
        window.openThesisModal($(this).data('fincode'), $(this).data('name'));
    });

    // ── About modal ─────────────────────────────────────────
    $(document).on('click', '.about-btn', function () {
        window.openAboutModal($(this).data('fincode'), $(this).data('name'));
    });

    // ── WittyScore modal ────────────────────────────────────
    $(document).on('click', '.witty-score-btn', function () {
        window.openWittyScoreModal($(this).data('fincode'), $(this).data('name'));
    });

    // ── About Sections (structured About page) modal ───────
    $(document).on('click', '.about-extra-btn', function () {
        window.openAboutExtraModal($(this).data('fincode'), $(this).data('name'));
    });

    // ── Company Insights modal ─────────────────────────────
    $(document).on('click', '.insights-btn', function () {
        window.openInsightsModal($(this).data('fincode'), $(this).data('name'));
    });

    // ── FAQ list modal ──────────────────────────────────────
    window.fqFincode = null;

    $(document).on('click', '.faq-view-btn', function () {
        window.fqFincode = $(this).data('fincode');
        loadFaqList();
    });

    function loadFaqList() {
        if (!window.fqFincode) return;
        $('#faqListModalWrap').html(loadingSpinner());
        $.get(window.STOCKS_BASE + '/' + window.fqFincode + '/faqs-list')
            .done(function (html) { $('#faqListModalWrap').html(html); })
            .fail(function ()     { $('#faqListModalWrap').empty(); alert('Failed to load.'); });
    }

    function closeFaqListModal() {
        $('#faqListModalWrap').empty();
        window.fqFincode = null;
    }

    // ── Shared loading spinner ─────────────────────────────
    function loadingSpinner() {
        return '<div style="position:fixed;inset:0;background:rgba(15,23,42,0.55);z-index:2100;display:flex;align-items:center;justify-content:center">' +
               '<div style="background:#fff;border-radius:12px;padding:40px;color:#888;font-size:14px">Loading…</div></div>';
    }
</script>
@endpush

