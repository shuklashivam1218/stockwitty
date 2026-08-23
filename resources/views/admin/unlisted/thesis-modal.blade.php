@once
@push('styles')
<style>
.th-overlay {
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
.th-overlay.open { display: flex; }
.th-modal {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 900px;
    max-height: 94vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 60px rgba(0, 0, 0, .22);
    animation: privSlideIn .2s cubic-bezier(.34, 1.56, .64, 1);
    overflow: hidden;
}
.th-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 22px;
    border-bottom: 1px solid #e2e8f0;
    flex-shrink: 0;
}
.th-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #1a1a1a; }
.th-close {
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
.th-close:hover { background: #e2e8f0; color: #1a1a1a; }
.th-body {
    flex: 1;
    min-height: 0;
    padding: 16px 22px 0;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}
.th-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 22px;
    border-top: 1px solid #e2e8f0;
    background: #fafafa;
    flex-shrink: 0;
    margin-top: 8px;
}
.th-active-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
}
.th-active-row select {
    padding: 7px 10px;
    border: 1.5px solid #e2e8f0;
    border-radius: 7px;
    font-size: 13px;
    color: #1a1a1a;
    outline: none;
    background: #fff;
    cursor: pointer;
    transition: border-color .15s;
}
.th-active-row select:focus { border-color: #076550; }
.th-save-msg { font-size: 13px; font-weight: 500; margin-right: 12px; }
.th-footer-right { display: flex; align-items: center; }
.th-submit-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 22px;
    background: #2196f3;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s, transform .1s;
}
.th-submit-btn:hover { background: #1976d2; }
.th-submit-btn:active { transform: scale(.98); }
.th-submit-btn:disabled { opacity: .6; cursor: not-allowed; }

/* TinyMCE dialogs/pickers must float above this modal (overlay z-index: 2100) */
.tox-tinymce-aux,
.tox-dialog-wrap { z-index: 2300 !important; }
</style>
@endpush
@endonce

<div class="th-overlay" onclick="if(event.target===this)closeThesisModal()">
<div class="th-modal">

    <div class="th-header">
        <h3 id="thCompanyName">Investment Thesis</h3>
        <button class="th-close" onclick="closeThesisModal()" type="button">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="th-body">
        <textarea id="UL_THESIS_CONTENT1"></textarea>
    </div>

    <div class="th-footer">
        <div class="th-active-row">
            <span>Active:</span>
            <select id="thesisActive">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="th-footer-right">
            <span id="thSaveMsg" class="th-save-msg"></span>
            <button id="thesisSubmitBtn" class="th-submit-btn">Submit</button>
        </div>
    </div>

</div>
</div>

@push('scripts')
<script>
(function () {
    var fincode = null;

    var isSmallScreen = window.matchMedia('(max-width: 1023.5px)').matches;

    function thesis_image_upload_handler(blobInfo) {
        return new Promise(function (resolve, reject) {
            var CSRF     = $('meta[name="csrf-token"]').attr('content');
            var formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('_token', CSRF);

            $.ajax({
                url:         window.STOCKS_BASE + '/' + fincode + '/thesis/upload-image',
                method:      'POST',
                data:        formData,
                processData: false,
                contentType: false,
            })
            .done(function (res) {
                if (res.location) {
                    resolve(res.location);
                } else {
                    reject('Upload failed: no location returned.');
                }
            })
            .fail(function (xhr) {
                reject((xhr.responseJSON && xhr.responseJSON.message) || 'Image upload failed.');
            });
        });
    }

    tinymce.init({
        selector: 'textarea#UL_THESIS_CONTENT1',
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | insertfile image media link anchor codesample | ltr rtl',
        toolbar_sticky: isSmallScreen,
        toolbar_sticky_offset: isSmallScreen ? 102 : 108,
        height: 600,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        toolbar_mode: 'sliding',
        tinycomments_mode: 'embedded',
        contextmenu: 'link image table',
        a11y_advanced_options: true,
        convert_urls: true,
        document_base_url: window.location.origin + '/',
        images_upload_handler: thesis_image_upload_handler,
        automatic_uploads: true,
        branding: false,
        promotion: false,
    });

    window.openThesisModal = function (fc, companyName) {
        fincode = fc;
        $('#thCompanyName').text('Investment Thesis — ' + companyName);
        $('#thSaveMsg').text('');

        $.get(window.STOCKS_BASE + '/' + fincode + '/thesis')
            .done(function (data) {
                var editor = tinymce.get('UL_THESIS_CONTENT1');
                if (editor) editor.setContent(data.UL_THESIS_CONTENT || '');
                $('#thesisActive').val(data.UL_THESIS_ACTIVE || '1');
                $('.th-overlay').addClass('open');
            })
            .fail(function () {
                alert('Failed to load thesis.');
            });
    };

    function closeThesisModal() { $('.th-overlay').removeClass('open'); }
    window.closeThesisModal = closeThesisModal;

    $('#thesisSubmitBtn').on('click', function () {
        var CSRF    = $('meta[name="csrf-token"]').attr('content');
        var content = tinymce.get('UL_THESIS_CONTENT1').getContent();
        var active  = $('#thesisActive').val();
        var $btn    = $(this).prop('disabled', true).text('Saving…');

        $.ajax({
            url:         window.STOCKS_BASE + '/' + fincode + '/thesis',
            method:      'POST',
            contentType: 'application/json',
            headers:     { 'X-CSRF-TOKEN': CSRF },
            data:        JSON.stringify({ UL_THESIS_CONTENT: content, UL_THESIS_ACTIVE: active }),
        })
        .done(function (res) {
            var color = res.success ? '#076550' : '#e53935';
            $('#thSaveMsg').css('color', color).text(res.message || (res.success ? 'Saved.' : 'Error.'));
        })
        .fail(function (xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Request failed.';
            $('#thSaveMsg').css('color', '#e53935').text(msg);
        })
        .always(function () {
            $btn.prop('disabled', false).text('Submit');
        });
    });
}());
</script>
@endpush
