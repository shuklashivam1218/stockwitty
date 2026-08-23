@php
    $targets = [
        'overview' => 'Overview',
        'about'    => 'About',
        'thesis'   => 'Thesis',
    ];
@endphp

@once
@push('styles')
<style>
.fq-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, .55);
    z-index: 2200;
    align-items: center;
    justify-content: center;
    padding: 16px;
    backdrop-filter: blur(2px);
}
.fq-overlay.open { display: flex; }
.fq-modal {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 640px;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 60px rgba(0, 0, 0, .22);
    animation: privSlideIn .2s cubic-bezier(.34, 1.56, .64, 1);
    overflow: hidden;
}
.fq-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #e2e8f0;
    flex-shrink: 0;
}
.fq-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #1a1a1a; }
.fq-close {
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
.fq-close:hover { background: #e2e8f0; color: #1a1a1a; }
#faqForm { display: flex; flex-direction: column; flex: 1; min-height: 0; overflow: hidden; }
.fq-body { flex: 1; min-height: 0; padding: 20px 22px; overflow-y: auto; }
.fq-field { margin-bottom: 16px; }
.fq-field label { display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 5px; text-transform: uppercase; letter-spacing: .04em; }
.fq-field input[type=text],
.fq-field input[type=number],
.fq-field select,
.fq-field textarea {
    width: 100%; padding: 9px 12px;
    border: 1.5px solid #e2e8f0; border-radius: 7px;
    font-size: 13px; color: #1a1a1a; outline: none;
    font-family: inherit;
    transition: border-color .15s, box-shadow .15s;
    box-sizing: border-box;
}
.fq-field input:focus, .fq-field select:focus, .fq-field textarea:focus {
    border-color: #076550;
    box-shadow: 0 0 0 3px rgba(7, 101, 80, .12);
}
.fq-field textarea { resize: vertical; min-height: 90px; }
.fq-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.fq-req { color: #e53935; }
.fq-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 22px;
    border-top: 1px solid #e2e8f0;
    background: #fafafa;
    flex-shrink: 0;
}
.fq-save-msg { font-size: 13px; font-weight: 500; }
.fq-save-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 22px; background: #076550; color: #fff;
    border: none; border-radius: 8px; font-size: 13px;
    font-weight: 600; cursor: pointer;
    transition: background .15s, transform .1s;
}
.fq-save-btn:hover { background: #054d3c; }
.fq-save-btn:disabled { opacity: .6; cursor: not-allowed; }
</style>
@endpush
@endonce

<div class="fq-overlay" onclick="if(event.target===this)closeFaqEditModal()">
<div class="fq-modal">

    <div class="fq-header">
        <h3 id="fqTitle">Add FAQ</h3>
        <button class="fq-close" onclick="closeFaqEditModal()" type="button">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <form id="faqForm">
        @csrf
        <div class="fq-body">

            <div class="fq-field">
                <label>Link To <span class="fq-req">*</span></label>
                <select name="UL_FAQ_TARGET" required>
                    <option value="">— Select —</option>
                    @foreach($targets as $val => $label)
                        <option value="{{ $val }}">{{ $label }} Page</option>
                    @endforeach
                </select>
            </div>

            <div class="fq-field">
                <label>Tab (optional — groups FAQs within the page, e.g. "Basics", "Business")</label>
                <input type="text" name="UL_FAQ_TAB" maxlength="60" placeholder="Leave blank for no tab">
            </div>

            <div class="fq-field">
                <label>Question <span class="fq-req">*</span></label>
                <input type="text" name="UL_FAQ_QUESTION" maxlength="500" required>
            </div>

            <div class="fq-field">
                <label>Answer</label>
                <textarea name="UL_FAQ_ANSWER"></textarea>
            </div>

            <div class="fq-row">
                <div class="fq-field">
                    <label>Sort Order</label>
                    <input type="number" name="UL_FAQ_SORT_ORDER" value="0">
                </div>
                <div class="fq-field">
                    <label>Active</label>
                    <select name="UL_FAQ_ACTIVE">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

        </div>

        <div class="fq-footer">
            <span id="faqSaveMsg" class="fq-save-msg"></span>
            <button type="submit" class="fq-save-btn" id="faqSaveBtn">
                <i class="fa-solid fa-floppy-disk"></i> Save
            </button>
        </div>
    </form>

</div>
</div>

@push('scripts')
<script>
(function () {
    var fincode = null;
    var faqId   = null;
    var isEdit  = false;

    window.openFaqAddModal = function (fc) {
        fincode = fc;
        faqId   = null;
        isEdit  = false;
        $('#fqTitle').text('Add FAQ');
        $('#faqForm')[0].reset();
        $('#faqSaveMsg').text('');
        $('#faqSaveBtn').html('<i class="fa-solid fa-floppy-disk"></i> Save');
        $('.fq-overlay').addClass('open');
    };

    window.openFaqEditModal = function (fc, id) {
        fincode = fc;
        faqId   = id;
        isEdit  = true;
        $('#fqTitle').text('Edit FAQ');
        $('#faqForm')[0].reset();
        $('#faqSaveMsg').text('');
        $('#faqSaveBtn').html('<i class="fa-solid fa-floppy-disk"></i> Update');

        $.get(window.STOCKS_BASE + '/' + fincode + '/faqs/' + id + '/edit')
            .done(function (data) {
                $('#faqForm select, #faqForm input, #faqForm textarea').each(function () {
                    var name = $(this).attr('name');
                    if (name && data[name] !== undefined && data[name] !== null) $(this).val(data[name]);
                });
                $('.fq-overlay').addClass('open');
            })
            .fail(function () {
                alert('Failed to load FAQ.');
            });
    };

    function closeFaqEditModal() {
        $('.fq-overlay').removeClass('open');
        if (typeof window.loadFaqList === 'function') window.loadFaqList();
    }
    window.closeFaqEditModal = closeFaqEditModal;

    $('#faqForm').on('submit', function (e) {
        e.preventDefault();
        var CSRF   = $('meta[name="csrf-token"]').attr('content');
        var url    = isEdit ? window.STOCKS_BASE + '/' + fincode + '/faqs/' + faqId : window.STOCKS_BASE + '/' + fincode + '/faqs';
        var method = isEdit ? 'PUT' : 'POST';

        var $btn = $(this).find('.fq-save-btn').prop('disabled', true)
                          .html('<i class="fa-solid fa-spinner fa-spin"></i> Saving…');
        var data = {};
        $(this).serializeArray().forEach(function (f) { data[f.name] = f.value; });

        $.ajax({
            url:         url,
            method:      method,
            contentType: 'application/json',
            headers:     { 'X-CSRF-TOKEN': CSRF },
            data:        JSON.stringify(data),
        })
        .done(function (res) {
            var color = res.success ? '#076550' : '#e53935';
            $('#faqSaveMsg').css('color', color).text(res.message || (res.success ? 'Saved.' : 'Error.'));
            if (res.success) {
                if (isEdit) {
                    setTimeout(function () { closeFaqEditModal(); }, 800);
                } else {
                    $('#faqForm')[0].reset();
                    if (typeof window.loadFaqList === 'function') window.loadFaqList();
                }
            }
        })
        .fail(function (xhr) {
            var errors   = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : {};
            var firstKey = Object.keys(errors)[0];
            var msg      = (firstKey ? errors[firstKey][0] : null)
                         || (xhr.responseJSON && xhr.responseJSON.message)
                         || 'Request failed.';
            $('#faqSaveMsg').css('color', '#e53935').text(msg);
        })
        .always(function () {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-floppy-disk"></i> ' + (isEdit ? 'Update' : 'Save'));
        });
    });
}());
</script>
@endpush
