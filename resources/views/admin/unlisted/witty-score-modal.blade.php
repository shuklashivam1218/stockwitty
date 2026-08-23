@once
@push('styles')
<style>
.ws-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, .55);
    z-index: 2100;
    align-items: center;
    justify-content: center;
    padding: 16px;
    backdrop-filter: blur(2px);
}
.ws-overlay.open { display: flex; }
.ws-modal {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 560px;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 60px rgba(0, 0, 0, .22);
    animation: privSlideIn .2s cubic-bezier(.34, 1.56, .64, 1);
    overflow: hidden;
}
.ws-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #e2e8f0;
    flex-shrink: 0;
}
.ws-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #1a1a1a; }
.ws-close {
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
.ws-close:hover { background: #e2e8f0; color: #1a1a1a; }
#wittyScoreForm { display: flex; flex-direction: column; flex: 1; min-height: 0; overflow: hidden; }
.ws-body { flex: 1; min-height: 0; padding: 20px 22px; overflow-y: auto; }
.ws-overall {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    background: #e7f1eb;
    border: 1px solid #b4d5c2;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 18px;
}
.ws-overall-label { font-size: 12px; font-weight: 700; color: #076550; text-transform: uppercase; letter-spacing: .04em; }
.ws-overall-value { font-size: 24px; font-weight: 800; color: #076550; }
.ws-field { margin-bottom: 16px; }
.ws-field-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px; }
.ws-field label { font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .04em; }
.ws-field .ws-weight { font-size: 11px; font-weight: 700; color: #076550; }
.ws-field input[type=number] {
    width: 100%; padding: 9px 12px;
    border: 1.5px solid #e2e8f0; border-radius: 7px;
    font-size: 14px; color: #1a1a1a; outline: none;
    box-sizing: border-box;
    transition: border-color .15s, box-shadow .15s;
}
.ws-field input:focus { border-color: #076550; box-shadow: 0 0 0 3px rgba(7, 101, 80, .12); }
.ws-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 22px;
    border-top: 1px solid #e2e8f0;
    background: #fafafa;
    flex-shrink: 0;
}
.ws-save-msg { font-size: 13px; font-weight: 500; }
.ws-save-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 22px; background: #076550; color: #fff;
    border: none; border-radius: 8px; font-size: 13px;
    font-weight: 600; cursor: pointer;
    transition: background .15s, transform .1s;
}
.ws-save-btn:hover { background: #054d3c; }
.ws-save-btn:disabled { opacity: .6; cursor: not-allowed; }
.ws-active-row { display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600; color: #475569; }
.ws-active-row select {
    padding: 7px 10px; border: 1.5px solid #e2e8f0; border-radius: 7px;
    font-size: 13px; color: #1a1a1a; outline: none; background: #fff; cursor: pointer;
}
</style>
@endpush
@endonce

@php
    $pillars = [
        ['field' => 'UL_WS_FINANCIAL_HEALTH', 'label' => 'Financial Health', 'weight' => 30],
        ['field' => 'UL_WS_VALUATION',        'label' => 'Valuation',        'weight' => 20],
        ['field' => 'UL_WS_GROWTH_POTENTIAL',  'label' => 'Growth Potential', 'weight' => 20],
        ['field' => 'UL_WS_IPO_PROBABILITY',   'label' => 'IPO Probability',  'weight' => 15],
        ['field' => 'UL_WS_LIQUIDITY_SAFETY',  'label' => 'Liquidity & Safety', 'weight' => 15],
    ];
@endphp

<div id="wittyScoreOverlay" class="ws-overlay" onclick="if(event.target===this)closeWittyScoreModal()">
<div class="ws-modal">

    <div class="ws-header">
        <h3 id="wsCompanyName">WittyScore</h3>
        <button class="ws-close" onclick="closeWittyScoreModal()" type="button">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <form id="wittyScoreForm">
        <div class="ws-body">

            <div class="ws-overall">
                <span class="ws-overall-label">Overall WittyScore</span>
                <span class="ws-overall-value"><span id="wsOverall">&mdash;</span>/10</span>
            </div>

            @foreach ($pillars as $p)
                <div class="ws-field">
                    <div class="ws-field-head">
                        <label>{{ $p['label'] }}</label>
                        <span class="ws-weight">{{ $p['weight'] }}% weight</span>
                    </div>
                    <input type="number" step="0.1" min="0" max="10"
                           name="{{ $p['field'] }}" class="ws-pillar-input" data-weight="{{ $p['weight'] }}"
                           placeholder="0.0 – 10.0">
                </div>
            @endforeach

        </div>

        <div class="ws-footer">
            <div class="ws-active-row">
                <span>Active:</span>
                <select name="UL_WS_ACTIVE">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:12px">
                <span id="wsSaveMsg" class="ws-save-msg"></span>
                <button type="submit" class="ws-save-btn">
                    <i class="fa-solid fa-floppy-disk"></i> Save
                </button>
            </div>
        </div>
    </form>

</div>
</div>

@push('scripts')
<script>
(function () {
    var fincode = null;

    function recalcOverall() {
        var total = 0, weight = 0;
        $('.ws-pillar-input').each(function () {
            var v = parseFloat($(this).val());
            var w = parseFloat($(this).data('weight'));
            if (!isNaN(v)) { total += v * w; weight += w; }
        });
        $('#wsOverall').text(weight > 0 ? (total / weight).toFixed(1) : '—');
    }
    $(document).on('input', '.ws-pillar-input', recalcOverall);

    window.openWittyScoreModal = function (fc, companyName) {
        fincode = fc;
        $('#wsCompanyName').text('WittyScore — ' + companyName);
        $('#wittyScoreForm')[0].reset();
        $('#wsSaveMsg').text('');
        recalcOverall();

        $.get(window.STOCKS_BASE + '/' + fincode + '/witty-score')
            .done(function (data) {
                $('.ws-pillar-input').each(function () {
                    var name = $(this).attr('name');
                    if (data[name] !== null && data[name] !== undefined) $(this).val(data[name]);
                });
                $('select[name="UL_WS_ACTIVE"]').val(data.UL_WS_ACTIVE || '1');
                recalcOverall();
                $('#wittyScoreOverlay').addClass('open');
            })
            .fail(function () {
                alert('Failed to load WittyScore.');
            });
    };

    function closeWittyScoreModal() { $('#wittyScoreOverlay').removeClass('open'); }
    window.closeWittyScoreModal = closeWittyScoreModal;

    $('#wittyScoreForm').on('submit', function (e) {
        e.preventDefault();
        var CSRF = $('meta[name="csrf-token"]').attr('content');
        var data = {};
        $(this).serializeArray().forEach(function (f) { data[f.name] = f.value; });
        var $btn = $(this).find('.ws-save-btn').prop('disabled', true)
                          .html('<i class="fa-solid fa-spinner fa-spin"></i> Saving…');

        $.ajax({
            url:         window.STOCKS_BASE + '/' + fincode + '/witty-score',
            method:      'POST',
            contentType: 'application/json',
            headers:     { 'X-CSRF-TOKEN': CSRF },
            data:        JSON.stringify(data),
        })
        .done(function (res) {
            var color = res.success ? '#076550' : '#e53935';
            $('#wsSaveMsg').css('color', color).text(res.message || (res.success ? 'Saved.' : 'Error.'));
        })
        .fail(function (xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Request failed.';
            $('#wsSaveMsg').css('color', '#e53935').text(msg);
        })
        .always(function () {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> Save');
        });
    });
}());
</script>
@endpush
