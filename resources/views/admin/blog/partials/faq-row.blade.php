<div class="blog-repeater-row">
    <div class="blog-repeater-grid">
        <input type="text" name="faqs[{{ $i }}][tab]" value="{{ $faq['tab'] ?? '' }}" maxlength="40"
               class="cms-input" placeholder="Tab (e.g. Basics)">
        <input type="text" name="faqs[{{ $i }}][q]" value="{{ $faq['q'] ?? '' }}" maxlength="300"
               class="cms-input" placeholder="Question">
    </div>
    <textarea name="faqs[{{ $i }}][a]" rows="3" maxlength="2000" class="cms-input" placeholder="Answer">{{ $faq['a'] ?? '' }}</textarea>
    <button type="button" class="blog-remove-row" title="Remove this FAQ" aria-label="Remove this FAQ"><i class="fa-solid fa-xmark"></i></button>
</div>
