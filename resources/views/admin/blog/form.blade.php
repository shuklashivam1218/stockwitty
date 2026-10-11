@extends('layout.admin')

@section('title', ($post->exists ? 'Edit' : 'New') . ' Post | Blog | Admin | StocksWitty')

@section('content')
<div class="admin-main">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <h1 class="admin-page-title">{{ $post->exists ? 'Edit Post' : 'New Post' }}</h1>
        <a href="{{ route('admin.blog.posts') }}" class="cms-back-link"><i class="fa-solid fa-arrow-left"></i> Back to Posts</a>
    </div>

    @include('admin.articles.banners', ['noun' => 'post'])

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
                    <input type="text" name="title" id="titleInput" value="{{ old('title', $post->title) }}" required maxlength="255"
                           class="cms-input" placeholder="Post title">
                    @error('title') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                @unless($post->exists)
                {{-- New post: the slug follows the title until it's typed in by hand. --}}
                <div class="cms-field">
                    <label>URL Slug</label>
                    <input type="text" name="slug" id="newSlugInput" value="{{ old('slug') }}" maxlength="200"
                           class="cms-input" placeholder="Filled in from the title — or type your own" autocomplete="off">
                    <p class="cms-field-hint">
                        <i class="fa-solid fa-link"></i>
                        stockswitty.com/blog/<strong id="newSlugPreview">{{ old('slug') ? \Illuminate\Support\Str::slug(old('slug')) : 'your-post-title' }}</strong>/
                    </p>
                    <p class="cms-field-hint">Lowercase letters, numbers and hyphens. Pick it carefully — once the post is live, the URL should not change.</p>
                    @error('slug') <div class="cms-error">{{ $message }}</div> @enderror
                </div>
                @endunless

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

                @include('admin.articles.main-fields', ['article' => $post, 'summaryHint' => 'One or two lines shown on the blog list and homepage cards'])

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
                    @include('admin.articles.publish-buttons', ['article' => $post, 'noun' => 'post'])
                    @if($post->exists)
                        <a href="{{ route('admin.blog.posts.preview', $post->id) }}" target="_blank" rel="noopener" class="cms-back-link" style="margin-top:10px;margin-right:14px;">
                            <i class="fa-solid fa-eye"></i> Preview
                        </a>
                    @endif
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
                    @include('admin.articles.info-card', ['article' => $post, 'title' => 'Post Info'])
                @endif

                @include('admin.articles.side-cards', ['article' => $post])

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

                @include('admin.articles.seo-card', ['article' => $post])

            </div>
        </div>
    </form>
    </fieldset>
</div>

@include('admin.articles.row-templates')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/cms-form.css') }}?v={{ filemtime(public_path('assets/css/admin/cms-form.css')) }}">
@endpush
@endsection

@push('scripts')
@include('admin.articles.editor-script', [
    'uploadUrl'      => route('admin.blog.posts.upload-image'),
    'heartbeatUrl'   => $post->exists ? route('admin.blog.posts.heartbeat', $post->id) : null,
    'releaseLockUrl' => $post->exists ? route('admin.blog.posts.release-lock', $post->id) : null,
    'isLockedOut'    => (bool) $lockedBy,
])
<script>
$(function () {
    const SEARCH_URL = @json(route('admin.blog.posts.stocks.search'));

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
    // Same shape as Str::slug for the common case; the server has the final say.
    function toSlug(text) {
        return text.toLowerCase().normalize('NFKD').replace(/[̀-ͯ]/g, '')
            .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 200);
    }

    $('#slugInput').on('input', function () {
        $('#slugPreview').text(toSlug($(this).val()));
    });

    // New post: follow the title until the slug is edited by hand; clearing it
    // hands control back to the title.
    const $newSlug = $('#newSlugInput');
    if ($newSlug.length) {
        let slugTouched = $newSlug.val().trim() !== '';
        const showPreview = function () {
            $('#newSlugPreview').text(toSlug($newSlug.val()) || 'your-post-title');
        };
        $('#titleInput').on('input', function () {
            if (!slugTouched) {
                $newSlug.val(toSlug($(this).val()));
                showPreview();
            }
        });
        $newSlug.on('input', function () {
            slugTouched = $(this).val().trim() !== '';
            showPreview();
        });
        $newSlug.on('blur', function () {
            if ($(this).val().trim() !== '') $(this).val(toSlug($(this).val()));
            showPreview();
        });
    }

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
});
</script>
@endpush
