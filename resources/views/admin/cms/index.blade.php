@extends('layout.admin')

@section('title', 'CMS | Admin | StockWitty')

@section('content')
<div class="admin-main">

    <h1 class="admin-page-title">CMS &mdash; Static Pages</h1>

    <div class="admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                    <tr>
                        <td>{{ $page->CMS_PAGE_TITLE }}</td>
                        <td>
                            <a href="{{ url('/' . $page->CMS_PAGE_SLUG) }}" target="_blank" rel="noopener"
                               style="color:inherit;text-decoration:none;">
                                /{{ $page->CMS_PAGE_SLUG }}
                                <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;color:#94a3b8;margin-left:4px;"></i>
                            </a>
                        </td>
                        <td>
                            <span class="admin-badge {{ $page->CMS_PAGE_ACTIVE === '1' ? 'badge-admin' : 'badge-locked' }}">
                                {{ $page->CMS_PAGE_ACTIVE === '1' ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td style="font-size:13px;color:#64748b;">
                            {{ $page->CMS_PAGE_UPDATE_TIME ? \Illuminate\Support\Carbon::parse($page->CMS_PAGE_UPDATE_TIME)->format('d M Y, h:i A') : '—' }}
                        </td>
                        <td>
                            <i class="fa-solid fa-pen ptf-icon-edit cms-edit-btn"
                               data-slug="{{ $page->CMS_PAGE_SLUG }}"
                               style="cursor:pointer;color:#2196f3" title="Edit page"></i>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:#aaa;padding:32px">
                            <i class="fa-regular fa-folder-open" style="font-size:24px;display:block;margin-bottom:8px"></i>
                            No CMS pages yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Edit modal container — injected via AJAX --}}
<div id="cmsEditModalWrap"></div>
@endsection

@push('scripts')
<script src="{{ asset('js/tinymce_6.1.2/tinymce.min.js') }}"></script>
<script>
    window.CMS_BASE = '{{ url("/admin/cms") }}';
    var CSRF = $('meta[name="csrf-token"]').attr('content');

    function loadingSpinner() {
        return '<div style="display:flex;align-items:center;justify-content:center;position:fixed;inset:0;z-index:2100;">' +
               '<i class="fa-solid fa-spinner fa-spin" style="font-size:28px;color:#fff;"></i></div>';
    }

    $(document).on('click', '.cms-edit-btn', function () {
        var slug = $(this).data('slug');
        $('#cmsEditModalWrap').html(loadingSpinner());
        $.get(window.CMS_BASE + '/' + slug + '/edit')
            .done(function (html) { $('#cmsEditModalWrap').html(html); })
            .fail(function ()     { $('#cmsEditModalWrap').empty(); alert('Failed to load.'); });
    });

    function closeCmsEditModal() {
        if (typeof tinymce !== 'undefined') tinymce.remove('#CMS_PAGE_CONTENT1');
        $('#cmsEditModalWrap').empty();
    }
</script>
@endpush
