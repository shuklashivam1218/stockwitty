@php
    use App\Http\Controllers\UnlistedStockEditorController as Editor;

    // Same fields, in the same columns, as the old Financials modal.
    $finColumns = [
        'P&L' => [
            'UL_FIN_NET_SALES' => 'Net sales', 'UL_FIN_OTHER_INCOME' => 'Other income', 'UL_FIN_TOTAL_INCOME' => 'Total income',
            'UL_FIN_TOTAL_EXPENDITURE' => 'Expenditure', 'UL_FIN_OPERATING_PROFIT' => 'Operating profit', 'UL_FIN_INTEREST' => 'Interest',
            'UL_FIN_DEPRECIATION' => 'Depreciation', 'UL_FIN_EXCEPTIONAL_INCOME' => 'Exceptional income', 'UL_FIN_PBT' => 'PBT',
            'UL_FIN_TAX' => 'Tax', 'UL_FIN_PAT' => 'PAT',
        ],
        'Balance Sheet' => [
            'UL_FIN_SHAREHOLDER_FUNDS' => 'Shareholder funds', 'UL_FIN_TOTAL_LIABILITIES' => 'Total liabilities',
            'UL_FIN_TOTAL_ASSETS' => 'Total assets', 'UL_FIN_TOTAL_DEBT' => 'Total debt',
            'UL_FIN_CURRENT_LIABILITIES' => 'Current liabilities', 'UL_FIN_NON_CURRENT_LIABILITIES' => 'Non-current liabilities',
            'UL_FIN_CURRENT_ASSETS' => 'Current assets', 'UL_FIN_NON_CURRENT_ASSETS' => 'Non-current assets',
        ],
        'Cash Flow' => [
            'UL_FIN_CASH_FLOW_FROM_OPERATING_ACTIVITIES' => 'Operating activities',
            'UL_FIN_CASH_FLOW_FORM_INVESTING_ACTIVITIES' => 'Investing activities',
            'UL_FIN_CASH_FLOW_FROM_FINANCING_ACTIVITIES' => 'Financing activities',
            'UL_FIN_FREE_CASH_FLOW' => 'Free cash flow',
        ],
    ];
@endphp

{{-- Financial periods: saved instantly through the existing financials endpoints.
     Inputs here have no name, so "Save all changes" never submits them. --}}
