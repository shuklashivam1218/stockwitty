@extends('layouts.sw')

@section('title', 'Contact StocksWitty — Unlisted Shares Help & Support')
@section('description', 'Get in touch with StocksWitty for unlisted and pre-IPO shares — email us or visit our New Delhi office. Distributor, not a SEBI-registered adviser.')

@php
$contact = config('sw.contact');
$address = implode(', ', [$contact['address']['street'], $contact['address']['locality'], $contact['address']['region'] . ' ' . $contact['address']['postcode']]);

// Detail cards on the right. Phone and WhatsApp appear only once their
// numbers are set in config/sw.php -> contact.
$cards = array_values(array_filter([
    ['icon' => 'map-pin', 'label' => 'Office', 'value' => $address, 'href' => $contact['maps_link'], 'cta' => 'Get directions'],
    $contact['phone'] ? ['icon' => 'phone', 'label' => 'Phone', 'value' => $contact['phone'], 'href' => 'tel:' . preg_replace('/[^\d+]/', '', $contact['phone'])] : null,
    ['icon' => 'mail', 'label' => 'Email', 'value' => $contact['email'], 'href' => 'mailto:' . $contact['email']],
    $contact['whatsapp'] ? ['icon' => 'message-circle', 'label' => 'WhatsApp', 'value' => 'Chat with us', 'href' => 'https://wa.me/' . $contact['whatsapp'], 'cta' => 'Open WhatsApp'] : null,
    ['icon' => 'clock', 'label' => 'Business hours', 'value' => $contact['hours']['label'], 'note' => $contact['hours']['closed']],
]));

$inputClass = 'mt-2 w-full rounded-xl border border-border bg-background px-3.5 py-3 text-sm text-foreground outline-none transition-colors focus:border-primary focus:ring-2 focus:ring-ring/40';
@endphp

@push('jsonld')
    {!! \App\Support\Seo\JsonLd::script(\App\Support\Seo\JsonLd::office()) !!}
@endpush

