@extends('layouts.sw')

@if ($active || $posts->currentPage() > 1)
    @section('title', $pageTitle . ' | StocksWitty')
@else
    @section('title', 'Unlisted Shares Blog — Honest Guides & Tax Explainers | StocksWitty')
@endif
@section('description', $active
    ? 'StocksWitty guides on ' . $active->name . ' for unlisted and pre-IPO shares in India — plain English, no sales pitch.'
    : 'Plain-English guides on unlisted and pre-IPO shares in India: what they are, how to buy and sell them, how they are taxed, and what the risks really are.')
@section('canonical', $canonical)

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Blog']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="StocksWitty research" title="Unlisted shares, explained — honestly."
                        subtitle="No jargon walls, no sales pitch dressed up as research. Just what we'd tell a friend before they wired money for pre-IPO shares." />

        {{-- Page and category changes load in place (no URL change): clicks on
             [data-blog-nav] links fetch the same URL with X-Requested-With and
             swap in the returned listing. If anything fails, the browser just
             follows the link, which renders the same listing server-side. --}}
        <section id="posts" class="scroll-mt-24 py-14 sm:py-20"
                 x-data="{
                     loading: false,
                     status: '',
                     async go(url) {
                         if (this.loading) return;
                         this.loading = true;
                         try {
                             const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
                             if (!res.ok) throw new Error(res.status);
                             this.$refs.list.innerHTML = await res.text();
                             this.status = this.$refs.list.firstElementChild?.dataset.blogStatus || 'Guides updated';
                             this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                             this.$refs.list.focus({ preventScroll: true });
                         } catch (e) {
                             window.location.href = url;
                         } finally {
                             this.loading = false;
                         }
                     },
                 }"
                 @click="
                     const link = $event.target.closest('a[data-blog-nav]');
                     if (!link || $event.ctrlKey || $event.metaKey || $event.shiftKey || $event.button !== 0) return;
                     $event.preventDefault();
                     go(link.href);
                 ">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="sr-only" aria-live="polite" x-text="status"></p>

                <div x-ref="list" tabindex="-1" :aria-busy="loading"
                     :class="loading ? 'pointer-events-none opacity-50' : ''"
                     class="outline-none transition-opacity duration-200">
                    @include('sw.blog.partials.listing')
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