<section class="admin-card se-section" id="financials" data-live>
    <div class="se-section-head">
        <div>
            <h2 class="se-section-title"><i class="fa-solid fa-chart-column"></i> Financials <span class="se-instant">Saves instantly</span></h2>
            <p class="se-section-sub">One row per period. Each row has its own switch — inactive periods are hidden from the site.</p>
        </div>
        <button type="button" class="se-live-btn" id="finAddBtn"><i class="fa-solid fa-plus"></i> Add period</button>
    </div>

    <div class="se-live-msg" id="finMsg"></div>

    <div class="se-table-wrap">
        <table class="se-table">
            <thead><tr><th>Period end</th><th>Type</th><th>Months</th><th>Unit</th><th>On site</th><th></th></tr></thead>
            <tbody id="finRows"><tr><td colspan="6" class="se-table-empty">Loading…</td></tr></tbody>
        </table>
    </div>

    <div class="se-fin-panel" id="finPanel" hidden>
        <div class="se-fin-panel-head">
            <strong id="finPanelTitle">Add period</strong>
            <button type="button" class="se-icon-btn" id="finCloseBtn" title="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="se-fin-grid">
            <fieldset>
                <legend>Period</legend>
                <div class="cms-field">
                    <label>Period end <span class="req">*</span></label>
                    <select data-f="UL_FIN_Period_end" class="cms-input">
                        <option value="">— Select —</option>
                        @foreach(Editor::periodOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cms-field">
                    <label>Type <span class="req">*</span></label>
                    <select data-f="UL_FIN_Type" class="cms-input">
                        <option value="">— Select —</option>
                        <option value="C">Consolidated</option>
                        <option value="S">Standalone</option>
                    </select>
                </div>
                <div class="cms-field">
                    <label>No. of months <span class="req">*</span></label>
                    <select data-f="UL_FIN_No_months" class="cms-input">
                        <option value="">— Select —</option>
                        <option value="3">3</option>
                        <option value="6">6</option>
                        <option value="12">12</option>
                    </select>
                </div>
                <div class="cms-field">
                    <label>Unit</label>
                    <select data-f="UL_FIN_Unit" class="cms-input">
                        <option value="">— Select —</option>
                        @foreach(Editor::UNITS as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cms-field">
                    <label>Face value</label>
                    <input type="number" step="0.01" data-f="UL_FIN_FV" class="cms-input">
                </div>
                <div class="cms-field">
                    <label>No. of shares</label>
                    <input type="number" step="0.01" data-f="UL_FIN_NUM_SHARES" class="cms-input">
                </div>
            </fieldset>

            @foreach($finColumns as $legend => $fields)
                <fieldset>
                    <legend>{{ $legend }}</legend>
                    @foreach($fields as $field => $label)
                        <div class="cms-field">
                            <label>{{ $label }}</label>
                            <input type="number" step="0.01" data-f="{{ $field }}" class="cms-input">
                        </div>
                    @endforeach
                    @if($legend === 'Balance Sheet')
                        <div class="cms-error" id="finBalanceErr" hidden>Total assets must equal total liabilities.</div>
                    @endif
                </fieldset>
            @endforeach
        </div>

        <div class="se-fin-panel-foot">
            <span class="se-live-msg" id="finPanelMsg"></span>
            <div style="display:flex;gap:8px;">
                <button type="button" class="cms-slug-toggle-btn" id="finCancelBtn" style="padding:9px 16px;">Cancel</button>
                <button type="button" class="se-live-btn" id="finSaveBtn" data-enter><i class="fa-solid fa-floppy-disk"></i> Save period</button>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
$(function () {
    const LIST_URL = @json(route('admin.unlisted.stocks.financials', $stock->UL_STOCKS_FINCODE));
    const BASE     = @json(url('/admin/unlisted/stocks/' . $stock->UL_STOCKS_FINCODE));
    const CSRF     = $('meta[name="csrf-token"]').attr('content');
    const TYPES    = { C: 'Consolidated', S: 'Standalone' };
    const $panel   = $('#finPanel');
    let editKey    = null; // { periodEnd, type, months } of the row being edited, null when adding

    function msg($el, text, ok) {
        $el.text(text || '').toggleClass('is-error', !ok).toggleClass('is-ok', !!ok);
    }
    function errorText(xhr, fallback) {
        const r = xhr.responseJSON || {};
        const first = r.errors ? Object.values(r.errors)[0][0] : null;
        return first || r.message || fallback;
    }
    function keyPath(k) {
        return '/financials/' + encodeURIComponent(k.periodEnd) + '/' + encodeURIComponent(k.type) + '/' + encodeURIComponent(k.months);
    }

    function render(rows) {
        const $tbody = $('#finRows').empty();
        if (!rows.length) {
            $tbody.append($('<tr>').append($('<td colspan="6" class="se-table-empty">').text('No financials yet — click "Add period".')));
        }
        rows.forEach(function (row) {
            const $tr = $('<tr>').data('key', { periodEnd: row.periodEnd, type: row.type, months: row.months }).toggleClass('is-off', !row.active);
            $tr.append($('<td>').text(row.label));
            $tr.append($('<td>').text(TYPES[row.type] || row.type));
            $tr.append($('<td>').text(row.months));
            $tr.append($('<td>').text(row.unit || '—'));
            $tr.append($('<td>').append(window.seLiveSwitch(row.active).addClass('fin-active')));
            $tr.append($('<td class="se-row-actions">').append(
                $('<button type="button" class="se-icon-btn fin-edit" title="Edit this period">').append('<i class="fa-solid fa-pen"></i>')
            ));
            $tbody.append($tr);
        });
    }

    function load() {
        $.getJSON(LIST_URL).done(render)
            .fail(function () { $('#finRows').html('<tr><td colspan="6" class="se-table-empty">Could not load financials.</td></tr>'); });
    }

    function checkBalance() {
        const a = parseFloat($panel.find('[data-f="UL_FIN_TOTAL_ASSETS"]').val());
        const l = parseFloat($panel.find('[data-f="UL_FIN_TOTAL_LIABILITIES"]').val());
        const mismatch = !isNaN(a) && !isNaN(l) && a !== l;
        $('#finBalanceErr').prop('hidden', !mismatch);
        $panel.find('[data-f="UL_FIN_TOTAL_ASSETS"], [data-f="UL_FIN_TOTAL_LIABILITIES"]').toggleClass('is-invalid', mismatch);
        return !mismatch;
    }
    $panel.on('input', '[data-f="UL_FIN_TOTAL_ASSETS"], [data-f="UL_FIN_TOTAL_LIABILITIES"]', checkBalance);

    function openPanel(title) {
        $panel.find('[data-f]').val('').removeClass('is-invalid');
        $('#finBalanceErr').prop('hidden', true);
        msg($('#finPanelMsg'), '', true);
        $('#finPanelTitle').text(title);
        $panel.prop('hidden', false);
        $panel[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    function closePanel() {
        $panel.prop('hidden', true);
        editKey = null;
    }

    $('#finAddBtn').on('click', function () {
        editKey = null;
        openPanel('Add period');
        $('#finSaveBtn').html('<i class="fa-solid fa-floppy-disk"></i> Save period');
    });
    $('#finCloseBtn, #finCancelBtn').on('click', closePanel);

    $('#finRows').on('click', '.fin-edit', function () {
        const $tr   = $(this).closest('tr');
        const key   = $tr.data('key');
        const title = 'Edit ' + $tr.children().map(function (i) { return i < 3 ? $(this).text() : null; }).get().join(' · ');
        $.getJSON(BASE + keyPath(key) + '/edit').done(function (data) {
            editKey = key;
            openPanel(title);
            // Older periods may predate the dropdown's 15-year range.
            const $period = $panel.find('[data-f="UL_FIN_Period_end"]');
            if (!$period.find('option[value="' + data.UL_FIN_Period_end + '"]').length) {
                const pe = String(data.UL_FIN_Period_end);
                $period.append($('<option>').val(pe).text(pe.slice(0, 4) + '-' + pe.slice(4)));
            }
            $panel.find('[data-f]').each(function () {
                const f = $(this).data('f');
                if (data[f] !== null && data[f] !== undefined) $(this).val(String(data[f]));
            });
            checkBalance();
            $('#finSaveBtn').html('<i class="fa-solid fa-floppy-disk"></i> Update period');
        }).fail(function () { msg($('#finMsg'), 'Could not load that period.', false); });
    });

    $('#finSaveBtn').on('click', function () {
        const data = {};
        $panel.find('[data-f]').each(function () { data[$(this).data('f')] = $(this).val(); });
        if (!data.UL_FIN_Period_end || !data.UL_FIN_Type || !data.UL_FIN_No_months) {
            msg($('#finPanelMsg'), 'Period end, type and months are required.', false);
            return;
        }
        if (!checkBalance()) {
            msg($('#finPanelMsg'), 'Total assets must equal total liabilities.', false);
            return;
        }

        const $btn = $(this).prop('disabled', true);
        $.ajax({
            url: BASE + (editKey ? keyPath(editKey) : '/financials'),
            method: editKey ? 'PUT' : 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            contentType: 'application/json',
            data: JSON.stringify(data),
        })
        .done(function (res) {
            closePanel();
            msg($('#finMsg'), res.message || 'Saved.', true);
            load();
        })
        .fail(function (xhr) { msg($('#finPanelMsg'), errorText(xhr, 'Could not save this period.'), false); })
        .always(function () { $btn.prop('disabled', false); });
    });

    // The endpoint flips the status; the response says which way it went.
    $('#finRows').on('change', '.fin-active input', function () {
        const $cb = $(this), $tr = $cb.closest('tr');
        $.ajax({ url: BASE + keyPath($tr.data('key')), method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF } })
            .done(function (res) {
                const active = res.status === '1';
                $cb.prop('checked', active);
                $tr.toggleClass('is-off', !active);
                msg($('#finMsg'), active ? 'Period shown on the site.' : 'Period hidden from the site.', true);
            })
            .fail(function () { $cb.prop('checked', !$cb.prop('checked')); msg($('#finMsg'), 'Could not change that period.', false); });
    });

    load();
});
</script>
@endpush
