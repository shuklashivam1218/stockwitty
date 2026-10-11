{{-- Meta title / description / keywords for an article. --}}
<div class="admin-card">
    <div class="cms-side-title">SEO Meta</div>
    <div class="cms-field">
        <label>Meta Title <span class="req" title="Required to publish">*</span></label>
        <input type="text" name="meta_title" value="{{ old('meta_title', $article->meta_title) }}" maxlength="255" class="cms-input">
        @error('meta_title') <div class="cms-error">{{ $message }}</div> @enderror
    </div>
    <div class="cms-field">
        <label>Meta Description <span class="req" title="Required to publish">*</span></label>
        <textarea name="meta_description" rows="3" maxlength="500" class="cms-input">{{ old('meta_description', $article->meta_description) }}</textarea>
        @error('meta_description') <div class="cms-error">{{ $message }}</div> @enderror
    </div>
    <div class="cms-field">
        <label>Meta Keywords</label>
        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $article->meta_keywords) }}" maxlength="500" class="cms-input">
        @error('meta_keywords') <div class="cms-error">{{ $message }}</div> @enderror
    </div>
</div>
