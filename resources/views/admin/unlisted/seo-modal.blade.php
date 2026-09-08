@once
@push('styles')
<style>
.seo-overlay {
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
.seo-overlay.open { display: flex; }
.seo-modal {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 720px;
    max-height: 94vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 60px rgba(0, 0, 0, .22);
    animation: privSlideIn .2s cubic-bezier(.34, 1.56, .64, 1);
    overflow: hidden;
}
.seo-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #e2e8f0;
    flex-shrink: 0;
}
.seo-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #1a1a1a; }
.seo-header p { margin: 2px 0 0; font-size: 12px; color: #64748b; }
.seo-close {
    background: #f1f5f9; border: none; border-radius: 8px;
    width: 32px; height: 32px; display: flex; align-items: center;
    justify-content: center; cursor: pointer; color: #64748b;
    transition: background .15s; flex-shrink: 0;
}
.seo-close:hover { background: #e2e8f0; color: #1a1a1a; }
#seoForm { display: flex; flex-direction: column; flex: 1; min-height: 0; overflow: hidden; }
.seo-body { flex: 1; min-height: 0; padding: 20px 22px; overflow-y: auto; }
.seo-section-title {
    font-size: 11px; font-weight: 800; color: #076550; text-transform: uppercase;
    letter-spacing: .06em; margin: 22px 0 12px; padding-top: 14px; border-top: 1px solid #eef2f1;
}
.seo-section-title:first-child { margin-top: 0; padding-top: 0; border-top: none; }
.seo-field { margin-bottom: 14px; }
.seo-field label { display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 5px; }
.seo-field .seo-hint { font-weight: 400; color: #94a3b8; margin-left: 6px; }
.seo-field input, .seo-field textarea {
    width: 100%; padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 7px;
    font-size: 13px; color: #1a1a1a; outline: none; font-family: inherit;
    box-sizing: border-box;
    transition: border-color .15s, box-shadow .15s;
}
.seo-field textarea { resize: vertical; min-height: 60px; }
.seo-field input:focus, .seo-field textarea:focus { border-color: #076550; box-shadow: 0 0 0 3px rgba(7, 101, 80, .12); }
.seo-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 22px; border-top: 1px solid #e2e8f0; background: #fafafa; flex-shrink: 0;
}
.seo-save-msg { font-size: 13px; font-weight: 500; }
.seo-save-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 22px; background: #076550; color: #fff;
    border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
    transition: background .15s, transform .1s;
}
.seo-save-btn:hover { background: #054d3c; }
.seo-save-btn:disabled { opacity: .6; cursor: not-allowed; }
.seo-active-row { display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600; color: #475569; }
.seo-active-row select {
    padding: 7px 10px; border: 1.5px solid #e2e8f0; border-radius: 7px;
    font-size: 13px; color: #1a1a1a; outline: none; background: #fff; cursor: pointer;
}
</style>
@endpush
@endonce

@php
    $seoPages = [
        [
            'key' => 'COMPANY', 'label' => 'Main company page', 'route' => '/unlisted-shares/{slug}/',
            'titleHint' => 'Leave blank to auto-generate from company name & price',
        ],
        [
            'key' => 'ABOUT', 'label' => 'About page', 'route' => '/unlisted-shares/{slug}/about/',
            'titleHint' => 'Leave blank to auto-generate from company name',
        ],
        [
            'key' => 'THESIS', 'label' => 'Thesis page', 'route' => '/unlisted-shares/{slug}/thesis/',
            'titleHint' => 'Leave blank to auto-generate from company name',
        ],
    ];

    // Computed here rather than inline in @json() below — a multi-line
    // closure with an array literal inside a Blade directive's own
    // parentheses can trip up the directive-argument parser (it compiles
    // without error but the generated PHP comes out malformed).
    $seoFieldNames = [];
    foreach ($seoPages as $p) {
        $seoFieldNames[] = 'UL_SEO_' . $p['key'] . '_TITLE';
        $seoFieldNames[] = 'UL_SEO_' . $p['key'] . '_DESCRIPTION';
        $seoFieldNames[] = 'UL_SEO_' . $p['key'] . '_KEYWORDS';
    }
@endphp

<div id="seoOverlay" class="seo-overlay" onclick="if(event.target===this)closeSeoModal()">
<div class="seo-modal">

    <div class="seo-header">
        <div>
            <h3 id="seoCompanyName">SEO Meta Tags</h3>
            <p>Override the meta title, description &amp; keywords per page. Leave a field blank to keep the auto-generated default.</p>
        </div>
        <button class="seo-close" onclick="closeSeoModal()" type="button">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <form id="seoForm">
        <div class="seo-body">

            @foreach ($seoPages as $p)
                <div class="seo-section-title">{{ $p['label'] }} <span class="seo-hint" style="text-transform:none;font-weight:600;letter-spacing:0">— {{ $p['route'] }}</span></div>

                <div class="seo-field">
                    <label>Meta title <span class="seo-hint">{{ $p['titleHint'] }}</span></label>
                    <input type="text" name="UL_SEO_{{ $p['key'] }}_TITLE" maxlength="255">
                </div>
                <div class="seo-field">
                    <label>Meta description <span class="seo-hint">~150-160 characters ideal</span></label>
                    <textarea name="UL_SEO_{{ $p['key'] }}_DESCRIPTION" maxlength="500"></textarea>
                </div>
                <div class="seo-field">
                    <label>Meta keywords <span class="seo-hint">comma-separated</span></label>
                    <input type="text" name="UL_SEO_{{ $p['key'] }}_KEYWORDS" maxlength="500">
                </div>
            @endforeach

        </div>

        <div class="seo-footer">
            <div class="seo-active-row">
                <span>Active:</span>
                <select name="UL_SEO_ACTIVE">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:12px">
                <span id="seoSaveMsg" class="seo-save-msg"></span>
                <button type="submit" class="seo-save-btn">
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
    var fields  = @json($seoFieldNames);

    window.openSeoModal = function (fc, companyName) {
        fincode = fc;
        $('#seoCompanyName').text('SEO Meta Tags — ' + companyName);
        $('#seoForm')[0].reset();
        $('#seoSaveMsg').text('');

        $.get(window.STOCKS_BASE + '/' + fincode + '/seo')
            .done(function (data) {
                fields.forEach(function (name) {
                    $('[name="' + name + '"]').val(data[name] || '');
                });
                $('select[name="UL_SEO_ACTIVE"]').val(data.UL_SEO_ACTIVE || '1');
                $('#seoOverlay').addClass('open');
            })
            .fail(function () {
                alert('Failed to load SEO meta.');
            });
    };

    function closeSeoModal() { $('#seoOverlay').removeClass('open'); }
    window.closeSeoModal = closeSeoModal;

    $('#seoForm').on('submit', function (e) {
        e.preventDefault();
        var CSRF = $('meta[name="csrf-token"]').attr('content');
        var data = {};
        $(this).serializeArray().forEach(function (f) { data[f.name] = f.value; });
        var $btn = $(this).find('.seo-save-btn').prop('disabled', true)
                          .html('<i class="fa-solid fa-spinner fa-spin"></i> Saving…');

        $.ajax({
            url:         window.STOCKS_BASE + '/' + fincode + '/seo',
            method:      'POST',
            contentType: 'application/json',
            headers:     { 'X-CSRF-TOKEN': CSRF },
            data:        JSON.stringify(data),
        })
        .done(function (res) {
            var color = res.success ? '#076550' : '#e53935';
            $('#seoSaveMsg').css('color', color).text(res.message || (res.success ? 'Saved.' : 'Error.'));
        })
        .fail(function (xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Request failed.';
            $('#seoSaveMsg').css('color', '#e53935').text(msg);
        })
        .always(function () {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> Save');
        });
    });
}());
</script>
@endpush
