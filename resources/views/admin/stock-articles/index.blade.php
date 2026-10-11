@extends('layout.admin')

@php
    use App\Models\StockArticle;
    $typeLabel = $type ? StockArticle::typeLabel($type) : null;
@endphp

@section('title', 'About / Thesis / WittyScore | CMS | Admin | StocksWitty')

@section('content')
<div class="admin-main">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 class="admin-page-title" style="margin-bottom:4px;">About / Thesis / WittyScore</h1>
            <p style="margin:0 0 20px;font-size:13px;color:#64748b;">
                Each stock's About, Thesis and WittyScore pages, at /unlisted-shares/<em>company</em>/about/, /thesis/ and /wittyscore/.
                Live for {{ $stocks }} active stocks:
                @foreach(StockArticle::TYPES as $key => $meta)
                    <strong>{{ $meta['label'] }} {{ $live[$key] ?? 0 }}</strong>@unless($loop->last) · @endunless
                @endforeach
            </p>
        </div>
        <a href="{{ route('admin.stock-articles.create', array_filter(['type' => $type])) }}" class="cms-new-btn">
            <i class="fa-solid fa-plus"></i> New {{ $typeLabel ? $typeLabel . ' ' : '' }}Page
        </a>
    </div>

    @if(session('success'))
        <div class="cms-flash-success">{{ session('success') }}</div>
    @endif

    <div class="admin-card">

        {{-- Page type, then status: the three page types live in this one list. --}}
        <div class="cms-status-tabs" style="margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid #f1f1f1;">
            @foreach(['' => 'All pages'] + array_map(fn ($m) => $m['label'], StockArticle::TYPES) as $key => $tab)
                <a href="{{ route('admin.stock-articles.index', array_filter(['type' => $key, 'status' => $status, 'search' => $search])) }}"
                   class="cms-status-tab {{ (string) $type === (string) $key ? 'active' : '' }}">
                    {{ $tab }}
                </a>
            @endforeach
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
            <div class="cms-status-tabs">
                @foreach(['' => 'All', 'draft' => 'Draft', 'published' => 'Published', 'trash' => 'Trash'] as $key => $tab)
                    <a href="{{ route('admin.stock-articles.index', array_filter(['type' => $type, 'status' => $key, 'search' => $search])) }}"
                       class="cms-status-tab {{ $status === $key ? 'active' : '' }}"
                       style="font-size:12.5px;padding:5px 12px;">
                        {{ $tab }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.stock-articles.index') }}" style="display:flex;gap:6px;">
                @if($type) <input type="hidden" name="type" value="{{ $type }}"> @endif
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by company or title..." class="cms-search-input">
                <button type="submit" class="cms-search-btn" aria-label="Search"><i class="fa-solid fa-search"></i></button>
            </form>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Page</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr>
                        <td>
                            <strong>{{ $article->stock->UL_STOCKS_COMPNAME ?? '—' }}</strong>
                            @if($article->isPublished() && !$article->trashed() && $article->stock)
                                <br><a href="{{ url($article->url()) }}" target="_blank" rel="noopener" style="font-size:12px;color:#076550;">
                                    View live <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;"></i>
                                </a>
                            @endif
                        </td>
                        <td><span class="admin-badge badge-admin">{{ StockArticle::typeLabel($article->type) }}</span></td>
                        <td>{{ $article->title }}</td>
                        <td>{{ $article->author->name ?? '—' }}</td>
                        <td>
                            @if($article->trashed())
                                <span class="admin-badge" style="background:#f1f1f1;color:#666;">Trashed</span>
                            @elseif($article->isPublished())
                                <span class="admin-badge" style="background:#e8f5e9;color:#2e7d32;">Published</span>
                            @else
                                <span class="admin-badge" style="background:#fff3e0;color:#e65100;">Draft</span>
                            @endif
                        </td>
                        <td style="font-size:13px;color:#64748b;">{{ $article->updated_at?->format('d M Y, h:i A') }}</td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            @if(!$article->trashed())
                                <a href="{{ route('admin.stock-articles.edit', $article->id) }}" class="cms-action-btn">Edit</a>
                                <button type="button" class="cms-action-btn cms-toggle-btn" data-id="{{ $article->id }}">
                                    {{ $article->isPublished() ? 'Unpublish' : 'Publish' }}
                                </button>
                                <button type="button" class="cms-action-btn cms-trash-btn" data-id="{{ $article->id }}">Trash</button>
                            @else
                                <button type="button" class="cms-action-btn cms-restore-btn" data-id="{{ $article->id }}">Restore</button>
                                <button type="button" class="cms-action-btn cms-danger-btn" data-id="{{ $article->id }}">Delete Forever</button>
                            @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:#aaa;padding:32px">
                            No {{ $typeLabel ? strtolower($typeLabel) . ' ' : '' }}pages found.
                            @if($status === '' && $search === '')
                                <a href="{{ route('admin.stock-articles.create', array_filter(['type' => $type])) }}">Write the first one</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px">{{ $articles->links() }}</div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/cms-index.css') }}?v={{ filemtime(public_path('assets/css/admin/cms-index.css')) }}">
@endpush
@endsection

@push('scripts')
<script>
$(function () {
    const CSRF = $('meta[name="csrf-token"]').attr('content');
    const BASE = @json(url('/admin/stock-pages'));

    function send(method, path, confirmText) {
        if (confirmText && !confirm(confirmText)) return;
        $.ajax({ url: BASE + path, method: method, headers: { 'X-CSRF-TOKEN': CSRF } })
            .done(function () { location.reload(); })
            .fail(function (xhr) {
                alert((xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong.');
            });
    }

    $(document).on('click', '.cms-toggle-btn',  function () { send('POST',   '/' + $(this).data('id') + '/publish-toggle'); });
    $(document).on('click', '.cms-trash-btn',   function () { send('DELETE', '/' + $(this).data('id'), 'Move this page to trash?'); });
    $(document).on('click', '.cms-restore-btn', function () { send('POST',   '/' + $(this).data('id') + '/restore'); });
    $(document).on('click', '.cms-danger-btn',  function () { send('DELETE', '/' + $(this).data('id') + '/force', 'Permanently delete this page? This cannot be undone.'); });
});
</script>
@endpush
