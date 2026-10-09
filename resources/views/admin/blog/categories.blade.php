@extends('layout.admin')

@section('title', 'Blog Categories | Admin | StocksWitty')

@section('content')
<div class="admin-main">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <h1 class="admin-page-title">Blog Categories</h1>
        <a href="{{ route('admin.blog.posts') }}" class="cms-back-link"><i class="fa-solid fa-arrow-left"></i> Back to Posts</a>
    </div>

    @if(session('success'))
        <div class="cms-flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="cms-lock-banner cms-lock-banner-error"><i class="fa-solid fa-circle-exclamation"></i><span>{{ session('error') }}</span></div>
    @endif
    @if($errors->any())
        <div class="cms-lock-banner cms-lock-banner-error"><i class="fa-solid fa-circle-exclamation"></i><span>{{ $errors->first() }}</span></div>
    @endif

    <div class="admin-card" style="margin-bottom:16px;">
        <div class="cms-side-title">Add a category</div>
        <form method="POST" action="{{ route('admin.blog.categories.store') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            @csrf
            <input type="text" name="name" value="{{ old('name') }}" required maxlength="80" class="cms-input" style="max-width:280px;" placeholder="Category name">
            <input type="number" name="sort_order" value="{{ old('sort_order', $categories->max('sort_order') + 1) }}" min="0" max="999" class="cms-input" style="max-width:110px;" title="Order on /blog/">
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" checked> Active
            </label>
            <button type="submit" class="cms-new-btn" style="border:none;cursor:pointer;"><i class="fa-solid fa-plus"></i> Add</button>
        </form>
    </div>

    <div class="admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Order</th>
                        <th>Active</th>
                        <th>Posts</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td colspan="3" style="padding:6px 14px;">
                            <form method="POST" action="{{ route('admin.blog.categories.update', $cat->id) }}" id="catForm{{ $cat->id }}"
                                  style="display:grid;grid-template-columns:minmax(160px,1fr) 90px 70px;gap:10px;align-items:center;">
                                @csrf @method('PUT')
                                <input type="text" name="name" value="{{ $cat->name }}" required maxlength="80" class="cms-input">
                                <input type="number" name="sort_order" value="{{ $cat->sort_order }}" min="0" max="999" class="cms-input">
                                <label style="display:flex;justify-content:center;">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" @checked($cat->is_active)>
                                </label>
                            </form>
                        </td>
                        <td>{{ $cat->posts_count }}</td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <button type="submit" form="catForm{{ $cat->id }}" class="cms-action-btn">Save</button>
                                @if($cat->posts_count === 0)
                                    <form method="POST" action="{{ route('admin.blog.categories.destroy', $cat->id) }}"
                                          onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="cms-action-btn cms-danger-btn">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;color:#aaa;padding:32px">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p class="cms-field-hint" style="margin-top:12px;"><i class="fa-solid fa-circle-info"></i> A category with posts can't be deleted — untick Active to hide it from the blog filters instead.</p>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/cms-form.css') }}?v={{ filemtime(public_path('assets/css/admin/cms-form.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin/cms-index.css') }}?v={{ filemtime(public_path('assets/css/admin/cms-index.css')) }}">
@endpush
@endsection
