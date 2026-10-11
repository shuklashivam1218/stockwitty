@extends('layout.admin')

@php
    use App\Http\Controllers\StockArticlesController;
    use App\Models\StockArticle;
    use App\Models\UnlistedWittyScore;

    $type  = $article->type;
    $isNew = !$article->exists;
@endphp

@section('title', ($isNew ? 'New' : 'Edit') . ' ' . $label . ' Page | CMS | Admin | StocksWitty')

@section('content')
<div class="admin-main">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <h1 class="admin-page-title">
            {{ $isNew ? 'New ' . $label . ' Page' : $label . ' — ' . ($article->stock->UL_STOCKS_COMPNAME ?? '') }}
        </h1>
        <a href="{{ route('admin.stock-articles.index', ['type' => $type]) }}" class="cms-back-link"><i class="fa-solid fa-arrow-left"></i> All {{ $label }} pages</a>
    </div>

    @include('admin.articles.banners', ['noun' => 'page'])

    <fieldset id="postFieldset" @if($lockedBy) disabled @endif style="border:none;padding:0;margin:0;">
    <form id="postForm" method="POST"
          action="{{ $isNew ? route('admin.stock-articles.store') : route('admin.stock-articles.update', $article->id) }}"
          enctype="multipart/form-data">
        @csrf
        @unless($isNew) @method('PUT') @endunless

        <div class="cms-form-grid">

            {{-- Main column --}}
            <div class="admin-card cms-main-col">

                @if($isNew)
                    <div class="cms-field">
                        <label>Page type <span class="req">*</span></label>
                        <select name="type" id="typeSelect" class="cms-input" data-create-url="{{ route('admin.stock-articles.create') }}">
                            @foreach(StockArticle::TYPES as $key => $meta)
                                <option value="{{ $key }}" @selected($type === $key)>{{ $meta['label'] }} — /unlisted-shares/company/{{ $key }}/</option>
                            @endforeach
                        </select>
                        <p class="cms-field-hint">Changing it reloads the stock list below.</p>
                        @error('type') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="cms-field">
                        <label>Stock <span class="req">*</span></label>
                        {{-- Search-as-you-type picker: 10 stocks per request, more on scroll. --}}
                        <div class="sp-combo" id="stockCombo" data-url="{{ route('admin.stock-articles.stocks', ['type' => $type]) }}">
                            <input type="hidden" name="ul_stocks_fincode" id="stockValue" value="{{ $selectedStock?->UL_STOCKS_FINCODE }}">
                            <input type="text" id="stockSearch" class="cms-input @error('ul_stocks_fincode') is-invalid @enderror" autocomplete="off"
                                   value="{{ $selectedStock?->UL_STOCKS_COMPNAME }}" placeholder="Search the company by name…"
                                   role="combobox" aria-expanded="false" aria-controls="stockOptions">
                            <ul class="sp-combo-list" id="stockOptions" role="listbox" hidden></ul>
                        </div>
                        <p class="cms-field-hint">Only stocks without a {{ strtolower($label) }} page show up. The page goes live at /unlisted-shares/<em>company</em>/{{ $type }}/.</p>
                        @error('ul_stocks_fincode') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                @else
                    <div class="cms-field">
                        <label>Page type</label>
                        <div class="cms-input cms-input-locked">{{ $label }}</div>
                    </div>

                    <div class="cms-field">
                        <label>Stock</label>
                        <div class="cms-input cms-input-locked">{{ $article->stock->UL_STOCKS_COMPNAME ?? '—' }}</div>
                        <p class="cms-field-hint"><i class="fa-solid fa-link"></i> /unlisted-shares/{{ $article->stock->UL_STOCKS_SLUG ?? '' }}/{{ $type }}/</p>
                    </div>
                @endif

                <div class="cms-field">
                    <label>Title <span class="req">*</span></label>
                    <input type="text" name="title" id="titleInput" value="{{ old('title', $article->title) }}" required maxlength="255"
                           class="cms-input" placeholder="{{ StockArticle::defaultTitle($type, 'Company name') }}">
                    @error('title') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                @include('admin.articles.main-fields', ['article' => $article, 'summaryHint' => 'One or two lines shown in search results and link previews'])

            </div>

            {{-- Sidebar --}}
            <div class="cms-side-col">

                <div class="admin-card">
                    <div class="cms-side-title">
                        Publish
                        <span class="admin-badge" style="float:right;{{ $article->isPublished() ? 'background:#e8f5e9;color:#2e7d32;' : 'background:#fff3e0;color:#e65100;' }}">
                            {{ $article->isPublished() ? 'Live' : 'Draft' }}
                        </span>
                    </div>
                    @include('admin.articles.publish-buttons', ['article' => $article, 'noun' => 'page'])
                    @if(!$isNew && $article->isPublished())
                        <a href="{{ url($article->url()) }}" target="_blank" rel="noopener" class="cms-back-link" style="margin-top:10px;">
                            View live <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    @endif
                </div>

                @if($type === StockArticle::TYPE_WITTYSCORE)
                    <div class="admin-card">
                        <div class="cms-side-title">
                            Pillar Scores
                            <span style="float:right;font-size:13px;color:#076550;">Overall <strong id="wsOverall">—</strong>/10</span>
                        </div>
                        @foreach(StockArticlesController::PILLARS as $field => $pillarLabel)
                            <div class="cms-field" style="margin-bottom:10px;">
                                <label>{{ $pillarLabel }} <span style="font-weight:400;color:#999;text-transform:none;">· {{ (int) round(UnlistedWittyScore::WEIGHTS[$field] * 100) }}%</span></label>
                                <input type="number" step="0.1" min="0" max="10" name="{{ $field }}" class="cms-input ws-pillar"
                                       data-weight="{{ UnlistedWittyScore::WEIGHTS[$field] }}"
                                       value="{{ old($field, $pillars?->$field) }}" placeholder="0.0 – 10.0">
                                @error($field) <div class="cms-error">{{ $message }}</div> @enderror
                            </div>
                        @endforeach
                        <p class="cms-field-hint">Out of 10. These drive the WittyScore widget on the company page and save with this page.</p>
                    </div>
                @endif

                @unless($isNew)
                    @include('admin.articles.info-card', ['article' => $article, 'title' => 'Page Info'])
                @endunless

                @include('admin.articles.side-cards', ['article' => $article])

                @include('admin.articles.seo-card', ['article' => $article])

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
    'uploadUrl'      => route('admin.stock-articles.upload-image'),
    'heartbeatUrl'   => $isNew ? null : route('admin.stock-articles.heartbeat', $article->id),
    'releaseLockUrl' => $isNew ? null : route('admin.stock-articles.release-lock', $article->id),
    'isLockedOut'    => (bool) $lockedBy,
])
<script>
$(function () {
    // New page: the stock list depends on the page type, so switching reloads it.
    $('#typeSelect').on('change', function () {
        const url = new URL($(this).data('create-url'), window.location.origin);
        url.searchParams.set('type', $(this).val());
        window.location = url.toString();
    });

    // New page: the title follows the chosen stock until it's typed by hand.
    const TITLE_SUFFIX = @json(StockArticle::TYPES[$type]['title']);
    const $title = $('#titleInput');
    let titleTouched = $title.val().trim() !== '';
    $title.on('input', function () { titleTouched = $(this).val().trim() !== ''; });

    // ── Stock picker: searches the server as you type, 10 at a time, and
    // loads the next 10 when the list is scrolled to the bottom. Names are
    // set with .text(), never put into HTML strings. ──
    (function () {
        const $combo = $('#stockCombo');
        if (!$combo.length) return;

        const URL_BASE = $combo.data('url');
        const $search  = $('#stockSearch');
        const $value   = $('#stockValue');
        const $list    = $('#stockOptions');
        let term = null, page = 1, more = false, loading = false, request = null, timer = null, chosenName = $search.val();

        function open()  { $list.prop('hidden', false); $search.attr('aria-expanded', 'true'); }
        function close() { $list.prop('hidden', true);  $search.attr('aria-expanded', 'false'); }

        function load(reset) {
            if (loading && !reset) return;
            if (reset) { page = 1; if (request) request.abort(); }
            loading = true;
            $list.find('.sp-combo-status').remove();
            $list.append($('<li class="sp-combo-status">').text('Loading…'));
            open();

            request = $.getJSON(URL_BASE, { term: term, page: page })
                .done(function (res) {
                    if (reset) $list.empty();
                    $list.find('.sp-combo-status').remove();
                    res.items.forEach(function (s) {
                        $('<li class="sp-combo-option" role="option">')
                            .attr('data-fincode', s.fincode).attr('data-name', s.name)
                            .text(s.name + (s.active ? '' : ' (inactive)'))
                            .toggleClass('is-selected', String(s.fincode) === String($value.val()))
                            .appendTo($list);
                    });
                    if (!$list.children('.sp-combo-option').length) {
                        $list.append($('<li class="sp-combo-status">').text(term ? 'No stock matches "' + term + '".' : 'Every stock already has this page.'));
                    }
                    more = res.more;
                    page += 1;
                })
                .fail(function (xhr) {
                    if (xhr.statusText !== 'abort') $list.find('.sp-combo-status').text('Could not load stocks.');
                })
                .always(function () { loading = false; });
        }

        function choose($opt) {
            const name = $opt.data('name');
            $value.val($opt.data('fincode'));
            $search.val(name).removeClass('is-invalid');
            chosenName = name;
            close();
            if (!titleTouched) $title.val(name + ': ' + TITLE_SUFFIX);
        }

        $search.on('focus', function () {
            if (term === null) { term = ''; load(true); } else { open(); }
        });
        $search.on('input', function () {
            $value.val('');                       // typing un-picks the stock until one is chosen
            clearTimeout(timer);
            timer = setTimeout(function () { term = $search.val().trim(); load(true); }, 250);
        });
        $search.on('keydown', function (e) {
            const $opts = $list.children('.sp-combo-option');
            let i = $opts.index($opts.filter('.is-active'));
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                open();
                i = e.key === 'ArrowDown' ? Math.min(i + 1, $opts.length - 1) : Math.max(i - 1, 0);
                $opts.removeClass('is-active').eq(i).addClass('is-active')[0]?.scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'Enter') {
                e.preventDefault();               // never submit the form from the picker
                if (i >= 0) choose($opts.eq(i));
            } else if (e.key === 'Escape') {
                close();
            }
        });
        $list.on('mousedown', '.sp-combo-option', function (e) {
            e.preventDefault();                   // keep focus so blur doesn't close first
            choose($(this));
        });
        $list.on('scroll', function () {
            if (more && !loading && this.scrollTop + this.clientHeight >= this.scrollHeight - 30) load(false);
        });
        $search.on('blur', function () {
            setTimeout(close, 150);
            // Left half-typed: put back the stock that is still selected (or clear).
            if (!$value.val()) { $search.val(''); chosenName = ''; } else { $search.val(chosenName); }
        });
    })();

    // WittyScore: overall from the same weights as the public formula.
    function recalcOverall() {
        let total = 0, weight = 0;
        $('.ws-pillar').each(function () {
            const v = parseFloat($(this).val());
            const w = parseFloat($(this).data('weight'));
            if (!isNaN(v)) { total += v * w; weight += w; }
        });
        $('#wsOverall').text(weight > 0 ? (total / weight).toFixed(1) : '—');
    }
    $(document).on('input', '.ws-pillar', recalcOverall);
    recalcOverall();
});
</script>
@endpush
