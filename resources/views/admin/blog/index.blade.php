@extends('layout.admin')

@section('title', 'Blog Posts | CMS | Admin | StocksWitty')

@section('content')
<div class="admin-main">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <h1 class="admin-page-title">Blog Posts</h1>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('admin.blog.categories') }}" class="cms-action-btn">
                <i class="fa-solid fa-tags"></i> Categories
            </a>
            <a href="{{ route('admin.blog.profile.edit') }}" class="cms-action-btn">
                <i class="fa-solid fa-id-badge"></i> My Author Profile
            </a>
            <a href="{{ route('admin.blog.posts.create') }}" class="cms-new-btn">
                <i class="fa-solid fa-plus"></i> New Post
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="cms-flash-success">{{ session('success') }}</div>
    @endif

    <div class="admin-card">

        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
            <div class="cms-status-tabs">
                @foreach(['' => 'All', 'draft' => 'Draft', 'published' => 'Published', 'trash' => 'Trash'] as $key => $label)
                    <a href="{{ route('admin.blog.posts', array_filter(['status' => $key, 'search' => $search])) }}"
                       class="cms-status-tab {{ $status === $key ? 'active' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.blog.posts') }}" style="display:flex;gap:6px;">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by title..." class="cms-search-input">
                <button type="submit" class="cms-search-btn" aria-label="Search"><i class="fa-solid fa-search"></i></button>
            </form>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td>
                            <strong>{{ $post->title }}</strong>
                            @if($post->is_featured)
                                <span class="admin-badge" style="background:#fff8e1;color:#8a6d00;margin-left:4px;">Featured</span>
                            @endif
                            @if($post->isPublished() && !$post->trashed())
                                <br><a href="{{ url($post->url()) }}" target="_blank" rel="noopener" style="font-size:12px;color:#076550;">
                                    View live <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;"></i>
                                </a>
                            @endif
                        </td>
                        <td>{{ $post->category->name ?? '—' }}</td>
                        <td>{{ $post->author->name ?? '—' }}</td>
                        <td>
                            @if($post->trashed())
                                <span class="admin-badge" style="background:#f1f1f1;color:#666;">Trashed</span>
                            @elseif($post->isPublished())
                                <span class="admin-badge" style="background:#e8f5e9;color:#2e7d32;">Published</span>
                            @else
                                <span class="admin-badge" style="background:#fff3e0;color:#e65100;">Draft</span>
                            @endif
                        </td>
                        <td style="font-size:13px;color:#64748b;">{{ $post->updated_at?->format('d M Y, h:i A') }}</td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            @if(!$post->trashed())
                                <a href="{{ route('admin.blog.posts.edit', $post->id) }}" class="cms-action-btn">Edit</a>
                                <button type="button" class="cms-action-btn cms-toggle-btn" data-id="{{ $post->id }}">
                                    {{ $post->isPublished() ? 'Unpublish' : 'Publish' }}
                                </button>
                                <button type="button" class="cms-action-btn cms-trash-btn" data-id="{{ $post->id }}">Trash</button>
                            @else
                                <button type="button" class="cms-action-btn cms-restore-btn" data-id="{{ $post->id }}">Restore</button>
                                <button type="button" class="cms-action-btn cms-danger-btn" data-id="{{ $post->id }}">Delete Forever</button>
                            @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:#aaa;padding:32px">No posts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px">{{ $posts->links() }}</div>
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
    const BASE = @json(url('/admin/blog'));

    function send(method, path, confirmText) {
        if (confirmText && !confirm(confirmText)) return;
        $.ajax({ url: BASE + path, method: method, headers: { 'X-CSRF-TOKEN': CSRF } })
            .done(function () { location.reload(); })
            .fail(function (xhr) {
                alert((xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong.');
            });
    }

    $(document).on('click', '.cms-toggle-btn',  function () { send('POST',   '/' + $(this).data('id') + '/publish-toggle'); });
    $(document).on('click', '.cms-trash-btn',   function () { send('DELETE', '/' + $(this).data('id'), 'Move this post to trash?'); });
    $(document).on('click', '.cms-restore-btn', function () { send('POST',   '/' + $(this).data('id') + '/restore'); });
    $(document).on('click', '.cms-danger-btn',  function () { send('DELETE', '/' + $(this).data('id') + '/force', 'Permanently delete this post? This cannot be undone.'); });
});
</script>
@endpush
