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
        <a href="{{ route('admin.stock-articles.index', $type) }}" class="cms-back-link"><i class="fa-solid fa-arrow-left"></i> All {{ $label }} pages</a>
    </div>

    @include('admin.articles.banners', ['noun' => 'page'])

    <fieldset id="postFieldset" @if($lockedBy) disabled @endif style="border:none;padding:0;margin:0;">
    <form id="postForm" method="POST"
          action="{{ $isNew ? route('admin.stock-articles.store', $type) : route('admin.stock-articles.update', $article->id) }}"
          enctype="multipart/form-data">
        @csrf
        @unless($isNew) @method('PUT') @endunless

        <div class="cms-form-grid">

            {{-- Main column --}}
            <div class="admin-card cms-main-col">

                @if($isNew)
                    <div class="cms-field">
                        <label>Stock <span class="req">*</span></label>
                        @if($availableStocks->isEmpty())
                            <p class="blog-empty-note">Every stock already has a {{ strtolower($label) }} page — find it in the list to edit it.</p>
                        @else
                            <select name="ul_stocks_fincode" id="stockSelect" class="cms-input" required>
                                <option value="">— Choose the company —</option>
                                @foreach($availableStocks as $s)
                                    <option value="{{ $s->UL_STOCKS_FINCODE }}" data-name="{{ $s->UL_STOCKS_COMPNAME }}"
                                            @selected((int) old('ul_stocks_fincode', $article->ul_stocks_fincode) === (int) $s->UL_STOCKS_FINCODE)>
                                        {{ $s->UL_STOCKS_COMPNAME }}{{ $s->UL_STOCKS_STATUS === '1' ? '' : ' (inactive)' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="cms-field-hint">Only stocks without a {{ strtolower($label) }} page are listed. The page goes live at /unlisted-shares/<em>company</em>/{{ $type }}/.</p>
                        @endif
                        @error('ul_stocks_fincode') <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                @else
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
    // New page: the title follows the chosen stock until it's typed by hand.
    const TITLE_SUFFIX = @json(StockArticle::TYPES[$type]['title']);
    const $title = $('#titleInput');
    let titleTouched = $title.val().trim() !== '';
    $title.on('input', function () { titleTouched = $(this).val().trim() !== ''; });
    $('#stockSelect').on('change', function () {
        const name = $(this).find('option:selected').data('name');
        if (!titleTouched && name) $title.val(name + ': ' + TITLE_SUFFIX);
    });

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