@section('content')
<div class="min-h-screen bg-background">
    <div class="pt-16">
        <x-sw.breadcrumb :items="[['label' => 'Home', 'href' => '/'], ['label' => 'Contact']]" />
    </div>

    <main>
        <x-sw.page-hero eyebrow="Contact" title="Let's talk"
                        subtitle="Questions about unlisted shares, WittyScore or how to buy? Write to us, or drop by our office." />

        <section class="py-14 sm:py-20">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.2fr_1fr] lg:px-8">
                <x-sw.reveal>
                    <form x-data="contactForm(@js(['email' => $contact['email']]))" x-ref="form" novalidate
                          @input="check()" @change="check()" @submit.prevent="submit()"
                          class="rounded-3xl border border-border bg-card p-6 shadow-soft sm:p-8">
                        <h2 class="text-xl font-bold text-foreground">Send us a message</h2>
                        <p class="mt-1 text-sm text-muted-foreground">We usually respond within one business day.</p>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-bold text-foreground">Name <span class="text-primary">*</span></span>
                                <input name="name" type="text" required maxlength="100" autocomplete="name" placeholder="Your full name" class="{{ $inputClass }}" />
                            </label>
                            <label class="block">
                                <span class="text-sm font-bold text-foreground">Email <span class="text-primary">*</span></span>
                                <input name="email" type="email" required maxlength="255" autocomplete="email" placeholder="you@example.com" class="{{ $inputClass }}" />
                            </label>
                        </div>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-bold text-foreground">Mobile <span class="text-primary">*</span></span>
                                <input name="mobile" type="tel" required inputmode="numeric" autocomplete="tel" maxlength="16"
                                       pattern="(\+91[ \-]?)?[6-9][0-9]{4}[ \-]?[0-9]{5}" title="10-digit Indian mobile number"
                                       placeholder="+91 98765 43210" class="{{ $inputClass }}" />
                            </label>
                            <label class="block">
                                <span class="text-sm font-bold text-foreground">What's this about? <span class="text-primary">*</span></span>
                                <select name="topic" required class="{{ $inputClass }}">
                                    <option value="" selected disabled>Choose a topic</option>
                                    @foreach ($contact['topics'] as $topic)
                                        <option value="{{ $topic }}">{{ $topic }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <label class="mt-4 block">
                            <span class="text-sm font-bold text-foreground">Message <span class="text-primary">*</span></span>
                            <textarea name="message" required rows="5" maxlength="2000" placeholder="How can we help?" class="{{ $inputClass }} resize-y"></textarea>
                        </label>

                        <label class="mt-4 flex items-start gap-2.5 text-sm text-muted-foreground">
                            <input name="consent" type="checkbox" required class="mt-0.5 size-4 accent-[var(--brand)]" />
                            <span>I agree to be contacted by StocksWitty about my enquiry.</span>
                        </label>

                        <button type="submit" :disabled="!valid"
                                class="bg-cta mt-6 inline-flex items-center gap-2 rounded-xl px-6 py-3.5 text-sm font-bold text-white shadow-soft transition-opacity disabled:cursor-not-allowed disabled:opacity-40">
                            Send message <x-sw.icon name="arrow-right" class="size-4" />
                        </button>

                        <p x-show="opened" x-cloak class="mt-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-900">
                            Your email app should now be open with your message to {{ $contact['email'] }} — just press send.
                            Nothing opened? Write to us directly at
                            <a href="mailto:{{ $contact['email'] }}" class="font-bold underline">{{ $contact['email'] }}</a>.
                        </p>

                        @if ($contact['whatsapp'])
                            <div class="mt-6 border-t border-border pt-5">
                                <a href="https://wa.me/{{ $contact['whatsapp'] }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 rounded-xl border border-primary/40 px-5 py-3 text-sm font-bold text-primary transition-all hover:border-primary hover:bg-muted">
                                    <x-sw.icon name="message-circle" class="size-4" />
                                    Chat on WhatsApp
                                </a>
                            </div>
                        @endif
                    </form>
                </x-sw.reveal>

                @agentOnly
                    <h2>Contact details</h2>
                    <ul>
                        @foreach ($cards as $card)
                            <li>
                                <strong>{{ $card['label'] }}:</strong>
                                @isset($card['href'])
                                    <a href="{{ $card['href'] }}">{{ $card['value'] }}</a>
                                @else
                                    {{ $card['value'] }}
                                @endisset
                                {{ isset($card['note']) ? '(' . $card['note'] . ')' : '' }}
                            </li>
                        @endforeach
                    </ul>
                @endagentOnly

                <x-sw.reveal data-agent-skip :delay="0.1">
                    <div class="space-y-4">
                        @foreach ($cards as $card)
                            <x-sw.contact-card :icon="$card['icon']" :label="$card['label']" :value="$card['value']"
                                               :href="$card['href'] ?? null" :cta="$card['cta'] ?? null" :note="$card['note'] ?? null" />
                        @endforeach
                    </div>
                </x-sw.reveal>
            </div>
        </section>

        <section class="pb-14 sm:pb-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-sw.reveal>
                    <div class="overflow-hidden rounded-2xl border border-border shadow-soft">
                        <iframe src="{{ $contact['maps_embed'] }}" width="100%" height="420" style="border: 0;" loading="lazy"
                                title="StocksWitty office location on Google Maps" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <a href="{{ $contact['maps_link'] }}" target="_blank" rel="noopener noreferrer"
                       class="mt-4 inline-flex items-center gap-2 rounded-xl border border-primary/40 px-5 py-3 text-sm font-bold text-primary transition-all hover:border-primary hover:bg-muted">
                        Open in Google Maps <x-sw.icon name="external-link" class="size-4" />
                    </a>
                </x-sw.reveal>
            </div>
        </section>

        <section class="pb-14">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="mx-auto max-w-3xl rounded-2xl border border-border bg-green-50 px-5 py-4 text-center text-xs leading-relaxed text-muted-foreground">
                    StocksWitty is a distributor of unlisted shares and an information platform — not a
                    SEBI-registered investment adviser. Unlisted shares are illiquid and high-risk; not
                    investment advice.
                </p>
            </div>
        </section>
    </main>
</div>
@endsection
