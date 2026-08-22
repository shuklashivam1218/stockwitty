@php
    $targetLabels = ['overview' => 'Overview', 'about' => 'About', 'thesis' => 'Thesis'];
    $targetColors = ['overview' => '#2196f3', 'about' => '#8b5cf6', 'thesis' => '#f59e0b'];
@endphp

<style>
    .fql-overlay {
        display: flex;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .55);
        z-index: 2100;
        align-items: center;
        justify-content: center;
        padding: 16px;
        backdrop-filter: blur(2px);
    }
    .fql-modal {
        background: #fff;
        border-radius: 12px;
        width: 100%;
        max-width: 780px;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 24px 60px rgba(0, 0, 0, .22);
        overflow: hidden;
        animation: privSlideIn .2s cubic-bezier(.34, 1.56, .64, 1);
    }
    .fql-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 22px;
        border-bottom: 1px solid #e2e8f0;
        flex-shrink: 0;
        gap: 12px;
    }
    .fql-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #1a1a1a; flex: 1; }
    .fql-add-btn {
        display: inline-flex; align-items: center; gap: 6px;
        background: #076550; color: #fff; border: none; border-radius: 8px;
        padding: 8px 16px; font-size: 12.5px; font-weight: 600; cursor: pointer;
        white-space: nowrap;
    }
    .fql-add-btn:hover { background: #054d3c; }
    .fql-close {
        background: #f1f5f9;
        border: none;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #64748b;
        transition: background .15s;
    }
    .fql-close:hover { background: #e2e8f0; color: #1a1a1a; }
    .fql-table-wrap { overflow-y: auto; flex: 1; }
    .fql-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .fql-table thead th {
        padding: 9px 14px;
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .05em;
        position: sticky;
        top: 0;
    }
    .fql-table tbody td { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; color: #1a1a1a; vertical-align: top; }
    .fql-question { max-width: 260px; }
    .fql-target-badge {
        display: inline-flex; align-items: center; font-size: 11px; font-weight: 700;
        padding: 3px 10px; border-radius: 20px; color: #fff; white-space: nowrap;
    }
    .fql-badge-active   { color: #076550; font-weight: 600; }
    .fql-badge-inactive { color: #e53935; font-weight: 600; }
</style>

<div class="fql-overlay" onclick="if(event.target===this)closeFaqListModal()">
    <div class="fql-modal">

        <div class="fql-header">
            <h3>FAQs &mdash; {{ $stock->UL_STOCKS_COMPNAME }}</h3>
            <button class="fql-add-btn" type="button" onclick="openFaqAddModal()">
                <i class="fa-solid fa-plus"></i> Add FAQ
            </button>
            <button class="fql-close" onclick="closeFaqListModal()" type="button">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="fql-table-wrap">
            <table class="fql-table">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Linked To</th>
                        <th>Sort</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($faqs as $faq)
                    <tr data-faq-id="{{ $faq->UL_FAQ_ID }}">
                        <td class="fql-question">{{ $faq->UL_FAQ_QUESTION }}</td>
                        <td>
                            <span class="fql-target-badge" style="background:{{ $targetColors[$faq->UL_FAQ_TARGET] ?? '#64748b' }}">
                                {{ $targetLabels[$faq->UL_FAQ_TARGET] ?? $faq->UL_FAQ_TARGET }}
                            </span>
                        </td>
                        <td>{{ $faq->UL_FAQ_SORT_ORDER }}</td>
                        <td>
                            <span class="fql-status {{ $faq->UL_FAQ_ACTIVE == '1' ? 'fql-badge-active' : 'fql-badge-inactive' }}">
                                {{ $faq->UL_FAQ_ACTIVE == '1' ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <i class="fa-solid fa-pen fql-edit-btn"
                                data-faq-id="{{ $faq->UL_FAQ_ID }}"
                                style="color:#2196f3;cursor:pointer;margin-right:10px"
                                title="Edit"></i>
                            <i class="fa-solid fa-trash fql-delete-btn"
                                data-faq-id="{{ $faq->UL_FAQ_ID }}"
                                style="color:#e53935;cursor:pointer"
                                title="Delete"></i>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px;color:#aaa">
                            <i class="fa-regular fa-circle-question" style="font-size:22px;display:block;margin-bottom:8px"></i>
                            No FAQs added yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
(function () {
    var STOCKS_BASE = window.STOCKS_BASE;
    var CSRF        = $('meta[name="csrf-token"]').attr('content');
    var $tbody      = $('.fql-table tbody');

    window.openFaqAddModal = function () {
        $('#faqEditModalWrap').html(loadingSpinner());
        $.get(STOCKS_BASE + '/' + window.fqFincode + '/faqs')
            .done(function (html) { $('#faqEditModalWrap').html(html); })
            .fail(function ()     { $('#faqEditModalWrap').empty(); alert('Failed to load.'); });
    };

    $tbody.on('click', '.fql-edit-btn', function () {
        var faqId = $(this).data('faq-id');
        $('#faqEditModalWrap').html(loadingSpinner());
        $.get(STOCKS_BASE + '/' + window.fqFincode + '/faqs/' + faqId + '/edit')
            .done(function (html) { $('#faqEditModalWrap').html(html); })
            .fail(function ()     { $('#faqEditModalWrap').empty(); alert('Failed to load.'); });
    });

    $tbody.on('click', '.fql-delete-btn', function () {
        if (!confirm('Delete this FAQ? This cannot be undone.')) return;
        var $row  = $(this).closest('tr');
        var faqId = $(this).data('faq-id');
        $.ajax({
            url:     STOCKS_BASE + '/' + window.fqFincode + '/faqs/' + faqId,
            method:  'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF },
        })
        .done(function (res) {
            if (res.success) $row.remove();
        })
        .fail(function () {
            alert('Delete failed.');
        });
    });
}());
</script>
