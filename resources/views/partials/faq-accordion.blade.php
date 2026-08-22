@php
    $prefix = $prefix ?? 'faq';
@endphp

@if(isset($faqs) && $faqs->isNotEmpty())
<section class="faq-section" style="padding:30px 0;">
    <div class="label-tag">// FAQS</div>
    <h2>{{ $faqTitle ?? 'Frequently Asked Questions' }}</h2>

    <div class="faq-list">
        @foreach($faqs as $faq)
        <div class="faq-item">
            <button class="faq-btn" data-target="{{ $prefix }}-{{ $faq->UL_FAQ_ID }}" type="button">
                <span>{{ $faq->UL_FAQ_QUESTION }}</span>
                <span class="faq-icon">+</span>
            </button>
            <div class="faq-body" id="{{ $prefix }}-{{ $faq->UL_FAQ_ID }}">{{ $faq->UL_FAQ_ANSWER }}</div>
        </div>
        @endforeach
    </div>
</section>
@endif
