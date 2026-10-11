{{-- Script shared by the blog and stock article editors: edit lock heartbeat,
     the TinyMCE body, featured image preview, FAQ/source repeaters and the
     related-posts limit. Include inside @push('scripts').
       $uploadUrl      body image upload endpoint
       $heartbeatUrl   null for a new article (no lock yet)
       $releaseLockUrl null for a new article
       $isLockedOut    someone else holds the lock --}}
<script src="{{ asset('js/tinymce_6.1.2/tinymce.min.js') }}"></script>
<script>
$(function () {
    const CSRF          = $('meta[name="csrf-token"]').attr('content');
    const UPLOAD_URL    = @json($uploadUrl);
    const IS_LOCKED_OUT = @json((bool) $isLockedOut);

    // ── Edit lock: first editor to open it keeps it; everyone else gets
    // a read-only form (enforced again server-side) until the holder saves,
    // leaves, goes idle, or their tab goes quiet past the TTL. ──
    let lastActivityAt = Date.now();
    function markActivity() {
        lastActivityAt = Date.now();
        $('#idleNotice').remove();
    }
    $(document).on('mousemove keydown click scroll', markActivity);

    @if($heartbeatUrl)
    (function () {
        const HEARTBEAT_URL    = @json($heartbeatUrl);
        const RELEASE_LOCK_URL = @json($releaseLockUrl);
        const HEARTBEAT_MS     = 60000;     // keep in sync with LOCK_TTL_MINUTES on the server
        const IDLE_TIMEOUT_MS  = 10 * 60000; // stop renewing the lock after 10 min idle

        const heartbeatTimer = setInterval(function () {
            if (!IS_LOCKED_OUT && (Date.now() - lastActivityAt) > IDLE_TIMEOUT_MS) {
                if (!$('#idleNotice').length) {
                    $('<div class="cms-lock-banner" id="idleNotice"><i class="fa-solid fa-hourglass-half"></i>' +
                      '<span>You have been idle for a while. If someone else opens this they may start editing it — move your mouse or keep typing to hold onto your lock.</span></div>')
                        .insertBefore('#postFieldset');
                }
                return;
            }

            $.post(HEARTBEAT_URL, { _token: CSRF }).done(function (state) {
                if (state.locked !== IS_LOCKED_OUT) location.reload();
            });
        }, HEARTBEAT_MS);

        $(window).on('pagehide', function () {
            clearInterval(heartbeatTimer);
            if (IS_LOCKED_OUT) return;
            const data = new FormData();
            data.append('_token', CSRF);
            navigator.sendBeacon(RELEASE_LOCK_URL, data);
        });
    })();
    @endif

    function contentImageUploadHandler(blobInfo) {
        return new Promise(function (resolve, reject) {
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            formData.append('_token', CSRF);

            $.ajax({ url: UPLOAD_URL, method: 'POST', data: formData, processData: false, contentType: false })
                .done(function (res) {
                    if (res.location) resolve(res.location);
                    else reject('Upload failed: no location returned.');
                })
                .fail(function (xhr) {
                    reject((xhr.responseJSON && xhr.responseJSON.message) || 'Image upload failed.');
                });
        });
    }

    tinymce.init({
        selector: 'textarea#post_content',
        plugins: 'preview searchreplace autolink autosave code visualblocks fullscreen image link media table charmap anchor advlist lists wordcount help quickbars',
        menubar: 'edit view insert format table',
        toolbar: 'undo redo | blocks styles | bold italic underline | bullist numlist | link image table | removeformat | code fullscreen',
        block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4',
        // Only classes in config('blog.content_classes') survive the purifier,
        // so these are the only styles offered.
        style_formats: [
            { title: 'Callout box',      block: 'div', classes: 'sw-callout', wrapper: true },
            { title: 'Pull quote',       block: 'blockquote', classes: 'sw-pullquote' },
            { title: 'Checklist',        selector: 'ul', classes: 'sw-checklist' },
            { title: 'Numbered steps',   selector: 'ol', classes: 'sw-steps' },
            { title: 'Comparison table', selector: 'table', classes: 'sw-table' },
        ],
        content_css: @json(asset('assets/css/admin/blog-editor-content.css') . '?v=' . filemtime(public_path('assets/css/admin/blog-editor-content.css'))),
        table_default_attributes: {},
        table_default_styles: {},
        invalid_styles: { '*': 'color background-color font-size font-family width height' },
        height: 560,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
        quickbars_insert_toolbar: false,
        toolbar_mode: 'sliding',
        contextmenu: 'link image table',
        convert_urls: true,
        relative_urls: false,
        remove_script_host: false,
        images_upload_handler: contentImageUploadHandler,
        automatic_uploads: true,
        paste_data_images: true,
        branding: false,
        promotion: false,
        readonly: IS_LOCKED_OUT,
        // TinyMCE lives in an iframe, so typing there never reaches the
        // document-level listeners markActivity is bound to.
        setup: function (editor) {
            editor.on('input keyup NodeChange', markActivity);
        },
    });

    // ── Featured image preview ──
    $('#featuredInput').on('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) { $('#featuredPreview').attr('src', e.target.result).show(); };
        reader.readAsDataURL(file);
    });

    // ── Repeaters (FAQs, sources) ──
    let rowCounter = Date.now();
    $(document).on('click', '.blog-add-row', function () {
        const html = $($(this).data('template')).html().replace(/__INDEX__/g, String(rowCounter++));
        $($(this).data('target')).append(html);
    });
    $(document).on('click', '.blog-remove-row', function () {
        $(this).closest('.blog-repeater-row').remove();
    });

    $('#relatedPosts').on('change', function () {
        const max = {{ (int) config('blog.max_related') }};
        const picked = $(this).find('option:selected');
        if (picked.length > max) {
            picked.slice(max).prop('selected', false);
            alert('You can pick up to ' + max + ' related posts.');
        }
    });

    $('#postForm').on('submit', function () {
        if (typeof tinymce !== 'undefined') tinymce.triggerSave();
    });
});
</script>
