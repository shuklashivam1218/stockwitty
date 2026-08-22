@props(['study'])

<div x-data="downloadGate()" data-study="{{ json_encode($study) }}" class="contents">
    <span class="contents" @click="open = true">{{ $slot }}</span>

    <div x-show="open" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4" @keydown.escape.window="open = false">
        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
        <div class="relative max-h-[92vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-card p-6 shadow-soft sm:p-7"
             x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h2 class="text-xl font-bold text-foreground">Download this case study</h2>
            <p class="mt-1 text-sm text-muted-foreground">Get the full one-page breakdown as a PDF.</p>

            <div x-show="done" style="display: none;" class="flex flex-col items-center gap-3 py-8 text-center">
                <x-sw.icon name="check-circle-2" class="size-10 text-primary" />
                <p class="text-base font-bold text-foreground">Thanks — your download has started.</p>
                <p class="text-sm text-muted-foreground">We'll be in touch.</p>
            </div>

            <form x-show="!done" style="display: block;" class="mt-5 space-y-4" @submit.prevent="submit()">
                <div>
                    <label class="text-xs font-bold tracking-wide text-foreground uppercase">Name*</label>
                    <input required x-model="form.name" placeholder="Your full name"
                           class="mt-1.5 h-11 w-full rounded-xl border border-border bg-card px-3.5 text-sm text-foreground outline-none focus:border-primary" />
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-bold tracking-wide text-foreground uppercase">Email*</label>
                        <input type="email" required x-model="form.email" placeholder="you@example.com"
                               class="mt-1.5 h-11 w-full rounded-xl border border-border bg-card px-3.5 text-sm text-foreground outline-none focus:border-primary" />
                    </div>
                    <div>
                        <label class="text-xs font-bold tracking-wide text-foreground uppercase">Mobile*</label>
                        <input inputmode="numeric" required x-model="form.mobile" placeholder="10-digit number"
                               class="mt-1.5 h-11 w-full rounded-xl border border-border bg-card px-3.5 text-sm text-foreground outline-none focus:border-primary" />
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold tracking-wide text-foreground uppercase">What are you interested in?</label>
                    <select x-model="form.interest" class="mt-1.5 h-11 w-full rounded-xl border border-border bg-card px-3.5 text-sm text-foreground outline-none focus:border-primary">
                        <option>Unlisted shares</option>
                        <option>Mutual funds</option>
                        <option>Other</option>
                    </select>
                </div>
                <label class="flex items-start gap-3 rounded-xl border border-border bg-green-50 p-3.5 text-sm text-muted-foreground">
                    <input type="checkbox" x-model="form.consent" class="mt-0.5 size-4 shrink-0 accent-[#076550]" />
                    <span>I agree to be contacted by StockWitty about my enquiry.</span>
                </label>

                <button type="submit" :disabled="!valid || busy"
                        class="bg-cta flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3.5 text-sm font-bold text-white transition-opacity disabled:cursor-not-allowed disabled:opacity-50">
                    <span x-show="!busy" style="display: inline-flex;" class="inline-flex items-center gap-2">
                        <x-sw.icon name="download" class="size-4" /> Download PDF →
                    </span>
                    <span x-show="busy" style="display: none;" class="inline-flex items-center gap-2">
                        Preparing PDF…
                    </span>
                </button>
                <p class="text-[0.7rem] text-muted-foreground">{{ $study['disclaimer'] }}</p>
            </form>
        </div>
    </div>
</div>
