{{-- Flash, edit-lock and validation banners shared by the blog and stock article editors.
     $noun names the thing being edited ("post", "page"). --}}
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
        <span><strong>{{ $lockedBy['name'] }}</strong> is currently editing this {{ $noun }} (started {{ $lockedBy['since'] }}). This form is read-only until they finish — it unlocks for you as soon as it's free.</span>
    </div>
@endif

@if($errors->any())
    <div class="cms-lock-banner cms-lock-banner-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span>Please fix the highlighted fields below.</span>
    </div>
@endif
