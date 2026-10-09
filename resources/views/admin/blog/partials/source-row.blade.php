<div class="blog-repeater-row">
    <div class="blog-repeater-grid">
        <input type="text" name="sources[{{ $i }}][label]" value="{{ $source['label'] ?? '' }}" maxlength="150"
               class="cms-input" placeholder="Label (e.g. SEBI)">
        <input type="url" name="sources[{{ $i }}][href]" value="{{ $source['href'] ?? '' }}" maxlength="500"
               class="cms-input" placeholder="https://…">
    </div>
    <button type="button" class="blog-remove-row" title="Remove this source" aria-label="Remove this source"><i class="fa-solid fa-xmark"></i></button>
</div>
