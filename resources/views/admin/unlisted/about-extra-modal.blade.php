@once
@push('styles')
<style>
.abx-overlay {
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
.abx-overlay.open { display: flex; }
.abx-modal {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 960px;
    max-height: 94vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 60px rgba(0, 0, 0, .22);
    animation: privSlideIn .2s cubic-bezier(.34, 1.56, .64, 1);
    overflow: hidden;
}
.abx-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #e2e8f0;
    flex-shrink: 0;
}
.abx-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #1a1a1a; }
.abx-header p { margin: 2px 0 0; font-size: 12px; color: #64748b; }
.abx-close {
    background: #f1f5f9; border: none; border-radius: 8px;
    width: 32px; height: 32px; display: flex; align-items: center;
    justify-content: center; cursor: pointer; color: #64748b;
    transition: background .15s; flex-shrink: 0;
}
.abx-close:hover { background: #e2e8f0; color: #1a1a1a; }
#aboutExtraForm { display: flex; flex-direction: column; flex: 1; min-height: 0; overflow: hidden; }
.abx-body { flex: 1; min-height: 0; padding: 20px 22px; overflow-y: auto; }
.abx-section-title {
    font-size: 11px; font-weight: 800; color: #076550; text-transform: uppercase;
    letter-spacing: .06em; margin: 22px 0 12px; padding-top: 14px; border-top: 1px solid #eef2f1;
}
.abx-section-title:first-child { margin-top: 0; padding-top: 0; border-top: none; }
.abx-field { margin-bottom: 14px; }
.abx-field label { display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 5px; }
.abx-field .abx-hint { font-weight: 400; color: #94a3b8; margin-left: 6px; }
.abx-field textarea {
    width: 100%; padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 7px;
    font-size: 13px; color: #1a1a1a; outline: none; font-family: inherit;
    box-sizing: border-box; resize: vertical;
    transition: border-color .15s, box-shadow .15s;
}
.abx-field textarea:focus { border-color: #076550; box-shadow: 0 0 0 3px rgba(7, 101, 80, .12); }
.abx-prose textarea { min-height: 90px; }
.abx-list textarea { min-height: 70px; font-family: ui-monospace, monospace; font-size: 12.5px; }
.abx-swot-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 700px) { .abx-swot-grid { grid-template-columns: 1fr; } }
.abx-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 22px; border-top: 1px solid #e2e8f0; background: #fafafa; flex-shrink: 0;
}
.abx-save-msg { font-size: 13px; font-weight: 500; }
.abx-save-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 22px; background: #076550; color: #fff;
    border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
    transition: background .15s, transform .1s;
}
.abx-save-btn:hover { background: #054d3c; }
.abx-save-btn:disabled { opacity: .6; cursor: not-allowed; }
.abx-active-row { display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600; color: #475569; }
.abx-active-row select {
    padding: 7px 10px; border: 1.5px solid #e2e8f0; border-radius: 7px;
    font-size: 13px; color: #1a1a1a; outline: none; background: #fff; cursor: pointer;
}
</style>
@endpush
@endonce

@php
    $proseFields = [
        ['field' => 'UL_ABX_OVERVIEW', 'label' => 'Overview of the company'],
        ['field' => 'UL_ABX_OPERATIONS', 'label' => 'What it does & how it operates'],
        ['field' => 'UL_ABX_GEOGRAPHY', 'label' => 'Geographical presence'],
        ['field' => 'UL_ABX_INDUSTRY_POSITION', 'label' => 'Industry position'],
        ['field' => 'UL_ABX_SHAREHOLDING', 'label' => 'Shareholding framework'],
        ['field' => 'UL_ABX_INVESTOR_INTEREST', 'label' => 'Why it draws investor interest'],
        ['field' => 'UL_ABX_MARKET_LANDSCAPE', 'label' => 'Market landscape & reach'],
        ['field' => 'UL_ABX_COMPETITIVE_STRENGTH', 'label' => 'Competitive strength'],
    ];

    $pairFields = [
        ['field' => 'UL_ABX_VERTICALS', 'label' => 'Business verticals', 'hint' => 'One per line — Title | Description'],
        ['field' => 'UL_ABX_REVENUE_SEGMENTS', 'label' => 'Revenue segments', 'hint' => 'One per line — Segment name | Description'],
        ['field' => 'UL_ABX_HISTORY', 'label' => 'History & evolution (timeline)', 'hint' => 'One per line — Year | Milestone text'],
        ['field' => 'UL_ABX_PRODUCTS_SERVICES', 'label' => 'Products & services', 'hint' => 'One per line — Name | Description'],
        ['field' => 'UL_ABX_SOURCES', 'label' => 'Sources & references', 'hint' => 'One per line — Label | https://...'],
    ];

    $swotFields = [
        ['field' => 'UL_ABX_SWOT_STRENGTHS', 'label' => 'Strengths'],
        ['field' => 'UL_ABX_SWOT_WEAKNESSES', 'label' => 'Weaknesses'],
        ['field' => 'UL_ABX_SWOT_OPPORTUNITIES', 'label' => 'Opportunities'],
        ['field' => 'UL_ABX_SWOT_THREATS', 'label' => 'Threats'],
    ];
@endphp

<div id="aboutExtraOverlay" class="abx-overlay" onclick="if(event.target===this)closeAboutExtraModal()">
<div class="abx-modal">

    <div class="abx-header">
        <div>
            <h3 id="abxCompanyName">About Page Sections</h3>
            <p>Structured content for the public "About" page (SWOT, timeline, verticals, etc). Leave a section blank to hide it.</p>
        </div>
        <button class="abx-close" onclick="closeAboutExtraModal()" type="button">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <form id="aboutExtraForm">
        <div class="abx-body">

            <div class="abx-section-title">Narrative sections</div>
            @foreach ($proseFields as $f)
                <div class="abx-field abx-prose">
                    <label>{{ $f['label'] }}</label>
                    <textarea name="{{ $f['field'] }}"></textarea>
                </div>
            @endforeach

            <div class="abx-section-title">List sections</div>
            @foreach ($pairFields as $f)
                <div class="abx-field abx-list">
                    <label>{{ $f['label'] }} <span class="abx-hint">{{ $f['hint'] }}</span></label>
                    <textarea name="{{ $f['field'] }}"></textarea>
                </div>
            @endforeach

            <div class="abx-section-title">SWOT analysis</div>
            <div class="abx-swot-grid">
                @foreach ($swotFields as $f)
                    <div class="abx-field abx-list">
                        <label>{{ $f['label'] }} <span class="abx-hint">one point per line</span></label>
                        <textarea name="{{ $f['field'] }}"></textarea>
                    </div>
                @endforeach
            </div>

        </div>

        <div class="abx-footer">
            <div class="abx-active-row">
                <span>Active:</span>
                <select name="UL_ABX_ACTIVE">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:12px">
                <span id="abxSaveMsg" class="abx-save-msg"></span>
                <button type="submit" class="abx-save-btn">
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
    var fields  = @json(collect($proseFields)->concat($pairFields)->concat($swotFields)->pluck('field'));

    window.openAboutExtraModal = function (fc, companyName) {
        fincode = fc;
        $('#abxCompanyName').text('About Page Sections — ' + companyName);
        $('#aboutExtraForm')[0].reset();
        $('#abxSaveMsg').text('');

        $.get(window.STOCKS_BASE + '/' + fincode + '/about-extra')
            .done(function (data) {
                fields.forEach(function (name) {
                    $('textarea[name="' + name + '"]').val(data[name] || '');
                });
                $('select[name="UL_ABX_ACTIVE"]').val(data.UL_ABX_ACTIVE || '1');
                $('#aboutExtraOverlay').addClass('open');
            })
            .fail(function () {
                alert('Failed to load About page sections.');
            });
    };

    function closeAboutExtraModal() { $('#aboutExtraOverlay').removeClass('open'); }
    window.closeAboutExtraModal = closeAboutExtraModal;

    $('#aboutExtraForm').on('submit', function (e) {
        e.preventDefault();
        var CSRF = $('meta[name="csrf-token"]').attr('content');
        var data = {};
        $(this).serializeArray().forEach(function (f) { data[f.name] = f.value; });
        var $btn = $(this).find('.abx-save-btn').prop('disabled', true)
                          .html('<i class="fa-solid fa-spinner fa-spin"></i> Saving…');

        $.ajax({
            url:         window.STOCKS_BASE + '/' + fincode + '/about-extra',
            method:      'POST',
            contentType: 'application/json',
            headers:     { 'X-CSRF-TOKEN': CSRF },
            data:        JSON.stringify(data),
        })
        .done(function (res) {
            var color = res.success ? '#076550' : '#e53935';
            $('#abxSaveMsg').css('color', color).text(res.message || (res.success ? 'Saved.' : 'Error.'));
        })
        .fail(function (xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Request failed.';
            $('#abxSaveMsg').css('color', '#e53935').text(msg);
        })
        .always(function () {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> Save');
        });
    });
}());
</script>
@endpush
