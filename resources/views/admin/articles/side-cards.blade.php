{{-- Sidebar cards every article has: featured image, hero & tags, video, related
     blog posts and the callback form. $article is the model; $otherPosts the
     blog posts that can be linked as related. --}}
<div class="admin-card">
    <div class="cms-side-title">Featured Image</div>
    <img src="{{ $article->featured_image ? asset($article->featured_image) : '' }}" class="cms-featured-preview" id="featuredPreview"
         alt="" @unless($article->featured_image) style="display:none;" @endunless>
    <input type="file" name="featured_image" accept="image/png,image/jpeg,image/gif,image/webp" id="featuredInput" class="cms-input">
    <p class="cms-field-hint">JPG, PNG, GIF or WebP, up to 5 MB. 1600×900 works best.</p>
    @error('featured_image') <div class="cms-error">{{ $message }}</div> @enderror
    <div class="cms-field" style="margin:12px 0 0;">
        <label>Image alt text</label>
        <input type="text" name="featured_image_alt" value="{{ old('featured_image_alt', $article->featured_image_alt) }}" maxlength="255"
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
            @foreach(config('blog.hero_icons') as $icon)
                <option value="{{ $icon }}" @selected(old('hero_icon', $article->hero_icon) === $icon)>{{ $icon }}</option>
            @endforeach
        </select>
        <p class="cms-field-hint">Shown in the hero when there's no featured image.</p>
        @error('hero_icon') <div class="cms-error">{{ $message }}</div> @enderror
    </div>
    <div class="cms-field" style="margin-bottom:0;">
        <label>Chips</label>
        <input type="text" name="chips_text" class="cms-input" maxlength="300"
               value="{{ old('chips_text', implode(', ', $article->chips ?? [])) }}" placeholder="Unlisted Shares, Tax, 2026">
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
               value="{{ old('video_url', isset($article->video['youtube_id']) ? 'https://www.youtube.com/watch?v=' . $article->video['youtube_id'] : '') }}"
               placeholder="https://www.youtube.com/watch?v=…">
        @error('video_url') <div class="cms-error">{{ $message }}</div> @enderror
    </div>
    <div class="cms-field" style="margin-bottom:0;">
        <label>Caption</label>
        <input type="text" name="video_caption" class="cms-input" maxlength="200"
               value="{{ old('video_caption', $article->video['caption'] ?? '') }}" placeholder="Watch: … explained simply">
        @error('video_caption') <div class="cms-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="admin-card">
    <div class="cms-side-title">Related Posts</div>
    @php $relatedIds = array_map('intval', (array) old('related_post_ids', $article->related_post_ids ?? [])); @endphp
    @if($otherPosts->isEmpty())
        <p class="blog-empty-note">No other posts yet. Once more posts exist you can link up to {{ config('blog.max_related') }} here.</p>
    @else
        <select name="related_post_ids[]" multiple size="{{ min(6, max(3, $otherPosts->count())) }}" class="cms-input" id="relatedPosts">
            @foreach($otherPosts as $other)
                <option value="{{ $other->id }}" @selected(in_array($other->id, $relatedIds, true))>
                    {{ $other->title }}{{ $other->isPublished() ? '' : ' (draft)' }}
                </option>
            @endforeach
        </select>
        <p class="cms-field-hint">Ctrl/Cmd-click to pick up to {{ config('blog.max_related') }} blog posts. Drafts are skipped on the live page until published.</p>
    @endif
    @error('related_post_ids') <div class="cms-error">{{ $message }}</div> @enderror
    @error('related_post_ids.*') <div class="cms-error">{{ $message }}</div> @enderror
</div>

<div class="admin-card">
    <div class="cms-side-title">Callback Form</div>
    <div class="cms-field">
        <label>Heading</label>
        <input type="text" name="lead_heading" class="cms-input" maxlength="255"
               value="{{ old('lead_heading', $article->lead_heading) }}" placeholder="Questions on this topic? We'll connect you.">
        @error('lead_heading') <div class="cms-error">{{ $message }}</div> @enderror
    </div>
    <div class="cms-field" style="margin-bottom:0;">
        <label>Subtext</label>
        <textarea name="lead_subtext" rows="3" maxlength="1000" class="cms-input" placeholder="Leave blank for the default text">{{ old('lead_subtext', $article->lead_subtext) }}</textarea>
        @error('lead_subtext') <div class="cms-error">{{ $message }}</div> @enderror
    </div>
</div>
