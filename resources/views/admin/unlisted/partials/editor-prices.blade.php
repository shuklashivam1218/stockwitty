{{-- Price history: saved instantly through the existing price endpoints.
     Inputs here have no name, so "Save all changes" never submits them. --}}
<section class="admin-card se-section" id="prices" data-live>
    <div class="se-section-head">
        <div>
            <h2 class="se-section-title"><i class="fa-solid fa-indian-rupee-sign"></i> Price History <span class="se-instant">Saves instantly</span></h2>
            <p class="se-section-sub">The newest valid price is the one shown on the site. Use the pencil to correct a price, or switch a row to Invalid to hide it from the site.</p>
        </div>
    </div>

    <div class="se-live-add">
        <div class="cms-field">
            <label>Date</label>
            <input type="date" id="pxDate" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="cms-input">
        </div>
        <div class="cms-field">
            <label>Bid price (₹)</label>
            <input type="number" id="pxPrice" step="0.01" min="0" placeholder="0.00" class="cms-input">
        </div>
        <button type="button" class="se-live-btn" id="pxAddBtn" data-enter><i class="fa-solid fa-plus"></i> Add price</button>
    </div>
    <div class="se-live-msg" id="pxMsg"></div>

    <div class="se-table-wrap">
        <table class="se-table">
            <thead><tr><th>Date</th><th>Bid price</th><th>On site</th><th></th></tr></thead>
            <tbody id="pxRows"><tr><td colspan="4" class="se-table-empty">Loading…</td></tr></tbody>
        </table>
    </div>
    <div class="se-pager" id="pxPager"></div>
</section>

@push('scripts')
<script>
$(function () {
    const LIST_URL = @json(route('admin.unlisted.stocks.prices', $stock->UL_STOCKS_FINCODE));
    const BASE     = @json(url('/admin/unlisted/stocks/' . $stock->UL_STOCKS_FINCODE));
    const CSRF     = $('meta[name="csrf-token"]').attr('content');
    let page = 1;

    function msg(text, ok) {
        $('#pxMsg').text(text || '').toggleClass('is-error', !ok).toggleClass('is-ok', !!ok);
    }
    function errorText(xhr, fallback) {
        const r = xhr.responseJSON || {};
        const first = r.errors ? Object.values(r.errors)[0][0] : null;
        return first || r.message || fallback;
    }
    function rupees(v) {
        return '₹' + Number(v).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Rows are built with .text() — values never go into HTML strings.
    function render(data) {
        const $tbody = $('#pxRows').empty();
        if (!data.rows.length) {
            $tbody.append($('<tr>').append($('<td colspan="4" class="se-table-empty">').text('No prices yet — add the first one above.')));
        }
        data.rows.forEach(function (row) {
            const $tr = $('<tr>').attr('data-key', row.key).attr('data-price', row.price).toggleClass('is-off', !row.valid);
            $tr.append($('<td>').text(row.label));
            $tr.append($('<td class="px-price">').text(rupees(row.price)));
            $tr.append($('<td>').append(window.seLiveSwitch(row.valid, 'Valid', 'Invalid').addClass('px-valid')));
            $tr.append($('<td class="se-row-actions">').append(
                $('<button type="button" class="se-icon-btn px-edit" title="Edit price">').append('<i class="fa-solid fa-pen"></i>')
            ));
            $tbody.append($tr);
        });
        window.sePager($('#pxPager'), data.page, data.lastPage, data.total, function (p) { load(p); });
    }

    function load(p) {
        page = p || page;
        $.getJSON(LIST_URL, { page: page }).done(render)
            .fail(function () { $('#pxRows').html('<tr><td colspan="4" class="se-table-empty">Could not load prices.</td></tr>'); });
    }

    $('#pxAddBtn').on('click', function () {
        const date = $('#pxDate').val(), price = $('#pxPrice').val();
        if (!date || price === '') { msg('Pick a date and enter the bid price.', false); return; }
        const $btn = $(this).prop('disabled', true);
        $.ajax({
            url: BASE + '/price', method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF },
            contentType: 'application/json', data: JSON.stringify({ UL_PD_DATE: date, UL_PD_BID_PRICE: price }),
        })
        .done(function (res) { msg(res.message || 'Price saved.', true); $('#pxPrice').val(''); load(1); })
        .fail(function (xhr) { msg(errorText(xhr, 'Could not save the price.'), false); })
        .always(function () { $btn.prop('disabled', false); });
    });

    $('#pxRows').on('click', '.px-edit', function () {
        const $tr = $(this).closest('tr');
        $tr.find('.px-price').empty().append(
            $('<input type="number" step="0.01" min="0" class="cms-input se-inline-input px-input">').val($tr.data('price'))
        );
        $(this).closest('td').empty().append(
            $('<button type="button" class="se-icon-btn se-icon-ok px-save" title="Save">').append('<i class="fa-solid fa-check"></i>'),
            $('<button type="button" class="se-icon-btn px-cancel" title="Cancel">').append('<i class="fa-solid fa-xmark"></i>')
        );
        $tr.find('.px-input').trigger('focus');
    });
    $('#pxRows').on('click', '.px-cancel', function () { load(); });
    $('#pxRows').on('keydown', '.px-input', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); $(this).closest('tr').find('.px-save').trigger('click'); }
        if (e.key === 'Escape') load();
    });
    $('#pxRows').on('click', '.px-save', function () {
        const $tr = $(this).closest('tr');
        const price = $tr.find('.px-input').val();
        if (price === '') return;
        $.ajax({
            url: BASE + '/price/' + encodeURIComponent($tr.data('key')), method: 'PATCH', headers: { 'X-CSRF-TOKEN': CSRF },
            contentType: 'application/json', data: JSON.stringify({ UL_PD_BID_PRICE: price }),
        })
        .done(function () { msg('Price updated.', true); load(); })
        .fail(function (xhr) { msg(errorText(xhr, 'Could not update the price.'), false); });
    });

    // The endpoint flips validity; the response says which way it went.
    $('#pxRows').on('change', '.px-valid input', function () {
        const $cb = $(this), $tr = $cb.closest('tr');
        $.ajax({ url: BASE + '/price/' + encodeURIComponent($tr.data('key')), method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF } })
            .done(function (res) {
                $cb.prop('checked', !res.invalid);
                $tr.toggleClass('is-off', !!res.invalid);
                msg(res.invalid ? 'Price hidden from the site.' : 'Price shown on the site.', true);
            })
            .fail(function () { $cb.prop('checked', !$cb.prop('checked')); msg('Could not change that price.', false); });
    });

    load(1);
});
</script>
@endpush
