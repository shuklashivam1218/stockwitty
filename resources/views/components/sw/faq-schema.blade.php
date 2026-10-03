{{-- FAQPage JSON-LD for a page's FAQ block. Renders nothing visible.
     Usage: <x-sw.faq-schema :faqs="[['q' => 'Question?', 'a' => 'Answer.'], ...]" />
     Use it once per page, next to the visible FAQ list it describes. --}}
@props(['faqs'])

@if (count($faqs))
    @push('jsonld')
        {!! \App\Support\Seo\JsonLd::script(\App\Support\Seo\JsonLd::faqPage($faqs)) !!}
    @endpush
@endif
