@once
@push('styles')
<style>
.ci-overlay {
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
.ci-overlay.open { display: flex; }
.ci-modal {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 780px;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 60px rgba(0, 0, 0, .22);
    animation: privSlideIn .2s cubic-bezier(.34, 1.56, .64, 1);
    overflow: hidden;
}
.ci-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #e2e8f0;
    flex-shrink: 0;
}
.ci-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #1a1a1a; }
.ci-header p { margin: 2px 0 0; font-size: 12px; color: #64748b; }
.ci-close {
    background: #f1f5f9; border: none; border-radius: 8px;
    width: 32px; height: 32px; display: flex; align-items: center;
    justify-content: center; cursor: pointer; color: #64748b;
    transition: background .15s; flex-shrink: 0;
}
.ci-close:hover { background: #e2e8f0; color: #1a1a1a; }
#insightsForm { display: flex; flex-direction: column; flex: 1; min-height: 0; overflow: hidden; }
.ci-body { flex: 1; min-height: 0; padding: 20px 22px; overflow-y: auto; }
.ci-section-title {
    font-size: 11px; font-weight: 800; color: #076550; text-transform: uppercase;
    letter-spacing: .06em; margin: 22px 0 12px; padding-top: 14px; border-top: 1px solid #eef2f1;
}
.ci-section-title:first-child { margin-top: 0; padding-top: 0; border-top: none; }
.ci-field { margin-bottom: 14px; }
.ci-field label { display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 5px; }
.ci-field .ci-hint { font-weight: 400; color: #94a3b8; margin-left: 6px; }
.ci-field input[type=text],
.ci-field textarea {
    width: 100%; padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 7px;
    font-size: 13px; color: #1a1a1a; outline: none; font-family: inherit;
    box-sizing: border-box; resize: vertical;
    transition: border-color .15s, box-shadow .15s;
}
.ci-field input:focus, .ci-field textarea:focus { border-color: #076550; box-shadow: 0 0 0 3px rgba(7, 101, 80, .12); }
.ci-field textarea { min-height: 90px; }
.ci-list textarea { min-height: 90px; font-family: ui-monospace, monospace; font-size: 12.5px; }
.ci-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 22px; border-top: 1px solid #e2e8f0; background: #fafafa; flex-shrink: 0;
}
.ci-save-msg { font-size: 13px; font-weight: 500; }
.ci-save-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 22px; background: #076550; color: #fff;
    border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;
    transition: background .15s, transform .1s;
}
.ci-save-btn:hover { background: #054d3c; }
.ci-save-btn:disabled { opacity: .6; cursor: not-allowed; }
.ci-active-row { display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600; color: #475569; }
.ci-active-row select {
    padding: 7px 10px; border: 1.5px solid #e2e8f0; border-radius: 7px;
    font-size: 13px; color: #1a1a1a; outline: none; background: #fff; cursor: pointer;
}
</style>
@endpush
@endonce

<div class="ci-overlay" onclick="if(event.target===this)closeInsightsModal()">
<div class="ci-modal">

    <div class="ci-header">
        <div>
            <h3 id="ciCompanyName">Company Insights</h3>
            <p>AI Summary, Founder's Take and IPO Roadmap for the public company page. Leave a section blank to hide it.</p>
        </div>
        <button class="ci-close" onclick="closeInsightsModal()" type="button">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <form id="insightsForm">
        @csrf
        <div class="ci-body">

            <div class="ci-section-title">AI Summary</div>
            <div class="ci-field">
                <label>30-second summary paragraph</label>
                <textarea name="UL_CI_AI_SUMMARY"></textarea>
            </div>

            <div class="ci-section-title">Founder's Take</div>
            <div class="ci-field">
                <label>Intro line</label>
                <input type="text" name="UL_CI_FOUNDERS_INTRO" maxlength="500" placeholder="e.g. NSE has been &quot;about to IPO&quot; since 2016 — here's the short version.">
            </div>
            <div class="ci-field">
                <label>Pull quote</label>
                <textarea name="UL_CI_FOUNDERS_QUOTE"></textarea>
            </div>
            <div class="ci-field">
                <label>Our verdict</label>
                <textarea name="UL_CI_FOUNDERS_VERDICT"></textarea>
            </div>

            <div class="ci-section-title">IPO Roadmap</div>
            <div class="ci-field ci-list">
                <label>Timeline <span class="ci-hint">One per line — Date | Milestone text</span></label>
                <textarea name="UL_CI_IPO_TIMELINE"></textarea>
            </div>
            <div class="ci-field ci-list">
                <label>IPO facts <span class="ci-hint">One per line — Label | Value</span></label>
                <textarea name="UL_CI_IPO_FACTS"></textarea>
            </div>

            <div class="ci-section-title">Thesis page</div>
            <div class="ci-field">
                <label>TL;DR summary</label>
                <textarea name="UL_CI_TLDR"></textarea>
            </div>
            <div class="ci-field ci-list">
                <label>Bull case <span class="ci-hint">one point per line</span></label>
                <textarea name="UL_CI_BULL_CASE"></textarea>
            </div>
            <div class="ci-field ci-list">
                <label>Bear case <span class="ci-hint">one point per line</span></label>
                <textarea name="UL_CI_BEAR_CASE"></textarea>
            </div>
            <div class="ci-field ci-list">
                <label>Might suit you if… <span class="ci-hint">one point per line</span></label>
                <textarea name="UL_CI_SUITS_IF"></textarea>
            </div>
            <div class="ci-field ci-list">
                <label>Probably not if… <span class="ci-hint">one point per line</span></label>
                <textarea name="UL_CI_NOT_SUITS_IF"></textarea>
            </div>
            <div class="ci-field ci-list">
                <label>Key risks <span class="ci-hint">One per line — Label | Body text</span></label>
                <textarea name="UL_CI_RISKS"></textarea>
            </div>
            <div class="ci-field">
                <label>Where we land (long verdict)</label>
                <textarea name="UL_CI_VERDICT_LONG"></textarea>
            </div>

        </div>

        <div class="ci-footer">
            <div class="ci-active-row">
                <span>Active:</span>
                <select name="UL_CI_ACTIVE">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;gap:12px">
                <span id="ciSaveMsg" class="ci-save-msg"></span>
                <button type="submit" class="ci-save-btn">
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
    var fields  = [
        'UL_CI_AI_SUMMARY', 'UL_CI_TLDR', 'UL_CI_FOUNDERS_INTRO', 'UL_CI_FOUNDERS_QUOTE',
        'UL_CI_FOUNDERS_VERDICT', 'UL_CI_IPO_TIMELINE', 'UL_CI_IPO_FACTS',
        'UL_CI_BULL_CASE', 'UL_CI_BEAR_CASE', 'UL_CI_SUITS_IF', 'UL_CI_NOT_SUITS_IF',
        'UL_CI_RISKS', 'UL_CI_VERDICT_LONG',
    ];

    window.openInsightsModal = function (fc, companyName) {
        fincode = fc;
        $('#ciCompanyName').text('Company Insights — ' + companyName);
        $('#insightsForm')[0].reset();
        $('#ciSaveMsg').text('');

        $.get(window.STOCKS_BASE + '/' + fincode + '/insights')
            .done(function (data) {
                fields.forEach(function (name) {
                    $('[name="' + name + '"]').val(data[name] || '');
                });
                $('select[name="UL_CI_ACTIVE"]').val(data.UL_CI_ACTIVE || '1');
                $('.ci-overlay').addClass('open');
            })
            .fail(function () {
                alert('Failed to load company insights.');
            });
    };

    function closeInsightsModal() { $('.ci-overlay').removeClass('open'); }
    window.closeInsightsModal = closeInsightsModal;

    $('#insightsForm').on('submit', function (e) {
        e.preventDefault();
        var CSRF = $('meta[name="csrf-token"]').attr('content');
        var data = {};
        $(this).serializeArray().forEach(function (f) { data[f.name] = f.value; });
        var $btn = $(this).find('.ci-save-btn').prop('disabled', true)
                          .html('<i class="fa-solid fa-spinner fa-spin"></i> Saving…');

        $.ajax({
            url:         window.STOCKS_BASE + '/' + fincode + '/insights',
            method:      'POST',
            contentType: 'application/json',
            headers:     { 'X-CSRF-TOKEN': CSRF },
            data:        JSON.stringify(data),
        })
        .done(function (res) {
            var color = res.success ? '#076550' : '#e53935';
            $('#ciSaveMsg').css('color', color).text(res.message || (res.success ? 'Saved.' : 'Error.'));
        })
        .fail(function (xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Request failed.';
            $('#ciSaveMsg').css('color', '#e53935').text(msg);
        })
        .always(function () {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> Save');
        });
    });
}());
</script>
@endpush
