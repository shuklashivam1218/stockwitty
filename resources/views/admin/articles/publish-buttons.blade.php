{{-- The clicked button decides the status. The first one is also what Enter
     submits, so it never changes the current state: Save Draft for drafts,
     Update for live articles. $noun names the thing ("post", "page"). --}}
<div style="display:flex;flex-direction:column;gap:8px;">
    @if($article->isPublished())
        <button type="submit" name="status" value="published" class="cms-submit-btn">
            <i class="fa-solid fa-check"></i> Update Live {{ ucfirst($noun) }}
        </button>
        <button type="submit" name="status" value="draft" class="cms-slug-toggle-btn" style="padding:10px;"
                onclick="return confirm('Take this {{ $noun }} off the site and move it back to drafts?')">
            <i class="fa-solid fa-eye-slash"></i> Unpublish (Move to Draft)
        </button>
    @else
        <button type="submit" name="status" value="draft" class="cms-slug-toggle-btn" style="padding:10px;">
            <i class="fa-regular fa-floppy-disk"></i> Save Draft
        </button>
        <button type="submit" name="status" value="published" class="cms-submit-btn">
            <i class="fa-solid fa-globe"></i> Publish
        </button>
    @endif
</div>
<p class="cms-field-hint" style="margin-top:10px;"><i class="fa-solid fa-circle-info"></i> Fields marked * are only required to publish — a draft can be saved half-done.</p>
@error('status') <div class="cms-error">{{ $message }}</div> @enderror
