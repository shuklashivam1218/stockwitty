@extends('layout.admin')

@section('title', 'Author Profile | Blog | Admin | StocksWitty')

@php
    $initial    = strtoupper(mb_substr($author->name, 0, 1));
    $defaultBio = 'Writes for StocksWitty on unlisted shares, pre-IPO investing and the Indian markets.';
    $socials    = [
        'linkedin'  => ['field' => 'author_linkedin',  'label' => 'LinkedIn',    'class' => 'ap-icon-li',  'icon' => 'fa-brands fa-linkedin-in', 'placeholder' => 'https://linkedin.com/in/yourname'],
        'twitter'   => ['field' => 'author_twitter',   'label' => 'Twitter / X', 'class' => 'ap-icon-tw',  'icon' => 'fa-brands fa-x-twitter',   'placeholder' => 'https://x.com/yourname'],
        'facebook'  => ['field' => 'author_facebook',  'label' => 'Facebook',    'class' => 'ap-icon-fb',  'icon' => 'fa-brands fa-facebook-f',  'placeholder' => 'https://facebook.com/yourname'],
        'instagram' => ['field' => 'author_instagram', 'label' => 'Instagram',   'class' => 'ap-icon-ig',  'icon' => 'fa-brands fa-instagram',   'placeholder' => 'https://instagram.com/yourname'],
        'website'   => ['field' => 'author_website',   'label' => 'Website',     'class' => 'ap-icon-web', 'icon' => 'fa-solid fa-globe',        'placeholder' => 'https://yourwebsite.com'],
    ];
@endphp

@section('content')
<div class="admin-main">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <h1 class="admin-page-title">Author Profile</h1>
        <a href="{{ route('admin.blog.posts') }}" class="cms-back-link"><i class="fa-solid fa-arrow-left"></i> Back to Posts</a>
    </div>

    @if(session('success'))
        <div class="cms-flash-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
    @endif

    <div class="ap-grid">

        <div class="admin-card">
            <p class="ap-hint">Your name, designation, bio and links appear as the byline and author card on every post you write.</p>

            <form method="POST" action="{{ route('admin.blog.profile.update') }}">
                @csrf

                <div class="cms-field">
                    <label>Name</label>
                    <input type="text" value="{{ $author->name }}" class="cms-input cms-input-locked" readonly>
                </div>

                <div class="cms-field">
                    <label>Designation</label>
                    <input type="text" name="author_designation" id="apDesignation" maxlength="120"
                           value="{{ old('author_designation', $author->author_designation) }}" class="cms-input"
                           placeholder="e.g. Research Analyst, CA">
                    @error('author_designation') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                <div class="cms-field">
                    <label>About You</label>
                    <textarea name="author_bio" id="apBio" rows="4" maxlength="1000" class="cms-input" placeholder="A short bio shown on your posts...">{{ old('author_bio', $author->author_bio) }}</textarea>
                    @error('author_bio') <div class="cms-error">{{ $message }}</div> @enderror
                </div>

                <div class="ap-section-label">Social Links</div>
                <p class="ap-section-hint">Leave any of these blank to hide that icon. Links must start with https://.</p>

                <div class="ap-social-grid">
                    @foreach($socials as $key => $s)
                    <div class="cms-field {{ $key === 'website' ? 'ap-social-full' : '' }}">
                        <label><span class="ap-icon {{ $s['class'] }}"><i class="{{ $s['icon'] }}"></i></span> {{ $s['label'] }}</label>
                        <input type="url" name="{{ $s['field'] }}" data-social="{{ $key }}" class="cms-input ap-social-input"
                               value="{{ old($s['field'], $author->{$s['field']}) }}" placeholder="{{ $s['placeholder'] }}" maxlength="255">
                        @error($s['field']) <div class="cms-error">{{ $message }}</div> @enderror
                    </div>
                    @endforeach
                </div>

                <button type="submit" class="cms-submit-btn ap-save-btn"><i class="fa-solid fa-check"></i> Save Profile</button>
            </form>
        </div>

        <div class="ap-preview-col">
            <div class="admin-card ap-preview-card">
                <div class="ap-preview-label"><i class="fa-solid fa-eye"></i> Live Preview</div>

                <div class="ap-preview-box">
                    <div class="ap-preview-avatar">{{ $initial }}</div>
                    <div class="ap-preview-body">
                        <h4>{{ $author->name }}</h4>
                        <p id="apPreviewDesignation" style="font-size:12px;font-weight:600;color:#076550;margin:0 0 6px;">{{ $author->author_designation }}</p>
                        <p id="apPreviewBio">{{ $author->author_bio ?: $defaultBio }}</p>
                        <div class="ap-preview-socials" id="apPreviewSocials">
                            @foreach($socials as $key => $s)
                                <span class="ap-icon {{ $s['class'] }}" data-social="{{ $key }}" @if(!$author->{$s['field']}) style="display:none;" @endif>
                                    <i class="{{ $s['icon'] }}"></i>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <p class="ap-preview-note">This is how your card appears at the bottom of your published posts.</p>
            </div>
        </div>

    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/cms-form.css') }}?v={{ filemtime(public_path('assets/css/admin/cms-form.css')) }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin/cms-author-profile.css') }}?v={{ filemtime(public_path('assets/css/admin/cms-author-profile.css')) }}">
@endpush

<script>
(function () {
    var defaultBio = @json($defaultBio);
    var bio = document.getElementById('apBio');
    var designation = document.getElementById('apDesignation');

    bio.addEventListener('input', function () {
        document.getElementById('apPreviewBio').textContent = bio.value.trim() || defaultBio;
    });
    designation.addEventListener('input', function () {
        document.getElementById('apPreviewDesignation').textContent = designation.value.trim();
    });

    document.querySelectorAll('.ap-social-input').forEach(function (input) {
        input.addEventListener('input', function () {
            var icon = document.querySelector('#apPreviewSocials [data-social="' + input.dataset.social + '"]');
            icon.style.display = input.value.trim() ? '' : 'none';
        });
    });
})();
</script>
@endsection
