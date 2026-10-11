{{-- The body of an article: summary, intro, takeaways, content, FAQs and sources.
     Shared by the blog and stock article editors; $article is the model being edited. --}}
<div class="cms-field">
    <label>Summary <span class="req" title="Required to publish">*</span></label>
    <textarea name="summary" rows="3" maxlength="1000" class="cms-input" placeholder="{{ $summaryHint ?? 'One or two lines shown on cards and in search results' }}">{{ old('summary', $article->summary) }}</textarea>
    @error('summary') <div class="cms-error">{{ $message }}</div> @enderror
</div>

<div class="cms-field">
    <label>Intro</label>
    <textarea name="intro" rows="5" maxlength="3000" class="cms-input" placeholder="Opening paragraphs shown above the key takeaways. Leave a blank line between paragraphs.">{{ old('intro', $article->intro) }}</textarea>
    @error('intro') <div class="cms-error">{{ $message }}</div> @enderror
</div>

<div class="cms-field">
    <label>Key Takeaways</label>
    <textarea name="takeaways_text" rows="5" class="cms-input" placeholder="One takeaway per line (up to {{ config('blog.max_takeaways') }})">{{ old('takeaways_text', implode("\n", $article->takeaways ?? [])) }}</textarea>
    @error('takeaways') <div class="cms-error">{{ $message }}</div> @enderror
    @error('takeaways.*') <div class="cms-error">{{ $message }}</div> @enderror
</div>

<div class="cms-field">
    <label>Content <span class="req" title="Required to publish">*</span></label>
    <textarea name="content" id="post_content">{{ old('content', $article->content) }}</textarea>
    <p class="cms-field-hint"><i class="fa-solid fa-circle-info"></i> Use Heading 2 for sections — each becomes a table-of-contents entry. The Styles menu turns a selection into a callout, pull quote, checklist, numbered steps or comparison table.</p>
    @error('content') <div class="cms-error">{{ $message }}</div> @enderror
</div>

{{-- FAQs: repeater rows. Blank rows are dropped on save. --}}
<div class="cms-field blog-section">
    <label>FAQs</label>
    <p class="cms-field-hint" style="margin:0 0 10px;">Shown under "Frequently asked questions" and as FAQ rich results in Google. The tab groups questions into filters.</p>
    <div id="faqRows" class="blog-repeater" data-empty="No FAQs yet — add the questions readers actually ask.">
        @foreach(old('faqs', $article->faqs ?? []) as $i => $faq)
            @include('admin.articles.faq-row', ['i' => $i, 'faq' => $faq])
        @endforeach
    </div>
    <button type="button" class="blog-add-row" data-target="#faqRows" data-template="#faqRowTemplate"><i class="fa-solid fa-plus"></i> Add FAQ</button>
    @error('faqs') <div class="cms-error">{{ $message }}</div> @enderror
    @foreach($errors->get('faqs.*') as $messages)
        <div class="cms-error">{{ $messages[0] }}</div>
    @endforeach
</div>

<div class="cms-field blog-section">
    <label>Sources &amp; References</label>
    <p class="cms-field-hint" style="margin:0 0 10px;">Official pages readers can check the facts against — SEBI, NSDL, CDSL, the company's filings.</p>
    <div id="sourceRows" class="blog-repeater" data-empty="No sources yet.">
        @foreach(old('sources', $article->sources ?? []) as $i => $source)
            @include('admin.articles.source-row', ['i' => $i, 'source' => $source])
        @endforeach
    </div>
    <button type="button" class="blog-add-row" data-target="#sourceRows" data-template="#sourceRowTemplate"><i class="fa-solid fa-plus"></i> Add Source</button>
    @error('sources') <div class="cms-error">{{ $message }}</div> @enderror
    @foreach($errors->get('sources.*') as $messages)
        <div class="cms-error">{{ $messages[0] }}</div>
    @endforeach
</div>
