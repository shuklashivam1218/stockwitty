@php
    // New rows start active; '0' from old input or a saved inactive row stays off.
    $faqActive = array_key_exists('active', $faq) ? (bool) $faq['active'] : true;
@endphp
<div class="blog-repeater-row se-faq-row @unless($faqActive) is-inactive @endunless">
    <input type="hidden" name="faqs[{{ $i }}][id]" value="{{ $faq['id'] ?? '' }}">
    <div class="se-faq-top">
        <select name="faqs[{{ $i }}][target]" class="cms-input" title="Which page this FAQ shows on">
            @foreach($faqPages as $value => $label)
                <option value="{{ $value }}" @selected(($faq['target'] ?? 'overview') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <input type="text" name="faqs[{{ $i }}][tab]" value="{{ $faq['tab'] ?? '' }}" maxlength="60"
               class="cms-input" placeholder="Tab (optional)">
        <input type="text" name="faqs[{{ $i }}][q]" value="{{ $faq['q'] ?? '' }}" maxlength="500"
               class="cms-input @error("faqs.$i.q") is-invalid @enderror" placeholder="Question">
    </div>
    <textarea name="faqs[{{ $i }}][a]" rows="3" class="cms-input" placeholder="Answer">{{ $faq['a'] ?? '' }}</textarea>
    @error("faqs.$i.q") <div class="cms-error">{{ $message }}</div> @enderror
    @error("faqs.$i.target") <div class="cms-error">{{ $message }}</div> @enderror
    <div class="se-faq-foot">
        @include('admin.unlisted.partials.editor-switch', ['name' => "faqs[$i][active]", 'checked' => $faqActive])
        <div class="se-faq-tools">
            <button type="button" class="se-faq-up" title="Move up" aria-label="Move up"><i class="fa-solid fa-arrow-up"></i></button>
            <button type="button" class="se-faq-down" title="Move down" aria-label="Move down"><i class="fa-solid fa-arrow-down"></i></button>
            <button type="button" class="se-faq-remove" title="Remove this FAQ" aria-label="Remove this FAQ"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>
</div>
