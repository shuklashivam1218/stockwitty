@extends('layout.admin')

@section('title', ($post->exists ? 'Edit' : 'New') . ' Post | Blog | Admin | StocksWitty')

@section('content')
<div class="admin-main">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <h1 class="admin-page-title">{{ $post->exists ? 'Edit Post' : 'New Post' }}</h1>
        <a href="{{ route('admin.blog.posts') }}" class="cms-back-link"><i class="fa-solid fa-arrow-left"></i> Back to Posts</a>
    </div>

    @if(session('success'))
        <div class="cms-flash-success">{{ session('success') }}</div>
    @endif

    @if(session('lock_error'))
        <div class="cms-lock-banner cms-lock-banner-error">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>{{ session('lock_error') }}</span>
        </div>
    @endif

    @if($lockedBy)
        <div class="cms-lock-banner" id="lockBanner">
            <i class="fa-solid fa-lock"></i>
            <span><strong>{{ $lockedBy['name'] }}</strong> is currently editing this post (started {{ $lockedBy['since'] }}). This form is read-only until they finish — it unlocks for you as soon as it's free.</span>
        </div>
    @endif

    @if($errors->any())
        <div class="cms-lock-banner cms-lock-banner-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>Please fix the highlighted fields below.</span>
        </div>
    @endif

    <fieldset id="postFieldset" @if($lockedBy) disabled @endif style="border:none;padding:0;margin:0;">
    <form id="postForm" method="POST"
          action="{{ $post->exists ? route('admin.blog.posts.update', $post->id) : route('admin.blog.posts.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if($post->exists) @method('PUT') @endif

        <div class="cms-form-grid">

            {{-- Main column --}}
            <div class="admin-card cms-main-col">

                <div class="cms-field">
                    <label>Title <span class="req">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" required maxlength="255"
                           class="cms-input" placeholder="Post title">
                    @error('title') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                @if($post->exists)
                <div class="cms-field">
                    <label>URL Slug</label>
                    <div class="cms-slug-row">
                        <input type="text" name="slug" id="slugInput"
                               value="{{ old('slug', $post->slug) }}"
                               data-original="{{ $post->slug }}"
                               readonly class="cms-input cms-input-locked">
                        <button type="button" id="slugEditToggle" class="cms-slug-toggle-btn">Edit</button>
                    </div>
                    <p class="cms-field-hint">/blog/<strong id="slugPreview">{{ $post->slug }}</strong>/</p>
                    <p class="cms-field-hint cms-field-warning" id="slugWarning" style="display:none;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        The old URL will redirect to the new one, but only change it if you really need to — shared links and Google rankings settle on the current URL.
                    </p>
                    @error('slug') <div class="cms-error">{{ $message }}</div> @enderror
                </div>
                @endif

                <div class="cms-field">
                    <label>Summary <span class="req" title="Required to publish">*</span></label>
                    <textarea name="summary" rows="3" maxlength="1000" class="cms-input" placeholder="One or two lines shown on the blog list and homepage cards">{{ old('summary', $post->summary) }}</textarea>
                    @error('summary') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                <div class="cms-field">
                    <label>Intro</label>
                    <textarea name="intro" rows="5" maxlength="3000" class="cms-input" placeholder="Opening paragraphs shown above the key takeaways. Leave a blank line between paragraphs.">{{ old('intro', $post->intro) }}</textarea>
                    @error('intro') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                <div class="cms-field">
                    <label>Key Takeaways</label>
                    <textarea name="takeaways_text" rows="5" class="cms-input" placeholder="One takeaway per line (up to {{ config('blog.max_takeaways') }})">{{ old('takeaways_text', implode("\n", $post->takeaways ?? [])) }}</textarea>
                    @error('takeaways') <div class="cms-error">{{ $message }}</div> @enderror
                    @error('takeaways.*') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                <div class="cms-field">
                    <label>Content <span class="req" title="Required to publish">*</span></label>
                    <textarea name="content" id="post_content">{{ old('content', $post->content) }}</textarea>
                    <p class="cms-field-hint"><i class="fa-solid fa-circle-info"></i> Use Heading 2 for sections — each becomes a table-of-contents entry. The Styles menu turns a selection into a callout, pull quote, checklist, numbered steps or comparison table.</p>
                    @error('content') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                {{-- FAQs: repeater rows. Blank rows are dropped on save. --}}
                <div class="cms-field">
                    <label>FAQs</label>
                    <p class="cms-field-hint" style="margin:0 0 8px;">Shown under "Frequently asked questions" and as FAQ rich results in Google. The tab groups questions into filters.</p>
                    <div id="faqRows" class="blog-repeater">
                        @foreach(old('faqs', $post->faqs ?? []) as $i => $faq)
                            @include('admin.blog.partials.faq-row', ['i' => $i, 'faq' => $faq])
                        @endforeach
                    </div>
                    <button type="button" class="cms-action-btn blog-add-row" data-target="#faqRows" data-template="#faqRowTemplate"><i class="fa-solid fa-plus"></i> Add FAQ</button>
                    @error('faqs') <div class="cms-error">{{ $message }}</div> @enderror
                    @foreach($errors->get('faqs.*') as $messages)
                        <div class="cms-error">{{ $messages[0] }}</div>
                    @endforeach
                </div>

                <div class="cms-field">
                    <label>Sources &amp; References</label>
                    <div id="sourceRows" class="blog-repeater">
                        @foreach(old('sources', $post->sources ?? []) as $i => $source)
                            @include('admin.blog.partials.source-row', ['i' => $i, 'source' => $source])
                        @endforeach
                    </div>
                    <button type="button" class="cms-action-btn blog-add-row" data-target="#sourceRows" data-template="#sourceRowTemplate"><i class="fa-solid fa-plus"></i> Add Source</button>
                    @error('sources') <div class="cms-error">{{ $message }}</div> @enderror
                    @foreach($errors->get('sources.*') as $messages)
                        <div class="cms-error">{{ $messages[0] }}</div>
                    @endforeach
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="cms-side-col">

                <div class="admin-card">
                    <div class="cms-side-title">
                        Publish
                        <span class="admin-badge" style="float:right;{{ $post->isPublished() ? 'background:#e8f5e9;color:#2e7d32;' : 'background:#fff3e0;color:#e65100;' }}">
                            {{ $post->isPublished() ? 'Live' : 'Draft' }}
                        </span>
                    </div>
                    <div class="cms-field" style="margin-bottom:12px;">
                        <label style="display:flex;align-items:center;gap:8px;text-transform:none;font-size:13px;">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))>
                            Feature at the top of /blog/
                        </label>
                    </div>
                    {{-- The clicked button decides the status. The first one is
                         also what Enter submits, so it never changes a post's
                         current state: Save Draft for drafts, Update for live posts. --}}
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        @if($post->isPublished())
                            <button type="submit" name="status" value="published" class="cms-submit-btn">
                                <i class="fa-solid fa-check"></i> Update Live Post
                            </button>
                            <button type="submit" name="status" value="draft" class="cms-slug-toggle-btn" style="padding:10px;"
                                    onclick="return confirm('Take this post off the site and move it back to drafts?')">
                                <i class="fa-solid fa-eye-slash"></i> Unpublish (Move to Draft)
                            </button>
                        @else
                            <button type="submit" name="status" value="draft" class="cms-slug-toggle-btn" style="padding:10px;">
                                <i class="fa-regular fa-floppy-disk"></i> Save Draft
                            </button>
                            <button type="submit" name="status" value="published" class="cms-submit-btn">
                                <i class="fa-solid fa-globe"></i> Publish
                            </button>
                        @endif
                    </div>
                    <p class="cms-field-hint" style="margin-top:10px;"><i class="fa-solid fa-circle-info"></i> Fields marked * are only required to publish — a draft can be saved half-done.</p>
                    @error('status') <div class="cms-error">{{ $message }}</div> @enderror
                    @if($post->exists && $post->isPublished())
                        <a href="{{ url($post->url()) }}" target="_blank" rel="noopener" class="cms-back-link" style="margin-top:10px;">
                            View live <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    @endif
                </div>

                <div class="admin-card">
                    <div class="cms-side-title">Category <span class="req" title="Required to publish">*</span></div>
                    <select name="category_id" class="cms-input">
                        <option value="">— Choose —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((int) old('category_id', $post->category_id) === $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                @if($post->exists)
                <div class="admin-card">
                    <div class="cms-side-title">Post Info</div>
                    <div class="cms-info-row">
                        <span class="cms-info-label">Author</span>
                        <span class="cms-info-value">{{ $post->author->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="cms-info-row">
                        <span class="cms-info-label">Reading time</span>
                        <span class="cms-info-value">{{ $post->readLabel() }}</span>
                    </div>
                    <div class="cms-info-row">
                        <span class="cms-info-label">First drafted</span>
                        <span class="cms-info-value">{{ $post->created_at?->format('d M Y, h:i A') }}</span>
                    </div>
                    <div class="cms-info-row">
                        <span class="cms-info-label">Last updated</span>
                        <span class="cms-info-value">{{ $post->updated_at?->format('d M Y, h:i A') }}</span>
                    </div>
                    @if($post->published_at)
                    <div class="cms-info-row">
                        <span class="cms-info-label">Published on</span>
                        <span class="cms-info-value">{{ $post->published_at->format('d M Y, h:i A') }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <div class="admin-card">
                    <div class="cms-side-title">Featured Image</div>
                    <img src="{{ $post->featured_image ? asset($post->featured_image) : '' }}" class="cms-featured-preview" id="featuredPreview"
                         alt="" @unless($post->featured_image) style="display:none;" @endunless>
                    <input type="file" name="featured_image" accept="image/png,image/jpeg,image/gif,image/webp" id="featuredInput" class="cms-input">
                    <p class="cms-field-hint">JPG, PNG, GIF or WebP, up to 5 MB. 1600×900 works best.</p>
                    @error('featured_image') <div class="cms-error">{{ $message }}</div> @enderror
                    <div class="cms-field" style="margin:12px 0 0;">
                        <label>Image alt text</label>
                        <input type="text" name="featured_image_alt" value="{{ old('featured_image_alt', $post->featured_image_alt) }}" maxlength="255"
                               class="cms-input" placeholder="Describe the image for screen readers">
                        @error('featured_image_alt') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="admin-card">
                    <div class="cms-side-title">Hero &amp; Tags</div>
                    <div class="cms-field">
                        <label>Hero icon</label>
                        <select name="hero_icon" class="cms-input">
                            <option value="">— None —</option>
                            @foreach($heroIcons as $icon)
                                <option value="{{ $icon }}" @selected(old('hero_icon', $post->hero_icon) === $icon)>{{ $icon }}</option>
                            @endforeach
                        </select>
                        <p class="cms-field-hint">Shown in the hero when there's no featured image.</p>
                        @error('hero_icon') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="cms-field" style="margin-bottom:0;">
                        <label>Chips</label>
                        <input type="text" name="chips_text" class="cms-input" maxlength="300"
                               value="{{ old('chips_text', implode(', ', $post->chips ?? [])) }}" placeholder="Unlisted Shares, Tax, 2026">
                        <p class="cms-field-hint">Comma-separated, up to {{ config('blog.max_chips') }}. Shown above the title.</p>
                        @error('chips') <div class="cms-error">{{ $message }}</div> @enderror
                        @error('chips.*') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="admin-card">
                    <div class="cms-side-title">Video</div>
                    <div class="cms-field">
                        <label>YouTube link</label>
                        <input type="url" name="video_url" class="cms-input" maxlength="255"
                               value="{{ old('video_url', isset($post->video['youtube_id']) ? 'https://www.youtube.com/watch?v=' . $post->video['youtube_id'] : '') }}"
                               placeholder="https://www.youtube.com/watch?v=…">
                        @error('video_url') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="cms-field" style="margin-bottom:0;">
                        <label>Caption</label>
                        <input type="text" name="video_caption" class="cms-input" maxlength="200"
                               value="{{ old('video_caption', $post->video['caption'] ?? '') }}" placeholder="Watch: … explained simply">
                        @error('video_caption') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="admin-card">
                    <div class="cms-side-title">Related Posts</div>
                    @php $relatedIds = array_map('intval', (array) old('related_post_ids', $post->related_post_ids ?? [])); @endphp
                    <select name="related_post_ids[]" multiple size="6" class="cms-input" id="relatedPosts">
                        @foreach($otherPosts as $other)
                            <option value="{{ $other->id }}" @selected(in_array($other->id, $relatedIds, true))>
                                {{ $other->title }}{{ $other->isPublished() ? '' : ' (draft)' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="cms-field-hint">Ctrl/Cmd-click to pick up to {{ config('blog.max_related') }}. Drafts are skipped on the live page until published.</p>
                    @error('related_post_ids') <div class="cms-error">{{ $message }}</div> @enderror
                    @error('related_post_ids.*') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                <div class="admin-card">
                    <div class="cms-side-title">Callback Form</div>
                    <div class="cms-field">
                        <label>Heading</label>
                        <input type="text" name="lead_heading" class="cms-input" maxlength="255"
                               value="{{ old('lead_heading', $post->lead_heading) }}" placeholder="Questions on this topic? We'll connect you.">
                        @error('lead_heading') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="cms-field" style="margin-bottom:0;">
                        <label>Subtext</label>
                        <textarea name="lead_subtext" rows="3" maxlength="1000" class="cms-input" placeholder="Leave blank for the default text">{{ old('lead_subtext', $post->lead_subtext) }}</textarea>
                        @error('lead_subtext') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="admin-card">
                    <div class="cms-side-title">Related Unlisted Stocks</div>
                    <div class="cms-tag-picker">
                        <div class="cms-tag-chips" id="tagChips">
                            @foreach($selectedStocks as $stock)
                                <span class="cms-tag-chip" data-fincode="{{ $stock->UL_STOCKS_FINCODE }}">
                                    {{ $stock->UL_STOCKS_COMPNAME }}
                                    <input type="hidden" name="stock_tickers[]" value="{{ $stock->UL_STOCKS_FINCODE }}">
                                    <i class="fa-solid fa-xmark cms-tag-remove"></i>
                                </span>
                            @endforeach
                        </div>
                        <input type="text" id="tagSearchInput" class="cms-input" placeholder="Search stock by name..." autocomplete="off">
                        <ul id="tagSuggestions" class="cms-tag-suggestions"></ul>
                    </div>
                    @error('stock_tickers.*') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                <div class="admin-card">
                    <div class="cms-side-title">SEO Meta</div>
                    <div class="cms-field">
                        <label>Meta Title <span class="req" title="Required to publish">*</span></label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" maxlength="255" class="cms-input">
                        @error('meta_title') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="cms-field">
                        <label>Meta Description <span class="req" title="Required to publish">*</span></label>
                        <textarea name="meta_description" rows="3" maxlength="500" class="cms-input">{{ old('meta_description', $post->meta_description) }}</textarea>
                        @error('meta_description') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="cms-field">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords) }}" maxlength="500" class="cms-input">
                        @error('meta_keywords') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                </div>

            </div>
        </div>
    </form>
    </fieldset>
</div>

{{-- New repeater rows are cloned from these; __INDEX__ is replaced in JS. --}}
<template id="faqRowTemplate">@include('admin.blog.partials.faq-row', ['i' => '__INDEX__', 'faq' => []])</template>
<template id="sourceRowTemplate">@include('admin.blog.partials.source-row', ['i' => '__INDEX__', 'source' => []])</template>

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/cms-form.css') }}?v={{ filemtime(public_path('assets/css/admin/cms-form.css')) }}">
@endpush
@endsection

@push('scripts')
<script src="{{ asset('js/tinymce_6.1.2/tinymce.min.js') }}"></script>
<script>
$(function () {
    const CSRF          = $('meta[name="csrf-token"]').attr('content');
    const UPLOAD_URL    = @json(route('admin.blog.posts.upload-image'));
    const SEARCH_URL    = @json(route('admin.blog.posts.stocks.search'));
    const IS_LOCKED_OUT = @json((bool) $lockedBy);

    // ── Edit lock: first editor to open the post keeps it; everyone else gets
    // a read-only form (enforced again server-side) until the holder saves,
    // leaves, goes idle, or their tab goes quiet past the TTL. ──
    let lastActivityAt = Date.now();
    function markActivity() {
        lastActivityAt = Date.now();
        $('#idleNotice').remove();
    }
    $(document).on('mousemove keydown click scroll', markActivity);

    @if($post->exists)
    (function () {
        const HEARTBEAT_URL    = @json(route('admin.blog.posts.heartbeat', $post->id));
        const RELEASE_LOCK_URL = @json(route('admin.blog.posts.release-lock', $post->id));
        const HEARTBEAT_MS     = 60000;     // keep in sync with LOCK_TTL_MINUTES on the server
        const IDLE_TIMEOUT_MS  = 10 * 60000; // stop renewing the lock after 10 min idle

        const heartbeatTimer = setInterval(function () {
            if (!IS_LOCKED_OUT && (Date.now() - lastActivityAt) > IDLE_TIMEOUT_MS) {
                if (!$('#idleNotice').length) {
                    $('<div class="cms-lock-banner" id="idleNotice"><i class="fa-solid fa-hourglass-half"></i>' +
                      '<span>You have been idle for a while. If someone else opens this post they may start editing it — move your mouse or keep typing to hold onto your lock.</span></div>')
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

    // ── Slug edit toggle ──
    $('#slugEditToggle').on('click', function () {
        const $input   = $('#slugInput');
        const editing  = !$input.prop('readonly');

        if (editing) {
            $input.val($input.data('original')).prop('readonly', true).addClass('cms-input-locked');
            $('#slugPreview').text($input.data('original'));
            $('#slugWarning').hide();
            $(this).text('Edit');
        } else {
            $input.prop('readonly', false).removeClass('cms-input-locked').trigger('focus');
            $('#slugWarning').show();
            $(this).text('Cancel');
        }
    });
    $('#slugInput').on('input', function () {
        $('#slugPreview').text($(this).val().toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, ''));
    });

    // ── Featured image preview ──
    $('#featuredInput').on('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) { $('#featuredPreview').attr('src', e.target.result).show(); };
        reader.readAsDataURL(file);
    });

    // ── Related stock picker. Names come from the DB: always set as text,
    // never concatenated into HTML. ──
    let searchTimer = null;
    $('#tagSearchInput').on('input', function () {
        const term = $(this).val().trim();
        clearTimeout(searchTimer);
        if (term.length < 2) { $('#tagSuggestions').hide().empty(); return; }

        searchTimer = setTimeout(function () {
            $.get(SEARCH_URL, { term: term }).done(function (stocks) {
                const existing = $('#tagChips [data-fincode]').map(function () { return String($(this).data('fincode')); }).get();
                const $list = $('#tagSuggestions').empty();
                stocks.forEach(function (s) {
                    if (existing.indexOf(String(s.fincode)) !== -1) return;
                    $('<li>').text(s.name).attr('data-fincode', s.fincode).attr('data-name', s.name).appendTo($list);
                });
                $list.toggle($list.children().length > 0);
            });
        }, 250);
    });

    $(document).on('click', '#tagSuggestions li', function () {
        const fincode = String($(this).data('fincode'));
        const $chip = $('<span>').addClass('cms-tag-chip').attr('data-fincode', fincode)
            .append(document.createTextNode($(this).data('name') + ' '))
            .append($('<input>', { type: 'hidden', name: 'stock_tickers[]', value: fincode }))
            .append($('<i>').addClass('fa-solid fa-xmark cms-tag-remove'));
        $('#tagChips').append($chip);
        $('#tagSearchInput').val('');
        $('#tagSuggestions').hide().empty();
    });

    $(document).on('click', '.cms-tag-remove', function () {
        $(this).closest('.cms-tag-chip').remove();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.cms-tag-picker').length) $('#tagSuggestions').hide();
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
@endpush
